<?php

namespace App\Http\Controllers;

use App\Load;
use App\Driver;
use App\Truck;
use App\CheckIn;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();
        return view('overview', ['loads' => $loads]);
    }


    public function filter(Request $request)
    {
        if($request->get('filter') === "All"){
            $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();
        }
        else{
            $loads = Load::where('dispatcher_id', \Auth::user()->id)
            ->where('type','=',$request->get('filter'))->get();
        }

        $loadsArray = array();       
        $loadsArray = array_values($loads->toArray());
        $json = json_encode($loadsArray); 

        return response($json);
    }


    public function displayLoad(Request $request)
    {
        if($request->get('id') != null){
            $load = Load::where('dispatcher_id', \Auth::user()->id)
            ->where('id','=',$request->get('id'))->first();
            
            if($load->driver_id != null){
                $driver = Driver::where('dispatcher_id', \Auth::user()->id)
                            ->where('id', '=', $load->driver_id)->first();

                $load->driver_id = $driver->driver_name;     
            }

            if($load->truck_id != null){
                $truck = Truck::where('dispatcher_id', \Auth::user()->id)
                ->where('id', '=', $load->truck_id)->first();

                $load->truck_id = $truck->truck_number;    
            }

            $json = json_encode($load);

            return response($json);
        }
        else{
            return response("Request Failed");
        }
       
    }  

    public function populateByType(Request $request)
    {
        if($request->get('type') != null){  
            if($request->get('type') === "All"){
                $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();

                $checkIns = CheckIn::where('dispatcher_id', \Auth::user()->id)->get();
            }
            else{
                $loads = Load::where('dispatcher_id', \Auth::user()->id)
                ->where('type','=',$request->get('type'))->pluck('id')->toArray();

                $checkIns = CheckIn::whereIn('load_id', $loads)->get();
            }
      
            $json = json_encode($checkIns);

            return response($json);
        }
        else{
            return response("Request Failed");
        }
    }

    public function populateById(Request $request)
    {
        if($request->get('id') != null){  
            $load = Load::where('dispatcher_id', \Auth::user()->id)
            ->where('id','=',$request->get('id'))->first();

            $checkIns = CheckIn::where('load_id','=',$load->id)->get();
      
            $json = json_encode($checkIns);

            return response($json);
        }
        else{
            return response("Request Failed");
        }
    }
}
