<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Auth;
use Input;
use Session;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'task';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
            'password' => 'required|min:4|confirmed',
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

    protected function login(Request $request)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required|'
        ];

        $val = Validator::make($request->all(), $rules);

        if ($val->fails()) {
            return redirect()->action('HomeController@index')->withErrors($val)->withInput($request->flashExcept('password'));
        } else {
            $credentials = $request->only('email', 'password'); 
            if (Auth::attempt($credentials)) {
                return redirect()->action('HomeController@home');
            }
            else{
                return redirect()->action('HomeController@index')->withErrors($val)->withInput($request->flashExcept('password'));
            } 
        }
    }

    protected function logout()
    {
        Auth::logout();
        return redirect()->action('HomeController@index');
    }

    protected function register(Request $request)
    {
        $input = $request->all();
        $val = $this->validator($input);

        if($val->fails())
        {
            return redirect()->action('HomeController@register')->withErrors($val);
        }
        else{            
            
            if ($this->create($input)) {
                Session::flash('register_success', 'Success');
                return redirect()->action('HomeController@index');
            } else {
                return redirect()->action('HomeController@register')->withErrors($val);
            }                
        }
    }
}
