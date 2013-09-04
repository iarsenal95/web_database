<?php
	include "lib.php";
	include "server.php";
	$title="View Picture";
	page_header($title);
	db_connect();
	
	$seq=$_GET['seq'];
	$query="select url,captain from Contain where sequencenum=$seq";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	$url=$row['url'];
	$caption=$row['captain'];
	echo "<img src=".$url."><br>";
	echo $caption;
	echo "<div id='similar'><br><br>similar images:<br></div>";
	
	$myResults = queryIndex(2000, "localhost", $caption);
	
	function my_sort($a, $b)
	{
    	if ($a["score"] > $b["score"]) {
        	return -1;
        } 
    	else if ($a["score"] < $b["score"]) {
        	return 1;
        } 
        else {
        	return 0;
        }        
    }
    usort($myResults, 'my_sort');
    
	//var_dump($myResults);
	$size=count($myResults);
	$c=0;
	echo "<table border=\"0\">";
	for($i=0;$i<$size;$i++){
		$snum=$myResults[$i]['id'];
		$sql="select url,captain,sequencenum from Contain where sequencenum=$snum and albumid=5";
		$result=mysql_query($sql);
		$row=mysql_fetch_array($result);
		$u=$row['url'];
		$caption=$row['captain'];
		$sequencenum=$row['sequencenum'];
		//echo $row['url'];
		if($u!=$url){
			if ($c % 5!=0)
				echo '<td class="result"><a href="./viewpicture.php?seq='.$sequencenum.'"><img src= '.$u.' " width="100" height="100" /></a><br>'.$caption.'</td>';
			else
				echo '<tr><td class="result"><a href="./viewpicture.php?seq='.$sequencenum.'"><img src= '.$u.' " width="100" height="100" /></a><br>'.$caption.'</td>';
		$c+=1;
		}
			
	
	}
	echo "</table>";
	mysql_close($conn);
	page_footer();
?>

