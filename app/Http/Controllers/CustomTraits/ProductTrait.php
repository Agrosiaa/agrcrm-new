<?php
namespace App\Http\Controllers\CustomTraits;

use App\BrandCategory;
use App\Category;
use App\CategoryHSNCodeTaxRelation;
use App\Feature;
use App\FeatureOption;
use App\HSNCodes;
use App\HSNCodeTaxRelation;
use App\Language;
use App\ProductCategoryRelation;
use App\ProductFeatureRelation;
use App\ProductImage;
use App\ProductQueryConversation;
use App\ProductTranslation;
use App\Seller;
use App\SellerAddress;
use App\Tax;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\Web\Seller\ProductRequest;
use App\Product;
use App\ProductQueryStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

trait ProductTrait{


    public function getCategory(){
        try{
            $categories = Category::where('category_id',null)->where('is_active',1)->get();
            if(!$categories->isEmpty()){
                $categories = $categories->toArray();
                $rootCategoryCount = count($categories);
                for($i=0;$i<$rootCategoryCount;$i++){
                    $subCategory = Category::where('category_id',$categories[$i]['id'])->where('is_active',1)->get();
                    if(!$subCategory->isEmpty()){
                        $categories[$i]['subCategory'] = $subCategory->toArray();
                    }
                }
                for($i=0;$i<$rootCategoryCount;$i++){
                    if (array_key_exists("subCategory",$categories[$i])){
                        $subCategoryCount = count($categories[$i]['subCategory']);
                        for($j=0;$j<$subCategoryCount;$j++){
                            $subSubCategory = Category::where('category_id',$categories[$i]['subCategory'][$j]['id'])->where('is_active',1)->get();
                            if(!$subSubCategory->isEmpty()){
                                $categories[$i]['subCategory'][$j]['subSubCategory'] = $subSubCategory->toArray();
                            }
                        }
                    }
                }
            }else{
                $categories = null;
            }
            return $categories;
        }catch (\Exception $e){
            $data = [
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Get category',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    function findCategoryBySlug($search_value) {
        $categories = $this->getCategory();
        $allCategoryWithSelected = $categories;
        $selectedCategory['flag'] = false;
        $selectedCategory['data'] = null;
        $selectedCategory['selected'] = null;
        $i = 0;
        foreach($categories as $category){
            if(array_key_exists("subCategory",$category)){
                if($category['slug']==$search_value){
                    $selectedCategory['flag'] = true;
                    $selectedCategory['data'] = $category;
                    $allCategoryWithSelected[$i]['selected'] = true;
                }
                $j = 0;
                foreach($category['subCategory'] as $subCategory){
                    if($subCategory['slug']==$search_value){
                        $selectedCategory['flag'] = true;
                        $selectedCategory['data'] = $subCategory;
                        $allCategoryWithSelected[$i]['subCategory'][$j]['selected'] = true;
                    }
                    if(array_key_exists("subSubCategory",$subCategory)){
                        $k = 0;
                        foreach($subCategory['subSubCategory'] as $subSubCategory){
                            if($subSubCategory['slug']==$search_value){
                                $selectedCategory['flag'] = true;
                                $selectedCategory['data'] = $subSubCategory;
                                $allCategoryWithSelected[$i]['subCategory'][$j]['subSubCategory'][$k]['selected'] = true;
                            }
                            $k++;
                        }
                    }
                    $j++;
                }
            }
            $i++;
        }
        $selectedCategory['selected'] = $allCategoryWithSelected;
        return $selectedCategory;
    }

    public function categoryProductListing(Request $request){
        try{
            $searchedData = $this->findCategoryBySlug($request->current_category);
            if($searchedData['flag']==true){
                if($this->userRoleType=='seller'){
                    $sellerProducts = Product::where('seller_id',$this->seller->id)->lists('id');
                    $iTotalRecords = ProductCategoryRelation::where('category_id',$searchedData['data']['id'])->whereIn('product_id',$sellerProducts)->count();

                }elseif($this->userRoleType=='superadmin'){
                    $iTotalRecords = ProductCategoryRelation::where('category_id',$searchedData['data']['id'])->count();

                }
                $iDisplayLength = intval($request->length);
                $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
                $iDisplayStart = intval($request->start);
                $sEcho = intval($request->draw);

                $records = array();
                $records["data"] = array();

                $end = $iDisplayStart + $iDisplayLength;
                $end = $end > $iTotalRecords ? $iTotalRecords : $end;

                $status_list = array(
                    array("success" => "Pending"),
                    array("info" => "Closed"),
                    array("danger" => "On Hold"),
                    array("warning" => "Fraud")
                );

                if($this->userRoleType=='seller'){
                    $products = ProductCategoryRelation::where('category_id',$searchedData['data']['id'])->lists('product_id');
                    $limitedProducts = Product::where('seller_id',$this->seller->id)->whereIn('id',$products)->take($iDisplayLength)->skip($iDisplayStart)->get()->toArray();

                }elseif($this->userRoleType=='superadmin'){
                    $products = ProductCategoryRelation::where('category_id',$searchedData['data']['id'])->lists('product_id');
                    $limitedProducts = Product::whereIn('id',$products)->take($iDisplayLength)->skip($iDisplayStart)->get()->toArray();

                }

               for($j=0,$i = $iDisplayStart; $i < $end; $i++,$j++) {
                    $id = ($i + 1);

                    $records["data"][$j] = array(
                        $id,
                        $limitedProducts[$j]['product_name'],
                        $searchedData['data']['sku'],
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

    public function productListing(Request $request){
        try{
            $tableData = NULL;
            if($this->userRoleType=='seller'){
                $products = $this->seller->product;
                $sellerId = array($this->seller->id);
            }elseif($this->userRoleType=='superadmin' || $this->userRoleType=='admin' || $this->userRoleType=='data-entry-admin'|| $this->userRoleType == 'vendorsupport' || $this->userRoleType == 'vendor-head-admin'){
                $sellerId = Seller::lists('id')->toArray();
            }
            $tableData = $request->all();
            $searchData = NULL;
            if($request->has('product_status') && $tableData['product_status']!="select"){
                $searchData['is_active'] = $tableData['product_status'];
            }
            if($request->has('product_query_status') && $tableData['product_query_status']!="select"){
                $searchData['product_query_status_id'] = intval($tableData['product_query_status']);
            }
            //seller_name
            if($this->userRoleType=='superadmin' || $this->userRoleType=='admin' || $this->userRoleType == 'vendorsupport' || $this->userRoleType == 'data-entry-admin' || $this->userRoleType == 'customersupport' || $this->userRoleType == 'vendor-head-admin'){
                if($request->has('seller_name') && $tableData['seller_name']!="select"){
                    $searchData['seller_id'] = intval($tableData['seller_name']);
                }
            }
            //product_name
            if($request->has('product_name') &&  $tableData['product_name']!="" ){
                if(strlen(trim($tableData['product_name'])) >= 3){
                    $product_name = trim($tableData['product_name']);
                } else{
                    $product_name ="";
                }
            }
            else{
              $product_name = "";
            }
            //item_based_sku
            if($request->has('item_based_sku') &&  $tableData['item_based_sku']!=""){
                $searchData['item_based_sku'] = trim($tableData['item_based_sku']);
            }

            if($request->has('sub_category') && $tableData['sub_category']!=""){
                if(strlen(trim($tableData['sub_category'])) >= 3){
                    $subCategoryName = trim($tableData['sub_category']);
                }else{
                    $subCategoryName = "";
                }
            }else{
                $subCategoryName = "";
            }
            $resultFlag = true;
            $productsId = NULL;
            $uniqueProductId = array();
            if($searchData == NULL){
                if($product_name == ""){
                    $products = Product::whereIn('seller_id',$sellerId)->orderBy('created_at','desc')->get();
                    $productsId = Product::whereIn('seller_id',$sellerId)->orderBy('created_at','desc')->lists('id')->toArray();
                    $uniqueProductId = array_unique($productsId);
                }else{
                    $products = Product::whereIn('seller_id',$sellerId)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','desc')->get();
                    $productsId = Product::whereIn('seller_id',$sellerId)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','desc')->lists('id')->toArray();
                    $uniqueProductId = array_unique($productsId);
                    if($products->count() == 0){
                      $resultFlag = false;
                    }
                }
            }else{
                $products = Product::whereIn('seller_id',$sellerId)->where($searchData)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','desc')->get();
                $productsId = Product::whereIn('seller_id',$sellerId)->where($searchData)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','desc')->lists('id')->toArray();
                $uniqueProductId = array_unique($productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
            }

            //sub_category
            if($resultFlag && $request->has('sub_category') &&  $tableData['sub_category']!=""){
                $tableData['sub_category'] = trim($tableData['sub_category']);
                $catId = Category::where('name','ILIKE','%'.$subCategoryName.'%')->lists('id');
                $itemHeadId = Category::whereIn('category_id',$catId)->lists('id');
                $categoryProducts = ProductCategoryRelation::whereIn('category_id',$itemHeadId)->lists('product_id')->toArray();
                if($searchData == NULL){
                    $products = Product::whereIn('seller_id',$sellerId)->wherein('id',$categoryProducts)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','asc')->get();
                    $productsId = Product::whereIn('seller_id',$sellerId)->wherein('id',$categoryProducts)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','asc')->lists('id')->toArray();
                }else{
                    $products = Product::wherein('seller_id',$sellerId)->wherein('id',$categoryProducts)->where($searchData)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','asc')->get();
                    $productsId = Product::wherein('seller_id',$sellerId)->wherein('id',$categoryProducts)->where($searchData)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('created_at','asc')->lists('id')->toArray();
                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                if($products->count() == 0){
                    $resultFlag = false;
                }
                $uniqueProductId = array_unique($productsId);
            }

            //product_sku
            if($resultFlag && $request->has('product_sku') &&  $tableData['product_sku']!=""){
                $tableData['product_sku'] = trim($tableData['product_sku']);
                $catId = Category::where('sku',$tableData['product_sku'])->first();
                $categoryProducts = ProductCategoryRelation::where('category_id',$catId['id'])->lists('product_id')->toArray();
                if($searchData == NULL){
                    $products = Product::whereIn('seller_id',$sellerId)->wherein('id',$categoryProducts)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('updated_at','desc')->get();
                    $productsId = Product::whereIn('seller_id',$sellerId)->wherein('id',$categoryProducts)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('updated_at','desc')->lists('id')->toArray();

                }else{
                    $products = Product::wherein('seller_id',$sellerId)->wherein('id',$categoryProducts)->where($searchData)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('updated_at','desc')->get();
                    $productsId = Product::wherein('seller_id',$sellerId)->wherein('id',$categoryProducts)->where($searchData)->where('product_name','ILIKE','%'.$product_name.'%')->orderBy('updated_at','desc')->lists('id')->toArray();

                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
                $uniqueProductId = array_unique($productsId);
            }
            //product_price_from   product_price_to
            if($resultFlag && $request->has('product_price_to') &&  $tableData['product_price_to']!="" && $request->has('product_price_from') &&  $tableData['product_price_from']!="" ){
                $tableData['product_price_to'] = trim($tableData['product_price_to']);
                $tableData['product_price_from'] = trim($tableData['product_price_from']);
                if($searchData == NULL){
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }else{
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->where($searchData)->orderBy('updated_at','asc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->where($searchData)->orderBy('updated_at','asc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('discounted_price', [$tableData['product_price_from'], $tableData['product_price_to']])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                $uniqueProductId = array_unique($productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
            }
            if($resultFlag && $request->has('product_price_from') &&  $tableData['product_price_from']!="" &&  $tableData['product_price_to'] == ""){
                $tableData['product_price_from'] = trim($tableData['product_price_from']);
                if($searchData == NULL){
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->orderBy('updated_at','asc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->orderBy('updated_at','asc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }else{

                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->where($searchData)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->where($searchData)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '>=', $tableData['product_price_from'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                $uniqueProductId = array_unique($productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
            }
            if($resultFlag && $request->has('product_price_to') &&  $tableData['product_price_to']!="" &&  $tableData['product_price_from']==""){
                $tableData['product_price_to'] = trim($tableData['product_price_to']);
                if($searchData == NULL){
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }else{
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->where($searchData)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->where($searchData)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('discounted_price', '<=', $tableData['product_price_to'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                $uniqueProductId = array_unique($productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
            }

            if($resultFlag && $request->has('product_quantity_from') &&  $tableData['product_quantity_from']!="" && $request->has('product_quantity_to') &&  $tableData['product_quantity_to']!=""){
                $tableData['product_quantity_from'] = trim($tableData['product_quantity_from']);
                $tableData['product_quantity_to'] = trim($tableData['product_quantity_to']);
                if($searchData == NULL){
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }else{
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->where($searchData)->orderBy('updated_at','asc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->where($searchData)->orderBy('updated_at','asc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->whereBetween('quantity', [$tableData['product_quantity_from'], $tableData['product_quantity_to']])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                $uniqueProductId = array_unique($productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
            }


            if($resultFlag && $request->has('product_quantity_from') &&  $tableData['product_quantity_from']!="" &&  $tableData['product_quantity_to']==""){
                $tableData['product_quantity_from'] = trim($tableData['product_quantity_from']);
                if($searchData == NULL){
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }else{
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->where($searchData)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->where($searchData)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '>=', $tableData['product_quantity_from'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                $uniqueProductId = array_unique($productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
            }

            if($resultFlag && $request->has('product_quantity_to') &&  $tableData['product_quantity_to']!="" &&  $tableData['product_quantity_from']==""){
                $tableData['product_quantity_to'] = trim($tableData['product_quantity_to']);
                if($searchData == NULL){
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }
                }else{
                    if($productsId == NULL){
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->where($searchData)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->where($searchData)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }else{
                        $products = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->get();
                        $productsId = Product::whereIn('seller_id',$sellerId)->where('quantity', '<=', $tableData['product_quantity_to'])->where($searchData)->wherein('id',$uniqueProductId)->orderBy('updated_at','desc')->lists('id')->toArray();
                    }

                }
                $productsId = array_intersect($uniqueProductId,$productsId);
                $uniqueProductId = array_unique($productsId);
                if($products->count() == 0){
                  $resultFlag = false;
                }
            }
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

            if($this->userRoleType == 'seller'){
              $disabled = "disabled";
            }
            else{
              $disabled = "";
            }
            $limitedProducts = $products;
            for($i = $iDisplayStart; $i < $end; $i++) {
                //$status = $status_list[rand(0, 1)];
                $status1 = $status1_list[rand(0, 1)];
                $id = ($i + 1);
                if($limitedProducts[$i]['is_active']){
                    $status = 'Enabled';
                    $lableStatus = 'success';
                }else{
                    $status = 'Disabled';
                    $lableStatus = 'danger';
                }
                $verificationStatus = ProductQueryStatus::findOrFail($limitedProducts[$i]['product_query_status_id']);
                if($verificationStatus->slug == 'pending'){
                    $lableVefificationStatus = 'warning';
                    $disabled = "";
                }elseif($verificationStatus->slug == 'query_raised'){
                    $lableVefificationStatus = 'danger';
                    $disabled = "disabled";
                }elseif($verificationStatus->slug == 'admin_approved'){
                    $lableVefificationStatus = 'success';
                    $disabled = "";
                }elseif($verificationStatus->slug == 'query_resolved'){
                    $lableVefificationStatus = 'success';
                    $disabled = "";
                }
                $categoryId = ProductCategoryRelation::where('product_id',$limitedProducts[$i]['id'])->first();
                $categoryInfo = Category::findOrFail($categoryId->category_id);
                $subCategoryName = Category::where('id',$categoryInfo['category_id'])->pluck('name');
                $rootCategoryName = Category::where('id',$categoryInfo['id'])->pluck('name');
                $userId = Seller::findOrFail($limitedProducts[$i]['seller_id']);
                $sellerName = User::findOrFail($userId->user_id);
                if($this->userRoleType == 'seller'){
                    $productId = "/product/edit/".$limitedProducts[$i]["id"];
                }elseif($this->userRoleType == 'admin'){
                    $productId = "/verification/product/edit/".$limitedProducts[$i]["id"];
                }elseif($this->userRoleType == 'superadmin' || $this->userRoleType=='data-entry-admin' || $this->userRoleType == 'vendor-head-admin'){
                    $productId = "/operational/products/edit/".$limitedProducts[$i]["id"];
                }elseif($this->userRoleType == 'vendorsupport'){
                    $productId = "/vendor-support/products/edit/".$limitedProducts[$i]["id"];
                }
                if($this->userRoleType=='seller'){
                    if($limitedProducts[$i]['is_deleted'] == false){
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="' . $limitedProducts[$i]['id'] . '"' . $disabled . '>',
                            $rootCategoryName,
                            $limitedProducts[$i]['product_name'],
                            $categoryInfo->sku,
                            $limitedProducts[$i]['discounted_price'],
                            $limitedProducts[$i]['quantity'],
                            '<span class="label label-sm label-' . $lableStatus . '">' . $status . '</span>',
                            '<span class="label label-sm label-' . $lableVefificationStatus . '">' . $verificationStatus->status . '</span>',
                            '<a href=' . $productId . ' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Edit</a>',
                        );
                    }else{
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="' . $limitedProducts[$i]['id'] . '"' . $disabled . '>',
                            '<span style="color: red">'.$rootCategoryName.'</span>',
                            '<span style="color: red">'.$limitedProducts[$i]['product_name'].'</span>',
                            '<span style="color: red">'.$categoryInfo->sku.'</span>',
                            '<span style="color: red">'.$limitedProducts[$i]['discounted_price'].'</span>',
                            '<span style="color: red">'.$limitedProducts[$i]['quantity'].'</span>',
                            '<span class="label label-sm label-' . $lableStatus . '">' . $status . '</span>',
                            '<span class="label label-sm label-' . $lableVefificationStatus . '">' . $verificationStatus->status . '</span>',
                            '<a href=' . $productId . ' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Edit</a>',
                        );
                    }
                }elseif($this->userRoleType == 'superadmin' || $this->userRoleType == 'data-entry-admin' || $this->userRoleType == 'vendorsupport'){
                    if($limitedProducts[$i]['is_deleted'] == true){
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="'.$limitedProducts[$i]['id'].'"'.$disabled.'>',
                            '<span style="color: red">'.$rootCategoryName.'</span>',
                            '<span style="color: red">'.$subCategoryName.'</span>',
                            '<span style="color: red">'.$limitedProducts[$i]['product_name'].'</span>',
                            '<span style="color: red">'.$categoryInfo->sku.'</span>',
                            '<span style="color: red">'.$sellerName->first_name."". $sellerName->last_name.'</span>',
                            '<span style="color: red">'. $limitedProducts[$i]['discounted_price'].'</span>',
                            '<span style="color: red">'.$limitedProducts[$i]['quantity'].'</span>',
                            '<span class="label label-sm label-'.$lableStatus.'">'.$status.'</span>',
                            '<span class="label label-sm label-'.$lableVefificationStatus.'">'.$verificationStatus->status.'</span>',
                            //'<a href='.$productId.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Edit</a><i class="fa fa-calculator calci-img" data-toggle="modal" data-target="#calculator" onclick="getProductCategoryTaxes('.$categoryInfo->id.')"></i>',
                            '<a href='.$productId.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Edit</a>',
                        );
                    }else{
                        $records["data"][] = array(
                            '<input type="checkbox" name="id[]" value="'.$limitedProducts[$i]['id'].'"'.$disabled.'>',
                            $rootCategoryName,
                            $subCategoryName,
                            $limitedProducts[$i]['product_name'],
                            $categoryInfo->sku,
                            $sellerName->first_name."". $sellerName->last_name,
                            $limitedProducts[$i]['discounted_price'],
                            $limitedProducts[$i]['quantity'],
                            '<span class="label label-sm label-'.$lableStatus.'">'.$status.'</span>',
                            '<span class="label label-sm label-'.$lableVefificationStatus.'">'.$verificationStatus->status.'</span>',
                            '<a href='.$productId.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Edit</a>',
                        );
                    }
                }elseif($this->userRoleType=='vendor-head-admin'){
                    $records["data"][] = array(
                        '<input type="checkbox" name="id[]" value="'.$limitedProducts[$i]['id'].'"'.$disabled.'>',
                        $rootCategoryName,
                        $subCategoryName,
                        $limitedProducts[$i]['product_name'],
                        $categoryInfo->sku,
                        $sellerName->first_name."". $sellerName->last_name,
                        $limitedProducts[$i]['discounted_price'],
                        $limitedProducts[$i]['quantity'],
                        '<span class="label label-sm label-'.$lableStatus.'">'.$status.'</span>',
                        '<span class="label label-sm label-'.$lableVefificationStatus.'">'.$verificationStatus->status.'</span>',
                        '',
                    );
                }else{
                    $records["data"][] = array(
                        '<input type="checkbox" name="id[]" value="'.$limitedProducts[$i]['id'].'"'.$disabled.'>',
                        $rootCategoryName,
                        $subCategoryName,
                        $limitedProducts[$i]['product_name'],
                        $categoryInfo->sku,
                        $limitedProducts[$i]['discounted_price'],
                        $limitedProducts[$i]['quantity'],
                        '<span class="label label-sm label-'.$lableStatus.'">'.$status.'</span>',
                        '<span class="label label-sm label-'.$lableVefificationStatus.'">'.$verificationStatus->status.'</span>',
                        '<a href='.$productId.' class="btn btn-sm btn-default btn-circle btn-editable"><i class="fa fa-pencil"></i> Edit</a>',
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
            return response()->json($records);
        }catch(\Exception $e){
            $records = '';
            return response()->json($records);
        }
    }

    public function getProductImagePath($imageName,$productOwnerId){
        try{
            $ds = DIRECTORY_SEPARATOR;
            $sellerUploadConfig = env('SELLER_FILE_UPLOAD');
            $sha1UserId = sha1($productOwnerId);
            $sellerUploadPath = public_path().$sellerUploadConfig;
            $sellerImageUploadPath = $sellerUploadPath.$sha1UserId.$ds.'product_images'.$ds.$imageName;
            /* Check file exists or not Directory If Not Exists */
            $file['status'] = false;
            if (file_exists($sellerImageUploadPath)) {
                $file['status'] = true;
            }
            $path = $sellerUploadConfig.$sha1UserId.$ds.'product_images'.$ds.$imageName;
            $file['path'] = $path;
            return $file;
        }catch(\Exception $e){
            $data = [
                'image name' => $imageName,
                'product owner id' => $productOwnerId,
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'get image path',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function getTaxes(Request $request,$hsnCodeId){
        try{
            $status = 200;
            $taxIds = HSNCodeTaxRelation::where('hsn_code_id',$hsnCodeId)->lists('tax_id');
            $taxes = Tax::whereIn('id',$taxIds)->get()->toArray();
            return response()->json($taxes,$status);
        }catch(\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function getAllTaxes(Request $request){
        try{
            $status = 200;
            $allTaxes=Tax::where('is_active',true)->get()->toArray();
            return response()->json($allTaxes,$status);
        }catch(\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    public function editProductView(ProductRequest $request,$productId){
        try{
            $product = Product::findOrFail($productId);
            $productImages = $product->images->toArray();
            $languageId = Language::where('abbreviation',$this->language)->first();
            $productTranslation = null;
            if($this->language != 'en'){
                $productTranslation = ProductTranslation::where('language_id',$languageId['id'])->where('product_id',$productId)->first();
            }
            $i = 0;
            if($this->userRoleType == 'seller'){
                $productOwnerId = $this->user->id;
            }else{
                $productOwner = Seller::findOrFail($product->seller_id);
                $productOwnerId = $productOwner->user_id;
            }
            $productImageArray = NULL;
            foreach($productImages as $productImage){
                $file = $this->getProductImagePath($productImage['name'],$productOwnerId);
                $random = mt_rand(1,10000000000);
                $productImageArray[$i]['id'] = $productImage['id'];
                $productImageArray[$i]['name'] = $productImage['name'];
                $productImageArray[$i]['path'] = $file['path'];
                $productImageArray[$i]['position'] = $productImage['position'];
                $productImageArray[$i]['product_id'] = $productImage['product_id'];
                $productImageArray[$i]['alternate_text'] = $productImage['alternate_text'];
                $productImageArray[$i]['random'] = $random;
                $productImageArray[$i]['file_exists'] = $file['status'];
                $i++;
            }
            $productStatus = ProductQueryStatus::findOrFail($product->product_query_status_id);
           if($productStatus['slug'] == 'admin_approved' ){
               $approvedBy = User::findOrFail($product->admin_id);
               $productStatus['admin_id'] = $approvedBy['first_name'];
           }
           $productQueryCount = ProductQueryConversation::where('product_id',$product->id)->count();
            $imageCount = count($productImageArray);
            $imageCount = 4 - $imageCount;
            $productCategory = ProductCategoryRelation::where('product_id',$product->id)->first();
            $categoryInfo = Category::findOrFail($productCategory->category_id);
            $sellerAddresses = SellerAddress::where('seller_id',$product->seller_id)->get();
            $productFeatures = ProductFeatureRelation::where('product_id',$productId)->with('feature')->get();
            if(!$productFeatures->isEmpty()){
                $featureIndex = 0;
                foreach ($productFeatures as $feature){

                    $featuresInfo = Feature::where('id',$feature['feature_id'])->with('inputs','measuringUnits','options')->first()->toArray();
                    if($featuresInfo['inputs']['slug']=='select'){
                        $featureOptions[$featureIndex]['required'] = $feature->feature->required;
                        $featureOptions[$featureIndex]['name'] = $feature->feature->name;
                        $featureOptions[$featureIndex]['type'] = 'select';
                        $featureOptions[$featureIndex]['measuring_unit_id'] = $feature->feature->measuring_unit_id;
                        $featureOptions[$featureIndex]['value'] = $feature->feature_option_id;
                        $optionCount =0;
                        foreach($featuresInfo['options'] as $options){
                            $featureOptions[$featureIndex]['data'][$optionCount]['id'] = $options['id'];
                            if($this->language == 'en'){
                                $featureOptions[$featureIndex]['data'][$optionCount]['name'] = $options['name'];
                            }else{
                                $featureOptions[$featureIndex]['data'][$optionCount]['name'] = $options['name_'.$this->language];
                            }

                            $optionCount++;
                        }
                    }elseif($featuresInfo['inputs']['slug']=='text' && $featuresInfo['measuring_unit_id']!=null){
                        $featureOptions[$featureIndex]['required'] = $feature->feature->required;
                        $featureOptions[$featureIndex]['name'] = $feature->feature->name;
                        $featureOptions[$featureIndex]['type'] = 'text';
                        $featureOptions[$featureIndex]['measuring_unit_id'] = $feature->feature->measuring_unit_id;
                        $featureOptions[$featureIndex]['data'] = explode(',',$featuresInfo['measuring_units']['values']);
                        if($this->language == 'en'){
                            $featureOptions[$featureIndex]['text_value'] = $feature->feature_text;
                        }else{
                            $featureOptions[$featureIndex]['text_value'] = $feature->feature_text_mr;
                        }

                        $featureOptions[$featureIndex]['select_value'] = $feature->feature_measuring_unit;

                    }elseif($featuresInfo['inputs']['slug']=='text' && $featuresInfo['measuring_unit_id']==null){
                        $featureOptions[$featureIndex]['required'] = $feature->feature->required;
                        $featureOptions[$featureIndex]['name'] = $feature->feature->name;
                        $featureOptions[$featureIndex]['type'] = 'text';
                        $featureOptions[$featureIndex]['measuring_unit_id'] = $feature->feature->measuring_unit_id;
                        if($this->language == 'en'){
                            $featureOptions[$featureIndex]['value'] = $feature->feature_text;
                        }else{
                            $featureOptions[$featureIndex]['value'] = $feature->feature_text_mr;
                        }

                    }
                    $featureIndex++;
                }
            }else{
                $featureOptions = null;
            }
            $searchedData = $this->findCategoryBySlug($categoryInfo->slug);
            $brandList = null;
            $brandMaster = BrandCategory::where('category_id',$productCategory->category_id)->with('brands')->get();
            if(!$brandMaster->isEmpty()){
                foreach($brandMaster as $brandMasterOptions){
                    if($this->language != 'en'){
                        $name = 'name_'.$this->language;
                        $brandList[$brandMasterOptions['brands']->id] = $brandMasterOptions['brands']->$name;
                    }else{

                        $brandList[$brandMasterOptions['brands']->id] = $brandMasterOptions['brands']->name;
                    }
                }
                $brandList = array_sort($brandList, function ($value) {
                    return $value;
                });
            }

            if($searchedData['flag']==true){
                $product['hsn_code_tax_data'] = HSNCodeTaxRelation::where('id',$product['hsn_code_tax_relation_id'])->first();
                $categoryHsnCodeTaxRelationIds = CategoryHSNCodeTaxRelation::where('category_id',$productCategory->category_id)->lists('hsn_code_tax_relation_id')->toArray();
                $hsnCodeIDs = HSNCodeTaxRelation::whereIn('id',$categoryHsnCodeTaxRelationIds)->distinct('hsn_code_id')->select('hsn_code_id')->get();

                $hsnCodes = HSNCodes::whereIn('id',$hsnCodeIDs)->get()->toArray();
                if($product['hsn_code_tax_data'] != null){
                    $taxIds = HSNCodeTaxRelation::where('hsn_code_id',$product['hsn_code_tax_data']['hsn_code_id'])->select('tax_id')->get();
                    $taxes = Tax::whereIn('id',$taxIds)->get()->toArray();
                }else{
                    $taxIds = HSNCodeTaxRelation::whereIn('hsn_code_id',$hsnCodeIDs)->select('tax_id')->get();
                    $taxes = Tax::whereIn('id',$taxIds)->get()->toArray();
                }
                $alltaxes = Tax::where('is_active',true)->get()->toArray();
                $discountedBasePrice = number_format((float)(($product['base_price'] - ($product['discount'] / 100) * $product['base_price'])), 2, '.', '');
                $commissionAmount = number_format((float)(($categoryInfo->commission / 100) * $discountedBasePrice), 2, '.', '');
                $logisticAmount =   number_format((float)((8.5/100) * $product['base_price']), 2, '.', '');
                $product['gst_on_commission'] = number_format((float)((18/100) * $commissionAmount), 2, '.', '');
                $product['gst_on_logistic'] = number_format((float)((18/100) * $logisticAmount), 2, '.', '');
                $categories = $searchedData['selected'];
                if($productStatus['slug'] == 'admin_approved'){
                   if($this->userRoleType=='superadmin' || $this->userRoleType == 'data-entry-admin') {
                       return view('backend.seller.product.edit')->with(compact('hsnCodes','product','taxes','categories','productImageArray','imageCount','productStatus','productQueryCount','categoryInfo','sellerAddresses','brandList','featureOptions','productTranslation','alltaxes'));
                   } else {
                       return view('backend.seller.product.edit-approved')->with(compact('hsnCodes','product','taxes','categories','productImageArray','imageCount','productStatus','productQueryCount','categoryInfo','sellerAddresses','brandList','featureOptions','productTranslation','alltaxes'));
                   }
                }else{
                    return view('backend.seller.product.edit')->with(compact('hsnCodes','product','taxes','categories','productImageArray','imageCount','productStatus','productQueryCount','categoryInfo','sellerAddresses','brandList','featureOptions','productTranslation','alltaxes'));
                }
            }
        }catch (\Exception $e){
            Log::critical('Product Edit View: user:'.$this->user.' request:'.json_encode($request->all()).' productId:'.$productId.' exception:'.$e->getMessage());
            abort(500,$e->getMessage());
        }
    }

    public function getImagePath($imageName,$productOwnerId){
        try{
            $ds = DIRECTORY_SEPARATOR;
            $sellerUploadConfig = env('SELLER_FILE_UPLOAD');
            if($this->userRoleType=='seller'){
                $sha1UserId = sha1($this->user->id);
            }else{
                $sha1UserId = sha1($productOwnerId);
            }
            $sellerUploadPath = public_path().$sellerUploadConfig;
            $sellerImageUploadPath = $sellerUploadPath.$sha1UserId.$ds.'product_images';
            /* Check file exists or not Directory If Not Exists */
            $file['status'] = false;
            if (file_exists($sellerImageUploadPath)) {
                $file['status'] = true;
            }
            $path = $sellerUploadConfig.$sha1UserId.$ds.'product_images';
            $file['path'] = $path;
            return $file;
        }catch(\Exception $e){
            $data = [
                'image name' => $imageName,
                'product owner id' => $productOwnerId,
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'get image path',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function editProduct(ProductRequest $request, $id){
        try{
            $currentTime = Carbon::now();
            $productData = $request->all();
            $product = Product::findOrFail($id);
            if($productData['quantity'] > $product['quantity'] && $productData['minimum_quantity'] != 0){
               $product->update(['out_of_stock_date' => null]);
            }elseif($productData['quantity'] == 0 || $productData['minimum_quantity'] == 0 || $productData['minimum_quantity'] > $productData['quantity'] ){
                $outOfStockDate = Carbon::now();
                $product->update(['out_of_stock_date' => $outOfStockDate]);
            }
            $count = 0;
            if($this->userRoleType=='superadmin'){
                $keys = array('master_sku','slug','approved_date','seller_id','category_id','product_images','features','imageArray');
            }else{
                $keys = array('master_sku','slug','approved_date','seller_id','category_id','is_active','product_images','features','imageArray');
            }
            $productData = $this->unsetKeys($keys,$productData);
            unset($productData['base_price']);
            $productData['selling_price'] = $productData['selling_price_without_discount'];
            $productData['subtotal'] = $productData['subtotal_final'];
            $productData['base_price'] = $productData['base_price_final'];
            $productData['commission_percent'] = $productData['commission'];
            $productData['logistic_percent'] = $productData['logistic_tax'];
            if($request->has('hsn_code')){
                $productData['hsn_code_tax_relation_id'] = HSNCodeTaxRelation::where('hsn_code_id', $productData['hsn_code'])->where('tax_id', $productData['tax_id'])->pluck('id');
                if($productData['hsn_code_tax_relation_id'] == null){
                    $hsnCodeTaxRelationData = [
                        'hsn_code_id' => $request->hsn_code,
                        'tax_id' => $request->tax_id
                    ];
                    $hsnCodeTaxRelation = HSNCodeTaxRelation::create($hsnCodeTaxRelationData);
                    $hsnCodeCategoryRelation['hsn_code_tax_relation_id'] = $hsnCodeTaxRelation->id;
                    $hsnCodeCategoryRelation['category_id'] = $product->productCategoryRel->category_id;
                    $productData['hsn_code_tax_relation_id'] = $hsnCodeTaxRelation->id;
                    $query= CategoryHSNCodeTaxRelation::create($hsnCodeCategoryRelation);
                }
            }else{
               $hsnCodeData = ['hsn_code' => $request->new_hsn_code];
               $hsnCode = HSNCodes::create($hsnCodeData);
               $hsnCodeData = ['hsn_code_id' => $hsnCode->id];
               $hsnCodeData['tax_id'] = $request->tax_id;
               $hsnCodeTaxRelation = HSNCodeTaxRelation::create($hsnCodeData);
               $hsnCodeCategoryRelation['hsn_code_tax_relation_id'] = $hsnCodeTaxRelation->id;
               $hsnCodeCategoryRelation['category_id'] = $product->productCategoryRel->category_id;
               $productData['hsn_code_tax_relation_id'] = $hsnCodeTaxRelation->id;
               $query= CategoryHSNCodeTaxRelation::create($hsnCodeCategoryRelation);
            }
            if($request->has('configurable_width')){
                $productData['configurable_width'] = $request->configurable_width;
            }
            unset($productData['hsn_code']);
            $productData = array_map('trim', $productData);
            $productData['product_name'] = strtolower($productData['product_name']);
            $productData['search_keywords'] = strtolower($productData['search_keywords']);
            $languageId = Language::where('abbreviation',$this->language)->first();
            $productTranslation = null;
            if($request->has('approve')){
                $queryStatus = ProductQueryStatus::where('slug','admin_approved')->first();
                $productData['approved_date'] = $currentTime;
                $productData['is_active'] = true;
                $productData['product_query_status_id'] = $queryStatus->id;
                $productData['product_query_status_id'] = $queryStatus->id;
                $productData['admin_id'] = $this->user->id;
            }
            if(array_key_exists('is_ps_campaign_checked',$productData)){
                $productData['is_ps_campaign'] = true;
                $productData['agrosiaa_campaign_charges'] = $request->agrosiaa_campaign_charges;
                $productData['vendor_campaign_charges'] = $request->vendor_campaign_charges;
            }else{
                $productData['is_ps_campaign'] = false;
                $productData['agrosiaa_campaign_charges'] = null;
                $productData['vendor_campaign_charges'] = null;
            }
            if($this->language != 'en'){
                $marathiColumn = array('product_name','key_specs_1','key_specs_2','key_specs_3','search_keywords','product_description',
                    'other_features_and_applications','sales_package_or_accessories','domestic_warranty',
                    'domestic_warranty_measuring_unit','warranty_summary','warranty_service_type','warranty_items_covered',
                    'warranty_items_not_covered');
                $productTranslation = ProductTranslation::where('language_id',$languageId['id'])->where('product_id',$id)->first();
                $productData['language_id'] = $languageId->id;
                if($productTranslation == null){
                    if($productData['product_name'] == null){
                        $productNameTranslation = array();
                        $productNameTranslation['product_name'] = $product['product_name'];
//                        $marathiName = $this->marathiName($productNameTranslation,$id);
                    }
                    $productData['product_id'] = $id;
                    $productTranslation = ProductTranslation::create($productData);
                }else{
                    $MarathiUpdatedProduct = $productTranslation->update($productData);
                }
                $englishColumn = $this->unsetKeys($marathiColumn,$productData);
                $updatedProduct = $product->update($englishColumn);
            }else{
                /*$verificationStatus = ProductQueryStatus::findOrFail($product['product_query_status_id']);
                if(!($verificationStatus->slug == 'admin_approved' && $this->userRoleType=='seller')){
                    $marathiColumn1 = $request->only('product_name','key_specs_1','key_specs_2','key_specs_3','search_keywords','product_description',
                        'other_features_and_applications','sales_package_or_accessories','domestic_warranty',
                        'domestic_warranty_measuring_unit','warranty_summary','warranty_service_type','warranty_items_covered',
                        'warranty_items_not_covered');
                    $marathiColumn = array();
                    foreach ($marathiColumn1 as $key => $value) {
                        if($value != "" || $value !=null){
                            $marathiColumn[$key] = $value;
                        }
                    }
                    $marathiName = $this->marathiName($marathiColumn,$id);
                }*/
                $updatedProduct = $product->update($productData);
            }
            $category = Category::where('slug',$productData['category'])->first();
            if($request->features != null){
                $productFeatures = $request->features;
                $timeStamp = Carbon::now();
                foreach($productFeatures as $key => $value) {
                    foreach($value as $innerKey => $innerValue){
                        $feature = Feature::where('name',$innerKey)->where('category_id',$category->id)->with('inputs')->first();
                        if($feature!=null){
                            if($feature->inputs->slug=='select'){
                                if($innerValue!= ""){
                                $options = FeatureOption::where('id',$innerValue)->where('feature_id',$feature->id)->first();
                                if($options!=null){
                                    $featureArray = array(
                                        'product_id' => $id,
                                        'feature_id' => $feature->id,
                                        'feature_text'=>null,
                                        'feature_measuring_unit'=>null,
                                        'feature_option_id' => $options->id,
                                        'created_at' => $timeStamp,
                                        'updated_at' => $timeStamp,
                                    );
                                }
                                }else{
                                    $featureArray = array(
                                        'product_id' => $id,
                                        'feature_id' => $feature->id,
                                        'feature_text'=>null,
                                        'feature_measuring_unit'=>null,
                                        'feature_option_id' => null,
                                        'created_at' => $timeStamp,
                                        'updated_at' => $timeStamp,
                                    );
                                }
                            }
                            if($feature->inputs->slug=='text'){
                                if($feature->measuring_unit_id==null){ //Not Measurable
                                    $featureArray = array(
                                        'product_id' => $id,
                                        'feature_id' => $feature->id,
                                        'feature_text' => $innerValue,
                                        'feature_measuring_unit'=>null,
                                        'feature_option_id'=>null,
                                        'created_at' => $timeStamp,
                                        'updated_at' => $timeStamp,
                                    );
                                }else{
                                    $featureArray = array(
                                        'product_id' => $id,
                                        'feature_id' => $feature->id,
                                        'feature_text' => $innerValue['text_data'],
                                        'feature_measuring_unit' => $innerValue['measuring_unit'],
                                        'feature_option_id'=>null,
                                        'created_at' => $timeStamp,
                                        'updated_at' => $timeStamp,
                                    );
                                }
                            }
                        }

                        if($this->language != 'en'){
                            $featureArray['feature_text_mr'] = $featureArray['feature_text'];
                            unset($featureArray['feature_text']);
                        }
                            $productFeaturesRelation = ProductFeatureRelation::where('product_id',$featureArray['product_id'])->where('feature_id',$featureArray['feature_id'])->firstOrFail();
                            $productFeaturesRelation->update($featureArray);

                    }
                }
                /*if($this->language == 'en' && (!($verificationStatus->slug == 'admin_approved' && $this->userRoleType=='seller'))){
                   $this->featureTextMarathiName($id);
                }*/
            }

            /* Move Product Images */

            if (array_key_exists("product_images",$request->all()) && $request->product_images!=null){
                $productImages = $request->product_images;
                if($this->userRoleType=='superadmin' || $this->userRoleType=='data-entry-admin'){
                $this->updateUploadedProductImages($productImages,$currentTime,$id,$product['seller_id']);
                }else{
                    $this->updateUploadedProductImages($productImages,$currentTime,$id);
                }
            }
            if($request->has('imageArray')) {
                foreach($request->imageArray as $deleteImage) {
                    ProductImage::where('product_id',$id)->where('name',$deleteImage)->delete();
                    File::delete($deleteImage);
                    $count++;
                }
               /* $file = $this->getImagePath($deleteImage,$product['seller_id']);
                $imagesMaster = ImageMagick::all()->toArray();
                $newFileName = pathinfo($deleteImage, PATHINFO_FILENAME);
                $ds = DIRECTORY_SEPARATOR;
                $extension = pathinfo($deleteImage, PATHINFO_EXTENSION);
                $indexCount = 0 ;
                $data = array();
               foreach($imagesMaster as $images){
                  // $data[] = $file['path'].$ds.$newFileName.'_'.$images['dimensions'].".".$extension;
                   File::delete($newFileName . '_' . $images['dimensions'] . "." . $extension);
                   $indexCount++;
               }*/
                $deletedImzge =  ProductImage::where('product_id',$id)->get();
                $count = 1;
                $da = 0;
                foreach($deletedImzge as $changePostion){
                        $dataImage['position'] = $count;
                        $da = ProductImage::where('id',$changePostion['id'])->update($dataImage);
                          $count++;
                }
            }
            if (!array_key_exists("max_quantity_equal_to_stock",$request->all()) && $product->max_quantity_equal_to_stock){
                $updatedProduct = $product->update(['max_quantity_equal_to_stock'=>0]);
            }
            if($request->has('approve')){
                $message = 'Product updated and approved successfully';
            }else{
                $message = 'Product updated successfully';
            }
            $request->session()->flash('success', $message);
            if($this->userRoleType == 'superadmin' || $this->userRoleType == 'data-entry-admin'){
                return redirect('/operational/products/edit/'.$id);
            } else {
                return redirect('product/edit/'.$id);
            }
        }catch (\Exception $e){
            Log::critical('Product Edit: user:'.$this->user.' request:'.json_encode($request->all()).' productId:'.$id.' exception:'.$e->getMessage());
            abort(500,$e->getMessage());
        }
    }

    public function productPreview(ProductRequest $request, $productId){
        try{
            $product = Product::where('id',$productId)->first();
            $featuresInfo = Feature::join('product_feature_relations','features.id','=','product_feature_relations.feature_id')
                ->where('product_feature_relations.product_id','=',$product['id'])
                ->orderBy('features.priority','asc')
                ->orderBy('features.name','asc')
                ->get()->toArray();
            $featureMaster = NULL;
            if(!empty($featuresInfo)){
                $i = 0;
                foreach($featuresInfo as $features){
                    $feature = Feature::findOrFail($features['feature_id'])->toArray();
                    $featureMaster[$i]['name'] = $feature['name'];
                    if($features['feature_option_id']!=null){
                        $featureOption = FeatureOption::findOrFail($features['feature_option_id'])->toArray();
                        $featureMaster[$i]['value'] = $featureOption['name'];
                    }else{
                        if($features['feature_text'] != null){
                            if($features['feature_measuring_unit']!=null){
                                $featureMaster[$i]['value'] = $features['feature_text']." ".$features['feature_measuring_unit'];
                            }else{
                                $featureMaster[$i]['value'] = $features['feature_text'];
                            }
                        }
                    }
                    $i++;
                }
            }
            $productImages = $product->images->toArray();
            $categoryData= array();
            $i = 0;
            if($this->userRoleType=='seller'){
                $productOwnerId = $this->user->id;
            }else{
                $productOwner = Seller::findOrFail($product->seller_id);
                $productOwnerId = $productOwner->user_id;
            }

            $productImageArray = NULL;
            foreach($productImages as $productImage){
                $file = $this->getProductImagePath($productImage['name'],$productOwnerId);
                if($file['status']){
                    $random = mt_rand(1,10000000000);
                    $productImageArray[$i]['id'] = $productImage['id'];
                    $productImageArray[$i]['name'] = $productImage['name'];
                    $productImageArray[$i]['path'] = $file['path'];
                    $productImageArray[$i]['position'] = $productImage['position'];
                    $productImageArray[$i]['product_id'] = $productImage['product_id'];
                    $productImageArray[$i]['alternate_text'] = $productImage['alternate_text'];
                    $productImageArray[$i]['random'] = $random;
                    $i++;
                }
            }
            $categoryId = ProductCategoryRelation::findOrFail($productId);
            $categoryInfo = Category::where('id',$categoryId->category_id)->get();
            foreach($categoryInfo as $dataInfo) {
                $categoryData['itemHead'] = $dataInfo['name'];
                $parentCategory = Category::where('id',$dataInfo['category_id'])->first();
                if($parentCategory != null ){
                    $categoryData['parentCategory'] = $parentCategory['name'];
                    $lastParentCategory = Category::where('id',$parentCategory['category_id'])->first();
                    if($lastParentCategory != null ){
                        $categoryData['lastParentCategory'] = $lastParentCategory['name'];
                    }
                }
            }
            $productStatus = ProductQueryStatus::findOrFail($product->product_query_status_id);
            $productQueryCount = ProductQueryConversation::where('product_id',$product->id)->count();
            $imageCount = count($productImageArray);
            $imageCount = 4 - $imageCount;
            $category = Category::findOrFail($categoryId->category_id);
            $brand = $product->brand()->first();
            $searchedData = $this->findCategoryBySlug($category->slug);
            if($searchedData['flag']==true){
                $taxes = Tax::where('code','<>','Service Tax')->get();
                $categories = $searchedData['selected'];
                if($productStatus['slug'] == 'admin_approved'){
                    return view('frontend.user.product.detail')->with(compact('product','taxes','categories','productImageArray','imageCount','productStatus','productQueryCount','categoryInfo','featureMaster','brand','categoryData'));
                }else{
                    return view('frontend.user.product.detail')->with(compact('product','taxes','categories','productImageArray','imageCount','productStatus','productQueryCount','categoryInfo','featureMaster','brand','categoryData'));
                }
            }
        }catch (\Exception $e){
            Log::critical('Product Preview user:'.$this->user.' request:'.json_encode($request->all()).' productId:'.$productId);
            abort(500,$e->getMessage());
        }
    }

    public function approveProduct(Request $request){
        try{
            $product = Product::findOrFail($request->product_id);
            $queryStatus = ProductQueryStatus::where('slug','admin_approved')->first();
            $time = Carbon::now();
            $data = array(
                'approved_date' => $time,
                'is_active' => 1,
                'product_query_status_id' => $queryStatus->id,
                'product_read_status' => $queryStatus->id,
                'admin_id' => $this->user->id
            );
            $product->update($data);
            $message = 'Product approved successfully';
            $request->session()->flash('success', $message);
            if($this->userRoleType=='admin') {
                return redirect('verification/product/manage');
            } elseif($this->userRoleType=='superadmin') {
                return redirect('/operational/products/manage');
            }
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'product_id' => $request->product_id,
                'action' => 'Admin product approval',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function marathiName($columnName,$productId){
        $lang ="mr";
        $string = null;
        $key = env('PRODUCT_TRANSLATION_KEY');
        $ch = curl_init();
        foreach($columnName as $key1=>$value){
            $string ="&q=".urlencode($value);
            curl_setopt_array($ch, array(
                CURLOPT_URL => "https://www.googleapis.com/language/translate/v2?key=".$key."&source=en&target=".$lang.$string,
                CURLOPT_RETURNTRANSFER => 1
            ));
            $output = curl_exec($ch);
            $result1= json_decode($output);
            if(!empty($result1->data)){
                $idArray = null;
                $productTranslationPresent = ProductTranslation::where('product_id',$productId)->first();
                if($productTranslationPresent != null){
                    ProductTranslation::where('product_id',$productId)->update(array($key1 => $result1->data->translations[0]->translatedText));
                }else{
                    $languageId = Language::where('abbreviation','mr')->first();
                    $translatedProduct['product_id'] = $productId;
                    $translatedProduct['language_id'] = $languageId['id'];
                    $translatedProduct[$key1] = $result1->data->translations[0]->translatedText;
                    ProductTranslation::create($translatedProduct);
                }
            }
        }
        curl_close($ch);
        return "success";
    }

    public function featureTextMarathiName($id){
        $lang ='mr';
        $productFeatureData = ProductFeatureRelation::where('product_id',$id)->whereNull('feature_option_id')->get();
        $column = "feature_text_".$lang;
        $string = null;
        foreach($productFeatureData as $productFeature){
            $string = $string."&q=".urlencode($productFeature['feature_text']);
        }
        $lang ="mr";
        $ch = curl_init();
        $key = env('PRODUCT_TRANSLATION_KEY');
        curl_setopt_array($ch, array(
            CURLOPT_URL => "https://www.googleapis.com/language/translate/v2?key=".$key."&source=en&target=".$lang.$string,
            CURLOPT_RETURNTRANSFER => 1
        ));
        $output = curl_exec($ch);
        $result1= json_decode($output);
        if(!empty($result1->data)){
            $i = 0;
            $idArray = null;
            foreach($productFeatureData as $productFeature){
                $ans = ProductFeatureRelation::Where('id',$productFeature['id'])->update(array($column=>$result1->data->translations[$i]->translatedText));
                echo $ans;
                $i++;
            }
        }
        curl_close($ch);
    }
    public function outOfStock(Request $request,$id){
        try{
            $product = Product::findOrFail($id);
            $outOfStockDate = Carbon::now();
            $product->update(['out_of_stock_date' => $outOfStockDate, 'quantity' => 0]);
            $message = 'Product Out Of Stock Done successfully';
            $request->session()->flash('success', $message);
            if($this->userRoleType == 'superadmin' || $this->userRoleType == 'data-entry-admin'){
                return redirect('/operational/products/edit/'.$id);
            } else {
                return redirect('product/edit/'.$id);
            }
        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'action' => 'Product out of stock',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }

    }
}
