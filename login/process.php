<?php
	session_start();
	$sqlitePath = "./login.sqlite";
	$db=new PDO('sqlite:'.$sqlitePath);
	$sql = "UPDATE user set login=0 WHERE user='$_SESSION[sessusername]' and login = 1";
	$results = $db->exec($sql);
	$db = NULL;
	session_destroy();

	header('location:index.php');
	exit;
?>
