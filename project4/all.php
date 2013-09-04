<?php 
include "lib.php";
include "server.php";

$title="All images on PhotoClip";
page_header($title);

echo '<div id="search_box">
	<form id="search_form" method="post" action="searchResult.php">
	<input type="text" id="s" name = "words" class="swap_value" />
	<input type="image" src="./Designs/search_btn.gif" width="27" height="24" id="go" alt="Search" title="Search" />
	</form>
	</div>';


//connect DB and do MYSQL
$conn=db_connect();

echo "<br><br>All images on PhotoClip<br><br><table border=\"0\">";
$query = "SELECT * FROM Contain WHERE albumid=5";
$result = mysql_query($query);
$c=0;
while($row = mysql_fetch_array($result))
{
	$url = $row['url'];
	$cap = $row['captain'];
	$seq = $row['sequencenum'];
	//echo "<table border=\"0\">";
	if ($c % 5!=0)
		echo '<td class="result"><a href="./viewpicture.php?seq='.$seq.'" target=_blank><img src= '.$url.' " width="100" height="100" /></a><br>'.$cap.'</td>';		
	else
		echo '<tr><td class="result"><a href="./viewpicture.php?seq='.$seq.'" target=_blank><img src= '.$url.' " width="100" height="100" /></a><br>'.$cap.'</td>';
	$c+=1;
}
echo "</table>";
	


	

page_footer();



	

?>

