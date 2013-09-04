<?php 
include "lib.php";
include "server.php";

$title="Search Result";
page_header($title);

echo '<div id="search_box">
	<form id="search_form" method="post" action="searchResult.php">
	<input type="text" id="s" name = "words" class="swap_value" />
	<input type="image" src="./Designs/search_btn.gif" width="27" height="24" id="go" alt="Search" title="Search" />
	</form>
	</div>';
	
$words = $_POST["words"];
$hits = queryIndex(2000, 'localhost', $words);

//var_dump($hits);
//echo "<br>";

function my_sort($a, $b)
{
    if ($a["score"] > $b["score"]) {
        return -1;
    } else if ($a["score"] < $b["score"]) {
        return 1;
    } else {
        return 0;
    }
}
usort($hits, 'my_sort');
/*
echo "<br>";
var_dump($hits);
echo "<br>";
*/

/*
foreach($hits as $hit){
     // echo  var_dump($hit)."<br>";
     echo $hit["score"]."<br>";
}
*/

//connect DB and do MYSQL
$conn=db_connect();
$count = count($hits);
echo '<br>'.$count.' results for "'.$words.'" are returned. <br><br>';

echo "<table border=\"0\">";
$c=0;
foreach($hits as $hit)
{

	$id = $hit["id"];
	$query = "SELECT * FROM Contain WHERE albumid=5 AND sequencenum = ".$id;
	//echo $query."<br>";
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
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

