<?php
	header("content-type; text/xml");
	$albumid = $_POST['albumid'];
	$index = $_POST['index'];
		//$albumid = 3;
	//$index = 1;
	//$op = "start";
	$mysql_server_name ="localhost";
   	$mysql_username    ="group23";
   	$mysql_password    ="ccl";
   	$mysql_database    ="group23";
   
   	$conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
   	if (!$conn)
  	{
  		die('Could not connect: ' . mysql_error());
  	}
   	mysql_select_db($mysql_database,$conn);
   

	$sql = "SELECT sequencenum,url from Contain where albumid = '$albumid' ORDER BY sequencenum";
	$result = mysql_query($sql);
	$num=0;
	while($row = mysql_fetch_array($result)){
		
		$url_array[$num]=$row['url'];
		if ($num==$index){
			$url=$row['url'];
		}
		$num+=1;
	}
	
	echo '<?xml version="1.0" encoding="ISO-8859-1"?><spv>';
	echo "<url>".$url."</url>";
	echo "</spv>";
		
	mysql_close($conn);
?> 
