    <?php
    	
    	include "lib.php";
    	$title="New User";
    	page_header_valid($title);
    	$conn=db_connect();
    	if(IsSet($_GET['error'])){
		echo $_GET['error'];
		}
    
    	echo "<form name='input' action='registerCheck.php' onsubmit='return validateForm()'; method='post'>
	Username: <input type='text' name='username'><br>
	Password: <input type='password' name='password'><br>
	Re-enter Password: <input type='password' name='password_check'><br>
	First Name: <input type='text' name='firstname'><br>
	Last Name: <input type='text' name='lastname'><br>
	Your Email: <input type='text' name='email'><br>
	<input type='submit' value='Submit'>
	</form>	";
	
	page_footer();
	?>
	