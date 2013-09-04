<?php 
	include 'lib.php';
	$title="View Album List";
	page_header($title);
	db_connect();

   /*$mysql_server_name ="localhost";
   $mysql_username    ="root";
   $mysql_password    ="root";
   $mysql_database    ="test1";
   
   $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
   if (!$conn)
  {
  die('Could not connect: ' . mysql_error());
  }
   mysql_select_db($mysql_database,$conn);*/
   
   $query="SELECT * FROM Album WHERE username=\"".$_GET['username']. "\"";
   $result=mysql_query($query);
   while($row = mysql_fetch_array($result))
   {
   $c=$row['albumid'];
   if ($row['access_type']=='public') 
   echo '<a href="./viewalbum.php?albumid='.$c.'">'.$row['title'].'</a>';
   else echo $row['title'];
   echo "<br />";
   }
   $back = "./Designs/back.png";
   $edit = "./Designs/edit.png";
   $newi=$_GET['username'];
   echo '<a href="./EditAlbumlist.php?username='.$newi.'"><img src= '.$edit.'></a><br>';
   echo '<a href="./index.php?"><img src= '.$back.'></a><br>';
   mysql_close($conn);
   page_footer();
   
?>



