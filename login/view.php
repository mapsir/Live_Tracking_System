<?php
	session_start();
	//print_r($_SESSION);
	if($_SESSION['sessusername']=="")
	{
		//print_r($_SESSION);
		header('location:index.php');
		exit();
	}
?>
<html>
<body>
<div style="position: absolute; top: 50%; left: 50%; height: 100px; margin-top: -50px; width: 250px; margin-left: -125px; align="center">
	<div align="right">
<?php
	echo "login as $_SESSION[sessusername]";
?>
<form action="process.php" method="post">
	<input type="submit" value="Logout">
</form>
	</div>
</div>
</body></html>
