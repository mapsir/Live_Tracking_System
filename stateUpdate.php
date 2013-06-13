<?php	
	/* 
		Arrival Time Part 
		@Version
			2013-05-17 09:34 Reorder station, Fix 1 Min Bug
			2013-05-15 09:13 Fix Tarin-Grandview
			2013-04-30 15:35 Fork from NAC 2013 Prototype, Change Traveltime structure, Fix minor bugs
			2013-03-29 10:39 Add calculateAverage feature and fix update arrival time bug 
			2013-03-27 13:30 edit transition when stationDiff = 0 and add compare when all positives in knn condition
			2013-03-25 14:27 add time_arrival_tbl update feature
			2013-03-25 13:54 fix bugs by P'Mu and Ton
			2013-03-22 10:34 Update to P'Mu's database notation
			2013-03-22 10:34 fix timediff calculation, add kNN, add indexStation Method
			2013-03-21 23:36 implement
		@Author: Wachara Fungwacharakorn (Ton)
	*/
	ini_set('display_errors', '1');
	header ('Content-type: text; charset=utf-8');
	/*
	//TESTCASE 
	gpstest();
	
	function gpstest()
	{
		$DBfile = ".\liteDB\APWS2013.sqlite";
		$db=new PDO('sqlite:'.$DBfile);
		$query = "SELECT rowid,* FROM archived WHERE julianday(mobileTime) > julianday('2013-05-02 15:33:50') AND julianday(mobileTime) < julianday('2013-05-02 16:04:00') order by mobileTime";
		$data = $db->query($query)->fetchAll();		
		$places = getPlaces();
		$radius = 0.1;
		
		foreach ($data as $row)
		{
			$userID = $row["userID"];
			$route = getRoute($userID);
			if(!isset($prevStatusStation[$userID]))  $prevStatusStation[$userID] = -1;
			$currentStatusStation = mapStationIndex($row["latitude"],$row["longitude"],$places[$route],$radius);
			echo "userid=".$userID."\tstation=".$currentStatusStation."\ttimestamp=".$row["mobileTime"]."\n";
			execTraveltime($userID,$prevStatusStation[$userID],$currentStatusStation,$row["mobileTime"],$db);
			//sleep(1);
			echo "------------------------------------------------------------------\n";
			$prevStatusStation[$row["userID"]] = $currentStatusStation;
		}
	}
	*/
	// determine current userID status and update traveltime
	function execTraveltime($sqlitePath,$currentTable,$userID,$currentStatusStation,$timestamp)
	{	
		$db = $db=new PDO('sqlite:'.$sqlitePath);
		$query = "SELECT station,rowid,displayText FROM current WHERE userID = '$userID' ORDER BY rowid DESC LIMIT 1";
		$data = $db->query($query)->fetchAll();
		$prevStatusStation = -1;
		if(isset($data[0])) $prevStatusStation = $data[0]["station"]; 		
		
		//echo "userID=$userID\tcurrentStatusStation=$currentStatusStation\tprevStatusStation=$prevStatusStation\n";
		
		//TARIN-GRANDVIEW MANAGEMENT
		if($prevStatusStation == 12 && $currentStation == 14) return;
		if($prevStatusStation == 14 && $currentStation == 12) return;
		
		if ($prevStatusStation == $currentStatusStation) // -1 -> -1 , MT -> MT
		{
			//$return = $data[0]["displayText"];
			if($currentStatusStation == '-1') updateArrivalTime($userID,$currentStatusStation,$timestamp,$db);
		}
		else if ($currentStatusStation != '-1')	// MT -> BT, -1 -> BT
		{
			//$return = "เข้าสู่ $currentStatusStation";
			updateTraveltime($userID,$currentStatusStation,$timestamp,$db);
			updateArrivalTime($userID,$currentStatusStation,$timestamp,$db);
			//echo "<strong>Actual</strong> userID = $userID Arrive Station = $currentStatusStation  timestamp = $timestamp \n ";
		}
		$db = NULL;
		//else	// MT -> -1
			//$return = "ออกจาก $prevStatusStation";
		
		//return $return;
	}
	
	function updateTraveltime($userID,$currentStation,$timestamp,$db){
	
		$prevStation = getPreviousStation($userID,$timestamp,$db);
		$edge = getEdge($prevStation,$currentStation);
		$timeDiff = getTimeDiff($userID,$timestamp,$db);
		//echo "timeDiff = $timeDiff \n";
		
		if($prevStation != -1 && $currentStation == nextStation($userID,$prevStation)){ // correctly enter station
			insertTravelTimeDataIfNotExist($edge,$prevStation,$currentStation,$timestamp,$db);
			$query = "SELECT * FROM traveltimeData WHERE edge = '$edge'";
			$data = $db->query($query)->fetchAll();
			if($timeDiff<3600){
				$newSum = $data[0]["sum"]+$timeDiff;
				$newCount = $data[0]["count"]+1;
			}else{
				$newSum = $data[0]["sum"];
				$newCount = $data[0]["count"];
			}
		
			$query = "UPDATE traveltimeData SET sum = '$newSum',count = '$newCount' WHERE edge = '$edge'";
			$db -> exec($query);
			
			$query = "INSERT INTO traveltime (userID,timestamp,edge,traveltime) VALUES ('$userID','$timestamp','$edge','$timeDiff')";
			$db -> exec($query);
		}
		
		$query = "UPDATE state SET station = '$currentStation', timestamp = '$timestamp' WHERE userID = '$userID'; ";
		$db -> exec($query);
	}
		
	function updateArrivalTime($userID,$currentStation,$timestamp,$db){
		
		$prevStation = getPreviousStation($userID,$timestamp,$db);	
		if($prevStation == '-1') return;
		if($currentStation == '-1'){
			$nextStation = nextStation($userID,$prevStation);
			$edge = getEdge($prevStation,$nextStation);
			$predictTravelTime = getPredictTravelTime($edge,$db);
			
			if($predictTravelTime <= 0) return;
			$leftTime = getLeftTime($userID,$nextStation,$predictTravelTime,$timestamp,$db);
			storeArrivalTime($userID,$nextStation,$timestamp,$leftTime,$db);
			$predictDT = plusSeconds($timestamp,$leftTime);
			//echo "<em>Predict</em> userID = $userID Arriving station = $nextStation leftTime = $leftTime predictDT = $predictDT \n ";

		}else{
			$nextStation = nextStation($userID,$currentStation);			
			storeArrivalTime($userID,$nextStation,0,$db);
			$cumulativeTime = 0;
					
			for($iStation=$nextStation; $iStation != $currentStation; $iStation = nextStation($userID,$iStation)){
				$nStation = nextStation($userID,$iStation);
				$edge = getEdge($iStation,$nStation);
				$predictTravelTime = getPredictTravelTime($edge,$db);
				
				if(isset($predictTravelTime) && $predictTravelTime > 0 && $cumulativeTime >= 0){
					$cumulativeTime += ceil($predictTravelTime);
					storeArrivalTime($userID,$nextStation,$timestamp,$cumulativeTime,$db);
					$predictDT =  plusSeconds($timestamp,$cumulativeTime);
					//echo "<em>Predict</em> userID = $userID Arriving station = $iStation cumulativeTime = $cumulativeTime predictDT = $predictDT\n ";
				}else{
					return;
				}
			}
		}
	}
	
	function getEdge ($fromStation,$toStation){
		return $fromStation;
	}
	
	function getLeftTime ($userID,$nextStation,$predictTravelTime,$timestamp,$db){
		$query ="SELECT updates FROM time_arrive_tbl WHERE bus_id = '$userID' AND bus_stop_id = $nextStation";
		$data = $db->query($query)->fetchAll();			
		$updateTime = new DateTime($data[0]["updates"]); 
		$nowTime = new DateTime($timestamp);
		$datetimediff = date_diff($updateTime,$nowTime);
		$delayTime = $datetimediff -> format("%i") * 60 + $datetimediff -> format("%s");
		
		$predictTT = ceil($predictTravelTime);
		//echo "userID = $userID nextStation = $nextStation predictWaitingTime = $predictTT delayTime = $delayTime \n";
		
		if($predictTravelTime < $delayTime || $delayTime ==  0) return 0; // Arrive at station or late compare to predicted arrival time
		else return ceil($predictTravelTime - $delayTime);
	}
	
	function getPredictTravelTime($edge,$db){
		$query = "SELECT sum,count FROM traveltimeData WHERE edge = '$edge'";
		$data = $db->query($query)->fetchAll();
		if(!isset($data[0]) || $data[0]["count"] == 0 || ($data[0]["sum"] == 0 && $data[0]["count"] = 1) ) return 0;
		else return $data[0]["sum"]/$data[0]["count"];
	}
	
	function getPreviousStation($userID,$timestamp,$db){
		insertStateIfNotExist($userID,$timestamp,$db);
		$query = "SELECT station FROM state WHERE userID = '$userID'";
		$data = $db->query($query)->fetchAll();
		return $data[0]["station"];
	}
	
	function getTimeDiff($userID,$timestamp,$db){
		$query = "SELECT timestamp FROM state WHERE userID = '$userID'";
		$data = $db->query($query)->fetchAll();
		$lastTime = new DateTime($data[0]["timestamp"]);
		$nowTime = new DateTime($timestamp);
		$datetimediff = date_diff($lastTime,$nowTime);
		$timeDiff = $datetimediff -> format("%i") * 60 + $datetimediff -> format("%s");
		return $timeDiff;
	}
	
	function insertArrivalTableIfNotExist($userID,$station,$db){
		$query = "SELECT count(*) AS count FROM time_arrive_tbl WHERE bus_id = '$userID' AND bus_stop_id = $station";
		$data = $db->query($query)->fetchAll();
		$containRow = $data[0]["count"];
		if($containRow == 0){
			$query = "INSERT INTO time_arrive_tbl (\"bus_id\",\"bus_stop_id\") VALUES ('$userID','$station')";
			$db->exec($query);
		}
	}
	
	function insertTravelTimeDataIfNotExist($edge,$fromStation,$toStation,$timestamp,$db){
		$query = "SELECT count(*) AS count FROM traveltimeData WHERE edge = '$edge'";
		$data = $db->query($query)->fetchAll();
		$containRow = $data[0]["count"];
		if($containRow == 0){
			$query = "INSERT INTO traveltimeData (edge,fromStation,toStation) VALUES ('$edge','$fromStation','$toStation')";
			$db->exec($query);
		}
	}
	
	function insertStateIfNotExist($userID,$timestamp,$db){
		$query = "SELECT count(*) AS count FROM state WHERE userID = '$userID'";
		$data = $db->query($query)->fetchAll();
		$containRow = $data[0]["count"];
		if($containRow == 0){
			$query = "INSERT INTO state (userID,insertFlag,timestamp) VALUES ('$userID','true','$timestamp')";
			$db->exec($query);
		}
	}
	
	function plusSeconds($timestamp,$leftTime){
		$nowDateTime = new DateTime($timestamp);
		$predictDateTime = $nowDateTime->add(new DateInterval("PT".$leftTime."S"));
		$predictDT = $predictDateTime->format('Y-m-d H:i:s');
		return $predictDT;
	}
	
	function storeArrivalTime($userID,$station,$timestamp,$arrivalTime,$db){	
		insertArrivalTableIfNotExist($userID,$station,$db);
		$query ="UPDATE time_arrive_tbl SET time_arrive = '$arrivalTime',updates = '$timestamp' WHERE bus_id = '$userID' AND bus_stop_id = $station";
		$db->exec($query);
	}
	
	function nextStation ($userID,$station){
		$route = getRoute($userID);
		if($route == 1 && $station<17) return $station + 1;
		if($route == 2 && $station<26) return $station + 1;
		if($route == 1) return 11;
		if($route == 2) return 21;
		return -1;
	}
	
	function mapStation($lat,$long,$places,$radius){
		foreach ($places as $name => $position){
			if( distance($lat,$long,$position[1],$position[2]) < $radius)
				return $name;
		}
		return "-----------";
	}
	
	function mapStationIndex($lat,$long,$places,$radius){
		foreach ($places as $position){
			if( distance($lat,$long,$position[1],$position[2]) < $radius)
				return $position[0];
		}
		return "-1";
	}

	
	// returns the distance (in kilometres) between two lat long. Adapt from http://www.thismuchiknow.co.uk/?p=71
	function distance ($lat1,$lon1,$lat2,$lon2){
		$lat1rad = deg2rad($lat1);
		$lat2rad = deg2rad($lat2);
		return acos(sin($lat1rad) * sin($lat2rad) + cos($lat1rad) * cos($lat2rad) * cos(deg2rad($lon2) - deg2rad($lon1))) * 6378.1;
	}
	
	function getPlaces($route){
		$places = array();
		
		if($route == 1){
			$places = array(
				"LOTUS PANG SUAN KAEW" => array(11,18.796102,98.974521),
				"TARIN" => array(12,18.805367,98.969841),
				"WAT JED YOD" => array(13,18.807432,98.972359),
				"CHIANG MAI GRAND VIEW" => array(14,18.805182,98.970490),
				"FURAMA" => array(15,18.802343,98.970810),
				"CHIANG MAI HILL" => array(16,18.804401,98.962105),
				"CMICE" => array(17,18.827052, 98.960338)
			);
		}
		else{
			$places = array(
				"CENTARA DUANGTAWAN" => array(21,18.784048,98.999176),
				"THE EMPRESS" => array(22,18.772186,98.998802),  
				"LANNA PALACE" => array(23,18.773676,98.998840),
				"THE CHEDI" => array(24,18.781658,99.003357),
				"PORN PING TOWER" => array(25,18.785295,99.002930),
				"CMICE" => array(26,18.827052, 98.960338)
			);
		}
		
		/*
		else
		{
			$places = array(
				"CMICE" => array(1,18.827052, 98.960338),
				"SHANGRI LA" => array(2,18.778744,98.999927),
				"DARA TEWEE" => array(3,18.779729,99.037371),
				"YAEK SANG TAWAN" => array(4,18.78109,98.999691),
				"YAEK RA KENG" => array(5,18.773898,98.998919),
				"YAEK PA DAD" => array(6,18.760306,98.99703),
				"YAEK NONG HOI" => array(7,18.758071,99.007373),
				"TRAIN ACROSS" => array(8,18.761322,99.020205),
				"DON CHAN" => array(9,18.764695,99.029431),
				"SRI BUA NGEN" => array(10,18.763395,99.038744),
				"BUAK KROK" => array(11,18.780338,99.042134),
				"PAYAP" => array(12,18.794234,99.022436),
				"MAE KAO" => array(13,18.80959,99.026341),
				"RUAM CHOK" => array(14,18.823076,99.011879),
				"RUEN PAE 2" => array(15,18.837882,99.000742),
				"SAPAN CHALERM PRAKIET" => array(16,18.844218,98.985229)
				"SUN RAJAKRAN" => array(17,18.839852,98.974028),
				"700 YEARS STADIUM" => array(18,18.83975,98.964908),
				"YAEK NONG HO" => array(19,18.822975,98.965144),
			);
		}
		*/
		return $places;
	}
	
	function getRoute($userID){
		return floor($userID/100);
	}

?>
