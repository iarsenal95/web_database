<?php
	include "lib.php";
	$title="Edit Album";
	page_header($title);
	db_connect();
//change the access type
if(IsSet($_POST["access_type"])){
	
	$id=$_POST['albumid'];
	$query="UPDATE Album SET access_type=\"".$_POST['access_type']. "\"
	WHERE albumid=\"".$id. "\"";;
	
	mysql_query($query);
	//echo "This album's access type has been changed to ".$_POST['access_type']."<br>";
}

//upload a new image
else if(IsSet($_FILES["file"])){
	$id=$_POST['albumid'];
	$result=mysql_query("select max(sequencenum) from Contain where albumid=\"".$id. "\""); 
	$row = mysql_fetch_array($result);
	$new_sn=$row['max(sequencenum)']+1;
	if ($_FILES["file"]["error"] > 0)
  {
  		echo "Error: " . $_FILES["file"]["error"] . "<br>";
  }
  	else
  {
  $dir = "./images/"; 
  $tmp_name = $_FILES['file']['tmp_name'];
  $actual_name = $_FILES['file']['name']; 
  
  move_uploaded_file($tmp_name,$dir.$actual_name); 
  
  $query1="insert into Photo (url,format,date_taken) 
  values('".$dir.$actual_name."','".$_FILES["file"]["type"]."','".date("Y-m-d")."')";
  mysql_query($query1);
  
  $query="INSERT INTO Contain (albumid,url,captain,sequencenum)
  VALUES (".$id.",'".$dir.$actual_name."','".$actual_name."',".$new_sn.")";
   //echo $query ;
	mysql_query($query);
    }
}

//delete one or more images from this album
else if (IsSet($_POST["url"])){
	$id=$_POST['albumid'];
	$url=$_POST['url'];
	for ($i=0; $i<count($url); $i++) {
	
	$query="DELETE FROM Photo WHERE url=\"".$url[$i]. "\"";
	//echo $query;
	mysql_query($query);
	$query="DELETE FROM Contain WHERE url=\"".$url[$i]. "\"";
	mysql_query($query);
	}
}
else $id=$_GET['albumid'];

$query="SELECT * FROM Contain WHERE albumid=\"".$id. "\"order by sequencenum";
$result=mysql_query($query);

echo "Change the access<br>";
echo "<form action='./EditAlbum.php' method='post'>
<input TYPE=HIDDEN name='albumid' value=".$id.">
<input type='radio' name='access_type' value='public'>Public<br>
<input type='radio' name='access_type' value='private'>Private<br>
<input type='submit' value='Submit'><br></form>";
echo "<br><br>";

echo "Add New Picture<br>";
echo "<form action='./EditAlbum.php' method='post'
enctype='multipart/form-data'>
<label for='file'>Filename:</label>
<input TYPE=HIDDEN name='albumid' value=".$id.">
<input type='file' name='file' id='file'><br>
<input type='submit' name='submit' value='Submit'><br></form>";
echo "<br>";

echo "Delete pictures<br>";
echo "<table border=\"0\">";
echo "<form action = './EditAlbum.php' method = 'post'>
<input TYPE=HIDDEN name='albumid' value=".$id.">";
$c=0;
while($row = mysql_fetch_array($result)){
if ($c % 5!=0) echo "<td>";
else echo "<tr><td>";

echo "<input type='checkbox' name='url[]' value =".$row['url'].">"."<img src=".$row['url'].' " width="100" height="100"/>'."</td>";
$c+=1;
}

//submit
echo "</table>";
echo "<input type='submit' value='submit'></form><br>";

//show "BACK"
$query2="SELECT * FROM Album WHERE albumid=\"".$id. "\"";
$result2=mysql_query($query2);
$row2 = mysql_fetch_array($result2);
$user = $row2['username'];
$back = "./Designs/back.png";
echo '<a href="./EditAlbumlist.php?username='.$user.'"><img src= '.$back.'></a>';

mysql_close($conn);
page_footer();
?>

