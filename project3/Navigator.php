 <?php


function page_header($title){


	echo "<HTML>
		<HEAD>   
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<meta name='viewport' content='width=1024' />
		<meta name='description' content='This is an online album'/>
		
		
		<TITLE>".$title."</TITLE>
		
		
		<link rel='stylesheet' href='pa3.css' type='text/css'>
		
		<script type='text/javascript' src='view.js'></script>
		<script type='text/javascript' src='drag_4.js'></script>

		
		</head>
		<BODY>
		
		<FONT COLOR=#8c972e>
		<body link=#8c972e vlink=#50580b alink=#cad397>
		<center>
		
		
		
		
		</SIZE>
		<FONT SIZE=3>";


	echo '<div style="position:relative; width:1024px; margin:0 auto;">
		<div id="frame">
		<!--Divide by 2-->
		<table width="1024" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="900" valign="top">
		    <center>
		
		
		<!--Left part start-->
		<div id="main">
		<font style="font-size:20px;">
		<FONT COLOR=#8c972e>
		<body link=#8c972e vlink=#50580b alink=#cad397>
		<br><br>';



}

function page_header_valid($title){
	echo "<HTML>
		<HEAD>   
		<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
		<meta name='viewport' content='width=1024' />
		<meta name='description' content='This is an online album'/>
		
		<link rel='stylesheet' href='pa3.css' type='text/css'>
		<script type='text/JavaScript' src='validateForm.js'></script> 
		<script type='text/javascript' src='view.js'></script>
		<script type='text/javascript' src='drag_4.js'></script>

		
		<TITLE>".$title."</TITLE>
		</head>
		<BODY>
		
		<FONT COLOR=#8c972e>
		<body link=#8c972e vlink=#50580b alink=#cad397>
		<center>
		
		
		
		
		</SIZE>
		<FONT SIZE=3>";


	echo '<div style="position:relative; width:1024px; margin:0 auto;">
		<div id="frame">
		<!--Divide by 2-->
		<table width="1024" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="900" valign="top">
		    <center>
		
		
		<!--Left part start-->
		<div id="main">
		<font style="font-size:20px;"><br><br>';

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
function page_footer($firstname,$lastname,$username){
	
	$logo = "./Designs/LOGO_small.png";		
	echo '</div>
		<!--Left part end-->
		</td>
		    
		    
		<td width="124" valign="top">
		<FONT COLOR=#8c972e>
		<font style="font-size:16px;">
		<!--right part start-->
		<p style="text-align:left">
		
		<p><a href="./Home.php" ><img src= '.$logo.' ></a><p>
		
		<div class="menu_title" style="text-align:left">Quick Navigator</div>
		<p style="text-align:left"></p>
		
		<p><font style="font-size:12px;">Logged in as <i>'.$firstname.' '.$lastname.'.</i></font></p>
		
		<div id="linklist"> 
		<ul>
				            <li ><a href="./Home.php">Home</a></li>
				            <li ><a href="./EditAccount.php">Edit Account</a></li>
				            <li ><a href="./MyAlbum.php?username='.$username.'">My Album</a></li>
				            <li ><a href="./Logout.php">Logout</a></li>
		</ul> 
		</div> 
		</div>
		<!--right part end-->
		   </td>
		  </tr>
		</table>
		<!-- 2 part end-->
		</div>
		</div>';
			
	echo '<hr />';
	echo '<p align="center"> <small><i>Copyright@group23,2013</i></small></p>';
	echo "</center></SIZE></BODY></HTML>";
}
?>
