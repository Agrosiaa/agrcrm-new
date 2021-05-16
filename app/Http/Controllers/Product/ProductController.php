<?php

namespace App\Http\Controllers\Product;

use App\CustomerNumberStatus;
use App\CrmCustomer;
use App\CustomerTagRelation;
use App\LoggedCustomerProfile;
use App\Reminder;
use App\TagCloud;
use App\TagType;
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

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->user = Auth::user();
    }
    public function manage(Request $request){
        try{
            $user = Auth::user();
            $role = UserRoles::where('id',$user['role_id'])->value('slug');
            return view('backend.product.manage')->with(compact('user','role'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get product manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
    public function productListing(Request $request){
        try{
            $products = Curl::to(env('BASE_URL')."/product-list")
                ->withData( array('retrieve' => 'ids'))->asJson()->post();
            $tableData = $request->all();
            $searchData = NULL;
            $orderName=null;
            if(!empty($products->productIds)){
                $resultFlag = true;
                // Search with root category
                if($request->has('root_category') && $tableData['root_category']!=""){
                    $products = Curl::to(env('BASE_URL')."/product-list")
                        ->withData( array(  'filter' => true , 'root_category' => $tableData['root_category'],'ids' => $products->productIds))->asJson()->post();
                    if(empty($products->productIds)){
                        $resultFlag = false;
                    }
                }
                // Search with sub category
                if($request->has('sub_category') && $tableData['sub_category']!=""){
                    $products = Curl::to(env('BASE_URL')."/product-list")
                        ->withData( array(  'filter' => true , 'sub_category' => $tableData['sub_category'],'ids' => $products->productIds))->asJson()->post();
                    if(empty($products->productIds)){
                        $resultFlag = false;
                    }
                }
                // Search with Item head
                if($request->has('itemhead') && $tableData['itemhead']!=""){
                    $products = Curl::to(env('BASE_URL')."/product-list")
                        ->withData( array(  'filter' => true , 'itemhead' => $tableData['itemhead'],'ids' => $products->productIds))->asJson()->post();
                    if(empty($products->productIds)){
                        $resultFlag = false;
                    }
                }
                // Filter Customer listing with respect product
                if($resultFlag == true && $request->has('product') && $tableData['product']!=""){
                    $products = Curl::to(env('BASE_URL')."/product-list")
                        ->withData( array(  'filter' => true,'product' => $tableData['product'],'ids' => $products->productIds))->asJson()->post();
                    if(empty($products->productIds)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('quantity') && $tableData['quantity']!=""){
                    $products = Curl::to(env('BASE_URL')."/product-list")
                        ->withData( array(  'filter' => true,'quantity' => $tableData['quantity'], 'ids' => $products->productIds))->asJson()->post();
                    if(empty($products->productIds)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('company') && $tableData['company']!=""){
                    $products = Curl::to(env('BASE_URL')."/product-list")
                        ->withData( array(  'filter' => true,'company' => $tableData['company'],'ids' => $products->productIds))->asJson()->post();
                    if(empty($products->productIds)){
                        $resultFlag = false;
                    }
                }

                if($resultFlag == true && $request->has('price') && $tableData['price']!=""){
                    $products = Curl::to(env('BASE_URL')."/product-list")
                        ->withData( array( 'filter' => true, 'price' => $tableData['price'], 'ids' => $products->productIds))->asJson()->post();
                    if(empty($products->productIds)){
                        $resultFlag = false;
                    }
                }

                $iTotalRecords = count($products->productIds);
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);
                $records = array();
                $records["data"] = array();
                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;
                $limitedOrders = Curl::to(env('BASE_URL')."/product-list")
                    ->withData(array('retrieve' => 'data','ids' => $products->productIds))->asJson()->post();
                for($i=0,$j = $iDisplayStart; $j < $end; $i++,$j++) {
                    $records["data"][] = array(
                        $limitedOrders[$j]->root_category_name,
                        $limitedOrders[$j]->subcategory_name,
                        $limitedOrders[$j]->itemhead_name,
                        $limitedOrders[$j]->product_name,
                        $limitedOrders[$j]->company,
                        $limitedOrders[$j]->discounted_price,
                        $limitedOrders[$j]->quantity,
                        '<a class="btn btn-sm btn-view btn-primary" target="_blank" href="/product/view/'.$limitedOrders[$j]->id.'">View</a>',
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
    public function view(Request $request, $id){
        try{
            $response = Curl::to(env('BASE_URL')."/product-details")
                ->withData(array('id' => $id))->asJson()->get();
            $categoryData = (array)$response->categoryData;
            $brand = (array)$response->brand;
            $productStatus = (array)$response->productStatus;
            $product = (array)$response->product;
            $featureMaster = $response->featureMaster;
            $productImageArray = $response->productImageArray;
            //dd($productImageArray);
            return view('backend.product.detail')->with(compact('response','product','productImageArray','productStatus','featureMaster','brand','categoryData'));
            //return view('backend.product.detail')->with(compact('hsnCodes','categories','searchedData','sellerAddresses','featureOptions','brandList','sellerMaster','categoryId','alltaxes'));
        }catch(\Exception $exception){
            $data =[
                'action' => 'get crm manage page',
                'exception' => $exception->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$exception->getMessage());
        }
    }
}
