$(function() {
    console.log( "ready!" );

      function initialize() {

      	var myLatlng = new google.maps.LatLng(43.7321998,-79.6090827);

        var mapOptions = {
          center: myLatlng,
          zoom: 16
        };
        var map = new google.maps.Map(document.getElementById('map-canvas'),
            mapOptions);
      
      



var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'Hello World!'
  });


var marker = new google.maps.Marker({
      position: new google.maps.LatLng(43.7341013,-79.6032085),
      map: map,
      title: 'Hello World!'
  });

var marker = new google.maps.Marker({
      position: new google.maps.LatLng(43.7304836,-79.6015318),
      map: map,
      title: 'Hello World!'
  });


}

google.maps.event.addDomListener(window, 'load', initialize);

});