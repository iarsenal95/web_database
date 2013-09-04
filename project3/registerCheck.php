<?php

	include "lib.php";

	// username and password sent from form
	$username=$_POST['username'];
	$password=$_POST['password'];
	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];

	// To protect MySQL injection (more detail about MySQL injection)
	$username = stripslashes($username);
	$password = stripslashes($password);
	$firstname = stripslashes($firstname);
	$lastname = stripslashes($lastname);
	$email = stripslashes($email);
	$username = mysql_real_escape_string($username);
	$password = mysql_real_escape_string($password);
	$firstname = mysql_real_escape_string($firstname);
	$lastname = mysql_real_escape_string($lastname);
	$email = mysql_real_escape_string($email);

	$conn=db_connect();
	$sql1="SELECT * FROM Users WHERE username=\"".$username."\"";
	$result=mysql_query($sql1);

	// Mysql_num_row is counting table row
	$count=mysql_num_rows($result);

	// If result matched $myusername and $mypassword, table row must be 1 row
	if($count == 0){

	// save new user's information and redirect to file "index.php"
	$sql2="INSERT INTO Users VALUES ('".$username."','".$firstname."','".$lastname."','".$password."','".$email."')";
	mysql_query($sql2);
	
	//send_email
	
	$to =$email ;
	$subject =	 'Welcome to PhotoClip';
	$bound_text =	"Group23";
	$bound =	"--".$bound_text."\r\n";
	$bound_last =	"--".$bound_text."--\r\n";
 	 
	
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .=	"From: eecs485.group23@umich.edu\r\n";
	
	$headers .= 'Cc: lvgen@umich.edu' . "\r\n";

	//$headers .=	"MIME-Version: 1.0\r\n"
 	//."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
 	
 	 
 	//$message .=	"If you can see this MIME than your client doesn't accept MIME types!\r\n"
 	//.$bound;
 	 
 	$message .=	"This email is send by PhotoClip by <b>Group23</b> to confirm your membership\r\n<br>"
 	
 	
 	.$bound;
 	mail($to, $subject, $message, $headers);

	
	
	
	
	session_start();
	session_register("username");
	$_SESSION['isLoggedIn'] = true;
		$_SESSION['timeOut'] = 300;
		$logged = time();
		$_SESSION['loggedAt']= $logged;
		$_SESSION['username']=$username;
		header("location:Home.php");
	
	}
	else {
	$error="This user has existed!";
	header("location:NewUser.php?error=".$error);
		
	}
	mysql_close($conn);
	page_footer();
?>
