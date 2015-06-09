<?php
  session_start();

  if (isset($_POST['submit'])) {
    # code...
    
    
    $_SESSION ['username'] = $_POST['uname'];
    $_SESSION ['side'] = $_POST['side'];
  }
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
<title>Google Maps JavaScript API v3 Example: Directions Complex</title>


<style>
html{height:100%;}
body{height:100%;margin:0px;font-family: Helvetica,Arial;}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type ="text/javascript" src="scripts/v3_epoly.js"></script>
<script type="text/javascript">
  



  var map;
  var directionDisplay;
  var directionsService;
  var stepDisplay;
  var markerArray = [];
  var position;
  var marker = null;
  var polyline = null;
  var poly2 = null;
  var speed = 0.000005, wait = 1;
  var infowindow = null;
  
    var myPano;   
    var panoClient;
    var nextPanoId;
  var timerHandle = null;

function createMarker(latlng, label, html) {
// alert("createMarker("+latlng+","+label+","+html+","+color+")");
    var contentString = '<b>'+label+'</b><br>'+html;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        icon: pinSymbol("#D2AB33"),
        title: label,
        zIndex: Math.round(latlng.lat()*-100000)<<5
        });
        marker.myname = label;
        // gmarkers.push(marker);

    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString); 
        infowindow.open(map,marker);
        });
    return marker;
}

function pinSymbol(color) {
    return {
        path: 'M 0,0 C -2,-20 -10,-22 -10,-30 A 10,10 0 1,1 10,-30 C 10,-22 2,-20 0,0 z M -2,-30 a 2,2 0 1,1 4,0 2,2 0 1,1 -4,0',
        fillColor: color,
        fillOpacity: 1,
        strokeColor: '#000',
        strokeWeight: 2,
        scale: 1,
   };
}


function initialize() {
  infowindow = new google.maps.InfoWindow(
    { 
      size: new google.maps.Size(150,50)
    });
    // Instantiate a directions service.
    directionsService = new google.maps.DirectionsService();
    
    // Create a map and center it on Manhattan.
    var myOptions = {
      zoom: 15,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    address = 'M9V4P6'
    geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': address}, function(results, status) {
       map.setCenter(results[0].geometry.location);
	});
    
    // Create a renderer for directions and bind it to the map.
    var rendererOptions = {
      map: map
    }
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);
    
    // Instantiate an info window to hold step text.
    stepDisplay = new google.maps.InfoWindow();

    polyline = new google.maps.Polyline({
	path: [],
	strokeColor: '#FF0000',
	strokeWeight: 3
    });
    poly2 = new google.maps.Polyline({
	path: [],
	strokeColor: '#FF0000',
	strokeWeight: 3
    });

var loc = {
  Point1  :{lat:43.7304836,lng:-79.6015318},
  Point2  :{lat:43.7342388,lng:-79.6031137},
  Point3  :{lat:43.7321998,lng:-79.6090827},
  Point4  :{lat:43.7307109,lng:-79.6069193},
  Point5  :{lat:43.726226,lng:-79.622325},
  Point6  :{lat:43.739808,lng:-79.580397},
  Point7  :{lat:43.742134,lng:-79.594387},
  Point8  :{lat:43.741359,lng:-79.613613},
  Point9  :{lat:43.731374,lng:-79.589795},
  Point10  :{lat:43.721729,lng:-79.613012},
  Point11  :{lat:43.726536,lng:-79.593872}
};

$.each(loc, function( index, value ) {
  createLoc( index,value );
});

