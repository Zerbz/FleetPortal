<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Driver;
use App\Load;
use App\Custom;
use App\Manifest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UploadController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {
        $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();

        if(\Auth::guard('driver')->check()){
            $load = Load::where('driver_id', \Auth::user()->id)->first();

            $customs = Custom::where('load_id', '=', $load->id)->get();
            
            $manifests = Manifest::where('load_id', '=', $load->id)->get();
        }

        if(Session::get('uploadSuccess') != ''){

            if(\Auth::guard('web')->check()){
                return view('upload', ['loads' => $loads, 'uploadSuccess'=> Session::get('uploadSuccess')]);
              
            }
            elseif(\Auth::guard('driver')->check()){
                return view('upload', ['customs' => $customs, 'manifests' => $manifests, 'loadInfo' => $load,'uploadSuccess'=> Session::get('uploadSuccess')]);
            }
        }else{
            if(\Auth::guard('web')->check()){
                return view('upload', ['loads' => $loads]);
            } 
            elseif(\Auth::guard('driver')->check()){
                return view('upload', ['customs' => $customs, 'manifests' => $manifests, 'loadInfo' => $load]);
            }
        }    
    }

    public function fetchLoad($id){
        $load = Load::where('dispatcher_id', \Auth::user()->id)
                    ->where('id', '=', $id)->first();

        $customs = Custom::where('load_id', '=', $id)->get();

        $manifests = Manifest::where('load_id', '=', $id)->get();

        $loads = Load::where('dispatcher_id', \Auth::user()->id)->get();

        return view('upload', ['customs' => $customs, 'manifests' => $manifests, 'loads' => $loads, 'loadInfo' => $load]);
    }

    public function fetchCustoms($id){
        $custom = Custom::where('id', '=', $id)->first();

        return response()->download("storage/app/".$custom->path);
    }

    public function fetchManifest($id){
        $manifest = Manifest::where('id', '=', $id)->first();

        return response()->download("storage/app/".$manifest->path);
    }

    public function uploadDocument(Request $request, $id){
        $this->validate($request, [
            'customsDocuments' => 'mimes:pdf',
            'manifestDocuments' => 'mimes:pdf',
        ]);
        
        if(\Auth::guard('driver')->check()){
            $load = Load::where('driver_id', \Auth::user()->id)
            ->where('id', '=', $id)->first();
        }
        else{
            $load = Load::where('dispatcher_id', \Auth::user()->id)
                    ->where('id', '=', $id)->first();
        }

        if($request->hasFile('customsDocuments')){
            $UserFolder = $load->dispatcher_id;
            
            $path = Storage::putFile('public/customs_documents/'. $UserFolder, $request->file('customsDocuments'));

            $customs = new Custom;
            
            $customs->load_id = $load->id;
            $customs->document_name = $request->file('customsDocuments')->getClientOriginalName();
            $customs->upload_date =  Carbon::now();
            $customs->path = $path;

            if(\Auth::guard('web')->check()){
                $customs->uploaded_by = \Auth::user()->first_name . " " . \Auth::user()->last_name;
            }
            elseif(\Auth::guard('driver')->check()){
                $customs->uploaded_by = \Auth::user()->driver_name;
            }

           
            
            $customs->save();         
        }

        if($request->hasFile('manifestDocuments')){
            $UserFolder = $load->dispatcher_id;

            $path = Storage::putFile('public/manifest_documents/'. $UserFolder, $request->file('manifestDocuments'));

            $manifest = new Manifest;
            
            $manifest->load_id = $load->id;       
            $manifest->document_name = $request->file('manifestDocuments')->getClientOriginalName();
            $manifest->upload_date =  Carbon::now();
            $manifest->path = $path;

            if(\Auth::guard('web')->check()){
                $manifest->uploaded_by = \Auth::user()->first_name . " " . \Auth::user()->last_name;
            }
            elseif(\Auth::guard('driver')->check()){
                $manifest->uploaded_by = \Auth::user()->driver_name;
            }
            
            $manifest->save();
        }

        return redirect('/upload')->with(['uploadSuccess' => "Document(s) successfully uploaded."]);
      
    }
}
