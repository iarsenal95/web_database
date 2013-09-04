<html>
<body>



<?PHP	

if(IsSet($_GET['user'])){
$to =$_GET['user'] ;
$subject =	 'Photo from PhotoClip Attached';
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
 	."This photo is send by PhotoClip by <b>Group23</b>\r\n"
 	.$bound;
 	
$url =  $_GET['url'] ;
//$url2 = '"'.$url.'"';
//echo $url2;	 
$file =	file_get_contents($url);
// $file = file_get_contents("./images/sports_s3.jpg");	 
$message .=	"Content-Type: image/jpg; name=\"image\"\r\n"
 	."Content-Transfer-Encoding: base64\r\n"
 	."Content-disposition: attachment; file=\"image\"\r\n"
 	."\r\n"
 	.chunk_split(base64_encode($file))
 	.$bound_last;
 	
mail($to, $subject, $message, $headers);

 

     echo 'MAIL SENT'; 
} 

else {
echo "<form name='input' action='./mail.php' method='get'>
Email this photo to: <input type='text' name='user'>
<input TYPE=HIDDEN name='url' value=".$_GET['url'].">
<input type='submit' value='Submit'></form>";}

//echo $_GET['url'];
 
?>
