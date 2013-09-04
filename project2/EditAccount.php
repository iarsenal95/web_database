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
	}
	else
	{
		$hasSessionExpired = checkIfTimedOut();
		if($hasSessionExpired)
		{
			session_unset();
			header("Location:index.php");
			exit;
		}	
		else
		{
			include "Navigator.php";
			$title="Edit Account";
			page_header_valid($title);
			$conn=db_connect();

			 echo
                        '<script type="text/JavaScript">
         
                        function DELcheck(){
                        var agree=confirm("Are you sure you want to delete this User?");
                        if (agree==true)
                          return true;
                        else
                                return false;
                         }
                        </script>';

			$username=$_SESSION['username'] ;
    	
			if (IsSet($_POST['firstname'])){
	    		$query="UPDATE Users SET firstname=\"".$_POST['firstname']. "\"
	    		WHERE username=\"".$username. "\"";
	    		mysql_query($query);
	    		$_SESSION['firstname']=$_POST['firstname'];
	    		echo "firstname has been changed<br>";
	    		}
	    		else if(IsSet($_POST['lastname'])){
		    	$query="UPDATE Users SET lastname=\"".$_POST['lastname']. "\"
		    	WHERE username=\"".$username. "\"";
		    	mysql_query($query);
		    	$_SESSION['lastname']=$_POST['lastname'];
		    	echo "lastname has been changed<br>";
		   	}
		    	else if(IsSet($_POST['password']) && IsSet($_POST['old_password'])){
				$sql="SELECT pass_word FROM Users WHERE username=\"".$username."\"";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
			
				if ($row['pass_word']==$_POST['old_password']){
		    		$query="UPDATE Users SET pass_word=\"".$_POST['password']. "\"
		    		WHERE username=\"".$username. "\"";
		    	
		    		mysql_query($query);
		    		echo "password changed<br>";
		    	}	
		    	else 
		    		echo "old password wrong!<br>";
		    
		    }
		    else if(IsSet($_POST['email'])){
		    	$query="UPDATE Users SET email=\"".$_POST['email']. "\"
		    	WHERE username=\"".$username. "\"";
		    	mysql_query($query);
		    	echo "email has been changed!<br>";
		    }
		    else if (IsSet($_POST['rootuser'])){
			$username = $_SESSION['username'];
			if($_POST['rootuser'] == 1&& $_SESSION['isRootUser'] == false){
				$query= "INSERT INTO RootUsers Values ('$username');";
				mysql_query($query);
				$_SESSION['isRootUser'] = true;
			}
			if ($_POST['rootuser']== 0&& $_SESSION['isRootUser']== true){
				$query= "DELETE FROM RootUsers WHERE username = '$username');";
				mysql_query($query);
				$_SESSION['isRootUser'] = false;
			}
		    }

		    mysql_close($conn);

    

		    echo "<form name='text' action='EditAccount.php' method='post'>
		    firstname: <input type='text' name='firstname'>
		    <input type='submit' value='Submit'></form>	";
		    
		    echo "<form name='text' action='EditAccount.php'  method='post'>
		    lastname: <input type='text' name='lastname'>
		    <input type='submit' value='Submit'></form>	";

    
		    echo "<form name='input' action='EditAccount.php' 
		    onsubmit='return validEmail()'; 			method='post'>
		    email: <input type='text' name='email'>
		    <input type='submit' value='Submit'></form>	";

				
		    echo "<form name='input2' action='EditAccount.php' 
		    onsubmit='return validPassword()'; method='post'>
		    old password:<input type='password' name='old_password'><br>
		    password: <input type='password' name='password'><br>
		    confirm password:<input type='password' name='password_check'><br>
		    <input type='submit' value='Submit'></form>	";
			
		    echo "<form name='input' action='EditAccount.php' method='post'>
                    <input type='radio' name='rootuser' value = 1>add root user privilege<br>
		    <input type='radio' name ='rootuser' value = 0>remove root user privilege<br>
                    <input type='submit' value='Submit'></form> ";

	
		    echo "<a href='./DelAccount.php' onClick='return DELcheck();'>Delete Account</a>";
		    // echo "<a href='./DelAccount.php' >delete account</a>";
		    page_footer($_SESSION['firstname'],$_SESSION['lastname'],$username);
		   }
	 }
?>
	
