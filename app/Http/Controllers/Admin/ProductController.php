<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\CustomTraits\ProductQuery;
use App\Http\Controllers\CustomTraits\ProductTrait;
use App\Product;
use App\ProductQueryStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Tax;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
        if(!Auth::guest()) {
            $this->user = Auth::user();
            if (Session::has('role_type')) {
                $this->userRoleType = Session::get('role_type');
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

    public function viewProductList(){
        try{
            $categories = $this->getCategory();
            return view('backend.admin.product.manage')->with(compact('categories'));
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
    public function createView(Request $request){
        try{
            $searchedData = $this->findCategoryBySlug($request->category);
            if($searchedData['flag']==true){
                $taxes = Tax::where('code','<>','Service Tax')->where('code','<>','Cess Tax')->get();
                $categories = $searchedData['selected'];
                return view('backend.seller.add-product')->with(compact('taxes','categories','searchedData'));
            }else{
                $message = 'Sorry selected category not found or allowed';
                $request->session()->flash('error', $message);
                return redirect('verification/product/manage');
            }
        }catch (\Exception $e){
            abort(500,$e->getMessage());
        }
    }
  
    public function  approveBulkProduct(Request $request){
        try{
            $productIdInfoArray = $request->all();
            $productIdArray = $productIdInfoArray['product_id'];
            $queryStatus = ProductQueryStatus::where('slug','admin_approved')->first();
            $time = Carbon::now();
            foreach($productIdArray as $productId){
                $data = array(
                    'approved_date' => $time,
                    'is_active' => 1,
                    'product_query_status_id' => $queryStatus->id,
                    'product_read_status' => $queryStatus->id,
                    'admin_id' => $this->user->id
                );
                Product::where('id', '=', $productId) ->update($data);
            }
        }catch (\Exception $e){
            $data = [
                'input_params' => $request->all(),
                'user' => $this->user,
                'role' => $this->userRoleType,
                'action' => 'Admin bulk product approval',
                'exception' => $e->getMessage()
            ];
            Log::critical(json_encode($data));
            abort(500,$e->getMessage());
        }
    }
}
