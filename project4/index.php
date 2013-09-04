<?php 
include "lib.php";
$title="PhotoClip";
page_header($title);

echo 'Search for images on PhotoClip :)<br><br><br>';

echo '<div id="search_box">
	<form id="search_form" method="post" action="searchResult.php">
	<input type="text" id="s" name = "words" class="swap_value" />
	<input type="image" src="./Designs/search_btn.gif" width="27" height="24" id="go" alt="Search" title="Search" />
	</form>
	</div>';

echo '<br><br><a href="./all.php">Show all images</a><br><br>';
page_footer();
?>

