<?php
	ini_set('display_errors', '1');
	$time_start = microtime_float();	//	measure execute time
	header ('Content-type: text; charset=utf-8');
	require_once("./recieveGPSHeader.php");

	$gps=explodeGPS();
	$sqlitePath = "./liteDB/APWS2013.sqlite";
	$archivedTable = "archivedVIP";
	$currentTable = "currentVIP";
	//$lastN = 50;	// maximum entry in currentTable;
	$lastN = 10;	// เปลี่ยนตามที่แจ๊คบอกเมื่อ 2013/05/12

	$status = insertSQLite($sqlitePath,$archivedTable,$currentTable,$gps,$lastN);
	if ($status == "OK")
	{
		$behaviour = "";	// call Jack's behaviour
		$aBehaviour = "";	// call Jack's accelerometer behaviour
		$arrivaltime = "";	// call Game's function
		echo $status;		// status,behaviour,aBehaviour,arrivaltime
	}
	else
		echo $status;		// Drop or Error

	$time_end = microtime_float();
	$etime = $time_end - $time_start;
	//echo "execute time = $etime\n";
	function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
?>
