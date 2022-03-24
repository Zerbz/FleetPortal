<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Truck;
use App\Repair;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ServiceController extends Controller
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
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)
                        ->where('in_service', '=', false)->get();

        $repairs = Repair::where('dispatcher_id', \Auth::user()->id)
                        ->where('repair_completed', '=', null)->get();

        if(Session::get('serviceSuccess') != ''){
            return view('service', ['trucks' => $trucks, 'trucksInService' => $repairs, 'serviceSuccess'=> Session::get('serviceSuccess')]);
        }else{
            return view('service', ['trucks' => $trucks, 'trucksInService' => $repairs]);
        }    
    }

    public function fetchTruck($id){
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)
                        ->where('in_service', '=', false)->get();

        $repairs = Repair::where('dispatcher_id', \Auth::user()->id)
                        ->where('repair_completed', '=', null)->get();

        $repairTruck = Repair::find($id);

        return view('service', ['trucks' => $trucks, 'trucksInService' => $repairs, 'repairTruck' => $repairTruck]);
    }

    public function removeService($id){
        $fetchedRepair = Repair::find($id);
        $truck = Truck::where('dispatcher_id', \Auth::user()->id)
                        ->where('id', '=', $fetchedRepair->truck_id)->first();
    
        $truck->in_service = false;
        $fetchedRepair->repair_completed = Carbon::now();


        $truck->save();
        $fetchedRepair->save();
       
        
        return redirect('/service')->with(['serviceSuccess' => "Truck has been successfully removed from service."]);
    }

    public function setService(Request $request){
        $this->validate($request, [
            'truck_number' => 'required',
        ]);

        $repair = new Repair;   
        $truck = Truck::where('dispatcher_id', \Auth::user()->id)
                        ->where('truck_number', '=', $request->get('truck_number'))->first();
        

        $repair->dispatcher_id = \Auth::user()->id;
        $repair->truck_id = $truck->id;
        $repair->truck_number = $request->get('truck_number');
        $repair->placed_in_service = Carbon::now();

        if($request->get('location') != null){
            $repair->location = $request->get('location');
        }

        if($request->get('eta') != null){
            $repair->estimated_repair_date = $request->get('eta');
        }

        if($request->get('description') != null){
            $repair->repair_description = $request->get('description');
        }
        $truck->in_service = true;

        $truck->save();
        $repair->save();

        return redirect('/service')->with(['serviceSuccess' => "Truck has been successfully put in service."]);
    }
}
