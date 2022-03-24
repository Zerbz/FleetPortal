@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Profile</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Modify your profile information here!
                    <form class="form-horizontal" method="POST" action="{{ route('update_profile') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label for="first_name" class="col-md-4 control-label">First Name</label>
    
                            <div class="col-md-6">
                            @if(isset($user))
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ $user->first_name }}" required autofocus>
                            @else
                                <input id="first_name" type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required autofocus>
                            @endif
                                @if ($errors->has('first_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label for="last_name" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                            @if(isset($user))
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $user->last_name }}" required autofocus>
                            @else
                                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" required autofocus>
                            @endif
                    
                                @if ($errors->has('last_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-mail Address</label>

                            <div class="col-md-6">
                            @if(isset($user))
                                <input id="email" type="text" class="form-control" name="email" value="{{ $user->email }}" required autofocus>
                            @else
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                            @endif
                                
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> -->

                        <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                            <label for="phone" class="col-md-4 control-label">Phone</label>

                            <div class="col-md-6">
                            @if(isset($user))
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ $user->phone }}" required autofocus>
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

                        @if(!empty($profileStatus))     
                            <div class="alert alert-success">
                                <p>{{$profileStatus}}</p>  
                            </div>              
                        @endif
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                     Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
