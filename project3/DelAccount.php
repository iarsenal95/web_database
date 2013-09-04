
<?php
	include "lib.php";
	$title="Account Deleted";
	page_header($title);
	$conn=db_connect();
	session_start();
	
    $username=$_SESSION['username'] ;	
    $username0="'".$username."'";
    
    //delete real files from album belong to this user, shared photo will not be deleted
    $query0= "SELECT temp.url
				FROM (	
						SELECT * 
						FROM (
						SELECT * , COUNT( * ) 
						FROM Contain
						GROUP BY url
						HAVING COUNT( * ) =1
						) AS temp0
					) AS temp
				INNER JOIN Album
				ON Album.albumid=temp.albumid
				WHERE Album.username=$username0"; 
	$result0=mysql_query($query0);
	while($row0=mysql_fetch_array($result0))
	{
		$file=$row0['url'];
		$file0="'".$file."'";
		//delete from database: Photo
		$query1="DELETE FROM Photo WHERE url = $file0";
		mysql_query($query1);
		//delete the real file
		unlink($file);
	}
	
	
	$query="select albumid from Album where username=$username0";
	$result=mysql_query($query);
	$index=0;
	while($row = mysql_fetch_array($result)){
		$id_array[$index]=$row['albumid'];
		
		$index+=1;
	}



	//delete from database: Album, Contain
	$query2="DELETE FROM Album WHERE username = $username0";
	mysql_query($query2);
	$i=0;
	for($i=0;$i<=$index;$i++){
		$query="DELETE FROM Contain WHERE albumid=\"".$id_array[$i]."\"";
		$query1="DELETE FROM AlbumAccess WHERE albumid=\"".$id_array[$i]."\"";
		mysql_query($query);
		mysql_query($query1);
	}
	
	
	//delete from database: AlbumAccess
	$query3="DELETE FROM AlbumAccess WHERE username = $username0";
	mysql_query($query3);
	
	//delete from database: Com : comments
	
	//delete from databas: Users
	$query5="DELETE FROM Users WHERE username = $username0";
	mysql_query($query5);

    echo 'User: ';
    echo $_SESSION['username'] ;
    echo " successfully deleted.<br>";
    session_destroy();
    //echo "delected";	
	mysql_close($conn);
	page_footer();
?>
