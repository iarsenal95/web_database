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
		header("Location:index.php?error=".$error);
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
			
		else{
			include "Navigator.php";
			$title="MyAlbum";
			page_header($title);
			$conn=db_connect();
	
			//javescript code here
			//pop up window when deleting album
			echo 
			'<script type="text/JavaScript">
	 
			function DELcheck(){
			var agree=confirm("Are you sure you want to delete this Album?");
			if (agree==true)
		     return true;
		    else
		     return false;
		    }
		    </script>';
	
	
	
	
		    //Delete Album
		    if ($_GET['albumid']){
		
			  	//find photos only contained by this album
			  	$albumid=$_GET['albumid'];
			  	$query1="SELECT * 
				FROM (
					SELECT * , COUNT( * ) 
					FROM Contain
					GROUP BY url
					HAVING COUNT( * ) =1
				) AS temp
				WHERE albumid =$albumid";
				$result1=mysql_query($query1);
				
				while($row1=mysql_fetch_array($result1))
				{
					$file=$row1['url'];
					$file0="'".$file."'";
					//delete from database: Photo
					$query2="DELETE FROM Photo WHERE url = $file0";
					mysql_query($query2);
					//delete the real file
					unlink($file);
				}


				//delete from database: Album, Contain
				$query="DELETE FROM Album WHERE albumid=\"".$_GET['albumid']. "\"";
				mysql_query($query);
				$query="DELETE FROM Contain WHERE albumid=\"".$_GET['albumid']. "\"";
                                mysql_query($query);

			}

			//Add new Album
			else if(IsSet($_POST['title'])){
				$name=$_POST['username'];
				$result=mysql_query("select max(albumid) from Album"); 
				$row = mysql_fetch_array($result);
				$new_id=$row['max(albumid)']+1;
				$query=
				"INSERT INTO Album(albumid,title,access_type,username,created,lastupdated) 
				VALUES (".$new_id.",'".$_POST['title']."','".$_POST['access_type']."','".					$name."',".date("Y-m-d").",".date("Y-m-d").")";
	
				mysql_query($query);
				
				$query1="insert into AlbumAccess values(".$new_id.",'".$name."')";
				mysql_query($query1);
				//echo $query1;
			}
			else{}; 
			$name=$_SESSION['username'];
			
				
			if ($_SESSION['isRootUser']){
				$query="SELECT * FROM Album";
			}
			else{
			$query="SELECT * FROM Album WHERE username=\"".$name. "\"";
			}
			$result=mysql_query($query);

			//show user's albums
			echo "<table border=\"0\">";
			echo "<tr><td>Name</td><td>Access</td><td></td><td></td></tr>";
			while($row = mysql_fetch_array($result)){
			$c=$row['albumid'];
			$name = $row['username'];
			echo "<tr><td><a href='./viewalbum.php?albumid=".$c."'>".$row['title']."</a></td>";
				echo "<td>".$row['access_type']."</td>";
			
				echo "<td>".'<a href="./EditAlbum.php?albumid='.$c.'">[Edit]</a>'."</td>";
				//<a href="somefile.php" onClick="confirmDelete();">Delete File</a>
				echo "<td>".'<a href="./MyAlbum.php?albumid='.$c.'&username='.$name.'"
  					 onClick="return DELcheck();">[Delete]</a>'."</td>";
  
  			}

  			//Add new album

  			echo " <tr><td>
  			<form action='./MyAlbum.php' method='post'>
  			<input TYPE=HIDDEN name='username' value=".$_SESSION['username'].">".
  			"New:<input type='text' name='title'></td><td><select name='access_type'>
  			<option value='public'>public</option>
  			<option value='private'>private</option></td>
  			<td><input type='submit' value='Add'></form></td></tr></table>";
	
  			//Back
  			//$back = "./Designs/back.png"; 
  			//echo '<a href="./MyAlbum.php?username='.$_SESSION['username'].'"><img src= '.$back.'></a>';
  			mysql_close($conn);
  			
  			page_footer($_SESSION['firstname'],$_SESSION['lastname'],	$_SESSION['username']);
  		}
  	}
?>


