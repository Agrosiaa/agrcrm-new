<?php

namespace App\Http\Controllers\Seller;

use App\BrandCategory;
use App\Category;
use App\CategoryHSNCodeTaxRelation;
use App\Feature;
use App\FeatureOption;
use App\HSNCodes;
use App\HSNCodeTaxRelation;
use App\Http\Controllers\CustomTraits\ImageMagickTrait;
use App\Http\Controllers\CustomTraits\ProductQuery;
use App\Http\Controllers\CustomTraits\ProductSku;
use App\Http\Controllers\CustomTraits\ProductTrait;
use App\Http\Controllers\CustomTraits\CategoryTrait;
use App\Product;
use App\ProductCategoryRelation;
use App\ProductFeatureRelation;
use App\ProductImage;
use App\ProductQueryStatus;
use App\Tax;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except'=>array('getTaxes')]);
        $this->middleware('seller',['except'=>array('getTaxes')]);
        if(!Auth::guest()) {
            $this->user = Auth::user();
            $this->seller = $this->user->seller()->first();
            if (Session::has('role_type')) {
                $this->userRoleType = Session::get('role_type');
            }else{
                $this->userRoleType = $this->user->role->slug;
            }
        }
        if (Session::has('applocale')) {
            $this->language = Session::get('applocale');
            App::setLocale(Session::get('applocale'));
        }else{
            $this->language = App::getLocale();
            Session::set('applocale', $this->language);
        }
    }
    use ProductQuery;
    use ProductTrait;
    use CategoryTrait;
    use ImageMagickTrait;
    use ProductSku;

    public function viewProductList(){
        try{
            $categories = $this->getCategory();
            $queryStatus = ProductQueryStatus::all()->toArray();
            return view('backend.seller.product.manage')->with(compact('categories','queryStatus'));
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    public function createProductView(Request $request){
        try{
            $searchedData = $this->findCategoryBySlug($request->category);
            if($searchedData['flag']==true){
                $categories = $searchedData['selected'];
                $sellerAddresses = $this->seller->addresses->toArray();
                $categoryId = Category::where('slug',$request->category)->first();
                $categoryHsnCodeTaxRelationIds = CategoryHSNCodeTaxRelation::where('category_id',$categoryId->id)->lists('hsn_code_tax_relation_id')->toArray();
                $hsnCodeIDs = HSNCodeTaxRelation::whereIn('id',$categoryHsnCodeTaxRelationIds)->distinct('hsn_code_id')->select('hsn_code_id')->get();
                $hsnCodes = HSNCodes::whereIn('id',$hsnCodeIDs)->get()->toArray();
                $features = Feature::where('category_id',$categoryId['id'])->with('inputs','options','measuringUnits')->get();

                if(!$features->isEmpty()){
                    $featureIndex = 0;
                    foreach($features as $feature){
                        if($feature->inputs->slug=='select'){
                            $featureOptions[$featureIndex]['required'] = $feature->required;
                            $featureOptions[$featureIndex]['name'] = $feature['name'];
                            $featureOptions[$featureIndex]['type'] = 'select';
                            $featureOptions[$featureIndex]['measuring_unit_id'] = $feature->measuring_unit_id;
                            $optionCount =0;
                            foreach($feature->options as $options){
                                $featureOptions[$featureIndex]['data'][$optionCount]['id'] = $options['id'];
                                $featureOptions[$featureIndex]['data'][$optionCount]['name'] = $options['name'];
                                $optionCount++;
                            }
                        }elseif($feature->inputs->slug=='text' && $feature->measuring_unit_id!=null){
                            $featureOptions[$featureIndex]['required'] = $feature->required;
                            $featureOptions[$featureIndex]['name'] = $feature['name'];
                            $featureOptions[$featureIndex]['type'] = 'text';
                            $featureOptions[$featureIndex]['measuring_unit_id'] = $feature->measuring_unit_id;
                             $featureOptions[$featureIndex]['data'] = explode(',',$feature->measuringUnits->values);
                        }elseif($feature->inputs->slug=='text' && $feature->measuring_unit_id==null){
                            $featureOptions[$featureIndex]['required'] = $feature->required;
                            $featureOptions[$featureIndex]['name'] = $feature['name'];
                            $featureOptions[$featureIndex]['type'] = 'text';
                            $featureOptions[$featureIndex]['measuring_unit_id'] = $feature->measuring_unit_id;
                        }
                        $featureIndex++;
                    }
                }else{
                    $featureOptions = null;
                }
                $brandList = null;
                $brandMaster = BrandCategory::where('category_id',$categoryId['id'])->with('brands')->get();
                if(!$brandMaster->isEmpty()){
                    foreach($brandMaster as $brandMasterOptions){
                        $brandList[$brandMasterOptions['brands']->id] = $brandMasterOptions['brands']->name;
                    }
                    $brandList = array_sort($brandList, function ($value) {
                        return $value;
                    });
                }
                $alltaxes=Tax::where('is_active',true)->get(['id','rate'])->toArray();

                return view('backend.seller.product.add')->with(compact('hsnCodes','categories','searchedData','sellerAddresses','featureOptions','brandList','categoryId','alltaxes'));
            }else{
                $message = 'Sorry selected category not found or allowed';
                $request->session()->flash('error', $message);
                return redirect('manage');
            }
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    public function createProduct(Requests\Web\Seller\ProductRequest $request){
        try{
            $searchedData = $this->findCategoryBySlug($request->category);
            $currentTime = Carbon::now();
            if($searchedData['flag']==true){
                $productData = $request->all();
                $productData['selling_price'] = $productData['selling_price_without_discount'];
                $productData['commission_percent'] = $productData['commission'];
                $productData['logistic_percent'] = $productData['logistic_tax'];
                $productData['subtotal'] = $productData['subtotal_amount'];
                $productData['discount'] = $productData['discount_percent'];
                $productData['product_name'] = strtolower($productData['product_name']);
                $productData['search_keywords'] = strtolower($productData['search_keywords']);
                $productData['created_at'] = $currentTime;
                $productData['updated_at'] = $currentTime;
                $queryStatus = ProductQueryStatus::where('slug','pending')->first();
                $productData['product_query_status_id'] = $queryStatus->id;
                $productData['seller_id'] = $this->seller->id;
                $productData['max_quantity_equal_to_stock'] = 1;
                $productData['category_id'] = $searchedData['data']['id'];
                if($request->has('configurable_width')){
                    $productData['configurable_width'] = $request->configurable_width;
                }else{
                    $productData['configurable_width'] = null;
                }
                if($request->has('hsn_code')) {
                    $productData['hsn_code_tax_relation_id'] = HSNCodeTaxRelation::where('hsn_code_id', $productData['hsn_code'])->where('tax_id', $productData['tax_id'])->pluck('id');
                }else{
                    $hsnCodeData = ['hsn_code' => $request->new_hsn_code];
                    $hsnCode = HSNCodes::create($hsnCodeData);
                    $hsnCodeData = ['hsn_code_id' => $hsnCode->id];
                    $hsnCodeData['tax_id'] = $request->tax_id;
                    $hsnCodeTaxRelation = HSNCodeTaxRelation::create($hsnCodeData);
                    $hsnCodeCategoryRelation['hsn_code_tax_relation_id'] = $hsnCodeTaxRelation->id;
                    $hsnCodeCategoryRelation['category_id'] = $searchedData['data']['id'];
                    $productData['hsn_code_tax_relation_id'] =  $hsnCodeTaxRelation->id;
                    $query= CategoryHSNCodeTaxRelation::create($hsnCodeCategoryRelation);
                }
                unset($productData['hsn_code']);
                $sellerAbbreviation = strtoupper($this->seller->seller_name_abbreviation);
                $itemHeadAbbreviation = strtoupper($searchedData['data']['item_head_abbreviation']);
                $sellerCategoryCount = $this->product_category_count($productData['seller_id'],$productData['category_id']);
                $productData['item_based_sku'] = 'AGR'.''.$itemHeadAbbreviation.$sellerAbbreviation.str_pad($sellerCategoryCount['count'], 6, "0", STR_PAD_LEFT);

                $createdProduct = Product::create($productData);
                $marathiColumn = $request->only('product_name','key_specs_1','key_specs_2','key_specs_3','search_keywords','product_description',
                    'other_features_and_applications','sales_package_or_accessories','domestic_warranty',
                    'domestic_warranty_measuring_unit','warranty_summary','warranty_service_type','warranty_items_covered',
                    'warranty_items_not_covered');
                $marathiName = $this->marathiName($marathiColumn,$createdProduct->id);
                $productCategoryData['product_id'] = $createdProduct->id;
                $productCategoryData['category_id'] = $searchedData['data']['id'];
                $productCategoryData['created_at'] = $currentTime;
                $productCategoryData['updated_at'] = $currentTime;
                $ProductCategory = ProductCategoryRelation::create($productCategoryData);
                /* Move Product Images */
                $productImages = $request->product_images;
                $this->uploadProductImages($productImages,$currentTime,$createdProduct->id);
                /* Add product features */
                if($request->features != null){
                $productFeatures = $request->features;

                $timeStamp = Carbon::now();
                foreach($productFeatures as $key => $value) {
                    foreach($value as $innerKey => $innerValue){
                        $feature = Feature::where('name',$innerKey)->where('category_id',$searchedData['data']['id'])->with('inputs')->first();
                        if($feature!=null){
                            if($feature->inputs->slug=='select'){
                                $options = FeatureOption::where('name',$innerValue)->where('feature_id',$feature->id)->first();

                                if($options!=null){
                                    $featureArray[] = array(
                                        'product_id' => $createdProduct->id,
                                        'feature_id' => $feature->id,
                                        'feature_text'=>null,
                                        'feature_measuring_unit'=>null,
                                        'feature_option_id' => $options->id,
                                        'created_at' => $timeStamp,
                                        'updated_at' => $timeStamp,
                                    );
                                }else{
                                    $featureArray[] = array(
                                        'product_id' => $createdProduct->id,
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
                                    $featureArray[] = array(
                                        'product_id' => $createdProduct->id,
                                        'feature_id' => $feature->id,
                                        'feature_text' => $innerValue,
                                        'feature_measuring_unit'=>null,
                                        'feature_option_id'=>null,
                                        'created_at' => $timeStamp,
                                        'updated_at' => $timeStamp,
                                    );
                                }else{
                                    $featureArray[] = array(
                                        'product_id' => $createdProduct->id,
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
                    }
                    $masterFeatureArray = array();
                    $masterFeatureArray = array_merge($masterFeatureArray,$featureArray);
                }
                if($masterFeatureArray!=null){
                    ProductFeatureRelation::insert($masterFeatureArray);
                    $this->featureTextMarathiName($createdProduct->id);
                }

                }
                $message = 'Product added successfully';
                $request->session()->flash('success', $message);
                return redirect('product/manage');
            }else{
                $message = 'Something went wrong';
                $request->session()->flash('danger', $message);
                return redirect('product/manage');
            }
        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Seller Create New Product',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function uploadProductImages($productImages,$currentTime,$productId){
        try{
            $ds = DIRECTORY_SEPARATOR;
            foreach($productImages as $productImage){
                $imagename = basename($productImage['image_name']);
                $productImageArray = array(
                    'name' => $imagename,
                    'position' => $productImage['image_type'],
                    'product_id' => $productId,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime,
                    'alternate_text' => $productImage['alternate_text']
                );
                ProductImage::create($productImageArray);
                $vendorUploadPath = public_path().env('SELLER_FILE_UPLOAD');
                $vendorTempImageUploadPath = public_path().$productImage['image_name'];
                $vendorOwnDirecory = $vendorUploadPath.sha1($this->user->id);
                $vendorImageUploadPath = $vendorOwnDirecory.$ds.'product_images';
                /* Create Upload Directory If Not Exists */
                if (!file_exists($vendorImageUploadPath)) {
                    File::makeDirectory($vendorImageUploadPath, $mode = 0777, true, true);
                }
                if(File::exists($vendorTempImageUploadPath)){
                    $vendorImageUploadNewPath = $vendorImageUploadPath.$ds.$imagename;
                    File::move($vendorTempImageUploadPath,$vendorImageUploadNewPath);
                }
                $this->cropImages($vendorImageUploadPath,$imagename);
            }
        }catch(\Exception $e){
            Log::critical('Product Image Upload Seller: '.['user'=>$this->user,'productId'=>$productId]);
            abort(500,$e->getMessage());
        }
    }


    public function categoryProductListing(Request $request){
        try{
            $searchedData = $this->findCategoryBySlug($request->current_category);
            if($searchedData['flag']==true){
                $iTotalRecords = Product::where('category_id',$searchedData['data']['id'])->count();
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
                $limitedProducts = Product::where('seller_id',$this->seller->id)->where('category_id',$searchedData['data']['id'])->take($iDisplayLength)->skip($iDisplayStart)->get()->toArray();
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

    public function imageUpload(Request $request){
        try{
            $sha1UserId = sha1($this->user->id);
            $sellerUploadPath = public_path().env('SELLER_PRODUCT_TEMP_UPLOAD');
            $sellerImageUploadPath = $sellerUploadPath.$sha1UserId;
            /* Create Upload Directory If Not Exists */
            if (!file_exists($sellerImageUploadPath)) {
                File::makeDirectory($sellerImageUploadPath, $mode = 0777, true, true);
            }
            $extension = $request->file('file')->getClientOriginalExtension();
            $filename = mt_rand(1,10000000000).sha1(time()).".{$extension}";
            $request->file('file')->move($sellerImageUploadPath,$filename);
            $path = env('SELLER_PRODUCT_TEMP_UPLOAD').$sha1UserId.DIRECTORY_SEPARATOR.$filename;
            $response = [
                'jsonrpc' => '2.0',
                'result' => 'OK',
                'path' => $path
            ];
        }catch (\Exception $e){
            $response = [
                'jsonrpc' => '2.0',
                'error' => [
                    'code' => 101,
                    'message' => 'Failed to open input stream.',
                ],
                'id' => 'id'
            ];
        }
        return response()->json($response);
    }

    public function displayProductImage(Request $request){
        try{
            $path = $request->path;
            $count = $request->count;
            $random = mt_rand(1,10000000000);
        }catch (\Exception $e){
            $path = null;
            $count = null;
        }
        return view('backend.partials.seller.product-image')->with(compact('path','count','random'));
    }

    public function deleteTempProductImage(Requests\Web\Seller\ProductImageRequest $request){
        try{
            $sellerUploadPath = public_path().$request->path;
            File::delete($sellerUploadPath);
            return response(200);
        }catch(\Exception $e){
            return response(500);
        }
    }


    public function viewCategory($categorySlug){
        try{
            $currentCategory = Category::findBySlugOrFail($categorySlug);
            $categories = $this->getCategory();
            $file = $this->getCategoryImagePath($currentCategory['image'],$currentCategory['created_by']);
            $currentCategory['path'] = $file['path'];
            return view('backend.seller.category')->with(compact('currentCategory','categories'));
        }catch(\Exception $e){
            abort(404,$e->getMessage());
        }
    }

    public function disableProduct(Requests\Web\Seller\DeleteProductRequest $request,$id){
        try{
            $product = Product::findOrFail($id);
            if($product->is_active){
                $product->update(array('is_active'=>0));
                $message = 'Product disabled successfully';
            }else{
                $product->update(array('is_active'=>1));
                $message = 'Product enabled successfully';
            }
            $request->session()->flash('success', $message);
            return back();
        }catch(\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    /*
    Function Name: calculateProductPrice
    Param: form data
    Return:'commissionAmount','serviceTaxAmount','vatAmount','sellingPrice'
    Desc:calculate product price based on parameter
    Developed By: Ganesh Dharmawat
    Date: 4/3/2016
    */
    public function calculateProductPrice(Request $request){
        try{
            $data = $request->all();
            $basePrice = $data['base_price'];
            $commission = $data['commission'];
            $logisticCost = $data['logistic_tax'];
            $discountPercent = $data['discount_percent'];
            $tax_rate = Tax::where('id',$data['tax_id'])->pluck('rate');
            $discountedBasePrice = number_format((float)(($basePrice - ($data['discount_percent'] / 100) * $basePrice)), 2, '.', '');
            $commissionAmount = number_format((float)(($commission / 100) * $discountedBasePrice), 2, '.', '');
            $logisticAmount =   number_format((float)(($logisticCost/100) * $discountedBasePrice), 2, '.', '');
            $gstOnCommission = number_format((float)((18/100) * $commissionAmount), 2, '.', '');
            $gstOnLogistic = number_format((float)((18/100) * $logisticAmount), 2, '.', '');
            $subtotal = number_format((float)($discountedBasePrice + $commissionAmount + $logisticAmount), 2, '.', '');
            $gstTaxAmount =  number_format((float)(($tax_rate / 100) * $subtotal), 2, '.', '');
            $discountedSellingPrice = ceil($subtotal + $gstTaxAmount);
            $commissionAmountWithoutDiscount = number_format((float)(($commission / 100) * $basePrice), 2, '.', '');
            $logisticAmountWithoutDiscount =   number_format((float)(($logisticCost/100) * $basePrice), 2, '.', '');
            $subtotalWithoutDiscount = number_format((float)($basePrice + $commissionAmountWithoutDiscount + $logisticAmountWithoutDiscount), 2, '.', '');
            $gstTaxAmountWithoutDiscount =  number_format((float)(($tax_rate / 100) * $subtotalWithoutDiscount), 2, '.', '');
            $sellingPriceWithoutDiscount = ceil($subtotalWithoutDiscount + $gstTaxAmountWithoutDiscount);
            return with(compact('basePrice','discountPercent','sellingPriceWithoutDiscount','discountedBasePrice','commissionAmount','logisticAmount','subtotal','gstTaxAmount','discountedSellingPrice','gstOnCommission','gstOnLogistic'));
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Seller calculate product price',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    /*
    Function Name: getProductCategoryTaxes
    Param: product id
    Return:'taxes','serviceTaxAmount','categoryInfo','serviceTax'
    Desc:get information of vat service tax and commission
    Developed By: Ganesh Dharmawat
    Date: 4/3/2016
    */
    public function getProductCategoryTaxes(Request $request){
        try{
            $productInfo = Product::findOrFail($request->product_id);
            if($productInfo['commission_percent'] != null){
                $percentage = Product::where('id',$request->product_id)->select('logistic_percent as logistic','commission_percent as commission')->first();
            }else{
                $percentage = Category::where('id',$request->id)->select('commission','logistic_percentage as logistic')->first();
            }
            return with(compact('percentage'));
        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Seller calculate product price',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
    public function getAddProductCategoryTaxes(Request $request){
        try{
            $categoryInfo = Category::findOrFail($request->id);
            return with(compact('categoryInfo'));
        }catch(\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Seller calculate add product price',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }

    }

    public function getProductVat(Request $request){
        try{
            $taxRate = Tax::where('rate',$request->rate)->first();
            $taxes = Tax::whereNotIn('code',['Service Tax'])->get();
            return with(compact('taxes','taxRate'));

        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'get tax on rate',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }

    public function updateUploadedProductImages($productImages,$currentTime,$productId){
        try{
            foreach($productImages as $productImage){
                if (isset($productImage['image_existing'])){
                    $imagename = basename($productImage['image_name']);
                    $position = $productImage['image_type'];
                    $alternate_text = $productImage['alternate_text'];
                    ProductImage::where('product_id',$productId)->where('name',$imagename)->update(['position' => $position,'alternate_text' => $alternate_text]);
                }else{
                    $imagename = basename($productImage['image_name']);
                    $productImageArray = array(
                        'name' => $imagename,
                        'position' => $productImage['image_type'],
                        'product_id' => $productId,
                        'created_at' => $currentTime,
                        'updated_at' => $currentTime,
                        'alternate_text' => $productImage['alternate_text']
                    );
                    ProductImage::create($productImageArray);
                    $vendorUploadPath = public_path().env('SELLER_FILE_UPLOAD');
                    $vendorTempImageUploadPath = public_path().$productImage['image_name'];
                    $vendorOwnDirecory = $vendorUploadPath.sha1($this->user->id);
                    $vendorImageUploadPath = $vendorOwnDirecory.DIRECTORY_SEPARATOR.'product_images';
                    /* Create Upload Directory If Not Exists */
                    if (!file_exists($vendorImageUploadPath)) {
                        File::makeDirectory($vendorImageUploadPath, $mode = 0777, true, true);
                    }
                    if(File::exists($vendorTempImageUploadPath)){
                        $vendorImageUploadNewPath = $vendorImageUploadPath.DIRECTORY_SEPARATOR.$imagename;
                        File::move($vendorTempImageUploadPath,$vendorImageUploadNewPath);
                    }
                    $this->cropImages($vendorImageUploadPath,$imagename);
                }
            }
        }catch(\Exception $e){
            $data = [
                'action'=> 'Edit Product',
                'user' => $this->user,
                'input_params' => $productImages,
                'productId' => $productId,
                'currentTime' =>$currentTime,
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
}
