@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3 col-md-offset left-panel">
            <div class="panel panel-default">
                <div class="panel-heading">Trucks</div>

                <div class="panel-body">
                <div>
                    <ul>
                        @if(isset($trucks))
                                @foreach($trucks as $truck)    
                                    <li>
                                        <a href="{{url('/truck/modifyTruck/')}}/{{$truck->id}}">{{$truck->truck_number}} - {{$truck->make}} {{$truck->model}}</a>
                                    </li>
                                @endforeach
                        @endif
                    </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-md-offset">
            <div class="panel panel-default">
                <div class="panel-heading">Trucks</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Select a truck from the list on the left to update or delete it. Otherwise a truck can be created here.
                    @if(isset($fetchedTruck))
                        <form class="form-horizontal" method="POST" action="{{url('/truck/updateTruck/')}}/{{$fetchedTruck->id}}">
                    @else  
                        <form class="form-horizontal" method="POST" action="{{ route('createTruck') }}">
                    @endif
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('truck_number') ? ' has-error' : '' }}">
                            <label for="truck_number" class="col-md-4 control-label">Truck #</label>

                            <div class="col-md-6">
                                @if(isset($fetchedTruck))
                                    <input id="truck_number" type="text" class="form-control" name="truck_number" value="{{$fetchedTruck->truck_number}}" required autofocus>
                                @else
                                    <input id="truck_number" type="text" class="form-control" name="truck_number" value="{{ old('truck_number') }}" required autofocus>
                                @endif

                                @if ($errors->has('truck_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('truck_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('make') ? ' has-error' : '' }}">
                            <label for="make" class="col-md-4 control-label">Make</label>

                            <div class="col-md-6">
                                @if(isset($fetchedTruck))
                                    <input id="make" type="text" class="form-control" name="make" value="{{$fetchedTruck->make }}" required autofocus>
                                @else
                                    <input id="make" type="text" class="form-control" name="make" value="{{ old('make') }}" required autofocus>
                                @endif

                                @if ($errors->has('make'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('make') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('model') ? ' has-error' : '' }}">
                            <label for="model" class="col-md-4 control-label">Model</label>

                            <div class="col-md-6">
                                @if(isset($fetchedTruck))
                                    <input id="model" type="text" class="form-control" name="model" value="{{$fetchedTruck->model }}" required autofocus>
                                @else
                                    <input id="model" type="text" class="form-control" name="model" value="{{ old('model') }}" required autofocus>
                                @endif                           

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group{{ $errors->has('plate_number') ? ' has-error' : '' }}">
                            <label for="model" class="col-md-4 control-label">Plate Number</label>

                            <div class="col-md-6">
                                @if(isset($fetchedTruck))
                                    <input id="plate_number" type="text" class="form-control" name="plate_number" value="{{$fetchedTruck->plate_number }}" required autofocus>
                                @else
                                    <input id="plate_number" type="text" class="form-control" name="plate_number" value="{{ old('plate_number') }}" required autofocus>
                                @endif                           

                                @if ($errors->has('plate_number'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('plate_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group{{ $errors->has('year') ? ' has-error' : '' }}">
                            <label for="year" class="col-md-4 control-label">Year</label>

                            <div class="col-md-6">
                                @if(isset($fetchedTruck))
                                    <input id="year" type="text" class="form-control" name="year" value="{{$fetchedTruck->year }}" required autofocus>
                                @else
                                    <input id="year" type="text" class="form-control" name="year" value="{{ old('year') }}" required autofocus>
                                @endif                           

                                @if ($errors->has('year'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('year') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('mileage') ? ' has-error' : '' }}">
                            <label for="mileage" class="col-md-4 control-label">Mileage</label>

                            <div class="col-md-6">
                                @if(isset($fetchedTruck))
                                    <input id="mileage" type="text" class="form-control" name="mileage" value="{{$fetchedTruck->mileage }}" required autofocus>
                                @else
                                    <input id="mileage" type="text" class="form-control" name="mileage" value="{{ old('mileage') }}" required autofocus>
                                @endif                           

                                @if ($errors->has('mileage'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mileage') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         
                        @if(!empty($truckSuccess))     
                            <div class="alert alert-success">
                                <p>{{$truckSuccess}}</p>  
                            </div>                        
                        @elseif(!empty($truckError))
                            <div class="alert alert-danger">
                                <p>{{$truckError}}</p>  
                            </div>  
                        @endif
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                @if(isset($fetchedTruck))  
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                    <a class="btn btn-danger" href="{{url('/truck/deleteTruck/')}}/{{$fetchedTruck->id}}">Delete</a>
                                @else
                                    <button type="submit" class="btn btn-primary">
                                        Create
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
