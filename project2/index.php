<?php 
	include "lib.php";
	$title="Online Album";
	page_header($title);
	$conn=db_connect();
	if(IsSet($_GET['error'])){
		echo $_GET['error'];
	}
	echo
	"<form name='input' action='checklogin.php' method='post'>
	Username: <input type='text' name='username'><br>
	Password: <input type='password' name='password'><br>
	<input type='submit' value='Log in'>
	</form><br> 
	<a href='./NewUser.php'>Sign Up</a><br>
	<a href='./mailpwd.php'>Forgot Password?</a><br>";
	
	echo '<br><p><b><font style="font-size:18px;">Welcome to PhotoClip. Share your moments with every one =P<br><br>Current Public Albums on PhotoClip:</b></p>';
	
	$query="SELECT * FROM Album WHERE access_type=\"".'public'. "\"";
   $result=mysql_query($query);
   while($row = mysql_fetch_array($result))
   {
   $c=$row['albumid'];
   
   echo '<a href="./viewalbum.php?albumid='.$c.'">'.$row['title'].'</a>';
   
   echo "<br />";
   }
   
   mysql_close($conn);

	page_footer();
?>
