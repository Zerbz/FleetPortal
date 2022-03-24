<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class DriverManagement extends Controller
{   

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)->get();
        
        if(Session::get('driverSuccess') != ''){
            return view('home', ['drivers' => $drivers, 'driverSuccess'=> Session::get('driverSuccess')]);
        }
        
        return view('home', ['drivers' => $drivers]);
    }

    public function fetchDriver($id){
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)->get();

        $fetchedDriver = Driver::find($id);

        return view('home', ['drivers' => $drivers, 'fetchedDriver' => $fetchedDriver]);
    }

    public function deleteDriver($id){
        $fetchedDriver = Driver::find($id);
        
        $fetchedDriver->delete();

        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)->get();

        return redirect('/home')->with(['driverSuccess' => "Driver has been successfully deleted."]);

    }

    public function updateDriver(Request $request, $id){
        $this->validate($request, [
            'driver_name' => 'required',
            'phone' => 'required',
            
        ]);

        $driver = Driver::find($id);
        
        $driver->driver_name = $request->get('driver_name');
        $driver->phone = $request->get('phone');
        $driver->password = Hash::make($request->get('password'));

        if($request->get('password') != ''){
            if($request->get('password') == $request->get('password_confirmation')){
                $driver->password = Hash::make($request->get('password'));
            }
            else{      
                $drivers = Driver::where('dispatcher_id', \Auth::user()->id)->get();
                return view('home', ['driverError' => "Passwords do not match. Cannot update.", 'drivers' => $drivers]);           
            }    
        }

        $driver->save();
        
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)->get();

        return redirect('/home')->with(['driverSuccess' => "Driver has been successfully updated."]);
    }


    public function createDriver(Request $request){
        $this->validate($request, [
            'driver_name' => 'required',
            'email' => 'required|email|unique:drivers',
            'phone' => 'required',
            'password' => 'required|confirmed'
        ]);

        $driver = new Driver;
        
        $driver->dispatcher_id = \Auth::user()->id;
        $driver->driver_name = $request->get('driver_name');
        $driver->phone = $request->get('phone');
        $driver->email = $request->get('email');
        $driver->password = Hash::make($request->get('password'));
        $driver->in_use = false;


        $driver->save();
        
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)->get();

        return view('home', ['driverSuccess' => "Driver has been successfully added and can now login.", 'drivers' => $drivers]);
    }
}
