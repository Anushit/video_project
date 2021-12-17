<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    
    public function login()
    {   
        return view('auth.login');
    }

    public function authenticate(Request $request){
       
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);
        
        // Retrive Input
        $credentials = $request->only('phone', 'password');
        if (Auth::attempt(['phone' => $credentials['phone'], 'password' => $credentials['password'] ])) {
            if(Auth()->user()->type==2) {
                Auth::logout();
                $message = "Oppes! You have entered invalid credentials";
            }else{
                toastr()->success("You are logged in successfully");
                return redirect()->intended(\Config::get('constants.admin_url.admin').'/dashboard');
            }
        } else {
            $message = "Oops! You have entered invalid credentials";
        }


        toastr()->error($message); 
        return redirect('login');
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();


        return redirect('/login')->with('info','You have been logged out.');
    }
}
