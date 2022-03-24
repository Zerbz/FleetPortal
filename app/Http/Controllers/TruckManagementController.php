<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Truck;
use Illuminate\Support\Facades\Session;

class TruckManagementController extends Controller
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
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)->get();

        if(Session::get('truckSuccess') != ''){
            return view('truck', ['trucks' => $trucks, 'truckSuccess'=> Session::get('truckSuccess')]);
        }else{
            return view('truck', ['trucks' => $trucks]);
        }    
    }

    public function fetchTruck($id){
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)->get();

        $fetchedTruck = Truck::find($id);

        return view('truck', ['trucks' => $trucks, 'fetchedTruck' => $fetchedTruck]);
    }

    public function deleteTruck($id){
        $fetchedTruck = Truck::find($id);
        
        $fetchedTruck->delete();

        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)->get();

        return redirect('/truck')->with(['truckSuccess' => "Truck has been successfully deleted."]);
    }

    public function updateTruck(Request $request, $id){
        $truck = Truck::find($id);

        $this->validate($request, [
            'truck_number' => 'required|unique:trucks,truck_number,'. $truck->id,
            'make' => 'required',
            'model' => 'required',
            'plate_number' => 'required',
            'year' => 'required',
            'mileage' => 'required'
        ]);

    
        $truck->truck_number = $request->get('truck_number');
        $truck->make = $request->get('make');
        $truck->model = $request->get('model');
        $truck->plate_number = $request->get('plate_number');
        $truck->year = $request->get('year');
        $truck->mileage = $request->get('mileage');

        $truck->save();

        return redirect('/truck')->with(['truckSuccess' => "Truck has been successfully updated."]);
    }


    public function createTruck(Request $request){
        $this->validate($request, [
            'truck_number' => 'required|unique:trucks',
            'make' => 'required',
            'model' => 'required',
            'plate_number' => 'required',
            'year' => 'required',
            'mileage' => 'required'
        ]);

        $truck = new Truck;
        
        $truck->dispatcher_id = \Auth::user()->id;
        $truck->truck_number = $request->get('truck_number');
        $truck->make = $request->get('make');
        $truck->model = $request->get('model');
        $truck->plate_number = $request->get('plate_number');
        $truck->year = $request->get('year');
        $truck->mileage = $request->get('mileage');
        $truck->in_use = false;
        $truck->in_service = false;

        $truck->save();
        
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)->get();

        return view('truck', ['truckSuccess' => "Truck has been successfully added.", 'trucks' => $trucks]);
    }
}
