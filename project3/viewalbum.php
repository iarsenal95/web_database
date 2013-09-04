<?php
	session_start();
	
	if (!IsSet($_GET['albumid'])){
           header("Location:index.php");
        }

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
	if(IsSet($_SESSION['isLoggedIn'])){
		
		$hasSessionExpired = checkIfTimedOut();
		if($hasSessionExpired)
		{
			session_unset();
			$error="Time out!Please login again.";
			header("Location:index.php?error=".$error);
			exit;
		}
		else
		
			include "Navigator.php";
	}
	else{
		include "lib.php";
		
	}
	
	
	$title="View Album";
	page_header($title);
	$conn=db_connect();

	$query="SELECT * FROM Album WHERE albumid=\"".$_GET['albumid']."\"";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	
	$query1="SELECT * FROM AlbumAccess WHERE username=\"".$_SESSION['username']."\"";
	$result1 = mysql_query($query1);
	
	$flag=0;
	while($row1 = mysql_fetch_array($result1)){
		if($row1['albumid']==$_GET['albumid']){
			$flag=1;
			break;
		}
		
		
	}
	
		
			
	
	
	if($row['access_type']=='public' || $flag==1||$_SESSION['isRootUser'])
	{
		echo $row['title']."<br>";
		echo $row['username']."<br>";
	
		$query="SELECT * FROM Contain WHERE albumid=\"".$_GET['albumid']."\"order by sequencenum";

		$result = mysql_query($query);
		
		
	
		echo "<table border=\"0\">";
		//echo "<div id='org_div1' class = 'Dragable'>";//!!!!!
	//
		$c=0;
		
				
		while($row = mysql_fetch_array($result))
		{
			$u=$row['url'];
			$seq=$row['sequencenum'];
			$aid=$row['albumid'];
			$query1="SELECT date_taken FROM Photo WHERE url=\"".$u."\"";
			$result1 = mysql_query($query1);
			$row1 = mysql_fetch_array($result1);
			if ($c % 5!=0){
				echo "<td><img src=".$u." id=\"thumbnail\" onclick=\"addLayout($c,$aid)\" onmousedown='event.preventDefault()' /></td>";
			}
			
			else
				echo "<tr><td><img src=".$u." id=\"thumbnail\" onclick=\"addLayout($c,$aid)\" onmousedown='event.preventDefault()' /></td>";
			$c+=1;
		}
		//echo "</div>";
		echo "</table>";
		
	

		$query2="SELECT * FROM Album WHERE albumid=\"".$_GET['albumid']."\"";
		$result2=mysql_query($query2);
		$row2 = mysql_fetch_array($result2);
		$user = $row2['username'];
		mysql_close($conn);
	
		if(IsSet($_SESSION['username'])){
			echo '<a href="./Home.php">BACK</a>';
			page_footer($_SESSION['firstname'],$_SESSION['lastname']);
		}	
		else{
			echo '<a href="./index.php">BACK</a>';
			page_footer();
		}
	}
	else {
		$error="You don't have the access to see this album!";
		echo $error."<br>";
		
		if(IsSet($_SESSION['username'])){
			echo '<a href="./Home.php">HOME</a>';
			page_footer($_SESSION['firstname'],$_SESSION['lastname']);
		}	
		else{
			echo '<a href="./index.php">HOME</a>';
			page_footer();
		}

		//header("location:index.php?error=".$error);
	}
?>


