<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Load;
use App\Driver;
use App\Truck;
use App\Custom;
use App\Manifest;
use App\CheckIn;

class DriverController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:driver');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $load = Load::where('driver_id', \Auth::user()->id)->first();

        if($load != null && $load->driver_id != null){
            $driver = Driver::where('dispatcher_id', $load->dispatcher_id)
                        ->where('id', '=', $load->driver_id)->first();

            $load->driver_id = $driver->driver_name;     
        }

        if($load != null && $load->truck_id != null){
            $truck = Truck::where('dispatcher_id', $load->dispatcher_id)
            ->where('id', '=', $load->truck_id)->first();

            $load->truck_id = $truck->truck_number;    
        }

        return view('overview', ['load' => $load]);
    }


    public function checkin(Request $request)
    {
        if($request->get('id') != null){     
            $load = Load::where('driver_id', $request->get('id'))->first();
            
            $driver = Driver::where('dispatcher_id', $load->dispatcher_id)
                        ->where('id', '=', $request->get('id'))->first();

            $checkIn = new CheckIn;

            $checkIn->load_id = $load->id;
            $checkIn->dispatcher_id = $load->dispatcher_id;
            $checkIn->driver_id = $request->get('id');
            $checkIn->driver_name = $driver->driver_name;

            if($load->truck_id != null){
                $truck = Truck::where('dispatcher_id', $load->dispatcher_id)
                ->where('id', '=', $load->truck_id)->first();
    
                $checkIn->truck_id = $truck->id;    
                $checkIn->truck_number = $truck->truck_number;    
            }

            if($request->get('position') != null){
                $position = $request->get('position');
                $checkIn->latitude = $position['coords']['latitude'];
                $checkIn->longitude = $position['coords']['longitude'];
            }

            $checkIn->save();
        
            $json = json_encode($checkIn);

            return response($json);
        }
        else{
            return response("Request Failed");
        }  
    }

    
    public function populate(Request $request)
    {
        if($request->get('id') != null){  
            $load = Load::where('driver_id', $request->get('id'))->first();

            $checkIns = CheckIn::where('driver_id', $request->get('id'))
                                ->where('load_id', '=', $load->id)->get();


            $json = json_encode($checkIns);

            return response($json);
        }
        else{
            return response("Request Failed");
        }
    }

    public function updateNotes(Request $request)
    {
        if($request->get('id') != null){  
            $load = Load::where('id', $request->get('id'))->first();

            $load->load_notes = $request->get('load_notes');
            $load->delivery_notes = $request->get('delivery_notes');
            $load->load_feedback = $request->get('load_feedback');

            $load->save();

            return response("Great Success");
        }
        else{
            return response("Request Failed");
        }
    }
    
    public function getLoadId(Request $request)
    {
        if($request->get('id') != null){  
            $load = Load::where('driver_id', $request->get('id'))->first();

            return response($load->id);
        }
        else{
            return response("Request Failed");
        }
    }
    
}
