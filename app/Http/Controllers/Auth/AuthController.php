<?php

namespace App\Http\Controllers\Auth;

use App\Cart;
use App\Customer;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\TempRequest;
use App\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
    public function viewTemp(){
        return view('temp');
    }
    public function postTemp(TempRequest $request){
        $request->session()->flash('xxx','yyy');
        return redirect('temp');
    }

    public function viewLogin(){
        return view('backend.login');
    }
    //http://stackoverflow.com/questions/22995847/hhvmhacklang-errors-warnings-output-into-browser
    //http://stackoverflow.com/questions/29264326/laravel-5-show-blank-page-on-server-error-and-no-laravel-log-running-with-hhv
    protected function authenticate(LoginRequest $request){
        try{
            $user = User::where('email', $request->email)->first();
            if ($user == NULL || empty($user)) {
                $message="The email address is invalid";
                $request->session()->flash('error', $message);
                return back()->withInput();//->with('error',$message);
            } else{
                if (Auth::attempt(['email' => $request->email,'password' => $request->password])) {
//                            $users = (Auth::user()->first());
                    return redirect('dashboard');
                } else{
                    $message="The email address or password is invalid";
                    $request->session()->flash('error', $message);
                    return back()->withInput();
                }
            }
        }catch(\Exception $e){
            abort(500,$e->getMessage());
        }
    }

    public function viewAdminLogin(){
        return view('backend.admin.login');
    }

    public function logout(\Illuminate\Http\Request $request){
        Auth::logout();
        $message="Logout Successful";
        $request->session()->flash('error', $message);
        if ($request->session()->has('cart')) {
            $request->session()->forget('cart');
        }
        return redirect('/');
    }

    public function confirm($confirmation,\Illuminate\Http\Request $request)
    {
        $user = User::where('remember_token', $confirmation)->first();
        $currentUrl = (explode('/',Request::url()));
        $domain = explode('.',$currentUrl[2]);
        if(count($domain) > 2){
            $redirectUrl = '/';
        }else{
            $redirectUrl = 'user/login';
        }
        if ($user == null) { // no record found
            $message= "Sorry!! No User found";
        } else {
            if ($user->is_email) { // already confirmed
                $message="Your email is already verified";
            } else {
                User::where('remember_token', $confirmation)->update(array('is_email' => 1));
                $message="Your email is confirmed, you can now login to your account";
            }
        }
        $request->session()->flash('success', $message);
        return redirect($redirectUrl);
    }
    public function viewShipmentAdminLogin(){
        return view('backend.shipmentAdmin.login');
    }
    public function viewFinanceAdminLogin(){
        return view('backend.financeAdmin.login');
    }
    public function viewAccountAdminLogin(){
        return view('backend.accountAdmin.login');
    }
}
