<?php  
	ini_set('display_errors', '1');
	//header ('Content-type: text; charset=utf-8');
	$salt = "apws2013";
	$password =  md5($_POST["password"].$salt);
	$sqlitePath = "./login.sqlite";
	$db=new PDO('sqlite:'.$sqlitePath);
	$query = "SELECT * FROM user WHERE user='$_POST[user]' and password = '$password'";
	$data = $db->query($query)->fetchAll();	
	echo '<div style="position: absolute; top: 50%; left: 50%; height: 100px; margin-top: -50px; width: 250px; margin-left: -125px; align="center">
	<div align="right">';
	if(empty($data))
	{
		echo "username หรือ password ไม่ถูกต้อง   <a href=index.php></br>ลองอีกครั้ง</a>";    
		echo '	</div>
</div>';
	}
	/*
	else if ($data[0]['login'] != 0)
	{
		echo "username นี้ถูกใช้งานอยู่แล้ว   <a href=index.php></br>ลองอีกครั้ง</a>";    
		echo '	</div>
</div>';
	}
	*/
	else
	{
		session_start();
		$_SESSION['sessusername'] = $data[0]['user'];
		//print_r($_SESSION);
		$sql = "UPDATE user set login=1 WHERE user='$_POST[user]' and password = '$password' and login = 0";
		//echo $sql;
		$results = $db->exec($sql);
		/*$query = "SELECT * FROM user WHERE user='$_POST[user]' and password = '$password' and login = 0";
		$data = $db->query($query)->fetchAll();
		print_r($data);*/
		$db = NULL;
		//exit;
		header("Location:../president/main.php");  // redirect ไปหน้า memberzone.php
		exit;		
	}
	$db = NULL;
?>
