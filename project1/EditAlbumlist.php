<?php
	include "lib.php";
	$title="Edit Album List";
	page_header($title);
	db_connect();
//Delete Album
if ($_GET['albumid']){
	$query="DELETE FROM Album WHERE albumid=\"".$_GET['albumid']. "\"";
	$name=$_GET['username'];
	mysql_query($query);
}

//Add new Album
else if(IsSet($_POST['title'])){
	$name=$_POST['username'];
	$result=mysql_query("select max(albumid) from Album"); 
	$row = mysql_fetch_array($result);
	$new_id=$row['max(albumid)']+1;
	$query="INSERT INTO Album (albumid,title,access_type,username,created,lastupdated) 
	VALUES (".$new_id.",'".$_POST['title']."','".$_POST['access_type']."','".$name."',".date("Y-m-d").",".date("Y-m-d").")";
	//日期显示不正确 全是0
	mysql_query($query);
}

else $name=$_GET['username'];
	

$query="SELECT * FROM Album WHERE username=\"".$name. "\"";
$result=mysql_query($query);

//show user's albums
echo "<table border=\"0\">";
echo "<tr><td>Name</td><td>Access</td><td></td><td></td></tr>";
while($row = mysql_fetch_array($result)){

   echo "<tr><td>". $row['title']."</td>";
   echo "<td>".$row['access_type']."</td>";
   $c=$row['albumid'];
   echo "<td>".'<a href="./EditAlbum.php?albumid='.$c.'">[Edit]</a>'."</td>";
  echo "<td>".'<a href="./EditAlbumlist.php?albumid='.$c.'&username='.$name.'">[Delete]</a>'."</td>";
  
}

//Add new album

echo " <tr><td>
<form action='./EditAlbumlist.php' method='post'>
<input TYPE=HIDDEN name='username' value=".$name.">".
"New:<input type='text' name='title'></td><td><input type='text' name='access_type'></td>
<td><input type='submit' value='Add'></form></td></tr></table>";

//Back
$back = "./Designs/back.png"; 
echo '<a href="./viewalbumlist.php?username='.$name.'"><img src= '.$back.'></a>';
mysql_close($conn);
page_footer();
?>


