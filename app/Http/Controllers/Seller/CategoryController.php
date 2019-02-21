<?php

namespace App\Http\Controllers\Seller;


use App\Category;
use App\CategoryHSNCodeTaxRelation;
use App\HSNCodes;
use App\HSNCodeTaxRelation;
use App\Http\Controllers\CustomTraits\CategoryTrait;
use App\Http\Controllers\CustomTraits\ImageMagickTrait;
use App\Http\Controllers\CustomTraits\ProductQuery;
use App\Http\Controllers\CustomTraits\ProductTrait;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('seller');
        if(!Auth::guest()) {
            $this->user = Auth::user();
            $this->seller = $this->user->seller()->first();
            if (Session::has('role_type')) {
                $this->userRoleType = Session::get('role_type');
            }
        }
    }
    use ProductQuery;
    use ProductTrait;
    use CategoryTrait;
    use ImageMagickTrait;



    public function viewCategory($categorySlug){
        try{
            $currentCategory = Category::findBySlugOrFail($categorySlug);
            $categories = $this->getCategory();
            $hsnCodeTaxRelationIds = CategoryHSNCodeTaxRelation::where('category_id',$currentCategory->id)->lists('hsn_code_tax_relation_id');
            $hsnCodeIds = HSNCodeTaxRelation::whereIn('id',$hsnCodeTaxRelationIds)->distinct('hsn_code_id')->lists('hsn_code_id')->toArray();
            $hsnCodeNames = HSNCodes::whereIn('id',$hsnCodeIds)->lists('hsn_code')->toArray();
            $hsn_code =  implode(',',$hsnCodeNames);
            $file = $this->getCategoryImagePath($currentCategory['image'],$currentCategory->slug);
            $currentCategory['path'] = $file['path'];
            return view('backend.seller.category.view')->with(compact('hsn_code','currentCategory','categories'));
        }catch(\Exception $e){
            abort(404,$e->getMessage());
        }
    }
}
