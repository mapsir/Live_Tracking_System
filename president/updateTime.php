<!doctype html>
<html>
<script>
<?
 
   		//$DBfile_bus = "../chiangmai_project/liteDB/DB.sqlite";
		$DBfile_bus = "../liteDB/APWS2013.sqlite";
		$db_bus=new PDO('sqlite:'.$DBfile_bus);
		if (!$db_bus) print_r($db_bus->errorInfo());
		
		//$query_bus = "SELECT * FROM bus WHERE route = 3";
		
		//$query_bus = "SELECT DISTINCT(userID), * FROM current WHERE userID = 701 OR userID = 702 OR userID = 901 OR userID = 902 GROUP BY userID ORDER BY userID ASC, datetime DESC ;";
		
		$query_bus = "SELECT DISTINCT(userID), * FROM current WHERE (userID >= 701 AND userID <= 799) GROUP BY userID ORDER BY userID ASC, datetime DESC ;";

		$result_bus = $db_bus->query($query_bus)->fetchAll();
	
		//Fetch each vehicle entrie and write to javascript array
		echo "v_delay = [ \n";
		foreach ($result_bus as $entry_bus)
		{
			echo "\t\t{ ID:".$entry_bus["userID"].", time:".time()-$entry_bus["datetime"]."},\n";
		}
		echo "\t];\n";
		$db_bus= null;
	
?>
updateTimeEntry(v_delay);
</script>
</html>