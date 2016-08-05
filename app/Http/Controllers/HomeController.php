<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class HomeController extends Controller
{
    public function index()
    {    	
    	return view('index');
    }

    public function register()
    {
    	return view('register');
    }

    public function home()
    {
    	$user = Auth::user();
    	return view('home')->with(['user' => $user]);
    }
}
