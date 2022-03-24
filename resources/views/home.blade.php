@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2 col-md-offset">
            <div class="panel panel-default">
                <div class="panel-heading">Drivers</div>

                <div class="panel-body">
                <div>
                    <ul>
                        @if(isset($drivers))
                                @foreach($drivers as $driver)    
                                    <li>
                                        <a href="{{url('/home/modifyDriver/')}}/{{$driver->id}}">{{$driver->driver_name}}</a>
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
                <div class="panel-heading">Drivers</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Select a driver from the list on the left to update or delete them. Otherwise a driver can be created here.
                    @if(isset($fetchedDriver))
                        <form class="form-horizontal" method="POST" action="{{url('/home/updateDriver/')}}/{{$fetchedDriver->id}}">
                    @else  
                        <form class="form-horizontal" method="POST" action="{{ route('createDriver') }}">
                    @endif
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('driver_name') ? ' has-error' : '' }}">
                            <label for="driver_name" class="col-md-4 control-label">Driver Name</label>

                            <div class="col-md-6">
                                @if(isset($fetchedDriver))
                                    <input id="driver_name" type="text" class="form-control" name="driver_name" value="{{$fetchedDriver->driver_name}}" required autofocus>
                                @else
                                    <input id="driver_name" type="text" class="form-control" name="driver_name" value="{{ old('driver_name') }}" required autofocus>
                                @endif

                                @if ($errors->has('driver_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('driver_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-mail Address</label>

                            <div class="col-md-6">
                                @if(isset($fetchedDriver))
                                    <input id="email" type="text" class="form-control" name="email" value="{{$fetchedDriver->email }}" required autofocus readonly>
                                @else
                                    <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                @endif

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                                @if(isset($fetchedDriver))
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{$fetchedDriver->phone }}" required autofocus>
                                @else
                                    <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required autofocus>
                                @endif                           

                                @if ($errors->has('phone'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('phone') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                @if(isset($fetchedDriver))
                                    <input id="password" type="password" class="form-control" name="password">
                                @else
                                    <input id="password" type="password" class="form-control" name="password" required>
                                @endif  

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                @if(isset($fetchedDriver))
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                                @else
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                @endif                 
                            </div>
                        </div>     
                        @if(!empty($driverSuccess))     
                            <div class="alert alert-success">
                                <p>{{$driverSuccess}}</p>  
                            </div>                        
                        @elseif(!empty($driverError))
                            <div class="alert alert-danger">
                                <p>{{$driverError}}</p>  
                            </div>  
                        @endif
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                @if(isset($fetchedDriver))  
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                    <a class="btn btn-danger" href="{{url('/home/deleteDriver/')}}/{{$fetchedDriver->id}}">Delete</a>
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
