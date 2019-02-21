<?php
namespace App\Http\Controllers\CustomTraits;
use App\Category;
use App\Http\Requests\Web\Seller\ProductQueryRequest;
use App\Http\Requests\Web\Seller\ProductQueryStatusRequest;
use App\Product;
use App\ProductCategoryRelation;
use App\ProductQueryConversation;
use App\ProductQueryStatus;
use App\Role;
use App\Seller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

trait ProductQuery{

    public function productQueryView(){
        try{
            $productStatus['slug'] = "";
            if($this->userRoleType=='seller'){
               $queryStatus = ProductQueryStatus::where('slug','query_raised')->first();
               Product::where('seller_id',$this->seller->id)->where('product_query_status_id',$queryStatus->id)->update(array('product_read_status'=>1));
                return view('backend.common.queries')->with(compact('productStatus'));;
            }if($this->userRoleType=='superadmin'){
                $queryStatus = ProductQueryStatus::where('slug', 'query_resolved')->first();
                Product::where('product_query_status_id', $queryStatus->id)->update(array('product_read_status_operational' => 1));
                return view('backend.superadmin.products.queries')->with(compact('productStatus'));
            }
            else {
               $queryStatus = ProductQueryStatus::where('slug', 'query_resolved')->first();
               Product::where('product_query_status_id', $queryStatus->id)->update(array('product_read_status' => 1));
               return view('backend.admin.product.queries')->with(compact('productStatus'));
            }
            return view('backend.common.queries')->with(compact('productStatus'));
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Query status displayed',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function productQuery(Request $request){
        try{
            if($this->userRoleType=='seller'){
                $queryStatus = ProductQueryStatus::whereNotIn('slug',['pending','admin_approved'])->lists('id');
                $products = Product::where('seller_id',$this->seller->id)->whereIn('product_query_status_id',$queryStatus)->orderBy('product_query_status_id','asc')->get();
            }else{

                $queryStatus = ProductQueryStatus::whereNotIn('slug',['pending','admin_approved'])->lists('id');
                $products = Product::whereIn('product_query_status_id',$queryStatus)->orderBy('product_query_status_id','desc')->get();
            }
            if(!$products->isEmpty()){
                $iTotalRecords = $products->count();
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $status_list = array(
                    array("default" => "Enabled"),
                    array("default" => "Disabled")
                );
                $status1_list = array(
                    array("default" => "Query Raised"),
                    array("default" => "Pending")
                );
                if($this->userRoleType=='seller'){
                    $limitedProducts = Product::where('seller_id',$this->seller->id)->whereIn('product_query_status_id',$queryStatus)->orderBy('product_query_status_id','asc')->take($iDisplayLength)->skip($iDisplayStart)->get()->toArray();
                }else{
                    $limitedProducts = Product::whereIn('product_query_status_id',$queryStatus)->orderBy('product_query_status_id','desc')->take($iDisplayLength)->skip($iDisplayStart)->get()->toArray();
                }
                for($j=0,$i = $iDisplayStart; $i < $end; $i++,$j++) {
                    $status = $status_list[rand(0, 1)];
                    $status1 = $status1_list[rand(0, 1)];

                    $categoryId = ProductCategoryRelation::findOrFail($limitedProducts[$j]['id']);
                    $category = Category::findOrFail($categoryId->category_id);
                    $sellerId = Seller::findOrFail($limitedProducts[$j]['seller_id']);
                    $sellerName = User::findOrFail($sellerId->user_id);
                    $latestDateQuery = ProductQueryConversation::where('product_id',$limitedProducts[$j]['id'])->orderby('id','DESC')->first()->toArray();
                    $productId = $limitedProducts[$j]['id'];
                    $name = $limitedProducts[$j]['item_based_sku'];
                    $id = ($i + 1);
                    $verification_status = ProductQueryStatus::findOrFail($limitedProducts[$j]['product_query_status_id']);
                    $limitedProducts[$j]['created_at'] = date('Y-m-d',strtotime($limitedProducts[$j]['created_at']));
                    if($this->userRoleType == 'superadmin') {
                        if($verification_status->slug == "query_resolved"){
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="' . $id . '" Disabled="Disabled">',
                            '<a href="edit/' . $productId . '">' . $limitedProducts[$j]["product_name"] .'        ('.$category->sku.')'.  '</a>',
                            $limitedProducts[$j]['seller_sku'],
                            $sellerName->first_name." ".$sellerName->last_name,
                            $latestDateQuery['created_at'],
                            $verification_status->status,
                            '<a class="btn btn-sm btn-circle btn-default btn-editable" onclick="getConversationData(' . $productId . ',\''.$name.'\')">View Query</a>',
                        );
                        } else {
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="' . $id . '" Disabled="Disabled">',
                                '<a href="edit/' . $productId . '">' . $limitedProducts[$j]["product_name"] .'        ('.$category->sku.')'.  '</a>',
                                $limitedProducts[$j]['seller_sku'],
                                $sellerName->first_name." ".$sellerName->last_name,
                                $latestDateQuery['created_at'],
                                $verification_status->status,
                                '<a class="btn btn-sm btn-circle btn-default btn-editable" onclick="getConversationData(' . $productId . ',\''.$name.'\')">View Query</a>',
                            );

                        }


                    }elseif($this->userRoleType == 'admin') {
                        if($verification_status->slug == "query_resolved"){
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="' . $id . '" Disabled="Disabled">',
                                '<a href="edit/' . $productId . '">' . $limitedProducts[$j]["product_name"] .'        ('.$category->sku.')'.  '</a>',
                                $limitedProducts[$j]['seller_sku'],
                                $sellerName->first_name." ".$sellerName->last_name,
                                $latestDateQuery['created_at'],
                                $verification_status->status,
                                '<a class="btn btn-sm btn-circle btn-default btn-editable" onclick="getConversationAdminData(' . $productId . ',\''.$name.'\')">View Query</a> <a class="btn btn-sm btn-circle btn-default btn-editable btn-approve" href="/verification/product/approve/'.$productId.'">Approve</a>',
                            );
                        } else {
                            $records["data"][] = array(
                                '<input type="checkbox" name="id[]" value="' . $id . '" Disabled="Disabled">',
                                '<a href="edit/' . $productId . '">' . $limitedProducts[$j]["product_name"] .'        ('.$category->sku.')'.  '</a>',
                                $limitedProducts[$j]['seller_sku'],
                                $sellerName->first_name." ".$sellerName->last_name,
                                $latestDateQuery['created_at'],
                                $verification_status->status,
                                '<a class="btn btn-sm btn-circle btn-default btn-editable" onclick="getConversationAdminData(' . $productId . ',\''.$name.'\')">View Query</a>',
                            );

                        }



                    }
                    else {
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="' . $id . '" Disabled="Disabled">',
                            '<a href="edit/' . $productId . '">' . $limitedProducts[$j]["product_name"] . '</a>',
                            $limitedProducts[$j]['seller_sku'],
                            $category->slug,
                            $latestDateQuery['created_at'],
                            $verification_status->status,
                            '<a class="btn btn-sm btn-circle btn-default btn-editable" onclick="getConversationData(' . $productId . ',\''.$name.'\')">View Query</a>',
                        );
                    }
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
        }catch (\Exception $e){
            $records = '';
        }
        return response()->json($records);
    }

    /* Put This in seperate Trait */
    public function getProfileImage($id,$roleType,$profileImage){
        try{
            if($roleType=='seller'){
                $UploadPath = env('SELLER_FILE_UPLOAD');
            }elseif($roleType=='admin'){
                $UploadPath = null;
            }

            if(!empty($profileImage)){
                $OwnDirecory = $UploadPath."/".sha1($id)."/"."profile_image/".$profileImage;
                return $OwnDirecory;
            }else{
                return '/assets/pages/img/no-image.png';
            }
        }catch(\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    function humanTiming ($time)
    {

        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }

    }
    public function getProductQueryConversation(ProductQueryRequest $request,$take=10,$skip=0){
        try{
            $conversationData = ProductQueryConversation::where('product_id',$request->id)->orderBy('created_at', 'desc')
                ->take($take)->skip($skip)->get();
            $user = Auth::user();
            $role = Role::where('id',$user->role_id)->first();
            if($role->slug == 'superadmin'){
                Product::where('id',$request->id)->update(array('product_read_status_operational' => true));
            }else{
                Product::where('id',$request->id)->update(array('product_read_status' => true));
            }
            if(!$conversationData->isEmpty()){
                $i = 0;
                foreach($conversationData as $conversation){
                    $user = User::findorFail($conversation->from_id);
                    $role = Role::findOrFail($user->role_id);
                    $chatHistoryData[$i]['userName'] = ucfirst($user->first_name)." ".ucfirst($user->last_name);
                    $chatHistoryData[$i]['time'] = $time = $this->humanTiming(strtotime($conversation->created_at));
                    if($role->slug=="seller"){
                        $chatHistoryData[$i]['class'] = 'info';
                        $chatHistoryData[$i]['status'] = 'Resolved';
                    }else{
                        $chatHistoryData[$i]['class'] = 'danger';
                        $chatHistoryData[$i]['status'] = 'Query Raised';
                    }
                    $chatHistoryData[$i]['image'] = $this->getProfileImage($user->id,$role->slug,$user->profile_image);
                    $chatHistoryData[$i]['message'] = $conversation['conversation'];
                    $i++;
                }
                return view('backend.partials.common.product-query-conversation')->with(compact('chatHistoryData'));
            }else{
                return response('Data Not found',404);
            }
        }catch (\Exception $e){
            return response('Something went wrong',500);
        }
    }

    public function getProductQueryCount(Request $request){
        try{
            $user = Auth::user();
            $seller = Seller::where('user_id',$user->id)->first();
            $role = Role::where('id',$user->role_id)->first();
            if($role->slug=='seller'){
                $queryStatus = ProductQueryStatus::where('slug','query_raised')->first();
                $productCount = Product::where('seller_id',$seller->id)->where('product_query_status_id',$queryStatus->id)->where('product_read_status',$queryStatus->id)->count();
            }elseif($role->slug=='admin'){
                $queryStatus = ProductQueryStatus::where('slug','query_resolved')->first();
                $productCount = Product::where('product_query_status_id',$queryStatus->id)->where('product_read_status',$queryStatus->id)->count();
            }else{
                $queryStatus = ProductQueryStatus::where('slug','query_resolved')->first();
                $productCount = Product::where('product_query_status_id',$queryStatus->id)->where('product_read_status_operational',$queryStatus->id)->count();
            }
            $response = [
                'message' => 'Done',
                'count' => $productCount
            ];
            $status = 200;
        }catch (\Exception $e){
            $response = [
                'action' => 'Get query Count',
                'message' => $e->getMessage()
            ];
            Log::critical(json_encode($response));
            $status = 500;
        }
        return response()->json($response,200);
    }
    public function productQueryResolved(ProductQueryStatusRequest $request){
        try{
            $queryStatus = ProductQueryStatus::where('slug','query_resolved')->first();
            Product::where('id','=',$request->product_id)->update(array('product_query_status_id'=>$queryStatus->id,'product_read_status'=>$queryStatus->id,'product_read_status_operational'=>$queryStatus->id));
            $date = Carbon::now();
            $statusData = [
                'product_id' => $request->product_id,
                'from_id' => $this->user->id,
                'conversation' => trim($request->conversation),
                'created_at' => $date,
                'updated_at' => $date
            ];
            ProductQueryConversation::create($statusData);
            $message = 'Status updated successfully';
            $request->session()->flash('success', $message);
            return back();
        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'seller query resolved',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function productQueryRaised(ProductQueryStatusRequest $request){
        try{
            $queryStatus = ProductQueryStatus::where('slug','query_raised')->first();
            Product::where('id','=',$request->product_id)->update(array('product_query_status_id'=>$queryStatus->id,'product_read_status'=>$queryStatus->id,'product_read_status_operational'=>$queryStatus->id));
            $date = Carbon::now();
            $statusData = [
                'product_id' => $request->product_id,
                'from_id' => $this->user->id,
                'conversation' => trim($request->conversation),
                'created_at' => $date,
                'updated_at' => $date
            ];
            ProductQueryConversation::create($statusData);
            if($this->userRoleType == 'superadmin'){
                return redirect('operational/products/queries');
            }else{
                return redirect('verification/product/queries');
            }

        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'admin query raised',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    
    public function getProductQueryStatus(ProductQueryRequest $request){
        try{
            $product = Product::findOrFail($request->id);
            $productStatus = ProductQueryStatus::findOrFail($product->product_query_status_id);
            $userRole = $request->session()->get('role_type');
            if($userRole == 'admin' || $userRole == 'superadmin'){
                if($productStatus->slug == 'query_resolved' || $productStatus->slug == 'pending'){
                    return "show";
                }else{
                    return "hide";
                }
            }elseif($userRole == 'seller'){
                if($productStatus->slug == 'query_raised'){
                    return "show";
                }else{
                    return "hide";
                }
            }

        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Get product query status.',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }

    }
}