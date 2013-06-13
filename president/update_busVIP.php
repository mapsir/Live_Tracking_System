<!doctype html>
<html>

<script>
<?
		//$DBfile_bus = "../chiangmai_project/liteDB/DB.sqlite";
		$DBfile_bus = "../liteDB/APWS2013.sqlite";
		$db_bus=new PDO('sqlite:'.$DBfile_bus);
		if (!$db_bus) print_r($db_bus->errorInfo());
		//else echo "connected.<br>";
		
		//EDIT BY JACK
		//$query_bus = "SELECT DISTINCT(userID), * FROM current WHERE (userID >= 700 AND userID <= 799) GROUP BY userID ORDER BY userID ASC, datetime DESC ;";
		$query_bus = "SELECT DISTINCT(userID), * FROM current WHERE  (userID >= 701 AND userID <= 799) GROUP BY userID ORDER BY userID ASC, datetime DESC ;";
		
		$result_bus = $db_bus->query($query_bus)->fetchAll();
	
	//EDIT By JACK
		
		$count_bus = -1;
		echo "v_location =[ \n";
		foreach ($result_bus as $entry_bus)
		{
			echo "{ ID: ".$entry_bus["userID"].",lat:".$entry_bus["latitude"].", long:".$entry_bus["longitude"]."},\n";
			
		}
		echo "];\n";
		$db_bus= null;
		
?>

updateMarker(v_location);
setZoomIcon();
</script>
</html>