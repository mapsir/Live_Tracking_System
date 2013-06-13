<?php
	ini_set('display_errors', '1');
	if (!isset($_SERVER['HTTP_REFERER']) or ($_SERVER['HTTP_REFERER'] != "http://203.185.67.159/apws2013/login/createuser.php"))
		header('location:createuser.php');
	//header ('Content-type: text; charset=utf-8');
	$salt = "apws2013";
	print_r($_POST);
	$user = $_POST['user'];
	$password =  md5($_POST["pwd"].$salt);

	$sqlitePath = "./login.sqlite";
	$db=new PDO('sqlite:'.$sqlitePath);
	$sql = 'INSERT INTO "main"."user" ("user","password","role") VALUES ("'.$user.'","'.$password.'","G")';
	$results = $db->exec($sql);
	$db = NULL;
	header('location:createuser.php');
	//echo $sql;
?>
