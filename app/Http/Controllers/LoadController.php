<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Load;
use App\Driver;
use App\Truck;
use App\Confirmation;
use App\Custom;
use App\Manifest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class LoadController extends Controller
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
        $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)->get();
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)
                         ->where('in_service','=',false)->get();
        
        if(Session::get('loadSuccess') != ''){
            return view('load', ['loads' => $loads, 'drivers' => $drivers, 'trucks'=>$trucks, 'loadSuccess'=> Session::get('loadSuccess')]);
        }  

        return view('load', ['loads' => $loads, 'drivers' => $drivers, 'trucks'=>$trucks]);
    }

    public function fetchLoad($id){
        if($id == null || $id == "undefined"){
            $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();
            return view('load', ['loads' => $loads, 'loadError'=> "No load was found with that id."]);
        }

        $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();
        
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)->get();
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)
                         ->where('in_service','=',false)->get();

        $fetchedLoad = Load::find($id);

        if($fetchedLoad != null){
            $fetchedDriver = Driver::find($fetchedLoad->driver_id);
            $fetchedTruck = Truck::find($fetchedLoad->truck_id);
            $confirmations = Confirmation::where('load_id', '=', $fetchedLoad->id)->get();
        }
        else{
            return view('load', ['loads' => $loads, 'loadError'=> "No load was found with that id."]);
        }

        if($fetchedLoad != null && $fetchedDriver != null && $fetchedTruck != null){
            return view('load', ['loads' => $loads, 'fetchedLoad' => $fetchedLoad, 'fetchedDriver'=>$fetchedDriver, 'fetchedTruck' =>$fetchedTruck, 'confirmations' => $confirmations]);
        }
        else if($fetchedLoad != null && $fetchedDriver == null && $fetchedTruck != null){
            return view('load', ['loads' => $loads, 'fetchedLoad' => $fetchedLoad, 'fetchedTruck' =>$fetchedTruck, 'confirmations' => $confirmations]);
        }
        else if($fetchedLoad != null && $fetchedDriver != null && $fetchedTruck == null){
            return view('load', ['loads' => $loads, 'fetchedLoad' => $fetchedLoad, 'fetchedDriver'=>$fetchedDriver, 'confirmations' => $confirmations]);
        }
        else{
            return view('load', ['loads' => $loads, 'fetchedLoad' => $fetchedLoad, 'drivers' => $drivers, 'trucks' => $trucks, 'confirmations' => $confirmations]);
        }  
    }

    public function deleteLoad($id){
        $fetchedLoad = Load::find($id);
        $fetchedDriver = Driver::find($fetchedLoad->driver_id);
        $fetchedTruck = Truck::find($fetchedLoad->truck_id);
        
        $fetchedConfirmations = Confirmation::where("load_id", "=", $fetchedLoad->id); 
        $fetchedCustoms = Custom::where("load_id", "=", $fetchedLoad->id); 
        $fetchedManifests = Manifest::where("load_id", "=", $fetchedLoad->id); 

        if($fetchedDriver != null){
            $fetchedDriver->in_use = false;
            $fetchedDriver->save();
        }
        
        if($fetchedTruck != null){
            $fetchedTruck->in_use = false;
            $fetchedTruck->save();
        }

        foreach($fetchedConfirmations->get() as $confirmation){
            Storage::delete($confirmation->path);
        }

        foreach($fetchedCustoms->get() as $customs){
            Storage::delete($customs->path);
        }

        foreach($fetchedManifests->get() as $manifest){
            Storage::delete($manifest->path);
        }
        
        $fetchedLoad->delete();
        $fetchedConfirmations->delete();
        $fetchedCustoms->delete();
        $fetchedManifests->delete();

        return redirect('/loads')->with(['loadSuccess' => "Load has been successfully deleted."]);
    }

    public function fetchConfirmation($id){
        $confirmation = Confirmation::where('id', '=', $id)->first();

        return response()->download("C:\wamp64\www\FleetPortal\storage\app/".$confirmation->path);
    }

    public function updateLoad(Request $request, $id){
        $load = Load::find($id);

        $this->validate($request, [
            'load_number' => 'required|unique:loads,load_number,'. $load->id,
            'confirmationDocument' => 'mimes:pdf'
        ]);

        $load->load_number = $request->get('load_number');
        $load->price = $request->get('price');
        $load->po_number = $request->get('po_number');
        $load->contact = $request->get('contact');
        $load->type = $request->get('type');
        $load->company = $request->get('company');
        $load->company_phone = $request->get('company_phone');
        $load->pickup_address = $request->get('pickup_address');
        $load->delivery_address = $request->get('delivery_address');
        $load->pickup_date = $request->get('pickup_date');
        $load->delivery_date = $request->get('delivery_date');
        $load->load_notes = $request->get('load_notes');
        $load->delivery_notes = $request->get('delivery_notes');
        $load->load_feedback = $request->get('load_feedback');
        
        if($request->get('driver') != null){
            $driver = Driver::where('driver_name', '=', $request->get('driver'))->first();
            $driver->in_use = true;
            $driver->save();
            $load->driver_id = $driver->id;
        }

        if($request->get('truck') != null){
            $truck = Truck::where('truck_number', '=', $request->get('truck'))->first();
            $truck->in_use = true;
            $truck->save();
            $load->truck_id = $truck->id;
        }

        $load->contact = $request->get('contact');
        $load->save();

        if($request->hasFile('confirmationDocument')){
            $UserFolder = \Auth::user()->id;
            
            $path = Storage::putFile('public/confirmations/'. $UserFolder, $request->file('confirmationDocument'));

            $confirmation = new Confirmation;
            
            $confirmation->load_id = $load->id;
            $confirmation->uploaded_by = \Auth::user()->first_name . " " . \Auth::user()->last_name;
            $confirmation->document_name = $request->file('confirmationDocument')->getClientOriginalName();
            $confirmation->upload_date =  Carbon::now();
            $confirmation->path = $path;
            
            $confirmation->save();         
        }

        return redirect('/loads')->with(['loadSuccess' => "Load has been successfully updated."]);
    }


    public function createLoad(Request $request){
        $this->validate($request, [
            'load_number' => 'required|unique:loads',
            'confirmationDocument' => 'mimes:pdf'
        ]);

        $load = new Load;  

        $load->dispatcher_id = \Auth::user()->id;
        $load->load_number = $request->get('load_number');
        $load->price = $request->get('price');
        $load->po_number = $request->get('po_number');
        $load->type = $request->get('type');
        $load->contact = $request->get('contact');
        $load->company = $request->get('company');
        $load->company_phone = $request->get('company_phone');
        $load->pickup_address = $request->get('pickup_address');
        $load->delivery_address = $request->get('delivery_address');
        $load->pickup_date = $request->get('pickup_date');
        $load->delivery_date = $request->get('delivery_date');
        $load->load_notes = $request->get('load_notes');
        $load->delivery_notes = $request->get('delivery_notes');
        $load->load_feedback = $request->get('load_feedback');
        
        
        if($request->get('driver') != null){
            $driver = Driver::where('driver_name', '=', $request->get('driver'))->first();
            $driver->in_use = true;
            $driver->save();
            $load->driver_id = $driver->id;
        }

        if($request->get('truck') != null){
            $truck = Truck::where('truck_number', '=', $request->get('truck'))->first();
            $truck->in_use = true;
            $truck->save();
            $load->truck_id = $truck->id;
        }
        
        $load->contact = $request->get('contact');
        $load->save();

        if($request->hasFile('confirmationDocument')){
            $UserFolder = \Auth::user()->id;
            
            $path = Storage::putFile('public/confirmations/'. $UserFolder, $request->file('confirmationDocument'));

            $confirmation = new Confirmation;
            
            $confirmation->load_id = $load->id;
            $confirmation->uploaded_by = \Auth::user()->first_name . " " . \Auth::user()->last_name;
            $confirmation->document_name = $request->file('confirmationDocument')->getClientOriginalName();
            $confirmation->upload_date =  Carbon::now();
            $confirmation->path = $path;
            
            $confirmation->save();         
        }
        
        $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();
        $drivers = Driver::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)->get();
        $trucks = Truck::where('dispatcher_id', \Auth::user()->id)
                         ->where('in_use', '=', false)
                         ->where('in_service','=',false)->get();

        return view('load', ['loadSuccess' => "Load has been successfully added.", 'loads' => $loads, 'drivers' => $drivers, 'trucks'=>$trucks]);
    }
}
