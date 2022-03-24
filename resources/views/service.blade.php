@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2 col-md-offset">
            <div class="panel panel-default">
                <div class="panel-heading">Trucks</div>

                <div class="panel-body">
                <div>
                    <ul>
                        @if(isset($trucksInService))
                                @foreach($trucksInService as $truck)    
                                    <li>
                                        <a href="{{url('/service/fetchTruck/')}}/{{$truck->id}}">{{$truck->truck_number}}</a>
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
                <div class="panel-heading">Trucks</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Select a truck from the list on the left to remove it from service. Otherwise a truck can be selected and set in service below.
                    @if(isset($repairTruck))
                        <form class="form-horizontal" method="POST" action="{{url('/service/removeService/')}}/{{$repairTruck->id}}">
                    @else  
                        <form class="form-horizontal" method="POST" action="{{ route('setService') }}">
                    @endif
                        {{ csrf_field() }}

                       <div class="form-group{{ $errors->has('truck_number') ? ' has-error' : '' }}">            
                        <label for="truck_number" class="col-md-4 control-label">Truck:</label>
                            <div class="col-md-6">
                                <select class="form-control" id="sel1" name="truck_number">
                                    @if(isset($repairTruck))
                                            <option readonly>{{$repairTruck->truck_number}}</option>
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
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location" class="col-md-4 control-label">Location: </label>

                            <div class="col-md-6">
                                @if(isset($repairTruck))
                                    <input id="location" type="text" class="form-control" name="location" value="{{$repairTruck->location }}" required autofocus>
                                @else
                                    <input id="location" type="text" class="form-control" name="location" value="{{ old('location') }}" required autofocus>
                                @endif

                                @if ($errors->has('make'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('make') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('eta') ? ' has-error' : '' }}">
                            <label for="eta" class="col-md-4 control-label">ETA:</label>
                                <div class='col-md-6'>
                                    <div class='input-group date' id='pickuppicker'>
                                        @if(isset($repairTruck))
                                            <input id="datetimepicker" type="text" class="form-control" name="eta" value="{{$repairTruck->estimated_repair_date }}" autofocus>
                                        @else
                                            <input id="datetimepicker" type="text"  class="form-control" name="eta"/>
                                        @endif                  
                                            <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                               
                        
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description" class="col-md-4 control-label">Description:</label>
                                    @if(isset($repairTruck))
                                        <div class="col-xs-4 col-md-4">
                                            <textarea class="form-control" rows="5" id="description" name="description">{{$repairTruck->repair_description}}</textarea>
                                        </div>
                                    @else
                                    <div class="col-xs-4 col-md-4">
                                        <textarea class="form-control" rows="5" id="description" name="description"></textarea>
                                    </div>
                                @endif 
                        </div>

                         
                        @if(!empty($serviceSuccess))     
                            <div class="alert alert-success">
                                <p>{{$serviceSuccess}}</p>  
                            </div>                        
                        @elseif(!empty($serviceError))
                            <div class="alert alert-danger">
                                <p>{{$serviceError}}</p>  
                            </div>  
                        @endif
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                @if(isset($repairTruck))  
                                    <a class="btn btn-danger" href="{{url('/service/removeService/')}}/{{$repairTruck->id}}">Remove truck from service</a>
                                @else
                                    <button type="submit" class="btn btn-primary">
                                        Set truck in service
                                    </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
