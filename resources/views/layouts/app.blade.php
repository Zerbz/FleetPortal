<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css')}}" rel="stylesheet">
    <link href="{{ asset('js/datetimepicker-master/build/jquery.datetimepicker.min.css')}}" rel="stylesheet"> 

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="headercontainer">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    @if(Auth::guard('web')->check())
                        <a class="navbar-brand" href="{{ url('/overview') }}">
                            <img class="headerLogo" src="https://i.imgur.com/QSYd3qN.png">
                        </a>
                    @elseif(Auth::guard('driver')->check())
                        <a class="navbar-brand" href="{{ url('/driver') }}">
                            <img class="headerLogo" src="https://i.imgur.com/QSYd3qN.png"> 
                        </a>
                    @else
                        <a class="navbar-brand">
                            <img class="headerLogo" src="https://i.imgur.com/QSYd3qN.png"> 
                        </a>
                    @endif
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>
 
                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <!-- <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li> -->
                        @else
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->first_name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                    @if(Auth::guard('web')->check())
                                        <li>
                                            <a href="{{ route('user_profile') }}">
                                                User Profile
                                            </a>
                                            <a href="{{ route('truck') }}">
                                                Truck Setup
                                            </a>
                                            <a href="{{ route('home') }}">
                                                Driver Setup
                                            </a>
                                            <a href="{{ route('load') }}">
                                                Loads
                                            </a>
                                            <a href="{{ route('reassign') }}">
                                                Truck Reassign
                                            </a>
                                            <a href="{{ route('service') }}">
                                                Trucks In Service
                                            </a>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                        @elseif(Auth::guard('driver')->check())
                                        <li>
                                            <a href="{{url('/driver/logout')}}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{url('/driver/logout')}}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                        @endif
                                    </ul>                                    
                                </li>                         
                        @endif
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        @if(Auth::user())
                            @if(Auth::guard('web')->check())                      
                                <a class="btn btn-default navbar-btn upload" href="{{url('/upload/')}}">Upload</a>
                            @elseif(Auth::guard('driver')->check())
                                <button type="button" class="btn btn-default navbar-btn upload" onclick="getLocation({{Auth::user()->id}})">Check-in</button>
                                <a class="btn btn-default navbar-btn upload" href="{{url('/upload/')}}">Upload</a>
                            @endif
                        @endif
                    </ul>   
                </div>
            </div>
        </nav>

        @yield('content')

        <!-- <div>
            <footer>
                <ul class="footerContents">
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Created by Matt Szczerba</a></li>
                </ul>        
            </footer>
        </div> -->
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- https://github.com/xdan/datetimepicker 

        Using this as the date time picker -->
    <script src="{{ asset('js/datetimepicker-master/build/jquery.datetimepicker.full.min.js')}}"></script>
    @if(Auth::user()) 
    <script type="text/javascript" src="https://www.bing.com/api/maps/mapcontrol?key=AkaMh3hQ1dPnUAmodTspSEFH62lWB3wCq2Pozu4N8P8MwYBncZH_fGL5F2a8iAmk&amp;callback=loadMapScenario" async="false" defer=""></script>
    @endif
    <script type="text/javascript">
        $( document ).ready(function() {
            $( "#loadInformationPanel" ).hide();
            filterLoads("All");
            $("#all").removeClass("btn btn-default").addClass("btn btn-danger"); 
            previouslyClicked = $("#all"); //Assuming first tab is selected by default
            let checkInUser = null;     
            var route;
            var loadId;
        });
        
        /* Sourced from here https://stackoverflow.com/questions/14212721/jquery-change-css-class-on-clicking */
        $(".btn").click(function () {       
            previouslyClicked.removeClass("btn btn-danger").addClass("btn btn-default");                 
            $(this).addClass("btn btn-danger");
            previouslyClicked = $(this);           
        });

        $.datetimepicker.setLocale('en');
        $('#datetimepicker').datetimepicker();
        $('#datetimepicker2').datetimepicker();
    
        var loadedMap;
        var directionsManager;

        function loadMapScenario() {
            var map = new Microsoft.Maps.Map(document.getElementById('loadedMap'), {
                center: new Microsoft.Maps.Location(44.412004, -95.806791),
                // Try to find a better one of US and Canada
                //maxBounds: Microsoft.Maps.LocationRect.fromLocations(new Microsoft.Maps.Location(68.852876, -166.202327), new Microsoft.Maps.Location(24.552719, -81.804300)),
            });
            Microsoft.Maps.loadModule('Microsoft.Maps.Directions', function() {
                directionsManager = new Microsoft.Maps.Directions.DirectionsManager(loadedMap)
            });
            
            map.setView({
                zoom: 4
            });

            loadedMap = map;
            infobox = new Microsoft.Maps.Infobox(loadedMap.getCenter(), {
                visible: !1
            });
            infobox.setMap(loadedMap);
            
            $.ajax({
                method: 'GET',
                url:'getUserId', 
                dataType: "json",            
                success: function(response){
                    populateDriverMap(response);                   
                }   
            });               
        }  

        function displayLoad(id){
            loadedMap.layers.clear(); 
                $.ajax({
                method: 'GET',
                url:'overview/display', 
                dataType: "json",
                data: {
                    id: id,
                },              
                    success: function(response){
                        $( "#loadInformationPanel" ).show();
                        $('#load_number').attr("href", "http://localhost:8080/FleetPortal/public/loads/modifyLoad/"+response.id);    
                        $('#load_number').text(response.load_number);    
                        $('#truckNumber').text(response.truck_id); 
                        $('#driverName').text(response.driver_id); 
                        $('#loadNumber').text(response.load_number);       
                        $('#pickupDate').text(response.pickup_date);    
                        $('#deliveryDate').text(response.delivery_date); 
                        $('#po').text(response.po_number);    
                        $('#loadNotes').text(response.load_notes);       
                        $('#deliveryNotes').text(response.delivery_notes);    
                        $('#loadFeedback').text(response.load_feedback); 

                        populateMapById(id);

                        if(response.pickup_address != null && response.delivery_address != null){
                            displayRoute(id);
                        }
                    }   
                });         
        } 

        function displayRoute(id){
                loadId = id;

                $.ajax({
                method: 'GET',
                url:'overview/displayRoute', 
                dataType: "json",
                data: {
                    id: id,
                },              
                success: function(response){
                    Microsoft.Maps.loadModule('Microsoft.Maps.Directions', function () {        
                        if(response[0]._waypointOptions != null){
                            directionsManager = new Microsoft.Maps.Directions.DirectionsManager(loadedMap); 

                            response.forEach(function(element) {                      
                                var waypoint = new Microsoft.Maps.Directions.Waypoint({ location: new Microsoft.Maps.Location(element._waypointOptions.location.latitude, element._waypointOptions.location.longitude) });
                                directionsManager.addWaypoint(waypoint);  
                            });
                            
                            directionsManager.setRenderOptions({itineraryContainer: '#directionsSection'});

                            directionsManager.calculateDirections();

                            Microsoft.Maps.Events.addHandler(directionsManager, 'directionsUpdated', directionsUpdated);
                        }else{
                            
                            directionsManager = new Microsoft.Maps.Directions.DirectionsManager(loadedMap); 
                            // pickup will represent the pickup address on the map.
                            var pickup = new Microsoft.Maps.Directions.Waypoint({ location: new Microsoft.Maps.Location(response[0].pickupLat, response[0].pickupLong) });
                            directionsManager.addWaypoint(pickup);
                            
                            // delivery will represent the delivery address.
                            var delivery = new Microsoft.Maps.Directions.Waypoint({ location: new Microsoft.Maps.Location(response[0].deliveryLat, response[0].deliveryLong) });
                            directionsManager.addWaypoint(delivery);  
                            
                            Microsoft.Maps.Events.addHandler(directionsManager, 'directionsUpdated', directionsUpdated);
                            
                            directionsManager.setRenderOptions({itineraryContainer: '#directionsSection'});

                            directionsManager.calculateDirections();
                        }  
                    });
                }   
            });         
        } 
        
        function directionsUpdated(e) {        
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                method: 'POST',
                url:'overview/storeRoute', 
                dataType: "json",
                data: {
                    route: JSON.stringify(directionsManager.getAllWaypoints()),
                    id: loadId
                },              
            });
        }

        function filterLoads(filter){
            $.ajax({
                method: 'GET',
                url:'overview/filter', 
                dataType: "json",
                data: {
                    filter: filter,
                },              
                success: function(response){
                    $("#loadList").empty();
                    var list = $("#loadList");
                    response.forEach(function(key) {
                        list.append('<li><a onclick="displayLoad('+key.id+')">'+key.load_number+'</a></li>');
                    });

                    populateMapByType(filter);                   
                }   
            });
        } 
        
        function getLocation(id) {
            checkInUser = id;
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError);
            } else {
                // If the browser is not supported inform the user.
                userLocation.innerHTML = "Geolocation is not supported by this browser.";
            }
        }

        function populateMapByType(type){
            $.ajax({
                    method: 'GET',
                    url:'overview/populate', 
                    dataType: "json",
                    data: {
                        type: type,
                    },              
                    success: function(response){
                        for (var i = loadedMap.entities.getLength() - 1; i >= 0; i--) {
                            var pushpin = loadedMap.entities.get(i);
                            loadedMap.entities.removeAt(i);
                        }

                        for (i = 0; i < response.length; i++) {                   
                            // Create a new location instance from the users positioning
                            var location = new Microsoft.Maps.Location(response[i].latitude, response[i].longitude);

                            // Create a new pin with the user's location
                            var pin = new Microsoft.Maps.Pushpin(location, {
                                color: 'blue'
                            });

                            // Only display to the user that the pin is their current location
                            pin.metadata = {
                                title: response[i].driver_name,
                                description: "<strong>" + response[i].truck_number + "</strong> " + '<br>' + "<strong>" + response[i].created_at + "</strong>"
                            }

                            // Adds the pushPinClicked handler that will set the pins infobox values 
                            Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);

                            // Adds the pushpin to the map.
                            loadedMap.entities.push(pin);
                    }
                }   
            });             
        }

        function populateMapById(id){
            $.ajax({
                    method: 'GET',
                    url:'overview/populateById', 
                    dataType: "json",
                    data: {
                        id: id,
                    },              
                    success: function(response){                       
                        for (var i = loadedMap.entities.getLength() - 1; i >= 0; i--) {
                            var pushpin = loadedMap.entities.get(i);
                            loadedMap.entities.removeAt(i);
                        }

                        for (i = 0; i < response.length; i++) {                   
                            // Create a new location instance from the users positioning
                            var location = new Microsoft.Maps.Location(response[i].latitude, response[i].longitude);

                            // Create a new pin with the user's location
                            var pin = new Microsoft.Maps.Pushpin(location, {
                                color: 'blue'
                            });

                            // Only display to the user that the pin is their current location
                            pin.metadata = {
                                title: response[i].driver_name,
                                description: "<strong>" + response[i].truck_number + "</strong> " + '<br>' + "<strong>" + response[i].created_at + "</strong>"
                            }

                            // Adds the pushPinClicked handler that will set the pins infobox values 
                            Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);

                            // Adds the pushpin to the map.
                            loadedMap.entities.push(pin);
                    }
                }   
            });             
        }

        function displayRouteByLoadId(id) {
                $.ajax({
                    method: 'GET',
                    url:'driver/populateByLoadId', 
                    dataType: "json",
                    data: {
                        id: id,
                },              
                success: function(response){     
                    displayRoute(response);
                }
            });
        } 
        
        function populateDriverMap(id) {
                $.ajax({
                    method: 'GET',
                    url:'driver/populate', 
                    dataType: "json",
                    data: {
                        id: id,
                    },              
                    success: function(response){                             
                        response.length > 0 ? displayRoute(response[0].load_id) : displayRouteByLoadId(id);

                        for (i = 0; i < response.length; i++) {                   
                            // Create a new location instance from the users positioning
                            var location = new Microsoft.Maps.Location(response[i].latitude, response[i].longitude);

                            // Create a new pin with the user's location
                            var pin = new Microsoft.Maps.Pushpin(location, {
                                color: 'blue'
                            });

                            // Only display to the user that the pin is their current location
                            pin.metadata = {
                                title: response[i].driver_name,
                                description: "<strong>" + response[i].truck_number + "</strong> " + '<br>' + "<strong>" + response[i].created_at + "</strong>"
                            }

                            // Adds the pushPinClicked handler that will set the pins infobox values 
                            Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);

                            // Adds the pushpin to the map.
                            loadedMap.entities.push(pin);
                    }
                }   
            });             
        }

        // This is the showPosition function, it will retrieve the users location and place a pin on the map
        function showPosition(position) {
            $.ajax({
                method: 'GET',
                url:'driver/checkin', 
                dataType: "json",
                data: {
                    id: checkInUser,
                    position:position
                },              
                success: function(response){
                    // Create a new location instance from the users positioning
                    var location = new Microsoft.Maps.Location(response.latitude, response.longitude);

                    // Create a new pin with the user's location
                    var pin = new Microsoft.Maps.Pushpin(location, {
                        color: 'green'
                    });

                    // Only display to the user that the pin is their current location
                    pin.metadata = {
                        title: response.driver_name,
                        description: "<strong>" + response.truck_number + "</strong> " + '<br>' + "<strong>" + response.created_at + "</strong>"
                    }

                    // Adds the pushPinClicked handler that will set the pins infobox values 
                    Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);

                    // Adds the pushpin to the map.
                    loadedMap.entities.push(pin);
                }   
            });       
        }
        
        function updateLoad(id){
            $.ajax({
                method: 'GET',
                url:'driver/updateNotes', 
                dataType: "json",
                data: {
                    id: id,
                    load_notes: $("#loadNotes").val(),
                    delivery_notes: $("#deliveryNotes").val(),
                    load_feedback: $("#loadFeedback").val()
                },                
            });       
        }

        function pushpinClicked(e) {
            // If there is metadata present
            if (e.target.metadata) {   
                infobox.setOptions({
                    location: e.target.getLocation(),
                    title: e.target.metadata.title,
                    description: e.target.metadata.description,
                    visible: true
                });
            }
        }

        // The showError function will set geoLoaded to false, so that certain features are disabled.
        function showError() {
            console.log('You must accept location services..')
        }

     </script>
</body>
</html>


