<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function index() 
    {
        return view('profile', ['user' => \Auth::user()]);
    }

    public function update(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',      
        ]);
        
        $user = \Auth::user();

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->phone = $request->get('phone');
        
        $user->save();       

        return view('profile', ['profileStatus' => "Profile has been successfully updated.", 'user' => \Auth::user()]);
    }

}
