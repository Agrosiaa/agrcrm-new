<?php

namespace App\Http\Controllers\Tag;

use App\CustomerTagRelation;
use App\TagCloud;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\User;
use Ixudra\Curl\Facades\Curl;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function manage(Request $request){
        try{
            $user = Auth::user();
            return view('backend.tag.manage');
        }catch(\Exception $exception){
            $data =[
                'action' => 'get crm orders',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function tagListing(Request $request){
        try{
            $tableData = $request->all();
            $searchData = NULL;
            $tagIds = TagCloud::lists('id')->toArray();
            if($tagIds != null){
                $resultFlag = true;
                // Search with tag name
                if($request->has('name') && $tableData['name']!=""){
                    $tagIds = TagCloud::whereIn('id',$tagIds)->where('name','like','%'.$tableData['name'].'%')->lists('id');
                    if(count($tagIds) <= 0){
                        $resultFlag = false;
                    }
                }
                $iTotalRecords = count($tagIds);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $limitedProducts = TagCloud::whereIn('id',$tagIds)->take($iDisplayLength)->skip($iDisplayStart)->orderBy('created_at','desc')->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    $editTagPara = $limitedProducts[$j]['id'].",'".$limitedProducts[$j]['name']."'";
                    $records["data"][] = array(
                        $limitedProducts[$j]['name'],
                        date('d F Y H:i:s', strtotime($limitedProducts[$j]['created_at'])),
                        '<a class="btn btn-sm btn-default btn-circle btn-editable" onclick="editTag('.$editTagPara.')"><i class="fa fa-pencil"></i> Edit</a>',
                    );
                }

                if (isset($request->customActionType) && $request->customActionType == "group_action") {
                    $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                    $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
                }
                $records["draw"] = $sEcho;
                $records["recordsTotal"] = $iTotalRecords;
                $records["recordsFiltered"] = $iTotalRecords;
            }else{
                $records = '';
            }
        }catch(\Exception $e){
            $records = $e->getMessage();
        }
        return response()->json($records);
    }
    public function createEditTag(Request $request){
        try{
            $user = Auth::User();
            if($request->has('tag_id') && $request->tag_id){
                TagCloud::where('id',$request->tag_id)->update(['name' => $request->tag_name,'user_id' => $user['id']]);
            }else{
                TagCloud::create(['name' => $request->tag_name,'user_id' => $user['id']]);
            }
            return back();
        }catch (\Exception $exception){
            $data = [
                'action' => 'Create/Edit new tag',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function syncTag(Request $request){
        try{
            $user = Auth::User();
            $lastUpdate = TagCloud::where('is_product',true)->orWhere('is_category',true)->orWhere('is_crop',true)->orderBy('id','DESC')->value('created_at');
            if($lastUpdate != null){
                $lastUpdate = $lastUpdate->toDateTimeString();
            }
            $tagData = Curl::to(env('BASE_URL')."/get-tags")->withData( array('last_update' => $lastUpdate))->asJson()->get();
            if($tagData != null){
                $data['user_id'] = $user['id'];
                foreach ($tagData as $key => $tagDatum){
                    if($key == 'categories'){
                        $data['is_category'] = true;
                        $data['is_product'] = null;
                        $data['is_crop'] = null;
                    }elseif ($key == 'products'){
                        $data['is_product'] = true;
                        $data['is_category'] = null;
                        $data['is_crop'] = null;
                    }elseif ($key == 'agronomy'){
                        $data['is_crop'] = true;
                        $data['is_product'] = null;
                        $data['is_category'] = null;
                    }
                    foreach ($tagDatum as $tag){
                        $tagPresent = TagCloud::where('name',$tag->tag_name)->first();
                        if($tagPresent == null){
                            $data['name'] = $tag->tag_name;
                            TagCloud::create($data);
                        }
                    }
                }
            }
            $request->session()->flash('success','All tags synced successfully');
            return back();
        }catch(\Exception $exception){
            $data =[
                'action' => 'Sync Tag',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function createCustomerTag(Request $request){
        try{
            $user = Auth::user();
            if($request->has('crm_cust_id') && $request->crm_cust_id != null){
                CustomerTagRelation::create(['user_id' => $user['id'],'crm_customer_id' => $request->crm_cust_id, 'tag_cloud_id' => $request->tag_id]);
            }
            return back();
        }catch (\Exception $exception){
            $data = [
                'action' => 'Create/Edit new tag',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }

    public function getTagsData(Request $request) {
        try{
            $status = 200;
            $keyword = trim($request->tag_name);
            if($keyword == ''){
                $relevantResult = "";
                $status = 500;
            }else{
                $relevantResult = $this->getRelevantResult($request->tag_name);
            }
        }catch (\Exception $e){
            $status = 500;
            $data = [
                'input_params' => $request->all(),
                'action' => 'auto suggest',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            $relevantResult = '';
        }
        return response()->json($relevantResult,$status);
    }
    public function getRelevantResult($keyword)
    {
        $tag_id = array();
        $searchResultsTake = env('SEARCH_RESULT');
        $keywordLower = strtolower($keyword);
        $tagsDataArray = TagCloud::whereIn('id',$tag_id)->where('is_active',1)->select('id','name')->orderBy('created_at','desc')->take($searchResultsTake)->skip(0)->get()->toArray();
        $tags = $this->getTags($keywordLower,$searchResultsTake);
        $tag = $tags['data'];
        $tagCount = count($tag);
        $tagDataCount = count($tagsDataArray);
        $max = max($tagCount,$tagDataCount);
        $k = 0;
        for($i = 0 ; $i  < $max ; $i++) {
            if(!empty($tag[$i])) {
                $relevantData[$k]['id'] = $tag[$i]['id'];
                $relevantData[$k]['is_product'] = $tag[$i]['is_product'];
                $relevantData[$k]['is_category'] = $tag[$i]['is_category'];
                $relevantData[$k]['name'] = ucwords($tag[$i]['name']);
                $stringPosition = stripos($tag[$i]['name'],$keywordLower);
                if(is_int($stringPosition)){
                    $relevantData[$k]['position'] = $stringPosition;
                } else {
                    $relevantData[$k]['position'] = 25;
                }
                $relevantData[$k]['translated_slug'] = trans('product');
                $relevantData[$k]['class'] = "btn-danger";
                $relevantData[$k]['url_param'] = '';
                $k++;
            }
        }
        return $relevantData;
    }
    public function getTags($keywordLower,$searchResultsTake) {
        $tagsDataArray = TagCloud::where('name','like','%'.$keywordLower.'%')
            ->where('is_active',1)
            ->orderBy('id','desc')
            ->select('id','name','is_product','is_category')
            ->take($searchResultsTake)->skip(0)->get()->toArray();
        $k = 0;
        $tagData = array();Log::info(json_encode($tagsDataArray));
        foreach($tagsDataArray as $tag) {
            $keywordsArray = explode(",", $tag['name']);
            $j = 0;
            foreach($keywordsArray as $keyword) {
                $keyword = strtolower($keyword);
                $percent[$j] = similar_text($keyword, $keywordLower);
                $j++;
            }
            $maxValue = max($percent);
            $key = array_search($maxValue,$percent);
            if(!empty($data) && $data['percent'] < $maxValue){
                $keywordsData['key'] = $key;
                $keywordsData['keyword'] = $keywordsArray[$key];
                $keywordsData['percent'] = $maxValue;
                $keywordsData['tag_id'] = $tag['id'];
            } else {
                $keywordsData['key'] = $key;
                $keywordsData['keyword'] = $keywordsArray[$key];
                $keywordsData['percent'] = $maxValue;
                $keywordsData['tag_id'] = $tag['id'];
            }
            $alreadyFlag = false;
            foreach($tagData as $tags){
                if($tags['name'] == $keywordsData['keyword']){
                    $alreadyFlag = true;
                    break;
                }
            }
            if(!$alreadyFlag){
                $tagData[$k]['id'] = $keywordsData['tag_id'];
                $tagData[$k]['name'] = $keywordsData['keyword'];
                $tagData[$k]['percent'] = $keywordsData['percent'];
                $tagData[$k]['is_product'] = $tag['is_product'];
                $tagData[$k]['is_category'] = $tag['is_category'];
                $k++;
            }
        }
        if (!empty($tagData)) {
            foreach ($tagData as $key => $part) {
                $sort[$key] = $part['percent'];
            }
            array_multisort($sort, SORT_DESC, $tagData);
            $tag['data'] = $tagData;
            $tag['condition'] = TRUE;
        }else{
            $tag['data'] = NULL;
            $tag['condition'] = FALSE;
        }
        return $tag;
    }

}