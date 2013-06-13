<? header('Content-Type: text/html; charset=utf-8'); ?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"> </script>
	<script src="http://maps.googleapis.com/maps/api/js?sensor=true&language=th"></script>
<script>

<?
		//Inittialize vehicle data array
		
		$DBfile_bus = "../liteDB/APWS2013.sqlite";
		$db_bus=new PDO('sqlite:'.$DBfile_bus);
		if (!$db_bus) print_r($db_bus->errorInfo());
	
		$query_bus = "SELECT DISTINCT(userID), * FROM current WHERE (userID >= 701 AND userID <= 799) GROUP BY userID ORDER BY userID ASC, datetime DESC ;";

		
		$result_bus = $db_bus->query($query_bus)->fetchAll();
		
		$count_bus = 0;
		echo "var v_location =[ \n";
		foreach ($result_bus as $entry_bus)
		{
			echo "{ ID: ".$entry_bus["userID"].",lat:".$entry_bus["latitude"].", lon:".$entry_bus["longitude"]."},\n";
			
		}
		echo "];";
		$db_bus= null;
		
?>

var v_prop={};
var v_marker=[];
var sign_marker=[];
var v_delay=[];
  
function initialize() 
{
	//Initial focus point
	var focus = new google.maps.LatLng(16.464896,99.51351);
	
	//Initialize map option
	var mapOptions = {
	zoom: 15,
	center: focus,
			disableDefaultUI: true,
	mapTypeId: google.maps.MapTypeId.ROADMAP
	};
          	 maps = new google.maps.Map(document.getElementById('map-load-canvas'),mapOptions);


	google.maps.event.addListener(maps, 'click', function(){
	    document.getElementById('focus_president').value = 0
	});
  
	google.maps.event.addListener(maps, 'dragend', function(){
	    document.getElementById('focus_president').value = 0
	});


          	 //- - - - - - - - - - - - - - - - - - - - -Initialize   Vehicle    - - - - - - - - - - - - - - - - - - - - - - - - - -//
		  
		 
		v_prop={
				//1
				701:{img:"pic/icon/1Yingluck_s.png",
				 imgp:"pic/icon_r/1Yingluck_s.png",
				 title:"นายกรัฐมนตรี",
				 zIndexp: 113,
				 zIndex: 213},
				//2 
				702:{img:"pic/icon/7-17Yukol_l.png",
				 imgp:"pic/icon_r/7-17Yukol_l.png",
				 title: "รองนายก ยุคล",
				 zIndexp: 113,
				 zIndex: 213},
				 //3
				703:{img:"pic/icon/3-12Kitttiratt_n.png",
				 imgp:"pic/icon_r/3-12Kitttiratt_n.png",
				 title: "รองนายก กิตติรัตน์",
				 zIndexp: 113,
				 zIndex: 213},
				 //4
				704:{img:"pic/icon/5-14Surapong_t.png",
				 imgp:"pic/icon_r/5-14Surapong_t.png",
				 title: "เลขา สมช. ภราดร",
				 zIndexp: 113,
				 zIndex: 213},
				 //5
				 //Wrong Picture
				705:{img:"pic/icon/6-35Phongthep_t.png",
				 imgp:"pic/icon_r/6-35Phongthep_t.png",
				 title: "รองนายก สุรพงษ์",
				 zIndexp: 113,
				 zIndex: 213},
				 //6
				706:{img:"pic/icon/7-17Yukol_l.png",
				 imgp:"pic/icon_r/7-17Yukol_l.png",
				 title: "รองนายก พงศ์เทพ",
				 zIndexp: 113,
				 zIndex: 213},
				 //7
				707:{img:"pic/icon/8Niwattumrong_b.png",
				 imgp:"pic/icon_r/8Niwattumrong_b.png",
				 title: "สำนักนายก วราเทพ",
				 zIndexp: 1,
				 zIndex: 101},
				 //8
				708:{img:"pic/icon/9Varathep_r.png",
				 imgp:"pic/icon_r/9Varathep_r.png",
				 title: "รมว.กห. สุกำพล",
				 zIndexp: 1,
				 zIndex: 101},
				 //9
				709:{img:"pic/icon/10Sanasenn_n.png",
				 imgp:"pic/icon_r/10Sanasenn_n.png",
				 title: "รมว.คม. ชัชชาติ",
				 zIndexp: 1,
				 zIndex: 101},
				 //10
				710:{img:"pic/icon/11Sukumpol_s.png",
				 imgp:"pic/icon_r/11Sukumpol_s.png",
				 title: "รมว.มท. จารุพงศ์",
				 zIndexp: 1,
				 zIndex: 101},
				 //11
				711:{img:"pic/icon/11Sukumpol_s.png",
				 imgp:"pic/icon_r/11Sukumpol_s.png",
				 title: "รมว.พณ. บุญทรง",
				 zIndexp: 101,
				 zIndex: 201},
				 //12
				712:{img:"pic/icon/15sc_p.png",
				 imgp:"pic/icon_r/15sc_p.png",
				 title: "รมว.ยธ. ประชา",
				 zIndexp: 2,
				 zIndex: 102},
				 //13
				713:{img:"pic/icon/34Woravat_A.png",
				 imgp:"pic/icon_r/34Woravat_A.png",
				 title: "รมว.วท. วรวัจน์",
				 zIndexp: 2,
				 zIndex: 102},
				 //14
				714:{img:"pic/icon/23Preecha_r.png",
				 imgp:"pic/icon_r/23Preecha_r.png",
				 title: "รมว.วธ. สนธยา",
				 zIndexp: 2,
				 zIndex: 102},
				 //15
				715:{img:"pic/icon/24Anudith_n.png",
				 imgp:"pic/icon_r/24Anudith_n.png",
				 title: "รมว.รง. เผดิมชัย",
				 zIndexp: 2,
				 zIndex: 102},
				 //16
				716:{img: "pic/icon/24Anudith_n.png",
				 title: "รมว.สธ. ประดิษฐ",
				 zIndexp: 2,
				 zIndex: 102},
				 //17
				717:{img:"pic/icon/police.png",
				 imgp:"pic/icon_r/police.png",
				 title: "ผบ.ตร.",
				 zIndexp: 2,
				 zIndex: 102},
				 //18
				718:{img:"pic/bus/bus_thailand.png",
				 imgp:"pic/bus/bus_thailand.png",
				 title: "เครื่องทดสอบ",
				 zIndexp: 1,
				 zIndex: 101},
				 //19
				798:{img:"pic/bus/bus_thailand.png",
				 imgp:"pic/bus/bus_thailand.png",
				 title: "เครื่องทดสอบ2",
				 zIndexp: 1,
				 zIndex: 101},
				 //20
				799:{img:"pic/bus/bus_thailand.png",
				 imgp:"pic/bus/bus_thailand.png",
				 title: "เครื่องทดสอบ3",
				 zIndexp: 1,
				 zIndex: 101},
			};
		
		for(var x in v_prop) {
			//create marker for each variable
			var mark={ a:new google.maps.Marker({
						//position:,
						map: maps,
						animation: google.maps.Animation.DROP,
						title:v_prop[x].title,
						icon: v_prop[x].img,
						zIndex:v_prop[x].zIndex
					}),
				   p:new google.maps.Marker({
						//position:,
						map: maps,
						animation: google.maps.Animation.DROP,
						title:v_prop[x].title,
						icon: v_prop[x].imgp,
						zIndex:v_prop[x].zIndexP
					})};
			v_marker[x]=mark;
				
		}
		
		  //- - - - - - - - - - - - - - - - - - - - - - - -    Place     - - - - - - - - - - - - - - - - - - - - - - - - - -//
		  
		  var latlng_cmice = new google.maps.LatLng(16.474547,99.496304);  
          var image = "pic/icon/place_1.png"; 
          var marker_cmice = new google.maps.Marker({
                  position: latlng_cmice,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "โรงแรมชากังราว วิเวอวิว",
				  icon: image,
				  zIndex: 0
          });
		 
				  		  
		  
		  //Mandarin Oriental Dhara Dhevi
		  var latlng_Mandarin = new google.maps.LatLng(16.482801,99.522662);  
          var image_Mandarin = "pic/icon/place_2.png"; 
          var marker_Mandarin = new google.maps.Marker({
                  position: latlng_Mandarin,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "Mandarin Oriental Dhara Dhevi",
				  icon: image_Mandarin,
				  zIndex: 0
          });
		  
	
		  
		  
				
		  var latlng_DusitD22 = new google.maps.LatLng(16.452899,99.513676);  
          var image_DusitD22 = "pic/icon/place5.png"; 
          var marker_DusitD22 = new google.maps.Marker({
                  position: latlng_DusitD22,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "มหาลัยราชภัฏกำแพงเพชร",
				  icon: image_DusitD22,
				  zIndex: 2
          });
		  	    
		  
		  
		  
	
		  
		  
		  
		  var latlng_DusitD2 = new google.maps.LatLng(16.477314,99.53111);  
          var image_DusitD2 = "pic/icon/place1.png"; 
          var marker_DusitD2 = new google.maps.Marker({
                  position: latlng_DusitD2,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "วัดคูยาง",
				  icon: image_DusitD2,
				  zIndex: 2
          });
		  	  
		   
		  
	
		  var latlng_Siripanna = new google.maps.LatLng(16.477952,99.521111);  
          var image_Siripanna = "pic/icon/place3.png"; 
          var marker_Siripanna = new google.maps.Marker({
                  position: latlng_Siripanna,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "สนามกีฬาชากังราว",
				  icon: image_Siripanna,
				  zIndex: 2
          });
		  
		  
		  
		 
		  
	
		  var latlng_WiangKumKam = new google.maps.LatLng(16.48644,99.522677);  
          var image_WiangKumKam = "pic/icon/place2.png"; 
          var marker_WiangKumKam = new google.maps.Marker({
                  position: latlng_WiangKumKam,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "สนามเฮลิคอปเตอร์",
				  icon: image_WiangKumKam,
				  zIndex: 0
          });
		  
		  
		  
		  
		 
		  
		 		 
		  var latlng_WiangKumKam = new google.maps.LatLng( 16.504731,99.515741);  
          var image_WiangKumKam = "pic/icon/place4.png"; 
          var marker_WiangKumKam = new google.maps.Marker({
                  position: latlng_WiangKumKam,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "อุทยานประวัติศาตร์",
				  icon: image_WiangKumKam,
				  zIndex: 0
          }); 
		  
		  
		  	 		  
		  var latlng_WiangKumKam2 = new google.maps.LatLng( 16.473024,99.52771);  
          var image_WiangKumKam2 =  "pic/place/icon_hotel2.png"; 
          var marker_WiangKumKam2 = new google.maps.Marker({
                  position: latlng_WiangKumKam2,
                  map: maps,
                  animation: google.maps.Animation.DROP,
                  title: "โรงแรมชากังราว",
				  icon: image_WiangKumKam2,
				  zIndex: 0
          }); 
		  
		  
		  
		  
		  
		  
		  
	// - - - - - - - - - - - - - - - - - - - - - - - -   sign  - - - - - - - - - - - - - - - - - - - - - - //
		  
	  var sign1_latlng = new google.maps.LatLng(18.78109,98.999691);	   
          var sign1_image = "pic/sign/YAEK_SANG_TAWAN.png";
           sign_marker[1] = new google.maps.Marker({
                      position: sign1_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "YAEK SANG TAWAN",
					  icon: sign1_image,
					  zIndex: 1
          });
		  
		  
		  var sign2_latlng = new google.maps.LatLng(18.773898,98.998919);	   
          var sign2_image = "pic/sign/YAEK_RA_KENG.png";
           sign_marker[2] = new google.maps.Marker({
                      position: sign2_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "YAEK RA KENG",
					  icon: sign2_image,
					  zIndex: 1
          });
		  
		  
		  var sign3_latlng = new google.maps.LatLng(18.760306,98.99703);	   
          var sign3_image = "pic/sign/YAEK_PA_DAD.png";
           sign_marker[3] = new google.maps.Marker({
                      position: sign3_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "YAEK PA DAD",
					  icon: sign3_image,
					  zIndex: 1
          });
		  
		  var sign4_latlng = new google.maps.LatLng(18.758071,99.007373);	   
          var sign4_image = "pic/sign/YAEK_NONG_HOI.png";
           sign_marker[4] = new google.maps.Marker({
                      position: sign4_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "YAEK NONG HOI",
					  icon: sign4_image,
					  zIndex: 1
          });
		  
		  var sign5_latlng = new google.maps.LatLng(18.761322,99.020205);	   
          var sign5_image = "pic/sign/TRAIN_ACROSS.png";
           sign_marker[5] = new google.maps.Marker({
                      position: sign5_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "TRAIN ACROSS",
					  icon: sign5_image,
					  zIndex: 1
          });
		  
		  var sign6_latlng = new google.maps.LatLng(18.764695,99.029431);	   
          var sign6_image = "pic/sign/DON_CHAN.png";
           sign_marker[6] = new google.maps.Marker({
                      position: sign6_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "DON CHAN",
					  icon: sign6_image,
					  zIndex: 1
          });
		  
		  var sign7_latlng = new google.maps.LatLng(18.763395,99.038744);	   
          var sign7_image = "pic/sign/SRI_BUA_NGEN.png";
           sign_marker[7] = new google.maps.Marker({
                      position: sign7_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "SRI BUA NGEN",
					  icon: sign7_image,
					  zIndex: 1
          });
		  
		  var sign8_latlng = new google.maps.LatLng(18.780338,99.042134);	   
          var sign8_image = "pic/sign/BUAK_KROK.png";
           sign_marker[8] = new google.maps.Marker({
                      position: sign8_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "BUAK KROK",
					  icon: sign8_image,
					  zIndex: 1
          });
		  
		  
		  var sign9_latlng = new google.maps.LatLng(18.762684,99.001919);	   
          var sign9_image = "pic/sign/RATI_RANNA.png";
           sign_marker[9] = new google.maps.Marker({
                      position: sign9_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "RATI RANNA",
					  icon: sign9_image,
					  zIndex: 1
          });
		  
		  
		  var sign10_latlng = new google.maps.LatLng(18.796092,99.036137);	   
          var sign10_image = "pic/sign/Phayap.png";
           sign_marker[10] = new google.maps.Marker({
                      position: sign10_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Phayap",
					  icon: sign10_image,
					  zIndex: 1
          });
		  
		  var sign11_latlng = new google.maps.LatLng(18.80959,99.026341);	   
          var sign11_image = "pic/sign/Mae_Khao.png";
           sign_marker[11] = new google.maps.Marker({
                      position: sign11_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Mae_Khao",
					  icon: sign11_image,
					  zIndex: 1
          });
		  
		  
		  var sign12_latlng = new google.maps.LatLng(18.823076,99.011879);	   
          var sign12_image = "pic/sign/Ruam_Chok.png";
           sign_marker[12] = new google.maps.Marker({
                      position: sign12_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Ruam Chok",
					  icon: sign12_image,
					  zIndex: 1
          });
		  
		  
		  var sign13_latlng = new google.maps.LatLng(18.837882,99.000742);	   
          var sign13_image = "pic/sign/Rueanphae2.png";
           sign_marker[13] = new google.maps.Marker({
                      position: sign13_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Rueanphae 2",
					  icon: sign13_image,
					  zIndex: 1
          });
		  
		  
		  var sign14_latlng = new google.maps.LatLng(18.844218,98.985229);	   
          var sign14_image = "pic/sign/Chaloemphrakiat1.png";
           sign_marker[14] = new google.maps.Marker({
                      position: sign14_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Chaloemphrakiat",
					  icon: sign14_image,
					  zIndex: 1
          });
		  
		  
		  var sign15_latlng = new google.maps.LatLng(18.839852,98.974028);	   
          var sign15_image = "pic/sign/Sun_Ratchakan.png";
           sign_marker[15] = new google.maps.Marker({
                      position: sign15_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Sun Ratchakan",
					  icon: sign15_image,
					  zIndex: 1
          });
		  
	
		  var sign16_latlng = new google.maps.LatLng(18.83975,98.964908);	   
          var sign16_image = "pic/sign/Sanamkila_700.png";
           sign_marker[16] = new google.maps.Marker({
                      position: sign16_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Sanamkila 700",
					  icon: sign16_image,
					  zIndex: 1
          });
		
		  
		  var sign17_latlng = new google.maps.LatLng(18.822975,98.965144);	   
          var sign17_image = "pic/sign/Nong_Ho.png";
           sign_marker[17] = new google.maps.Marker({
                      position: sign17_latlng,
                      map: maps,
                      animation: google.maps.Animation.DROP,
                      title: "Nong Ho",
					  icon: sign17_image,
					  zIndex: 1
          });
	
}

