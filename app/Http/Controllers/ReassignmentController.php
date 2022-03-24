<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Load;
use App\Driver;
use App\Truck;
use App\Route;
use Illuminate\Support\Facades\Session;

class ReassignmentController extends Controller
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
        $loads = Load::where('dispatcher_id', \Auth::user()->id)
                         ->where('truck_id', '=', null)
                         ->where('driver_id', '=', null)->get();

        $assignedLoads = Load::where('dispatcher_id', \Auth::user()->id)
                                ->where('truck_id', '!=', null)
                                ->where('driver_id', '!=', null)->get();
      
        
        if(Session::get('loadSuccess') != ''){
            return view('reassign', ['loads' => $loads, 'assignedLoads' => $assignedLoads, 'loadSuccess'=> Session::get('loadSuccess')]);
        }
        
        return view('reassign', ['loads' => $loads, 'assignedLoads' => $assignedLoads]);
    }

    public function fetchLoad($id){
        $loads = Load::where('dispatcher_id', \Auth::user()->id)
                         ->where('truck_id', '=', null)
                         ->where('driver_id', '=', null)->get();

        $assignedLoads = Load::where('dispatcher_id', \Auth::user()->id)
                            ->where('truck_id', '!=', null)
                            ->where('driver_id', '!=', null)->get();
        
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)->get();
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)
                         ->where('in_service','=',false)->get();

        $fetchedLoad = Load::find($id);
        $fetchedDriver = Driver::find($fetchedLoad->driver_id);
        $fetchedTruck = Truck::find($fetchedLoad->truck_id);

        if($fetchedLoad != null && $fetchedDriver != null && $fetchedTruck != null){
            return view('reassign', ['loads' => $loads, 'assignedLoads' => $assignedLoads, 'fetchedLoad' => $fetchedLoad, 'fetchedDriver'=>$fetchedDriver, 'fetchedTruck' =>$fetchedTruck, 'drivers' => $drivers, 'trucks' => $trucks]);
        }
        else if($fetchedLoad != null && $fetchedDriver == null && $fetchedTruck != null){
            return view('reassign', ['loads' => $loads, 'assignedLoads' => $assignedLoads, 'fetchedLoad' => $fetchedLoad, 'fetchedTruck' =>$fetchedTruck, 'drivers' => $drivers, 'trucks' => $trucks]);
        }
        else if($fetchedLoad != null && $fetchedDriver != null && $fetchedTruck == null){
            return view('reassign', ['loads' => $loads, 'assignedLoads' => $assignedLoads, 'fetchedLoad' => $fetchedLoad, 'fetchedDriver'=>$fetchedDriver, 'drivers' => $drivers, 'trucks' => $trucks]);
        }
        else{
            return view('reassign', ['loads' => $loads, 'assignedLoads' => $assignedLoads, 'fetchedLoad' => $fetchedLoad, 'drivers' => $drivers, 'trucks' => $trucks]);
        }  
    }

    public function removeUnit($id){
        $fetchedLoad = Load::find($id);
        $fetchedDriver = Driver::find($fetchedLoad->driver_id);
        $fetchedTruck = Truck::find($fetchedLoad->truck_id);
        $fetchedRoute = Route::where('load_id', '=', $fetchedLoad->id)->first();

        $fetchedLoad->driver_id = null;
        $fetchedLoad->truck_id = null;

        $fetchedDriver->in_use = false;
        $fetchedTruck->in_use = false;

        if($fetchedRoute != null){
            $fetchedRoute->driver_id = null;
            $fetchedRoute->save();
        }
        
        $fetchedLoad->save();
        $fetchedDriver->save();
        $fetchedTruck->save();
        

        return redirect('/reassign')->with(['loadSuccess' => "Unit has been successfully removed."]);

    }

    public function updateLoad(Request $request, $id){
        $load = Load::find($id);
        $truck = Truck::find($load->truck_id);
        $route = Route::where('load_id', '=', $load->id)->first();

        $this->validate($request, [
            'load_number' => 'required|unique:loads,load_number,'. $load->id,
        ]);

        $load->load_number = $request->get('load_number');
        
        
        if($request->get('driver') != null){
            $driverOld = Driver::where('id', '=', $load->driver_id)->first();

            if($driverOld != null){
                $driverOld->in_use = false;
                $driverOld->save();
            }
            
            $driver = Driver::where('driver_name', '=', $request->get('driver'))->first();
            $driver->in_use = true;
          
            $driver->save();
            $load->driver_id = $driver->id;
        }

        if($request->get('truck') != null){
            $truckOld = Truck::where('id', '=', $load->truck_id)->first();

            if($truckOld != null){
                $truckOld->in_use = false;
                $truckOld->save();
            }

            $truck = Truck::where('truck_number', '=', $request->get('truck'))->first();

            if($request->get('truck_notes') != null){
                $truck->truck_notes = $request->get('truck_notes');
            }

            $truck->in_use = true;
            $truck->save();
            $load->truck_id = $truck->id;
        }

        if($route != null){
            $route->driver_id = $load->driver_id;
            $route->save();
        }

        $load->save();

        return redirect('/reassign')->with(['loadSuccess' => "Load has been successfully updated."]);
    }

}
