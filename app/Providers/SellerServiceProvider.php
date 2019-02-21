<?php

namespace App\Providers;

use App\Agronomy;
use App\Category;
use App\OrderStatus;
use App\RmaStatus;
use App\Role;
use App\Product;
use App\ProductQueryStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class SellerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        /*view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $userProfileImage = null;
                $role = null;
            }else{
                $user = Auth::user();
                $role = Role::findOrFail($user->role_id);
                if($user->profile_image!=null){
                    if($role->slug=='seller'){
                        $UploadPath = env('SELLER_FILE_UPLOAD');
                    }elseif($role->slug=='admin'){
                        $UploadPath = env('SELLER_FILE_UPLOAD');
                    }
                    $userOwnDirecory = $UploadPath."/".sha1($user->id)."/"."profile_image/".$user->profile_image;
                    $userProfileImage = $userOwnDirecory;
                }else{
                    $userProfileImage = null;
                }
            }
            $view->with(compact('userProfileImage','role'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(!Auth::guest()){
                $firstCategory = Category::first();
                if($firstCategory==null || empty($firstCategory)){
                    $firstCategory = null;
                }
            }else{
                $firstCategory = null;
            }
            $view->with(compact('firstCategory'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(!Auth::guest()){
                $user = Auth::user();
                $role = Role::findOrFail($user->role_id);
                $this->seller = $user->seller()->first();
                if($role->slug=='seller'){
                    $queryStatus = ProductQueryStatus::where('slug','query_raised')->first();
                    $productCount = Product::where('seller_id',$this->seller->id)->where('product_query_status_id',$queryStatus->id)->where('product_read_status',$queryStatus->id)->count();
                }elseif($role->slug=='admin'){
                    $queryStatus = ProductQueryStatus::where('slug','query_resolved')->first();
                    $productCount = Product::where('product_query_status_id',$queryStatus->id)->where('product_read_status',$queryStatus->id)->count();
                }else{
                    $queryStatus = ProductQueryStatus::where('slug','query_resolved')->first();
                    $productCount = Product::where('product_query_status_id',$queryStatus->id)->where('product_read_status_operational',$queryStatus->id)->count();

                }
            }else{
                $productCount = 0;
            }
            $view->with(compact('productCount'));
        });

        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $firstName = null;
            }else{
                $user = Auth::user();
                $is_email = $user->is_email;
                $is_active = $user->is_active;
                $firstName = ucfirst($user->first_name);
            }
            $view->with(compact('firstName','is_email','is_active'));
        });

        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $orderStatus = null;
            }else{
                $orderStatus = OrderStatus::where('slug','to_pack')->first();
            }
            $view->with(compact('orderStatus'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $readyToPick = null;
            }else{
                $readyToPick = OrderStatus::where('slug','ready_to_pick')->first();
            }
            $view->with(compact('readyToPick'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $readyToPick = null;
            }else{
                $backOrdered = OrderStatus::where('slug','back_ordered')->first();
            }
            $view->with(compact('backOrdered'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $pendingOrder = null;
            }else{
                $pendingOrder = OrderStatus::where('slug','pending')->first();
            }
            $view->with(compact('pendingOrder'));
        });
        view()->composer('backend.seller.layouts.master',function($view){
            if(Auth::guest()){
                $userProfileImage = null;
                $role = null;
            }else{
                $user = Auth::user();
                $role = Role::findOrFail($user->role_id);
            }
            $view->with(compact('role'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $rmaStatus = null;
            }else{
                $rmaStatus = RmaStatus::where('slug','requested')->first();
            }
            $view->with(compact('rmaStatus'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $rmaStatusRejected = null;
            }else{
                $rmaStatusRejected = RmaStatus::where('slug','rejected')->first();
            }
            $view->with(compact('rmaStatusRejected'));
        });
        view()->composer('backend.partials.common.nav',function($view){
            if(Auth::guest()){
                $agronomyCount = null;
            }else{
                $agronomyCount = Agronomy::count();
            }
            $view->with(compact('agronomyCount'));
        });*/
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
}
