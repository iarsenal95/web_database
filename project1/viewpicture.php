<?php
	include "lib.php";
	$title="View Picture";
	page_header($title);
	db_connect();
	
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
	if ($target==$index-1){echo '<br>';}//echo "next<br>";
	else {
		$next=$seq_array[$target+1];
		echo '<a href="./viewpicture.php?sequencenum='.$next.'&albumid='.$aid.'"><img src= '.$nextpic.' ></a><br>';
	}
	echo '<a href="./viewalbum.php?albumid='.$aid.'"><img src= '.$back.' ></a><br>';
	
	echo "comments:<br>";
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
	page_footer();
?>


