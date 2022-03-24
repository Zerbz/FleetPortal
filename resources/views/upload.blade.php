@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
    @if(Auth::guard('web')->check())
        <div class="col-md-2 col-md-offset">
            <div class="panel panel-default">
                <div class="panel-heading">Loads</div>

                <div class="panel-body">
                <div>
                    <ul>
                        @if(isset($loads))
                            @foreach($loads as $load)    
                                <li>
                                    <a href="{{url('/upload/fetchLoad/')}}/{{$load->id}}">{{$load->load_number}}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset">
    @elseif(Auth::guard('driver')->check())
        <div class="col-md-12 col-md-offset">
    @endif
            <div class="panel panel-default">
                <div class="panel-heading">File Upload</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif                 
                    @if(isset($loadInfo)) 
                    <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="{{url('/upload/uploadDocument/')}}/{{$loadInfo->id}}">
                    @else
                    <form class="form-horizontal">
                    @endif
                        {{ csrf_field() }}
                        @if(isset($loadInfo))
                            <h4>Load #{{$loadInfo->load_number}} Files</h4>
                        <div class="row">
                            <div class="form-group{{ $errors->has('customsDocuments') ? ' has-error' : '' }} col-md-6">
                                <label for="customsDocuments" class="col-md-6 control-label">Upload Customs Documents</label>
                                <div class='col-md-10'>
                                    <label class="btn btn-default">
                                        <input type="file" name="customsDocuments">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('manifestDocuments') ? ' has-error' : '' }} col-md-6">
                                <label for="manifestDocuments" class="col-md-4 control-label">Upload Manifests</label>
                                <div class='col-md-10'>
                                    <label class="btn btn-default">
                                        <input type="file" name="manifestDocuments">
                                    </label>
                                </div>
                            </div>
                        </div>
        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                @if(isset($loadInfo))  
                                    <button type="submit" class="btn btn-primary">
                                        Upload
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                    <div>
                        <h2>Upload History</h2>
                        <hr/>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <h6>Customs Documents</h6>
                                @if(!empty($customs))
                                <ul>
                                    @foreach($customs as $custom)    
                                        <li><a href="{{url('/upload/fetchCustoms/')}}/{{$custom->id}}">{{$custom->document_name}} - {{$custom->uploaded_by}}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            <div class="form-group col-md-6">
                                <h6>Manifests</h6>
                                @if(!empty($manifests))
                                <ul>
                                    @foreach($manifests as $manifest)    
                                        <li><a href="{{url('/upload/fetchManifest/')}}/{{$manifest->id}}">{{$manifest->document_name}} - {{$manifest->uploaded_by}}</a></li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div> 
                    @else
                            Select a load from the list on the left to begin upload. 
                    @endif 
                    <div class="col-md-10">
                        @if(!empty($uploadSuccess))     
                            <div class="alert alert-success">
                                <p>{{$uploadSuccess}}</p>  
                            </div>                        
                            @elseif(!empty($uploadError))
                            <div class="alert alert-danger">
                                <p>{{$uploadError}}</p>  
                            </div>  
                        @endif
                    </div>          
                </div>               
             </div>
        </div>
    </div>
</div>
@endsection
