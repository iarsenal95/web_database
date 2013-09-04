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
		else
		{
			include "Navigator.php";
			$title="Edit Album";
			page_header($title);
			$conn=db_connect();
			$query="SELECT * FROM Album WHERE albumid=\"".$id. "\"";
                        $result=mysql_query($query);
                        $row = mysql_fetch_array($result);
                        $user = $row['username'];
	
			//change album title
			if (IsSet($_POST["title"])){
				$id = $_POST['albumid'];
				$query="UPDATE Album SET title=\"".$_POST['title']. "\"
				WHERE albumid=\"".$id. "\"";
				mysql_query($query);
			}


			//change the access type
			else if(IsSet($_POST["access_type"])){
				$id = $_POST['albumid'];
				$query="UPDATE Album SET access_type=\"".$_POST['access_type']. "\"
				WHERE albumid=\"".$id. "\"";
				mysql_query($query);
				echo "This album's access type has been changed to 
				".$_POST['access_type']."!<br>";
			}

			//share album with other users
			else if(IsSet($_POST["share_users"])){
				$id = $_POST['albumid'];
				$query="INSERT INTO AlbumAccess 
				VALUES (".$id.",'".	$_POST['share_users']."')";
				mysql_query($query);
	
			}
			
			else if(IsSet($_POST["cancle_access"])){
	
				$id=$_POST['albumid'];
				$query="Delete from AlbumAccess where username=\"".$_POST['cancle_access']
				. "\" and albumid=$id";
	
				mysql_query($query);
	
			}



			//upload a new image
			else if(IsSet($_FILES["file"])){
				$id = $_POST['albumid'];
				$result=mysql_query("select max(sequencenum) from 
				Contain where albumid=\"".$id. "\""); 
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
					$type=ltrim($_FILES["file"]["type"],'image/');
					move_uploaded_file($tmp_name,$dir.$actual_name); 
  
					$query1="insert into Photo (url,format,date_taken) 
					values('".$dir.$actual_name."','".$type."','".date("Y-m-d")."')";
					mysql_query($query1);
  
					$query="INSERT INTO Contain (albumid,url,captain,sequencenum)
					VALUES (".$id.",'".$dir.$actual_name."','".$actual_name."',".$new_sn.")";
					//echo $query ;
					mysql_query($query);
				}
			}		
	
			//delete one or more images from this album
                        else if (IsSet($_POST["url"])){
				$id = $_POST['albumid'];
                                $url=$_POST['url'];
                                for ($i=0; $i<count($url); $i++) {
                                        //delete the real file
                                        $query="SELECT url FROM Contain WHERE 
                                        url=\"".$url[$i]
                                        ."\" Having COUNT(url)=1";
                                        $result=mysql_query($query);
                                        $row=mysql_fetch_array($result);
                                        $file=$row['url'];
                                        unlink($file);
                                        $file0="'".$file."'";
                                        //delte from database: Photo, only delete the photos that contained by one album
                                        $query="DELETE FROM Photo WHERE url=$file0";
                                        //delte from database: Contain
                                        mysql_query($query);
                                        $query="DELETE FROM Contain WHERE url=\""
                                        .$url[$i]."\" AND albumid=$id";
                                        mysql_query($query);
                                }
                        }
                        else $id=$_GET['albumid'];
			$query="SELECT * FROM Album WHERE albumid=\"".$id. "\"";
                        $result=mysql_query($query);
                        $row = mysql_fetch_array($result);
                        $user = $row['username'];
			$flag = 0;
			if (!$_SESSION['isRootUser']){
				$query1="SELECT * FROM AlbumAccess WHERE username=\"".$user."\"";
				$result1 = mysql_query($query1);	
				while($row1 = mysql_fetch_array($result1))
				{
					if($row1['albumid']==$id){
						$flag = 1;
						break;
					}
				}
			}
			if ($_SESSION['isRootUser']||$flag == 1){

					echo "Change the album name<br>";
					echo "<form action='./EditAlbum.php' method='post'>
					<input TYPE=HIDDEN name='albumid' value=".$id.">
					newname:<input type='text' name='title'><br>
					<input type='submit' value='Submit'><br></form>";

					echo "Change the access<br>";
					echo "<form action='./EditAlbum.php' method='post'>
					<input TYPE=HIDDEN name='albumid' value=".$id.">
					<input type='radio' name='access_type' value='public'>Public<br>
					<input type='radio' name='access_type' value='private'>Private<br>
					<input type='submit' value='Submit'><br></form><br><br>";
					$query="SELECT access_type FROM Album WHERE albumid=\"".$id. "\"";
					$result=mysql_query($query);
					$row = mysql_fetch_array($result);

					if($row['access_type']=='private'){
						echo "Give access to:<br>";
						$query="select username from Users";
						$result=mysql_query($query);
						echo "<form action='./EditAlbum.php' method='post'>
						<input TYPE=HIDDEN name='albumid' value=".$id.">
						<select name='share_users'>";
						while($row = mysql_fetch_array($result)){
							if ($row['username']!=$user){
								echo "<option value=".$row['username'].
								">".$row['username']."</option>";
							}
						}
	
						echo "</select><input type='submit' 
						name='submit' value='Submit'><br></form>";
						
						echo "Cancle access:";
						$query="select username from AlbumAccess where albumid=\"".$id. "\"";
						$result=mysql_query($query);
						echo "<form action='./EditAlbum.php' method='post'>
						<input TYPE=HIDDEN name='albumid' value=".$id.">
						<select name='cancle_access'>";
						while($row = mysql_fetch_array($result)){
							if ($row['username']!=$_SESSION['username']){
								echo "<option value=".$row['username'].
								">".$row['username']."</option>";
							}
						}
	
						echo "</select><input type='submit' 
						name='submit' value='Submit'><br></form>";

					}

					echo "Add New Picture<br>";
					echo "<form action='./EditAlbum.php' method='post'
					enctype='multipart/form-data'>
					<label for='file'>Filename:</label>
					<input TYPE=HIDDEN name='albumid' value=".$id.">
					<input type='file' name='file' id='file'><br>
					<input type='submit' name='submit' value='Submit'><br></form><br>";

					echo "Delete pictures<br>";
					echo "<table border=\"0\">";
					echo "<form action = './EditAlbum.php' method = 'post'>
					<input TYPE=HIDDEN name='albumid' value=".$id.">";
					$c=0;
					$query="SELECT * FROM Contain WHERE albumid=\"".$id.
					 "\"order by sequencenum";
					 $result=mysql_query($query);
					 while($row = mysql_fetch_array($result)){
						 if ($c % 5!=0) echo "<td>";
						 else echo "<tr><td>";

						 echo "<input type='checkbox' name='url[]' 
						 value =".$row['url'].">".'
						 <a href="./viewpicture.php?url='.$row['url'].
						 '&albumid='.$id.'" target=_blank>'.
						 "<img src=".$row['url'].' " width="100" height="100"/>'.
						 "<br>".$row['captain']."</td>";
						 $c+=1;
					}

					//submit
					echo "</table>";
					echo "<input type='submit' value='submit'></form><br>";

					//show "BACK"
					echo '<a href="./MyAlbum.php?username='.$user.'">BACK</a>';
			
	
		}
		if($flag==0){
			if($id==null)
				echo "which album?";
			else
				echo "You don't have access to this album!";
				
	
		}

		mysql_close($conn);
		page_footer($_SESSION['firstname'],$_SESSION['lastname'],$_SESSION['username']);
	}
}
?>

