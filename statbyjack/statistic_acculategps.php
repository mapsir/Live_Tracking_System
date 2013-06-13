<!doctype html>
<html>
<head>
<meta charset="UTF-8">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"> </script>
	<script src="http://maps.googleapis.com/maps/api/js?sensor=true"></script>
<?
$user = $_REQUEST['user'];
$dt_start = $_REQUEST['start'];
$dt_stop = $_REQUEST['stop'];

$sr_y = substr($dt_start, 0,4);
$sr_m = substr($dt_start, 4, 2);
$sr_d = substr($dt_start, 6, 2);

$sr_h = substr($dt_start, 8, 2);
$sr_i = substr($dt_start, 10, 2);
$sr_s = substr($dt_start, 12, 2);


$st_y = substr($dt_stop, 0,4);
$st_m = substr($dt_stop, 4, 2);
$st_d = substr($dt_stop, 6, 2);

$st_h = substr($dt_stop, 8, 2);
$st_i = substr($dt_stop, 10, 2);
$st_s = substr($dt_stop, 12, 2);


$result_dt_start =  $sr_y."-".$sr_m."-".$sr_d." ".$sr_h.":".$sr_i.":".$sr_s;
$result_dt_stop =  $st_y."-".$st_m."-".$st_d." ".$st_h.":".$st_i.":".$st_s;


  echo"  <script>	
		$(document).ready(function()
			{ 
				 $('#map').load('gps_point.php');	
				 $('#update_bus').load('setGPSPoint.php', { user: $user , dt_start : '$result_dt_start' , dt_stop : '$result_dt_stop' });
				 $('#updateTime').load('calculateAverage.php', { user: $user , dt_start : '$result_dt_start' , dt_stop : '$result_dt_stop' });
			
		});
				
	
	
	</script>";
?>
    	 <link rel="stylesheet" href="mapfull.css" type="text/css">
    
<title>Demo</title>
</head>

<body>
<div class="content">
			<div class="map-content"> 
             <div id="map"> </div>
             <div id="update_bus">   </div>
        </div>
        
        <div class="update_bus">
             <div id="updateTime"> </div>
        </div>
        
    
        
</div>
</body>
</html>