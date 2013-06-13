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
	

	
	
	    $count_bus = 0;
		$avg = 0.0;
		$sum = 0;
		
		
		$count_sum = 0;
		$count_green = 0;
		$count_yellow = 0;
		$count_red = 0;
	 
		foreach ($result_bus as $entry_bus)
		{
					
            $bus_lat = $entry_bus["latitude"];
			$bus_long = $entry_bus["longitude"];
			$bus_level = $entry_bus[4];	
			
			
	        if($bus_level <= 1)
			{
				$count_green++;
			}
			else if($bus_level <= 10)
			{
				$count_yellow++;
			}
			else
			{
				$sum  = $sum + $bus_level;
				$count_bus++;
				$count_red++;
			}
	

			$count_sum++;
			
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

	echo "Average : ".($sum/$count_bus)." sec.<br>";
	echo "Delay 10 sec. up: ".(($count_red/$count_sum)*100)." %.<br><br>";
	echo "Delay 2 sec. - 10 sec. : ".(($count_yellow/$count_sum)*100)." %.<br><br>";
	echo "No Delay : ".(($count_green/$count_sum)*100)." %.<br><br>";
	
		
?>
</body>
</html>