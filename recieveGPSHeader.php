<?php
	ini_set('display_errors', '1');
	header ('Content-type: text; charset=utf-8');
	function explodeGPS()
	{
		$gps = array();
		if (isset($_GET["gps"]))
		{
			$gps  = $_GET["gps"]; 
			$gps = explode(',',$gps);
			//echo "gps=$gps\n";
			/*
			[0] = userID
			[1] = timestamp from mobile 20131231235559 *need to convert to 2013-03-20 15:38:07
			[2] = latitude
			[3] = longitude
			[4] = speed
			[5] = heading
			[6] = acculacy
			[7] = x from accelerometer
			[8] = y from accelerometer
			[9] = z from accelerometer
			*/
			if (sizeof($gps) <= 6)
			{
				$gps[7] = -99;	// default for x is not recieved
				$gps[8] = -99;	// default for y is not recieved
				$gps[9] = -99;	// default for z is not recieved
			}
			$gps[1] = substr($gps[1],0,4)."-".substr($gps[1],4,2)."-".substr($gps[1],6,2)." ".substr($gps[1],8,2).":".substr($gps[1],10,2).":".substr($gps[1],12,2);
		}
		else
			$gps[0] = -1;	// error no input
		return $gps;
	}
	function insertSQLite($sqlitePath,$archivedTable,$currentTable,$gps,$lastN,$station = '-1')
	{
		$worning = "";
		$db=new PDO('sqlite:'.$sqlitePath);
		//echo "sqlitePath=$sqlitePath\narchivedTable=$archivedTable\ncurrentTable=$currentTable\n";
		//print_r($gps);
		$query = "SELECT max(mobileTime) FROM $currentTable WHERE userID = $gps[0]";
		//echo "query=$query\n";
		$data = $db->query($query)->fetchAll();
		//print_r($data);
		//echo $data[0][0]."\n";
		//echo "$gps[1]\n";
		if (!isset($gps[7]))
		{
			$my_file = '/var/www/apws2013/errorLog/gpsError.txt';
			$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
			fwrite($handle, implode($gps,","));
			$newline = "\n";
			fwrite($handle, $newline);
			$worning = "No x,y,z\n";
			$gps[7] = -99;	// default for x is not recieved
			$gps[8] = -99;	// default for y is not recieved
			$gps[9] = -99;	// default for z is not recieved
			//return;
		}
		if ($data[0][0] >= $gps[1])	// delay mobileTime(n) < mobileTime(n-1)
		{
			$gps[6] = $gps[6]*-1;		// mark accuracy x -1 for drop 
			$query = "INSERT INTO 						$archivedTable".' ("userID","latitude","longitude","speed","heading","accuracy","mobileTime","x","y","z") VALUES ('."'$gps[0]',$gps[2],			$gps[3],$gps[4],$gps[5],$gps[6],'$gps[1]','$gps[7]','$gps[8]','$gps[9]')";
			$resultArchived = $db->exec($query);
			$lastInsertIdArchived = $db->lastInsertId();
			echo "Drop\n";
			$db = NULL;
			return;
		}
		else
		{
			$query = "INSERT INTO $archivedTable".' ("userID","latitude","longitude","speed","heading","accuracy","mobileTime","x","y","z") VALUES ('."'$gps[0]',$gps[2],$gps[3],$gps[4],$gps[5],$gps[6],'$gps[1]','$gps[7]','$gps[8]','$gps[9]')";
			$resultArchived = $db->exec($query);
			$lastInsertIdArchived = $db->lastInsertId();
			//echo "Added $lastInsertIdArchived in $archivedTable\n";
			
			//$query = "INSERT INTO archivedVIP ".' ("userID","latitude","longitude","speed","heading","accuracy","mobileTime","x","y","z") VALUES ('."'$gps[0]',$gps[2],$gps[3],$gps[4],$gps[5],$gps[6],'$gps[1]','$gps[7]','$gps[8]','$gps[9]')";
			//$resultArchived = $db->exec($query);
			//echo "$query\n";
			//echo "Added $lastInsertId in $archivedTable\n";
			
			lastN($db,$gps[0],$lastN);

			$query = "INSERT INTO $currentTable".' ("userID","latitude","longitude","speed","heading","accuracy","mobileTime","x","y","z",station) VALUES ('."'$gps[0]',$gps[2],$gps[3],$gps[4],$gps[5],$gps[6],'$gps[1]','$gps[7]','$gps[8]','$gps[9]','$station')";
			$resultCurrent = $db->exec($query);
			$lastInsertIdCurrent = $db->lastInsertId();
			//echo "$query\n";
			//echo "Added $lastInsertIdCurrent in $currentTable\n";
		}
		$db = NULL;
		if ($lastInsertIdArchived == 0 || $lastInsertIdCurrent == 0)
			return "Error Can't Insert";
		else
			return "OK $worning";
	}
	function lastN($db,$userID,$n)
	{
		$n = $n-1;
		$query = "DELETE FROM current WHERE userID = '$userID' AND ROWID NOT IN (SELECT ROWID from current WHERE userID = '$userID' ORDER BY ROWID DESC LIMIT $n)";
		$resultLastN = $db->exec($query);
		$query = "SELECT ROWID,* FROM current WHERE userID = '$userID'";
		$data = $db->query($query)->fetchAll();
		//echo "current have ".count($data)." entry \n";
		//print_r($data);
		//echo "resultLastN=$resultLastN\n";
	}
?>