google.maps.event.addDomListener(window, 'load', initialize);


 //$(document).ready(function()
 $(window).load(function()
			{
				setZoomIcon(); 
				 //Code when window finish loading
				 updateBus();
				 updateTime();
			}
		);


//schedule update of vehicle position every 1 sec after previous update is completed
function updateBus() {
	//$('#update_bus').empty();
	$('#update_bus').load('update_busVIP.php',function() {setTimeout(updateBus,1000); } );
	
	
	//code
}
//schedule update of vehicle update time every 1 sec after previous update is completed
function updateTime() {
	//$('#updateTime').empty();
	$('#updateTime').load('updateTime.php',function() {setTimeout(updateTime,1000); });
	//code
}

//This update map focus and location of marker
function updateMarker(v_loc) {
	//Update map focus
	for (var i=0;i<v_loc.length;i++) {
		//check for focus
		if(document.getElementById('focus_president').value == v_loc[i].ID)
		{
			maps.setCenter( new google.maps.LatLng(v_loc[i].lat, v_loc[i].lat.lon) );
			break;
		}
	}
	//Update marker position
	for (var i=0;i<v_loc.length;i++) {
		//update every marker
		var pos = new google.maps.LatLng(v_loc[i].lat, v_loc[i].lat.lon);
		v_marker[v_loc[i].ID].a.setPosition(pos);
		v_marker[v_loc[i].ID].p.setPosition(pos);
		
	}			
}
function initTimeEntry() {
	$("#updateTime").append("<table id=\"timeEntryTable\" width=\"290\" border=\"0\"></table>");
	for (var i=0;i<v_location.length;i++) {
		//code
		$("#timeEntryTable").append("<tr><td>"+
						"<div class=\"content_country\">"+
							"<div class=\"content_name_logo\">"+
								"<table border=\"0\" ><tr>"+
									"<td width=\"230\">"+
										"<b><font size=\"5\">"+(i+1)+".)"+v_prop[v_location[i].ID].title+"</font></b>"+
									"</td>"+
									"<td width=\"60\">"+
										"<img src=\"pic/cabinet/Yingluck_s.jpg\" width=\"55\" height=\"60\"\">"+
									"</td>"+
								"</tr><tr>"+
									"<td id=\"last_update\""+v_location[i].ID+" class =\"content_last_update\" width=\"170\">N\A</td>"+
									"<td width=\"120\">"+
											"<img id=\"btn_"+v_location[i].ID+"\" src=\"pic/icon_focus.png\" width=\"40\" height=\"40\" "+
											" style=\"cursor: pointer;\" onDblClick=\"focus_main()\" onClick=\"focus("+v_location[i].ID+","+v_location[i].lat+","+v_location[i].lon+") \">"+
											"&nbsp;<img id=\"status_p"+v_location[i].ID+"\" src=\"pic/status_red.png\" width=\"40\" height=\"40\"\">"+
											"&nbsp;<img id=\"status_a"+v_location[i].ID+"\" src=\"pic/status_green.png\" width=\"40\" height=\"40\"\">"+
									"</td>"+
								"</tr></table>"+
							"</div>"+
						"</div>"+
					"</td></tr>");	
	}
}	
				
