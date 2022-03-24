@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-2 col-md-offset left-panel">
            <div class="panel panel-default">
                <div class="panel-heading">Loads</div>

                <div class="panel-body">
                <div>
                    <ul>
                        @if(isset($loads))
                                @foreach($loads as $load)    
                                    <li>
                                        <a href="{{url('/loads/modifyLoad/')}}/{{$load->id}}">{{$load->load_number}}</a>
                                    </li>
                                    @endforeach
                        @endif
                    </ul>
                    </div>
                </div>
            </div>
            @if(isset($confirmations) && !$confirmations->isEmpty())
            <div class="panel panel-default">
                <div class="panel-heading">Confirmations</div>

                <div class="panel-body">
                    <div>
                        <ul>
                            @foreach($confirmations as $confirmation)    
                                <li>
                                    <a href="{{url('/loads/fetchConfirmation/')}}/{{$confirmation->id}}">{{$confirmation->document_name}}</a>
                                </li>
                            @endforeach      
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        </div>
        <div class="col-md-10 panel-large">
            <div class="panel panel-default">
                <div class="panel-heading">Load Info</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    Select a load from the list on the left to update or delete them. Otherwise a load can be created here.
                    @if(isset($fetchedLoad))
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{url('/loads/updateLoad/')}}/{{$fetchedLoad->id}}">
                    @else  
                        <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="{{ route('createLoad') }}">
                    @endif
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group{{ $errors->has('load_number') ? ' has-error' : '' }} col-md-6">
                                <label for="load_number" class="col-md-4 control-label">Load Number: </label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="load_number" type="text" class="form-control" name="load_number" value="{{$fetchedLoad->load_number}}" required autofocus>
                                    @else
                                        <input id="load_number" type="text" class="form-control" name="load_number" value="{{ old('load_number') }}" required autofocus>
                                    @endif

                                    @if ($errors->has('load_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('load_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }} col-md-6">
                                <label for="price" class="col-md-4 control-label">Price:</label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="price" type="text" class="form-control" name="price" value="{{$fetchedLoad->price }}" autofocus>
                                    @else
                                        <input id="price" type="text" class="form-control" name="price" value="{{ old('price') }}" autofocus>
                                    @endif

                                    @if ($errors->has('price'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('price') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group{{ $errors->has('po_number') ? ' has-error' : '' }} col-md-6">
                                <label for="po_number" class="col-md-4 control-label">PO#: </label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="po_number" type="text" class="form-control" name="po_number" value="{{$fetchedLoad->po_number}}" autofocus>
                                    @else
                                        <input id="po_number" type="text" class="form-control" name="po_number" value="{{ old('po_number') }}" autofocus>
                                    @endif

                                    @if ($errors->has('po_number'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('po_number') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('contact') ? ' has-error' : '' }} col-md-6">
                                <label for="contact" class="col-md-4 control-label">Contact Name:</label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="contact" type="text" class="form-control" name="contact" value="{{$fetchedLoad->contact }}" autofocus>
                                    @else
                                        <input id="contact" type="text" class="form-control" name="contact" value="{{ old('contact') }}" autofocus>
                                    @endif

                                    @if ($errors->has('contact'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('contact') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group{{ $errors->has('company') ? ' has-error' : '' }} col-md-6">
                                <label for="company" class="col-md-4 control-label">Company: </label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="company" type="text" class="form-control" name="company" value="{{$fetchedLoad->company}}" autofocus>
                                    @else
                                        <input id="company" type="text" class="form-control" name="company" value="{{ old('company') }}" autofocus>
                                    @endif

                                    @if ($errors->has('company'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('company_phone') ? ' has-error' : '' }} col-md-6">
                                <label for="company_phone" class="col-md-4 control-label">Company Phone:</label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="company_phone" type="text" class="form-control" name="company_phone" value="{{$fetchedLoad->company_phone }}" autofocus>
                                    @else
                                        <input id="company_phone" type="text" class="form-control" name="company_phone" value="{{ old('company_phone') }}" autofocus>
                                    @endif

                                    @if ($errors->has('company_phone'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('company_phone') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group{{ $errors->has('pickup_address') ? ' has-error' : '' }} col-md-6">
                                <label for="pickup_address" class="col-md-4 control-label">Pickup Address: </label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="pickup_address" type="text" class="form-control" name="pickup_address" value="{{$fetchedLoad->pickup_address}}" autofocus>
                                    @else
                                        <input id="pickup_address" type="text" class="form-control" name="pickup_address" value="{{ old('pickup_address') }}" autofocus>
                                    @endif

                                    @if ($errors->has('pickup_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('pickup_address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('delivery_address') ? ' has-error' : '' }} col-md-6">
                                <label for="delivery_address" class="col-md-4 control-label">Delivery Address:</label>

                                <div class="col-md-6">
                                    @if(isset($fetchedLoad))
                                        <input id="delivery_address" type="text" class="form-control" name="delivery_address" value="{{$fetchedLoad->delivery_address }}" autofocus>
                                    @else
                                        <input id="delivery_address" type="text" class="form-control" name="delivery_address" value="{{ old('delivery_address') }}" autofocus>
                                    @endif

                                    @if ($errors->has('delivery_address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('delivery_address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="driver" class="col-md-4 control-label">Driver:</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="sel1" name="driver">
                                    @if(isset($fetchedDriver))
                                            <option>
                                                {{$fetchedDriver->driver_name}}
                                            </option>
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
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="truck" class="col-md-4 control-label">Truck:</label>
                                <div class="col-md-6">
                                    <select class="form-control" id="sel1" name="truck">
                                        @if(isset($fetchedTruck))
                                                <option>
                                                    {{$fetchedTruck->truck_number}}
                                                </option>
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
                        </div>
                        <div class="row">
                            <div class="form-group{{ $errors->has('pickup_date') ? ' has-error' : '' }} col-md-6">
                                <label for="pickup_date" class="col-md-4 control-label">Pickup Time:</label>
                                    <div class='col-md-6'>
                                        <div class='input-group date' id='pickuppicker'>
                                            @if(isset($fetchedLoad))
                                                <input id="datetimepicker" type="text" class="form-control" name="pickup_date" value="{{$fetchedLoad->pickup_date }}" autofocus>
                                            @else
                                                <input id="datetimepicker" type="text"  class="form-control" name="pickup_date"/>
                                            @endif                  
                                            <span class="input-group-addon">
                                                 <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                            </div>
                            <div class="form-group{{ $errors->has('delivery_date') ? ' has-error' : '' }} col-md-6">
                                <label for="delivery_date" class="col-md-4 control-label">Delivery Time:</label>
                                    <div class='col-md-6'>
                                        <div class='input-group date' id='deliverypicker'name="delivery_date"> 
                                            @if(isset($fetchedLoad))
                                                <input id="datetimepicker2" type="text" class="form-control" name="delivery_date" value="{{$fetchedLoad->delivery_date }}" autofocus>
                                            @else
                                                <input id="datetimepicker2" type="text" class="form-control" name="delivery_date"/>
                                            @endif   
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                    <label for="type" class="col-md-4 control-label">Type:</label>
                                    <div class="col-md-6">
                                        <select class="form-control" id="sel3" name="type">
                                            @if(isset($fetchedLoad))
                                                    <option>
                                                        {{$fetchedLoad->type}}
                                                    </option>
                                                @if($fetchedLoad->type === "Outbound")
                                                    <option>
                                                        Inbound
                                                    </option>
                                                @elseif($fetchedLoad->type === "Inbound")
                                                    <option>
                                                        Outbound
                                                    </option>
                                                @else
                                                    <option>
                                                        Outbound
                                                    </option>
                                                    <option>
                                                        Inbound
                                                    </option>
                                                @endif
                                            @else
                                                <option></option>     
                                                <option>Outbound</option>     
                                                <option>Inbound</option>                                      
                                            @endif      
                                        </select>
                                    </div>
                            </div>                           
                        </div>
                        <div class="row">
                            <div class="form-group{{ $errors->has('load_notes') ? ' has-error' : '' }} col-md-4">
                                <div class="col-xs-4 col-md-12">
                                    <label for="load_notes" class="control-label">Load Notes: </label>
                                    @if(isset($fetchedLoad))
                                        <textarea class="form-control" rows="5" id="load_notes" name="load_notes">{{$fetchedLoad->load_notes}}</textarea>
                                    @else
                                        <textarea class="form-control" rows="5" id="load_notes" name="load_notes"></textarea>
                                    @endif  
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('delivery_notes') ? ' has-error' : '' }} col-md-4">                               
                                <div class="col-xs-4 col-md-12">
                                    <label for="delivery_notes" class="control-label">Delivery Notes: </label>
                                    @if(isset($fetchedLoad))
                                        <textarea class="form-control" rows="5" id="delivery_notes" name="delivery_notes">{{$fetchedLoad->delivery_notes}}</textarea>
                                    @else
                                        <textarea class="form-control" rows="5" id="delivery_notes" name="delivery_notes"></textarea>
                                    @endif 
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('load_feedback') ? ' has-error' : '' }} col-md-4">                               
                                <div class="col-xs-4 col-md-12">
                                    <label for="load_feedback" class="control-label">Load Feedback: </label>
                                    @if(isset($fetchedLoad))
                                        <textarea class="form-control" rows="5" id="load_feedback" name="load_feedback">{{$fetchedLoad->load_feedback}}</textarea>
                                    @else
                                        <textarea class="form-control" rows="5" id="load_feedback" name="load_feedback"></textarea>
                                    @endif 
                                </div>
                            </div>
                        </div>
                        @if(!empty($loadSuccess))     
                            <div class="alert alert-success">
                                <p>{{$loadSuccess}}</p>  
                            </div>                        
                        @elseif(!empty($loadError))
                            <div class="alert alert-danger">
                                <p>{{$loadError}}</p>  
                            </div>  
                        @endif
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-6">
                                @if(isset($fetchedLoad))  
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                    <a class="btn btn-danger" href="{{url('/loads/deleteLoad/')}}/{{$fetchedLoad->id}}">Delete</a>
                                @else
                                    <button type="submit" class="btn btn-primary">
                                        Create
                                    </button>
                                @endif
                                <label class="btn btn-default">
                                    Confirmation Upload<input type="file" name="confirmationDocument">
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
