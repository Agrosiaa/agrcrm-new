<?php

namespace App\Http\Controllers\Tag;

use App\CustomerTagRelation;
use App\TagCloud;
use App\TagType;
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
            $tagTypes = TagType::all();
            return view('backend.tag.manage')->with(compact('tagTypes'));
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
                if($resultFlag && $request->has('name') && $tableData['name']!=""){
                    $tagIds = TagCloud::whereIn('id',$tagIds)->where('name','like','%'.$tableData['name'].'%')->lists('id');
                    if(count($tagIds) <= 0){
                        $resultFlag = false;
                    }
                }
                if($resultFlag && $request->has('tag_type') && $tableData['tag_type']!=""){
                    $tagIds = TagCloud::whereIn('id',$tagIds)->where('tag_type_id',$tableData['tag_type'])->lists('id');
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
                $limitedProducts = TagCloud::whereIn('id',$tagIds)->take($iDisplayLength)->skip($iDisplayStart)->with('TagType')->orderBy('created_at','desc')->get()->toArray();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    $editTagPara = $limitedProducts[$j]['id'].",'".$limitedProducts[$j]['name']."',".$limitedProducts[$j]['tag_type_id'];
                    $records["data"][] = array(
                        $limitedProducts[$j]['name'],
                        $limitedProducts[$j]['tag_type'] ? $limitedProducts[$j]['tag_type']['name'] : '',
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
                TagCloud::where('id',$request->tag_id)->update([
                    'name' => $request->tag_name,
                    'tag_type_id' => $request->tag_type_id,
                    'user_id' => $user['id']
                ]);
            }else{
                TagCloud::create([
                    'name' => $request->tag_name,
                    'tag_type_id' => $request->tag_type_id,
                    'user_id' => $user['id']
                ]);
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
            $lastUpdate = TagCloud::whereIn('tag_type_id',[10])->orderBy('id','DESC')->value('created_at');
            if($lastUpdate != null){
                $lastUpdate = $lastUpdate->toDateTimeString();
            }
            $tagData = Curl::to(env('BASE_URL')."/get-tags")->withData( array('last_update' => $lastUpdate))->asJson()->get();
            if($tagData != null){
                $data['user_id'] = $user['id'];
                foreach ($tagData as $key => $tagDatum){
                    if($key == 'categories'){
                        $data['tag_type_id'] = TagType::where('slug','category')->value('id');
                        foreach ($tagDatum as $tag){
                            $tagPresent = TagCloud::where('name',$tag->tag_name)->where('tag_type_id',$data['tag_type_id'])->first();
                            if($tagPresent == null){
                                $data['name'] = $tag->tag_name;
                                TagCloud::create($data);
                            }
                        }
                    }elseif ($key == 'products'){
                        foreach ($tagDatum as $tag){
                            $data['tag_type_id'] = TagType::where('slug','product')->value('id');
                            $tagPresent = TagCloud::where('name',$tag->tag_name)->where('tag_type_id',$data['tag_type_id'])->first();
                            if($tagPresent == null){
                                $data['name'] = $tag->tag_name;
                                TagCloud::create($data);
                            }
                            if($tag->cat_slug == 'tools-a-machinery'){
                                $data['tag_type_id'] = TagType::where('slug','tools')->value('id');
                                $tagPresent = TagCloud::where('name',$tag->tag_name)->where('tag_type_id',$data['tag_type_id'])->first();
                                if($tagPresent == null){
                                    $data['name'] = $tag->tag_name;
                                    TagCloud::create($data);
                                }
                            }
                            /*if($tag->cat_slug == 'seeds'){
                                $data['tag_type_id'] = TagType::where('slug','crop')->value('id');
                                $tagPresent = TagCloud::where('name',$tag->tag_name)->where('tag_type_id',$data['tag_type_id'])->first();
                                if($tagPresent == null){
                                    $data['name'] = $tag->tag_name;
                                    TagCloud::create($data);
                                }
                            }*/
                            if($tag->sub_cat_slug == 'pesticide' || $tag->sub_cat_slug == 'organic-pesticide' || $tag->item_head_slug == 'organic-pesticide1'){
                                $data['tag_type_id'] = TagType::where('slug','pesticide')->value('id');
                                $tagPresent = TagCloud::where('name',$tag->tag_name)->where('tag_type_id',$data['tag_type_id'])->first();
                                if($tagPresent == null){
                                    $data['name'] = $tag->tag_name;
                                    TagCloud::create($data);
                                }
                            }
                        }
                    }elseif ($key == 'agronomy'){
                        $data['tag_type_id'] = TagType::where('slug','crop')->value('id');
                        foreach ($tagDatum as $tag){
                            $tagPresent = TagCloud::where('name',$tag->tag_name)->where('tag_type_id',$data['tag_type_id'])->first();
                            if($tagPresent == null){
                                $data['name'] = $tag->tag_name;
                                TagCloud::create($data);
                            }
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
            if(isset($request->crm_cust_id)){
                $tagTypeId = null;
                if(isset($request->tag_type)){
                    $tagTypeId = TagType::where('slug',$request->tag_type)->value('id');
                }else{
                    $tagTypeId = TagCloud::where('id',$request->tag_id)->value('tag_type_id');
                }
                $check = CustomerTagRelation::where('crm_customer_id',$request->crm_cust_id)
                    ->where('tag_cloud_id',$request->tag_id)->where('tag_type_id',$tagTypeId)->first();
                    if(!$check){
                        CustomerTagRelation::create([
                            'user_id' => $user['id'],
                            'crm_customer_id' => $request->crm_cust_id,
                            'tag_cloud_id' => $request->tag_id,
                            'tag_type_id' => $tagTypeId
                        ]);
                    }elseif($check['is_deleted']){
                        $check->update(['is_deleted' => false]);
                    }
            }
        }catch (\Exception $exception){
            $data = [
                'action' => 'Create/Edit Assign new tag',
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
                $relevantResult = $this->getRelevantResult($request->tag_name, $request->tag_type);
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
    public function getRelevantResult($keyword, $tagType)
    {
        $relevantData = array();
        $searchResultsTake = env('SEARCH_RESULT');
        $keywordLower = strtolower($keyword);
        if(isset($tagType)){
            $tagTypeId = TagType::where('slug',$tagType)->value('id');
            $tagsDataArray = TagCloud::where('is_active',1)->where('tag_type_id',$tagTypeId)
                ->select('id','name')->orderBy('created_at','desc')
                ->take($searchResultsTake)->skip(0)->get()->toArray();
        }else{
            $tagsDataArray = TagCloud::where('is_active',1)->select('id','name')->orderBy('created_at','desc')->take($searchResultsTake)->skip(0)->get()->toArray();
        }
        $tags = $this->getTags($keywordLower,$searchResultsTake, $tagType);
        $tag = $tags['data'];
        if($tags['condition']){
            $tagCount = count($tag);
            $tagDataCount = count($tagsDataArray);
            $max = max($tagCount,$tagDataCount);
            $k = 0;
            for($i = 0 ; $i  < $max ; $i++) {
                if(!empty($tag[$i])) {
                    $relevantData[$k]['id'] = $tag[$i]['id'];
                    $relevantData[$k]['tag_type_id'] = $tag[$i]['tag_type_id'];
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
        }
        return $relevantData;
    }
    public function getTags($keywordLower,$searchResultsTake, $tagType) {
        if(isset($tagType)){
            $tagTypeId = TagType::where('slug',$tagType)->value('id');
            $tagsDataArray = TagCloud::where('name','like','%'.$keywordLower.'%')
                ->where('is_active',1)
                ->where('tag_type_id',$tagTypeId)
                ->orderBy('id','desc')
                ->select('id','name','tag_type_id')
                ->take($searchResultsTake)->skip(0)->get()->toArray();
        }else{
            $tagsDataArray = TagCloud::where('name','like','%'.$keywordLower.'%')
                ->where('is_active',1)
                ->orderBy('id','desc')
                ->select('id','name','tag_type_id')
                ->take($searchResultsTake)->skip(0)->get()->toArray();
        }
        $k = 0;
        $tagData = array();
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
                $tagData[$k]['tag_type_id'] = $tag['tag_type_id'];
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