function updateTimeEntry(delaySec) {
	//code
	
	for (var x in delaySec) {
		//code
		if(delaySec[x].time < 60){
			setActive(delaySec[x].ID);
		}
		else{
			setPassive(delaySec[x].ID)
		}
		if(delaySec[x].time>= (3600*24))
		{
			displayDelay(delaySec[x].ID,floor(delaySec[x].time/(3600.0*24))+"day.");	
		}	
	       else if(delaySec[x].time >= 3600)
		{
			displayDelay(delaySec[x].ID,floor(delaySec[x].time/3600.0)+" hour.");	
		}			
	       else if(delaySec[x].time  >= 60)
	       {
			displayDelay(delaySec[x].ID,floor(delaySec[x].time/60.0)+" min.");	
	       }
	       else
	       {
			displayDelay(delaySec[x].ID,delaySec[x].time+" sec");	
	       }
	}
}
function setActive(id) {
	//code
	v_marker[id].p.setVisible(false);
	v_marker[id].a.setVisible(true);
}
function setPassive(id) {
	//code
	v_marker[id].a.setVisible(false);
	v_marker[id].p.setVisible(true);
}
function displayDelay(id,txt) {
	//code
	$("#last_update"+id).html(txt);
}
function focus_main(){
		  maps.panTo(focus);
		  maps.setZoom(14);
		  
		  document.getElementById('focus_president').value = 0;
}
function setFocus(id,lat,lon){
	
	maps.panTo(new google.maps.LatLng(lat, lon));
	document.getElementById('btn_'+id).src = "pic/icon_focus1.png";
	document.getElementById('focus_president').value =id;
	
}
function setZoomIcon(){
	/* if(maps.getZoom() >= 15){

		sign_marker[1].setVisible(true);
		sign_marker[2].setVisible(true);
		sign_marker[3].setVisible(true);
		sign_marker[4].setVisible(true);
		sign_marker[5].setVisible(true);
		sign_marker[6].setVisible(true);
		sign_marker[7].setVisible(true);
		sign_marker[8].setVisible(true);
		sign_marker[9].setVisible(true);
		sign_marker[10].setVisible(true);
		sign_marker[11].setVisible(true);
		sign_marker[12].setVisible(true);
		sign_marker[13].setVisible(true);
		sign_marker[14].setVisible(true);
		sign_marker[15].setVisible(true);
		sign_marker[16].setVisible(true);
		sign_marker[17].setVisible(true);
		
	      
		}
	 else{
		sign_marker[1].setVisible(false);
		sign_marker[2].setVisible(false);
		sign_marker[3].setVisible(false);
		sign_marker[4].setVisible(false);
		sign_marker[5].setVisible(false);
		sign_marker[6].setVisible(false);
		sign_marker[7].setVisible(false);
		sign_marker[8].setVisible(false);
		sign_marker[9].setVisible(false);
		sign_marker[10].setVisible(false);
		sign_marker[11].setVisible(false);
		sign_marker[12].setVisible(false);
		sign_marker[13].setVisible(false);
		sign_marker[14].setVisible(false);
		sign_marker[15].setVisible(false);
		sign_marker[16].setVisible(false);
		sign_marker[17].setVisible(false);
		 } */
	v_marker[718].a.setVisible(false);
	v_marker[798].a.setVisible(false);
	v_marker[799].a.setVisible(false);
	v_marker[718].p.setVisible(false);
	v_marker[798].p.setVisible(false);
	v_marker[799].p.setVisible(false);
}
</script>
	
