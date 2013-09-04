<?php
	$name = $_POST["username"];
	$albumid = $_POST["albumid"];
	$op = $_POST["operation"];
	//include "lib.php";
	//$conn=db_connect();
	
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
   

	$sql = "SELECT username, title from Album where albumid = '$albumid'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$title = $row['title'];
	$ownername = $row['username'];
	if ($op == "add"){
		$sql="INSERT INTO AlbumAccess VALUES('$albumid', '$name')";
		$result = mysql_query($sql);
	}
	if ($op == "remove"){
		if ($name != $ownername){
			$sql = "DELETE FROM AlbumAccess WHERE albumid = '$albumid' AND username = '$name'";
			$result = mysql_query($sql);
		}
	}
	
			echo "<script type='text/javascript' src='DD.js'></script>";
			//echo "<img src='./images/football_s1.jpg' onload='OnLoad()'>";
			
			echo "<table class='album' border='0' onmouseover='OnLoad()'>";
			echo "<tr><td>Name</td><td>Access</td><td></td><td></td></tr>";
			//echo "</table>";
			
			$query="SELECT * FROM Album WHERE username=\"".$ownername. "\"";
			$result=mysql_query($query);
			while($row = mysql_fetch_array($result)){
				$c=$row['albumid'];
				//echo "<span id='$c'>";
				//echo "<table class='album' border='1'>";
  				if($row['access_type']=='private'){
  					//class:AlbumName
  					echo "<tr><td nowrap><div style='position:absolute;' class='AlbumName' data-albumid ='$c'>Give Access to</div>
  					<div>".$row['title']."</div></td>";
  					echo "<td>private</td>";
  					echo "<td>".'<a href="./EditAlbum.php?albumid='.$c.'">[Edit]</a>'."</td>";
  					echo "<td>".'<a href="./MyAlbum.php?albumid='.$c.'&username='.$ownername.'"
  					onClick="return DELcheck();">[Delete]</a>'."</td></tr>";
  					echo "<tr><td nowrap>users can access:</td></tr>";
  					$query1="select username from AlbumAccess where albumid=\"".$c."\"";
  					$result1=mysql_query($query1);
  					while($row1=mysql_fetch_array($result1)){
  						//class:RMUser
  						echo "<tr><td></td><td nowrap>
  						<div style='position:absolute;' data-albumid=".$c
  						." data-username=".$row1['username']." class='RMUser'>Wanna to delete?</div>
  						<div>".$row1['username'].
  						"</div></td></tr><span id='$c'></span>";
  						}
  				}
  				else{
	  				echo "<tr><td>". $row['title']."</td>";
	  				echo "<td>".$row['access_type']."</td>";
	  				echo "<td>".'<a href="./EditAlbum.php?albumid='.$c.'">[Edit]</a>'."</td>";
	  				echo "<td>".'<a href="./MyAlbum.php?albumid='.$c.'&username='.$ownername.'"
	  				onClick="return DELcheck();">[Delete]</a>'."</td></tr>";
  				}
  				//echo "</span>";
				
				//echo "<script type='text/javascript' src='DD.js'></script>";
				//echo "<script>OnLoad();</script>";
				
  			}
  			echo "</table>";
  			//echo "</script>";
  			//echo "</div>"

	
	
	
	/*
echo "<tr><td></td><td nowrap><div style='position:absolute;' data-albumid=".$albumid
  	." data-username=".$name." class='RMUser'>Wanna to delete?</div>
  	<div>".$name."</div></td></tr>";
*/

	//echo "<table border='1'>";
	/*
echo "<tr><td nowrap><div style='position:absolute;' class='AlbumName' data-albumid ='$albumid'>Give Access to</div><div>".$title."</div></td>";
  	echo "<td>private</td>";
  	echo "<td>".'<a href="./EditAlbum.php?albumid='.$albumid.'">[Edit]</a>'."</td>";
  	echo "<td>".'<a href="./MyAlbum.php?albumid='.$albumid.'&username='.$name.'"onClick="return DELcheck();">[Delete]</a>'."</td></tr>";
  	echo "<tr><td nowrap>users can access:</td></tr>";
	$query1="select username from AlbumAccess where albumid= '$albumid'";
  	$result1=mysql_query($query1);
	while($row1=mysql_fetch_array($result1))
	{
  	//class:RMUser
  		echo"<tr><td></td><td nowrap>
  		<div style='position:absolute;' data-albumid=".$albumid
  		." data-username=".$row1['username']." class='RMUser'>Wanna to delete?</div>
  		<div>".$row1['username'].
  		"</div></td></tr>";
  	}
	echo "</table>";
*/
	//echo "<script>OnLoad();</script>";
	mysql_close($conn);
?> 
