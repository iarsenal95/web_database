<?php


function page_header($title){
$logo = "./Designs/LOGO_large.png";

	echo "<HTML>
<HEAD>   
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<meta name='viewport' content='width=1024' />
<meta name='description' content='This is an online album'/>


<TITLE>".$title."</TITLE>
</head>
<BODY>
<FONT SIZE=9>
<FONT COLOR=#8c972e>
<body link=#8c972e vlink=#50580b alink=#cad397>
<center>

<p>".'<a href="./index.php" ><img src= '.$logo.' ></a>'."<p>


</SIZE>
<FONT SIZE=5>";

//echo '<img src='.$logo.'alt="PhotoClip" align="center">';
//<img src="./Designs/LOGO large.png" alt="PhotoClip" align="center">
//<td><a href="链接1地址" target=_blank><img scr="图片1地址" /></a></td>

}
function db_connect(){
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
   return $conn;
}
function page_footer(){
	echo '<hr />';
	echo '<p align="center"> <small><i>Copyright@group23,2013</i></small></p>';
	echo "</center></SIZE></BODY></HTML>";
}
?>
