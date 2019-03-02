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
        return redirect('/');
    }
}
