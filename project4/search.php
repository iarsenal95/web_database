<?php
include 'server.php';
$logo = "./Designs/LOGO_small.png";
echo "<!DOCTYPE html><HTML>
		<HEAD>   
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<meta name='viewport' content='width=1024' />
		<meta name='description' content='This is an online album'/>
		
		
		<TITLE>search</TITLE>
		</head>
		<BODY>
		
		<FONT COLOR=#8c972e>
		<body link=#8c972e vlink=#50580b alink=#cad397>
		<center>
		
		<p>".'<a href="./index.php" ><img src= '.$logo.' ></a>'."<p>
		</SIZE>
		<FONT SIZE=3>";
	/*
echo '<div style="position:relative; width:1024px; margin:0 auto;">
		<div id="frame">';
*/
		
	
	echo "<form action='search.php' method='post'><input type='text' name='query'><input type=submit value=search>";	
	
	
	if(isset($_POST['query'])){
		$query_words=$_POST['query'];
		$portnumber=8888;
		$myResults = queryIndex($portnumber, "localhost", $query_words);
		var_dump($myResults) ;
	}
		
	echo '<hr />';
	echo '<p align="center"> <small><i>Copyright@group23,2013</i></small></p>';
	echo "</center></SIZE></BODY></HTML>";

?>