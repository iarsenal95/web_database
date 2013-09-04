<?php
	include "lib.php";
	$title="View Album";
	page_header($title);
	db_connect();
// show captain

	
	//$query="SELECT * FROM Contain WHERE albumid=3";
	$query="SELECT * FROM Contain WHERE albumid=\"".$_GET['albumid']."\"order by sequencenum";

	$result = mysql_query($query);
	
	echo "<table border=\"0\">";
	//
	$c=0;
	while($row = mysql_fetch_array($result))
	{
		$u=$row['url'];
		$seq=$row['sequencenum'];
		$aid=$row['albumid'];
		//echo $seq;
		if ($c % 5!=0)
			echo '<td>'.'<a href="./viewpicture.php?url='.$u.'&albumid='.$aid.'" target=_blank><img src= '.$u.' " width="100" height="100" />'.'</a></td>';
			//<td><a href="链接1地址" target=_blank><img scr="图片1地址" /></a></td>
			//echo '<a href="./viewalbum.php?albumid='.$c.'">'.$row['title'].'</a>';
		else
			echo '<tr><td>'.'<a href="./viewpicture.php?url='.$u.'&albumid='.$aid.'" target=_blank><img src= '.$u.' " width="100" height="100" />'.'</a></td>';
		$c+=1;
	}
	echo "</table>";
	
	//not solved here. easier ways?
	$query2="SELECT * FROM Album WHERE albumid=\"".$_GET['albumid']."\"";
	$result2=mysql_query($query2);
	$row2 = mysql_fetch_array($result2);
	$user = $row2['username'];
	//echo $user;
        $back = "./Designs/back.png";
	echo '<a href="./viewalbumlist.php?username='.$user.'"><img src= '.$back.'></a>';
	//<a href="http://www.w3schools.com/">Visit W3Schools</a>
	//?username=sportslover
	
	//echo "<form action = 'EditAlbum.php' method = 'post'>
	//<input type='submit' value='submit'></form>";
	mysql_close($conn);
	page_footer();
?>


