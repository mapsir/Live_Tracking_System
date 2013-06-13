<!doctype html>
<html>
<head>
<meta charset="UTF-8">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"> </script>
    <script src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
<title>Map</title>
</head>
<script>
		  var focus = new google.maps.LatLng(18.811652, 98.963458);
          var mapOptions = {
                  zoom: 15,
                  center: focus,
				  disableDefaultUI: true,
                  mapTypeId: google.maps.MapTypeId.ROADMAP
          };
          maps = new google.maps.Map(document.getElementById('map-canvas'),mapOptions);	   
		  
		 
          //Bus1
          //var bus1_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          //var bus1_image = "pic/bus/bus_thailand.png";
		  var bus1_image = "pic/bus/bus_korea.png";
          var bus1_marker = new google.maps.Marker({
                      //position: bus1_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus Thailand",
					  icon: bus1_image
          });
		  
		  //Bus2   
          //var bus2_image = "pic/bus/bus_india.png";
		  var bus2_image = "pic/bus/bus_vanuatu.png";
          var bus2_marker = new google.maps.Marker({
                      //position: bus1_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus2",
					  icon: bus2_image
          });
		  
		  //Bus3
          //var bus3_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus3_image = "pic/bus/bus_thailand.png";
          var bus3_marker = new google.maps.Marker({
                      //position: bus3_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus3",
					  icon: bus3_image
          });
		  
		  //Bus4
          //var bus4_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus4_image = "pic/bus/bus_thailand.png";
          var bus4_marker = new google.maps.Marker({
                      //position: bus4_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus4",
					  icon: bus4_image
          });
		  
		  //Bus5
          //var bus5_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus5_image = "pic/bus/bus_thailand.png";
          var bus5_marker = new google.maps.Marker({
                      //position: bus5_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus5",
					  icon: bus5_image
          });
		  
		  //Bus6
          //var bus6_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus6_image = "pic/bus/bus_thailand.png";
          var bus6_marker = new google.maps.Marker({
                      //position: bus6_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus6",
					  icon: bus6_image
          });
		  
		  //Bus7
          //var bus7_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus7_image = "pic/bus/bus_thailand.png";
          var bus7_marker = new google.maps.Marker({
                      //position: bus7_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus7",
					  icon: bus7_image
          });
		  
		  //Bus8
          //var bus8_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus8_image = "pic/bus/bus_thailand.png";
          var bus8_marker = new google.maps.Marker({
                      //position: bus8_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus8",
					  icon: bus8_image
          });
		  
		  
		  //Bus9
          //var bus9_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus9_image = "pic/bus/bus_thailand.png";
          var bus9_marker = new google.maps.Marker({
                      //position: bus8_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus9",
					  icon: bus9_image
          });
		  
		  
		  //Bus10
          //var bus10_latlng = new google.maps.LatLng(18.796976,98.975299);	   
          var bus10_image = "pic/bus/bus_thailand.png";
          var bus10_marker = new google.maps.Marker({
                      //position: bus8_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Bus10",
					  icon: bus10_image
          });
		  
		   //CMICE
		  var latlng_cmice = new google.maps.LatLng(18.827052, 98.960338);	   
          //var image = "pic/path1/CMICE/CMICE_place.png";
          var marker_cmice = new google.maps.Marker({
                  position: latlng_cmice,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "CMICE",
				  //icon: image
          });
		  
		  
		  		   // Shangri-La Hotel
		  var latlng_CHANGAREE = new google.maps.LatLng(18.778744,98.999927);	   
          //var image = "pic/path1/CMICE/CMICE_place.png";
          var marker_CHANGAREE  = new google.maps.Marker({
                  position: latlng_CHANGAREE,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "Shangri-La Hotel",
				  //icon: image
          });
		  
		  		   // Dhara Dhevi Hotel
		  var latlng_DARA = new google.maps.LatLng(18.779729,99.037371);	   
          //var image = "pic/path1/CMICE/CMICE_place.png";
          var marker_DARA  = new google.maps.Marker({
                  position: latlng_DARA,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "Dhara Dhevi Hotel",
				  //icon: image
          });		  
		  
		  
</script>

<style>
   #map-canvas {
           display: block;
           margin: 20px auto;
           height: 990px;
           width: 1200px;
           background-color: #ccc;
      }
</style>

<body>
    <input id="txt_show" name="txt_show" type="hidden">
	<div id="map-canvas"></div>
</body>
</html>