function createLoc(place,latlong){
  
    var pos = new google.maps.LatLng(latlong.lat,latlong.lng);

      //alert(place+ " "+ position);

         var marker = new google.maps.Marker({
              position: pos,
              map: map,
              icon: pinSymbol("#65C7F0"),
              title: place
          }); 


         google.maps.event.addListener(marker, 'click', function() {
        //alert(marker.title +" " + marker.position);
        var str=(marker.position).toString();
        //alert(typeof(str));
        str=str.substring(1,(str.length-1));
        $('#end').val(str);
      });

  } // ----- end of createloc ------ //

} // ----- end of intialize ------ //

  
  
	var steps = []

	function calcRoute(){

        if (timerHandle) { clearTimeout(timerHandle); }
        if (marker) { marker.setMap(null);}
        polyline.setMap(null);
        poly2.setMap(null);
        directionsDisplay.setMap(null);
            polyline = new google.maps.Polyline({
        	path: [],
        	strokeColor: '#FF0000',
        	strokeWeight: 10
            });
            poly2 = new google.maps.Polyline({
        	path: [],
        	strokeColor: '#FF0000',
        	strokeWeight: 10
            });
            // Create a renderer for directions and bind it to the map.
            var rendererOptions = {
              map: map
    }

  directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

	    var start = document.getElementById("start").value; 
	    var end = document.getElementById("end").value;
		  var travelMode = google.maps.DirectionsTravelMode.WALKING

	    var request = {
	        origin: start,
	        destination: end,
	        travelMode: travelMode
	    };

		// Route the directions and pass the response to a
		// function to create markers for each step.
  directionsService.route(request, function(response, status) {
    if (status == google.maps.DirectionsStatus.OK){
	directionsDisplay.setDirections(response);

        var bounds = new google.maps.LatLngBounds();
        var route = response.routes[0];
        startLocation = new Object();
        endLocation = new Object();

        // For each route, display summary information.
	var path = response.routes[0].overview_path;
	var legs = response.routes[0].legs;
        for (i=0;i<legs.length;i++) {
          if (i == 0) { 
            startLocation.latlng = legs[i].start_location;
            startLocation.address = legs[i].start_address;
            // marker = google.maps.Marker({map:map,position: startLocation.latlng});
            marker = createMarker(legs[i].start_location,"start",legs[i].start_address,"blue");
          }
          endLocation.latlng = legs[i].end_location;
          endLocation.address = legs[i].end_address;
          var steps = legs[i].steps;
          for (j=0;j<steps.length;j++) {
            var nextSegment = steps[j].path;
            for (k=0;k<nextSegment.length;k++) {
              polyline.getPath().push(nextSegment[k]);
              bounds.extend(nextSegment[k]);

            }
          }
        }

        polyline.setMap(map);
        map.fitBounds(bounds);
//        createMarker(endLocation.latlng,"end",endLocation.address,"red");
	map.setZoom(15);
	startAnimation();
    }                                                    
 });

$('#start').val($('#end').val());

}  // ----- end of calcRoute ------ //
  

  
      var step = 50; // 5; // metres
      var tick = 100; // milliseconds
      var eol;
      var k=0;
      var stepnum=0;
      var speed = "";
      var lastVertex = 1;


//=============== animation functions ======================
      function updatePoly(d) {
        // Spawn a new polyline every 20 vertices, because updating a 100-vertex poly is too slow
        if (poly2.getPath().getLength() > 20) {
          poly2=new google.maps.Polyline([polyline.getPath().getAt(lastVertex-1)]);
          // map.addOverlay(poly2)
        }

        if (polyline.GetIndexAtDistance(d) < lastVertex+2) {
           if (poly2.getPath().getLength()>1) {
             poly2.getPath().removeAt(poly2.getPath().getLength()-1)
           }
           poly2.getPath().insertAt(poly2.getPath().getLength(),polyline.GetPointAtDistance(d));
        } else {
          poly2.getPath().insertAt(poly2.getPath().getLength(),endLocation.latlng);
        }
      }


      function animate(d) {
// alert("animate("+d+")");
        if (d>eol) {
          map.panTo(endLocation.latlng);
          marker.setPosition(endLocation.latlng);
          return;
        }
        var p = polyline.GetPointAtDistance(d);
        map.panTo(p);
        marker.setPosition(p);
        updatePoly(d);
        timerHandle = setTimeout("animate("+(d+step)+")", tick);
      }


function startAnimation() {
        eol=polyline.Distance();
        map.setCenter(polyline.getPath().getAt(0));
        // map.addOverlay(new google.maps.Marker(polyline.getAt(0),G_START_ICON));
        // map.addOverlay(new GMarker(polyline.getVertex(polyline.getVertexCount()-1),G_END_ICON));
        // marker = new google.maps.Marker({location:polyline.getPath().getAt(0)} /* ,{icon:car} */);
        // map.addOverlay(marker);
        poly2 = new google.maps.Polyline({path: [polyline.getPath().getAt(0)], strokeColor:"#0000FF", strokeWeight:10});
        // map.addOverlay(poly2);
        setTimeout("animate(50)",2000);  // Allow time for the initial map display
}


//=============== ~animation funcitons =====================

$(function(){

$("#submit").click(function(){
calcRoute();
setTimeout("initialize()",8000);
$('#end').val(" ");
});


});

</script>

<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body onload="initialize()">

<div id="tools">
  <h2> <?=$_SESSION['username']?> </h2>
  <h4> <?=$_SESSION['side']?> </h4>
	<h2>Current Location:</h2>
	<input type="text" name="start" id="start" value="43.7307109,-79.6069193" disabled/><br><br>
	<h2>Destination:</h2>
	<input type="text" name="end" id="end" value="" disabled/>
	<input type="submit" id="submit" value="Travel" />
</div>

<div id="map_canvas" style="float:right; width:80%; height:100%;"></div>

</body>
</html>