<?php
	ini_set('display_errors', '1');
	header ('Content-type: text; charset=utf-8');
	//$time_start = microtime_float();	//	measure execute time
	$sqlitePath = "./liteDB/APWS2013.sqlite";
	$currentTable = "current";
	if (isset($_GET["db"]))
	{
		if (strtoupper($_GET["db"]) == "VIP")
			$currentTable = "currentVIP";
	}
	if (isset($_GET["last"]))
	{
		if ($_GET["last"] > 50)
			$last = 50;
		else
			$last = 1;
	}
	else
		$last = 50;
	$db=new PDO('sqlite:'.$sqlitePath);
	$query = "SELECT * FROM $currentTable ORDER BY rowid DESC LIMIT $last";
//	$query = "SELECT * FROM $currentTable WHERE userID = '701' ORDER BY rowid DESC LIMIT $last";
	$data = $db->query($query)->fetchAll();
	echo "<table border='1'><tr><th>userID</th><th>datetime</th><th>latitude</th><th>longitude</th><th>speed</th><th>heading</th><th>accuracy</th><th>mobileTime</th><th>station</th><th>behaviour</th><th>displayText</th><th>x</th><th>y</th><th>z</th><th>ABehaviour</th></tr>";
	foreach ($data as $row)
	{
		echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[3]</td><td>$row[4]</td><td>$row[5]</td><td>$row[6]</td><td>$row[7]</td><td>$row[8]</td><td>$row[9]</td><td>$row[10]</td><td>$row[11]</td><td>$row[12]</td><td>$row[13]</td><td>$row[14]</td></tr>";
	}
	echo "</table>";
	//print_r($data);
	$db = NULL;
/*	$time_end = microtime_float();
	$etime = $time_end - $time_start;
	echo "execute time = $etime\n";
	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}*/
?>
