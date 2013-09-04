<?PHP	
include "lib.php";
$title="Reset Password";
page_header_valid($title);
$conn=db_connect();

if(IsSet($_POST['email'])){
$query="select * from Users where username=\"".$_POST['username']."\"";
$result = mysql_query($query);
$count = mysql_num_rows($result);
if($count==1){


$to =$_POST['email'] ;
$subject =	 'Reset your PhotoClip password';
$bound_text =	"Group23";
$bound =	"--".$bound_text."\r\n";
$bound_last =	"--".$bound_text."--\r\n";
 	 
$headers =	"From: eecs485.group23@umich.edu\r\n";
$headers .=	"MIME-Version: 1.0\r\n"
 	."Content-Type: multipart/mixed; boundary=\"$bound_text\"";
 	 
$message .=	"If you can see this MIME than your client doesn't accept MIME types!\r\n"
 	.$bound;
 	 
$message .=	"Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
 	."Content-Transfer-Encoding: 7bit\r\n\r\n"
 	."Hi ".$_POST['username'].":<br>"
 	."This email is send by PhotoClip by <b>Group23</b>\r\n<br>"
 	
 	."This is your new password: <b>123456</b>\r\n"
 	.$bound;
 	
//$url =  $_GET['url'] ;
//$url2 = '"'.$url.'"';
//echo $url2;	 
//$file =	file_get_contents($url);
// $file = file_get_contents("./images/sports_s3.jpg");	 
/*$message .=	"Content-Type: image/jpg; name=\"image\"\r\n"
 	."Content-Transfer-Encoding: base64\r\n"
 	."Content-disposition: attachment; file=\"image\"\r\n"
 	."\r\n"
 	.chunk_split(base64_encode($file))
 	.$bound_last;*/
 	
mail($to, $subject, $message, $headers);

 

     echo 'MAIL SENT'; }
 else{
 	echo "this user doesn't exist!<br>";
 	echo "<a href='./mailpwd.php'>Back</a>";
 	}
} 

else {
echo "<form name='input' action='mailpwd.php' onsubmit='return validateReset()'; method='post'>
Username: <input type='text' name='username'>
Your Email: <input type='text' name='email'>

<input type='submit' value='Submit'></form>";}

page_footer();
//echo $_GET['url'];
 
?>
