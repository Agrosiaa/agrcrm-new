<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\CustomTraits\ExcelTrait;
use App\Http\Controllers\CustomTraits\ImageMagickTrait;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ExcelController extends Controller
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

    use ExcelTrait;
    use ImageMagickTrait;
}
