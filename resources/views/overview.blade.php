@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row row-extend">
        @if(Auth::guard('web')->check())
        <div class="col-md-1 col-md-offset overview-left-panel">
            <div class="panel panel-default">
                <div class="panel-heading">                 
                    <div class="btn-group" role="group" aria-label="...">               
                        <button id="all" type="button" onclick="filterLoads('All')" class="btn btn-default">All</button>
                        <button id="outbound" type="button" onclick="filterLoads('Outbound')" class="btn btn-default">Outbound</button>
                        <button id="inbound" type="button" onclick="filterLoads('Inbound')" class="btn btn-default">Inbound</button>
                    </div>
                </div>
                <div class="panel-body">
                <div>
                    <ul id="loadList"></ul>
                    </div>
                </div>
            </div>
        </div>
        @elseif(Auth::guard('driver')->check())
        <div class="col-md-1 col-md-offset directions-left-panel scrollNoneParent">
            <div id="directions" class="panel panel-default scrollNoneParent">
                <div id="directionsSection" class="scrollNoneChild displayLight"></div>
            </div>
        </div>
        @endif
        @if(Auth::guard('web')->check())
        <div class="col-md-10 col-md-offset map-container">
        @elseif(Auth::guard('driver')->check())
        <div class="col-md-10 col-md-offset map-container">
        @endif
            <div class="panel panel-default">
                <div class="panel-heading">Overview</div>
                <div id="loadedMap" class="panel-body"></div>
            </div>
        </div>
        @if(Auth::guard('web')->check())
            <div id="loadInformationPanel" class="col-md-1 col-md-offset overview-right-panel">
                <div class="panel panel-default">
                    <div class="panel-heading">                 
                        <div class="btn-group" role="group" aria-label="..." >
                            <a href="" id="load_number"></a>
                        </div>
                    </div>
                    <div class="panel-body">
                            <label>Truck: </label><p id="truckNumber"></p>
                            <label>Driver: </label><p id="driverName"></p>  
                            <br>
                            <label>Pickup Date: </label><p id="pickupDate"></p>
                            <label>Delivery Date: </label><p id="deliveryDate"></p>
                            <label>Delivery PO#: </label><p id="po"></p>
                            <br>
                            <label>Load Notes: </label><p id="loadNotes"></p>
                            <label>Delivery Notes: </label><p id="deliveryNotes"></p>
                            <label>Load Feedback: </label><p id="loadFeedback"></p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(Auth::guard('driver')->check())
            @if(isset($load))                    
                <div class="col-md-1 col-md-offset overview-right-panel">
                    <div class="panel panel-default">
                        <div class="panel-heading">                 
                            <div class="btn-group" role="group" aria-label="..." >
                                <h5>{{$load->load_number}}</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                                <label>Truck: </label><p>{{$load->truck_id}}</p>
                                <label>Driver: </label><p>{{$load->driver_id}}</p>  
                                <label>Pickup Date: </label><p>{{$load->pickup_date}}</p>
                                <label>Delivery Date: </label><p>{{$load->delivery_date}}</p>
                                <label>Delivery PO#: </label><p>{{$load->po_number}}</p>
                                <label>Load Notes: </label><textarea class="driverInput" id="loadNotes" rows="2">{{$load->load_notes}}</textarea>
                                <label>Delivery Notes: </label><textarea class="driverInput"  id="deliveryNotes" rows="2">{{$load->delivery_notes}}</textarea>
                                <label>Load Feedback: </label><textarea class="driverInput"  id="loadFeedback" rows="2">{{$load->load_feedback}}</textarea>
                                <button type="button" class="btn btn-default navbar-btn upload" onclick="updateLoad({{$load->id}})">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
@endsection
