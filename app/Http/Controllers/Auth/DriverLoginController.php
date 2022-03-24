<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;


class DriverLoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:driver', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
      return view('auth.driver-login');
    }

    public function login(Request $request)
    {
      $request->session()->flush();
      $this->validate($request, [
        'email'   => 'required|email',
        'password' => 'required'
      ]);

      if (Auth::guard('driver')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {

        return redirect()->intended(route('driver.overview'));
        
      }

      return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::guard('driver')->logout();

        return redirect('/');
    }
}
