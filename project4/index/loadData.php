<?php 
/*ini_set('display_errors', 1);
error_reporting(E_ALL);
// or even better:
if (!class_exists('DOMDocument'))
{
    die('DOMDocument is not configured.');
}*/ 
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

	$album = simplexml_load_file('search.xml');
	$title = "";

		$title = $album['title'];
		$created = $album['created'];
		$last = $album['lastupdated'];
		$per = $album['permission'];
		$sql = "INSERT INTO Album (title, created, lastupdated, access_type) VALUES ('$title','$created','$last','$per')";
	    mysql_query($sql);
	echo "album: ".$title." is inserted<br>";
	$sql = "SELECT albumid FROM Album WHERE title = '$title'";
	$result = mysql_query($sql);
	$row = mysql_fetch_array($result);
	$id = $row['albumid'];
	//$id = 23;
	$photos = $album->photo;
	$count = 0;
	foreach ($photos as $photo) {
		$seq = $photo['sequencenum'];
		$url = $photo['url'];
		$fn = $photo['filename'];
		$cap = $photo['caption'];
		$dt = $photo['datetaken'];
		$sql = "INSERT INTO Photo (url, format, date_taken) VALUES ('$url', 'jpg', '$dt')";
	    mysql_query($sql);
		$sql = "INSERT INTO Contain (albumid, url, captain, sequencenum) VALUES (\"$id\", \"$url\", \"$cap\", \"$seq\")";
		mysql_query($sql);
		$count++;
	}
	echo $count." rows inserted";
	mysql_close($conn);
?>

