<?php
	ini_set('display_errors', '1');
	if (!isset($_SERVER['HTTP_REFERER']) or ($_SERVER['HTTP_REFERER'] != "http://203.185.67.159/apws2013/login/createuser.php"))
		header('location:createuser.php');
	//header ('Content-type: text; charset=utf-8');
	$sqlitePath = "./login.sqlite";
	$db=new PDO('sqlite:'.$sqlitePath);
	$sql = "UPDATE user set login=0 WHERE user='$_GET[userid]' and login = 1";
	echo $sql;
	$results = $db->exec($sql);
	$db = NULL;
	header('location:createuser.php');
	exit;
?>
