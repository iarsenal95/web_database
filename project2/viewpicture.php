<?php
	if (!isSet($_GET['albumid'])){
		 header("Location:index.php");
	}
	session_start();
	//include "time.php";
	$_SESSION['albumid']=$_GET['albumid'];
	
	//$conn=db_connect();
	
	//function db_connect(){
    $mysql_server_name ="localhost";
    $mysql_username    ="group23";
    $mysql_password    ="ccl";
    $mysql_database    ="group23";
   
   $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
   if (!$conn)
  {
  die('Could not connect: ' . mysql_error());
  }
   mysql_select_db($mysql_database,$conn);
   //return $conn;
//}
	
	
	$query="select access_type from Album where albumid=\"".$_SESSION['albumid']."\"";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	
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
			if($row['access_type']=='public')
				include "lib.php";
			else{
			session_unset();
			$error="Time out!Please login again.";
			header("Location:index.php?error=".$error);
			exit;
			}
		}
		else
		
			include "Navigator.php";
	}
	else{
		if($row['access_type']=='private'){
			$error="visitor cannot see this picture";
			
			header("Location:index.php?error=".$error);
		}
		else
			include "lib.php";
		
	}

	$title="View Picture";
	page_header($title);
	//$conn=db_connect();
	
	if($_GET['sequencenum']){
		
		$snum=$_GET['sequencenum'];
		$aid=$_GET['albumid'];
		$query="SELECT url FROM Contain WHERE sequencenum=\"".$snum."\"and 
		albumid=\"".$aid."\"";
		$result = mysql_query($query);
		$row = mysql_fetch_array($result);
		$url=$row['url'];
		//echo "<img src=".$row['url']."><br>";
		
	}
	else if(IsSet($_POST['comments'])){
		//echo "dd";
		$com=$_POST['comments'];
		$name=$_POST['username'];
		$url=$_POST['url'];
		$snum=$_POST['sequencenum'];
		$aid=$_POST['albumid'];
		
		$floors=$_POST['floors'];
		//echo $floors;
		//$query="insert into Com values()";
		$query="insert into Com values('".$name."','".$url."','".$com."',".$floors.")";
		//echo $query;
		mysql_query($query);
	}
	else {
		$url=$_GET['url'];
		$aid=$_GET['albumid'];
		$query="select sequencenum from Contain where url=\"".$url."\"";
	    $result=mysql_query($query);
	    $row = mysql_fetch_array($result);
	    $snum=$row['sequencenum'];
	}
	
	echo "<img src=".$url."><br>";
	
	$query="select captain from Contain where url=\"".$url."\"";
	$result=mysql_query($query);
	$row = mysql_fetch_array($result);
	echo "Caption:";
	echo $row['captain'];
	echo "<br>";
	
	
	
	
	
	$query1="select sequencenum from Contain where albumid=\"".$aid."\" 
	order by sequencenum";
	$result=mysql_query($query1);
	$index=0;
	while($row = mysql_fetch_array($result)){
		$seq_array[$index]=$row['sequencenum'];
		if ($row['sequencenum']==$snum)$target=$index;
		$index+=1;
		
		
	}
	$prepic = "./Designs/prev.png";
	$nextpic = "./Designs/next.png";
	$back = "./Designs/back.png";
	if ($target==0){}//echo "This is first photo<br>";
	else {
		$pre=$seq_array[$target-1];
		echo '<a href="./viewpicture.php?sequencenum='.$pre.'&albumid='.$aid.'"><img src= '.$prepic.' ></a>';
		//<p>".'<a href="./index.php" ><img src= '.$logo.' ></a>'."<p>
	}
	if ($target==$index-1){}//echo "next<br>";
	else {
		$next=$seq_array[$target+1];
		echo '<a href="./viewpicture.php?sequencenum='.$next.'&albumid='.$aid.'"><img src= '.$nextpic.' ></a>';
	}
	//echo '<br><a href="./viewalbum.php?albumid='.$aid.'"><img src= '.$back.' ></a><br>';
	
	echo "<br>comments:<br>";
	$query="select * from Com where url=\"".$url."\"";
	$result=mysql_query($query);
	$i=1;
	while($row = mysql_fetch_array($result)){
		echo $row['username'].":";
		echo $row['comments']."<br>";
		$i+=1;
	}
	//echo $i;
	echo "<form name='input' action='./viewpicture.php' method='post'>
	<input TYPE=HIDDEN name='sequencenum' value=".$snum.">
	<input TYPE=HIDDEN name='albumid' value=".$aid.">
	<input TYPE=HIDDEN name='url' value=".$url.">
	<input TYPE=HIDDEN name='floors' value=".$i.">
	Username: <input  type='text' name='username'><br>
    Comments: <textarea name='comments' col=35 rows=3></textarea><br>
    <input type='submit' value='Submit'><br></form>";
    
    echo '<a href="./mail.php?url='.$url.'" target=_blank>Email this picture</a><br>';
    
	mysql_close($conn);
	if(IsSet($_SESSION['isLoggedIn'])){
		//echo '<a href="./LoggedInHome.php">BACK</a>';
		page_footer($_SESSION['firstname'],$_SESSION['lastname'],$_SESSION['username']);
	}
	else{
		//echo '<a href="./index.php">BACK</a>';
		page_footer();
	}
?>


