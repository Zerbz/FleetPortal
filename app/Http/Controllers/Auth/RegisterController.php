<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EmailConfirmation;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Override the register function to prevent it from automatically logging in the user. 
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return view('auth/login', ['needsVerify' => "Thank you! We have sent you a verification email. Please complete your registration by clicking the button in the email!"]);
    }

    public function authorizeUser($key){
        // Get the user that has the matching confirmation key.
        $user = User::where('verification_key', '=', $key)->first();

        // Set the users status to Y for verified
        $user->verified = true;

        // Save the user.
        $user->save();

        return view('auth/login', ['needsVerify' => "Thank you! Your email has been successfully verified, you may proceed to login!"]);
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:11',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   
        $verification_val = strval(uniqid());

        $user = new User;
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->company = $data['company'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];
        $user->password = bcrypt($data['password']);
        $user->verification_key = $verification_val;
        $user->verified = false;
        $user->save();

        $user->notify(new EmailConfirmation($user->verification_key));

    }
}