<!--height: 1340px;-->
	<style>
   		#map-load-canvas 
   		{
	      	height: 100%;
          	width: 100%;
		 	z-index: 1;
           	background-color: #ccc;
        }
	</style>



    	 <link rel="stylesheet" href="draw.css" type="text/css">
    
<title>President Demo</title>
</head>
<body>



<div class="content"> 

       <form name="Logout" id="Logout" action="../login/process.php" style='position:fixed; top:5px; right:30px; z-index: 2' method="post">
        	<div class="content_logout">
         		<input type="submit" value="Logout" class="button-primary">
        	</div>
       </form>
	   
	   
	   
	   
	<div class="map-content"> 
		
             <div id="map-load-canvas"></div>
			<div id="update_bus">   </div> 
			<div id="slidebottom" class="slide"  style='position:fixed; bottom:20px; right:50px; z-index: 20'  >
				<button>Show</button>
			</div>
        </div>
        <div class="update_bus">
			<input name="focus_president" id="focus_president" type="hidden">
             <div id="updateTime"> </div>
        </div>      
		 <img src='pic/new_logo.png' height='90px' style='position:fixed; bottom:5px; left:100px; z-index: 3'/>
	
	
		
</div>




<script>

	$( "#slidebottom button" ).click(function() {
					$("div.update_bus")										
					.hide().fadeIn()								
					.animate({marginRight:"0px"});
	});


	var p = $("div.content");
	$("div.update_bus").click(function() {
	
					$("div.update_bus").fadeOut().animate({marginRight:"0px"});
				
				});
</script>

</body>
</html>