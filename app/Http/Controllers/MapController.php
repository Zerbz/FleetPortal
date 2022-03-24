<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Load;
use App\Route;

class MapController extends Controller
{   
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:web,driver');
    }

    public function getId(){
        if(Auth::check()){
           $userId = Auth::user()->id;

           $json = json_encode($userId);

           return response($json);
        }
    }

    public function displayRoute(Request $request)
    {
        if($request->get('id') != null){
            if(Auth::check()){
                if(Auth::guard('web')->check()){
                    $load = Load::where('dispatcher_id', \Auth::user()->id)
                    ->where('id','=',$request->get('id'))->first();
                    $route = Route::where('dispatcher_id', \Auth::user()->id) 
                    ->where('load_id','=',$request->get('id'))->first();
                }
                else if(Auth::guard('driver')->check()){
                    $load = Load::where('id','=',$request->get('id'))->first();
                    $route = Route::where('load_id','=',$request->get('id'))->first();  
                }
                
                if($route != null && $route->load_id == $request->get('id')){
                    $json = json_decode($route->route);
                }else if($load->pickup_address != null && $load->delivery_address != null){
                        $pickupAddress = urlencode(trim($load->pickup_address));
                        $deliveryAddress = urlencode(trim($load->delivery_address));

                        $xmlFile = file_get_contents("http://dev.virtualearth.net/REST/v1/Locations?o=xml&q=$pickupAddress&key=Ajamsez-IhfvKV1ztlkAy69_bmdtCBAQi0vfzUNO6gTqWK1_zvBlRQXd46aKcOfi");
                        $data = new \SimpleXMLElement($xmlFile);
                        
                        $pickupLat = (string) $data->ResourceSets->ResourceSet->Resources->Location->Point->Latitude;
                        $pickupLong = (string) $data->ResourceSets->ResourceSet->Resources->Location->Point->Longitude;
                        
                        $xmlFile = file_get_contents("http://dev.virtualearth.net/REST/v1/Locations?o=xml&q=$deliveryAddress&key=Ajamsez-IhfvKV1ztlkAy69_bmdtCBAQi0vfzUNO6gTqWK1_zvBlRQXd46aKcOfi");
                        $data = new \SimpleXMLElement($xmlFile);
                        
                        $deliveryLat = (string) $data->ResourceSets->ResourceSet->Resources->Location->Point->Latitude;
                        $deliveryLong = (string) $data->ResourceSets->ResourceSet->Resources->Location->Point->Longitude;
                        

                        $loadRoute[] = 
                        array('pickupLat' => $pickupLat,
                            'pickupLong' => $pickupLong,
                            'deliveryLat' => $deliveryLat,
                            'deliveryLong' => $deliveryLong,
                        );

                        $json = json_encode($loadRoute);
                }
                else{
                    $json = "Pickup address or delivery address is null";
                }

                return response($json);
            }
        }
        else{
            return response("Request Failed");
        }
       
    }

    public function storeRoute(Request $request)
    {
        if($request->get('route') != null && $request->get('id') != null){

            if(Auth::check()){
                if(Auth::guard('web')->check()){
                    $load = Load::where('dispatcher_id', \Auth::user()->id)
                    ->where('id','=',$request->get('id'))->first();        
                    $route = Route::where('dispatcher_id', \Auth::user()->id) 
                    ->where('load_id','=',$request->get('id'))->first();   
                }
                else if(Auth::guard('driver')->check()){  
                    $load = Load::where('driver_id', \Auth::user()->id)
                            ->where('id','=',$request->get('id'))->first(); 
                    $route = Route::where('driver_id', \Auth::user()->id)
                            ->where('load_id','=',$request->get('id'))->first();  
                }

                if($load != null){
                    if($route == null){
                        $route = new Route;
                    }

                    $json = json_encode($request->get('route'));

                    $route->load_id = $load->id;
                    $route->dispatcher_id = $load->dispatcher_id;

                    if($load->driver_id != null){
                        $route->driver_id = $load->driver_id;
                    }
                    
                    $route->route = $json;

                    $route->save();

                    return response($json);
                }
                else{
                    return response("No load found");
                }
            }

            return response("An Error has occured.");
        }
    }
}
