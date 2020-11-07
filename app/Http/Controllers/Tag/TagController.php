<?php

namespace App\Http\Controllers\Tag;

use App\CustomerNumberStatus;
use App\CrmCustomer;
use App\Reminder;
use App\TagCloud;
use App\UserRoles;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\CallStatus;
use App\SalesChat;
use App\User;
use Illuminate\Support\Facades\Session;
use Ixudra\Curl\Facades\Curl;

class TagController extends Controller
{
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
                TagCloud::where('id',$request->tag_id)->update(['name' => $request->tag_name]);
            }else{
                TagCloud::create(['name' => $request->tag_name]);
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

}
