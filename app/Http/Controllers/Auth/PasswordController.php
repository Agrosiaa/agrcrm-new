<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Requests\UpdatePasswordRequest;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest',['except'=>['updatePassword']]);
        $this->middleware('auth',['only'=>['updatePassword']]);
    }

    public function updatePassword(UpdatePasswordRequest $request){
        try{
            $user = Auth::user();
            if (Hash::check($request->current_password, $user->password)) {
                $data = [
                    'password' => bcrypt($request->password)
                ];
                $user->update($data);
                $message = trans('message.password_updated_message');
                return Redirect::back()->with('success', $message);
            }else{
                $message = trans('message.current_password_mismatched');
                return Redirect::back()->with('error', $message);
            }
        }catch(\Exception $e){
            abort(500,$e->getMessage());
        }
    }
}
