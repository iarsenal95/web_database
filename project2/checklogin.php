<?php
	include "lib.php";
	$conn=db_connect();
	// username and password sent from form
	$username=$_POST['username'];
	$password=$_POST['password'];

	// To protect MySQL injection (more detail about MySQL injection)
	$username = stripslashes($username);
	$password = stripslashes($password);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	
	$sql="SELECT * FROM Users WHERE username=\"".$username."\"and pass_word=\"".$password."\"";
	$result = mysql_query($sql);
	//echo $sql;
	// Mysql_num_row is counting table row
	$count = mysql_num_rows($result);

	// If result matched $myusername and $mypassword, table row must be 1 row

	if($count == 1){

		// Register $myusername, $mypassword and redirect to file "login_success.php"
		session_start();
		session_register("username");
		$query="SELECT * FROM RootUsers WHERE username=\"".$username."\"";
		$result = mysql_query($query);
		$count = mysql_num_rows($result);
		if($count == 1){
			$_SESSION['isRootUser'] = true;
		}
		else{ 
			$_SESSION['isRootUser'] = false;
		}
		$_SESSION['isLoggedIn'] = true;
		$_SESSION['timeOut'] = 300;
		$logged = time();
		$_SESSION['loggedAt']= $logged;
		$_SESSION['username']=$username;
		header("location:Home.php");
	}
	else {
		$query="SELECT * FROM Users WHERE username=\"".$username."\"";
		$result = mysql_query($query);
		$count = mysql_num_rows($result);
		$row = mysql_fetch_array($result);
		if($count == 0){
			$error = "No such user!Join us now!";
		}
		else{
			$error="Wrong password!Try again!";
		}
		header("location:index.php?error=".$error);
	}
	mysql_close($conn);
	page_footer();
?>
