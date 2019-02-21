<?php

namespace App\Providers;

use App\Category;
use App\Product;
use App\ProductCategoryRelation;
use Illuminate\Support\ServiceProvider;

class CategoryMenuProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('frontend.partials.common.category-listing',function($view){
            $categories = $this->getCategories();
            $view->with(compact('categories'));
        });
        view()->composer('frontend.partials.common.post-footer',function($view){
            $categories = $this->getCategories();
            $view->with(compact('categories'));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function getCategories(){

        $categories = Category::where('category_id',null)->where('is_active',1)->orderBy('name','ASC')->get();
        if(!$categories->isEmpty()){
            $categories = $categories->toArray();
            $rootCategoryCount = count($categories);
            for($i=0;$i<$rootCategoryCount;$i++){
                $subCategory = Category::where('category_id',$categories[$i]['id'])->where('is_active',1)->orderBy('name','ASC')->get();
                if(!$subCategory->isEmpty()){
                    $categories[$i]['subCategory'] = $subCategory->toArray();
                }
            }
            for($i=0;$i<$rootCategoryCount;$i++){
                if (array_key_exists("subCategory",$categories[$i])){
                    $subCategoryCount = count($categories[$i]['subCategory']);
                    for($j=0;$j<$subCategoryCount;$j++){
                        $subSubCategory = Category::where('category_id',$categories[$i]['subCategory'][$j]['id'])->where('is_active',1)->orderBy('name','ASC')->get();
                        if(!$subSubCategory->isEmpty()){
                            $subSubCategoryId = Category::where('category_id',$categories[$i]['subCategory'][$j]['id'])->where('is_active',1)->orderBy('name','ASC')->lists('id')->toarray();
                            $productList = ProductCategoryRelation::whereIn('category_id',$subSubCategoryId)->lists('product_id')->toarray();
                            $productCount = Product::wherein('id',$productList)->where('is_active',1)->count();
                            if($productCount > 0){
                                $categories[$i]['productCount'] += $productCount;
                            }
                            $categories[$i][$categories[$i]['slug']][$categories[$i]['subCategory'][$j]['name']]['leaf_level'] = $subSubCategory->toArray();
                        }else{
                            if($categories[$i]['subCategory'][$j]['is_item_head'] == 0){
                                $categories[$i][$categories[$i]['slug']][$categories[$i]['subCategory'][$j]['name']]['leaf_level'][0] = $categories[$i]['subCategory'][$j];
                            }else{
                                $categories[$i][$categories[$i]['slug']]['others']['direct_item_heads'][$categories[$i]['subCategory'][$j]['name']] = $categories[$i]['subCategory'][$j];
                                $productList = ProductCategoryRelation::where('category_id',$categories[$i]['subCategory'][$j]['id'])->lists('product_id')->toarray();
                                $productCount = Product::wherein('id',$productList)->where('is_active',1)->count();
                                if($productCount > 0){
                                    $categories[$i]['productCount'] += $productCount;
                                }
                            }
                        }
                    }
                }
            }
        }else{
            $categories = null;
        }

        return $categories;

    }
}
