@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
        <div class="col-md-2 col-md-offset">
            <div class="panel panel-default">
                <div class="panel-heading">Loads</div>

                <div class="panel-body">
                <div>
                    <ul>
                        @if(isset($loads))
                                @foreach($loads as $load)    
                                    <li>
                                        <a href="{{url('/reassign/fetchLoad/')}}/{{$load->id}}">{{$load->load_number}}</a>
                                    </li>
                                    @endforeach
                        @endif
                    </ul>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">Assigned Loads</div>

                <div class="panel-body">
                <div>
                    <ul>
                        @if(isset($assignedLoads))
                                @foreach($assignedLoads as $assigned)    
                                    <li>
                                        <a href="{{url('/reassign/fetchLoad/')}}/{{$assigned->id}}">{{$assigned->load_number}}</a>
                                    </li>
                                @endforeach
                        @endif
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10 col-md-offset">
            <div class="panel panel-default">
                <div class="panel-heading">Reassignment</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Select a load from the list on the left to begin reassignment. 
                    @if(isset($fetchedLoad))
                    <form class="form-horizontal" method="POST" action="{{url('/reassign/update')}}/{{$fetchedLoad->id}}">
                    @else
                    <form class="form-horizontal">
                    @endif
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('load_number') ? ' has-error' : '' }}">
                            
                                @if(isset($fetchedLoad))
                                <label for="load_number" class="col-md-4 control-label">Load #</label>
                                <div class="col-md-4">   
                                    <input id="load_number" type="text" class="form-control" name="load_number" value="{{$fetchedLoad->load_number}}" required autofocus readonly>
                                @endif
                                @if ($errors->has('load_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('load_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('driver_id') ? ' has-error' : '' }}">
                            @if(isset($fetchedLoad))
                            <label for="driver" class="col-md-4 control-label">Driver:</label>
                             <div class="col-md-4">
                                    <select class="form-control" id="sel1" name="driver">
                                    @if(isset($fetchedDriver))
                                            <option>
                                                {{$fetchedDriver->driver_name}}
                                            </option>
                                        @if(isset($drivers))
                                            @foreach($drivers as $driver)    
                                                <option>
                                                    {{$driver->driver_name}}
                                                </option>
                                                @endforeach
                                        @endif
                                    @else
                                        <option></option>
                                        @if(isset($drivers))
                                                @foreach($drivers as $driver)    
                                                    <option>
                                                      {{$driver->driver_name}}
                                                    </option>
                                                @endforeach
                                        @endif
                                    @endif      
                                    </select>
                                    @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('truck_id') ? ' has-error' : '' }}">
                        
                            @if(isset($fetchedLoad))
                                <label for="truck" class="col-md-4 control-label">Truck:</label>
                                <div class="col-md-4">
                                    <select class="form-control" id="sel1" name="truck">
                                        @if(isset($fetchedTruck))
                                                <option>
                                                    {{$fetchedTruck->truck_number}}
                                                </option>
                                                @if(isset($trucks))
                                                    @foreach($trucks as $truck)    
                                                        <option>
                                                            {{$truck->truck_number}}
                                                        </option>
                                                    @endforeach               
                                        @endif    
                                        @else
                                            <option></option>
                                            @if(isset($trucks))
                                                    @foreach($trucks as $truck)    
                                                        <option>
                                                        {{$truck->truck_number}}
                                                        </option>
                                                    @endforeach
                                            @endif
                                        @endif      
                                        </select>
                                    @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('truck_notes') ? ' has-error' : '' }}">
                             @if(isset($fetchedLoad))
                                    <label for="truck_notes" class="col-md-4 control-label">Truck Notes:</label>
                                    @if(isset($fetchedTruck))
                                        <div class="col-xs-4 col-md-4">
                                            <textarea class="form-control" rows="5" id="truck_notes" name="truck_notes">{{$fetchedTruck->truck_notes}}</textarea>
                                        </div>
                                    @else
                                        <div class="col-xs-4 col-md-4">
                                            <textarea class="form-control" rows="5" id="truck_notes" name="truck_notes"></textarea>
                                        </div>
                                    @endif
                                @endif     
                        </div>
        
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                @if(isset($fetchedLoad))  
                                    <button type="submit" class="btn btn-primary">
                                        Update Load
                                    </button>
                                @endif
                                @if(isset($fetchedDriver) || isset($fetchedTruck))
                                    <a class="btn btn-danger" href="{{url('/reassign/remove/')}}/{{$fetchedLoad->id}}">Remove Unit</a>
                               @endif
                            </div>
                        </div>
                    </form>
                    <div class="col-md-10">
                        @if(!empty($loadSuccess))     
                            <div class="alert alert-success">
                                <p>{{$loadSuccess}}</p>  
                            </div>                        
                            @elseif(!empty($loadError))
                            <div class="alert alert-danger">
                                <p>{{$loadError}}</p>  
                            </div>  
                        @endif
                    </div>            
                </div>               
             </div>
        </div>
    </div>
</div>
@endsection
