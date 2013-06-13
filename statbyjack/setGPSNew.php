<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Update busVIP</title>
</head>

<body>
<?
		$user = $_REQUEST['user'];
		$dt_start = $_REQUEST['dt_start'];
		$dt_stop = $_REQUEST['dt_stop'];
			
			
			
		//echo $dt_start;
		//$DBfile_bus = "../chiangmai_project/liteDB/DB.sqlite";
		$DBfile_bus = "../liteDB/APWS2013.sqlite";
		$db_bus=new PDO('sqlite:'.$DBfile_bus);
		if (!$db_bus) print_r($db_bus->errorInfo());
		//else echo "connected.<br>";
		
		//$query_bus = "SELECT * FROM bus WHERE route = 3";

		//EDIT BY JACK
		$query_bus = "SELECT mobileTime, datetime, latitude, longitude, strftime('%s',datetime)-strftime('%s',mobileTime) FROM archived WHERE mobileTime >= '$dt_start'  AND mobileTime <= '$dt_stop' AND userID = $user ORDER BY mobileTime ASC;";

		
		$result_bus = $db_bus->query($query_bus)->fetchAll();
	
	//EDIT By JACK
		
		$count_bus = -1;
		
		foreach ($result_bus as $entry_bus)
		{
	       $count_bus++;
		}

		//echo "<br>Count: ".$count_bus."<br>";
		
		$bus_id[$count_bus];
	  	$bus_lat[$count_bus]; 
		$bus_long[$count_bus]; 
		$bus_level[$count_bus]; 
	
	
	    $count_bus = 0;
	   
		foreach ($result_bus as $entry_bus)
		{
					
            $bus_lat[$count_bus] = $entry_bus["latitude"];
			$bus_long[$count_bus] = $entry_bus["longitude"];
			$bus_level[$count_bus] = $entry_bus[4];	
	        
		//echo $bus_lat[$count_bus]." ".$bus_long[$count_bus]."<br/>";
		
			$count_bus++;
			break;
		}
		 

		 
		$db_bus= null;
	/*

	echo"
							for(var i = 0; i < bus_lat.length; ++i) {
									var latlng_gps = new google.maps.LatLng(bus_lat[i],bus_long[i]); 
									
									var marker_gps = new google.maps.Marker({
										  position: latlng_gps,
										  map: maps,
										  animation: google.maps.Animation.DROP,
										  title: '11',
										  //icon: image
									});
							}";
		//      </script>";
		
*/

	echo "<script>";
							for($i = 0; $i < count($bus_lat); $i++) {
							
							
							echo"
											var latlng_gps = new google.maps.LatLng(".$bus_lat[$i].",".$bus_long[$i]."); 
											//var image_gps = new google.maps.MarkerImage('pic/circle_green.png');
							";
						if($bus_level[$i] <= 1){
										echo"
														var image_gps = new google.maps.MarkerImage(
															'pic/circle_green.png',
															null, /* size is determined at runtime */
															null, /* origin is 0,0 */
															null, /* anchor is bottom center of the scaled image */
															new google.maps.Size(20, 20)
														);  
										
										";
							
							}
							else if($bus_level[$i] <= 10)
							{
							
												echo"
														var image_gps = new google.maps.MarkerImage(
															'pic/circle_yellow.png',
															null, /* size is determined at runtime */
															null, /* origin is 0,0 */
															null, /* anchor is bottom center of the scaled image */
															new google.maps.Size(20, 20)
														);  
										
										";
							}
							else
							{
																			echo"
														var image_gps = new google.maps.MarkerImage(
															'pic/circle_red.png',
															null, /* size is determined at runtime */
															null, /* origin is 0,0 */
															null, /* anchor is bottom center of the scaled image */
															new google.maps.Size(20, 20)
														);  
										
										";
							
							
							}
							
							echo"
											var marker_gps = new google.maps.Marker({
												  position: latlng_gps,
												  map: maps,
												  animation: google.maps.Animation.DROP,
												  title: '".$bus_level[$i]." sec',
												  icon: image_gps
												
											});
										";
						}
		echo "     </script>";


		
?>
</body>
</html>