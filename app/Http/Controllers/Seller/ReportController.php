<?php

namespace App\Http\Controllers\Seller;

use App\Category;
use App\FeatureOption;
use App\Product;
use App\ProductCategoryRelation;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CustomTraits\ReportTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
use ReportTrait;
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('seller');
        if(!Auth::guest()) {
            $this->user = Auth::user();
            $this->seller = $this->user->seller()->first();
            if (Session::has('role_type')) {
                $this->userRoleType = Session::get('role_type');
            }else{
                $this->userRoleType = $this->user->role->slug;
            }
        }
    }

    public function view()
    {

        $seller_id = $this->user->seller()->first();
        $productDetail = Product::where('seller_id',$seller_id['id'])->lists('id')->toArray();
        $categories = ProductCategoryRelation::whereIn('product_id',$productDetail)->distinct()->lists('category_id')->toArray();
        $itemHead= Category::whereIn('id',$categories)->where('is_item_head','=',true)->select('id','name')->orderBy('name','asc')->get()->toArray();
        return view('backend.seller.report.lists')->with(compact('itemHead'));
    }
}
