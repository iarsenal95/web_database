<?php
	session_start();
	function checkIfTimedOut(){
		$current = time();// take the current time
		$diff = $current - $_SESSION['loggedAt'];
		if($diff > $_SESSION['timeOut'])
		{
			return true;
		}
		else
		{
			return false;
			$_SESSION['loggedAt']= time();// update last accessed time
		}
	}
	if(!isset($_SESSION['isLoggedIn']) || !($_SESSION['isLoggedIn'])){
		$error="please login first!";
		header("location:index.php?error=".$error);
		exit;
	}
	else
	{
		
		$hasSessionExpired = checkIfTimedOut();
		if($hasSessionExpired)
		{
			session_unset();
			$error="Time out!Please login again.";
			header("Location:index.php?error=".$error);
			exit;
		}
		else
		{
			include "Navigator.php";
	$title="Home";
	page_header($title);
	$conn = db_connect();
	
	//welcome messgae:
	echo '<p><b><font style="font-size:24px;">Welcome to PhotoClip. Share your moments with every one =P</b></p>';
	echo '<p><b><font style="font-size:18px;">Here are your accessible albums:</b></p><br>';
	
	echo'<link rel="stylesheet" type="text/css" href="tableSet.css" media="screen" />';
	
	$username = $_SESSION["username"];
	$query = "SELECT firstname, lastname FROM Users WHERE username = '$username'";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);
	$firstname = $row["firstname"];
	$lastname = $row["lastname"];
	
	$_SESSION['firstname']=$firstname;
	$_SESSION['lastname']=$lastname;
	if ($_SESSION['isRootUser']){
		$query = "SELECT albumid, username, title From Album";
	}
	else{	
		$query = "SELECT DISTINCT Album.albumid, Album.username, Album.title FROM AlbumAccess, Album WHERE 				(AlbumAccess.username = '$username' AND AlbumAccess.albumid = Album.albumid) OR 					Album.access_type = \"public\" ORDER BY Album.username";
   	}
	$result = mysql_query($query);
	$previous = "";
	
	/*$query1="select albumid from AlbumAccess where username='$username'";
	$result1=mysql_query($query1);
	$index=0;
	while($row1=mysql_fetch_array($result1)){
		$id_array[$index]=$row1['albumid'];
		$index+=1;
	}*/
	
	
	// album table
	echo '<table class="tableSet">
			<tr>
						<th>User</th>
						<td>Album Name</td>
			</tr>';
	while($row = mysql_fetch_array($result))
	{
		echo '<tr>';
		$name = $row["username"];
		$id = $row["albumid"];
		if ($name != $previous){
			echo '<th>'.$name.'</th>';
		}
		else{
			echo '<th></th>';
		}
		
		echo '<td><a href="./viewalbum.php?albumid='.$id.'">'.$row['title'].'</a></td>';
		$previous = $name;
		echo '</tr>';
	}
	echo '</table><br><br>';
	
	mysql_close($conn);
	page_footer($firstname, $lastname,$username);
		}
	}	
?>
