<?php
	ini_set('display_errors', '1');
	//header ('Content-type: text; charset=utf-8');
	header ('Content-type: text/html; charset=utf-8');
	$sqlitePath = "./login.sqlite";
	$db=new PDO('sqlite:'.$sqlitePath);
	$query = "SELECT * FROM user";
	$data = $db->query($query)->fetchAll();	
	//print_r($data);
	echo '<div id="creatNewUserForm" style="width:414px; background-color:EEEEEE" align="right">';
	echo '<h1>create new user </h1>';
	echo '<form action="createuserprocess.php" method="post">
		user: <input type="text" name="user"><br>
		password: <input type="password" name="pwd"><br>
		<input type="submit" value="create new user">
	      </form>';
	echo '</div>';
	echo '<table border="1"><tr><th>user</th><th>md5(password+salt)</th><th>role</th><th>login</th></tr>';
	echo '<tr>';
	foreach ($data as $row)
	{
		echo "<td>$row[0]</td><td>$row[1]</td><td>$row[2]</td></td>";
		if ($row[3] == 1)
			echo '<td><button type="button" onclick="window.location.href=\'./clearuser.php?userid='.$row[0].'\'">Logout</button></td>';
		else
			echo "<td></td>";
		echo '</tr>';
	}
	echo '</table>';
	$db = NULL;
?>
