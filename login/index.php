<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="draw.css" type="text/css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"> </script>

<script type="text/javascript">
	window.onload=function() {
	document.forms[0][0].focus();
}
</script>


<script type="text/javascript">     	
	jQuery(document).ready(function() {          	
	          jQuery("#loginform").css("display", "none");                	
			  jQuery("#loginform").fadeIn(3500);     }); 	
</script>
   
</head>
<body>


<form name="loginform" id="loginform" action="checklogin.php" method="post" enctype="multipart/form-data">
     <div class="content_logo">
     	<img src="pic/logo1.jpg" width="500" height="200" style="cursor: pointer;">
     </div>
     <div class="content">
     	<div align="left">
          <br><br>
	      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <font color="#FFFFFF" size="4"> Username </font> 
          <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="text" name="user" size="20" class="input" style="width:260px; height:30px; border-radius:5px; font-size:20px;">
          <br><br>
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <font color="#FFFFFF" size="4"> Password </font> 
          <br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="password" name="password" size="20" class="input" style="width:260px; height:30px; border-radius:5px; font-size:20px;">
          <br><br><br><br>
		  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input type="submit" value="Login" class="button-primary"">
        </div>
     </div>
</form>
 

</body>
</html>


