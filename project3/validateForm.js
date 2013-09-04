
function check(input){	
  return /^[a-zA-Z0-9_]+$/i.test(input);
}

function validateForm(){
	var username = document.forms["input"]["username"].value; 
	if(check(username) == false || username.length < 3)
	{	
		alert("Username must be at least 3 characters long and can only have letters, digits and underscores!");
		return false;
	}
	var password = document.forms["input"]["password"].value;
	var password_check =document.forms["input"]["password_check"].value;
	if (password.length <5 || password.length > 15)
	{
		alert("Password should be at least 5 and at most 15 characters long!")
		return false;
	}
	if (password != password_check)
	{
		alert("Your password do not match. Please try again.")
		return false;
	}
	
	var firstname = document.forms["input"]["firstname"].value; 
	if(check(firstname) == false || firstname.length < 1)
	{	
		alert("Please input your first name!");
		return false;
	}
	var lastname = document.forms["input"]["lastname"].value; 
	if(check(lastname) == false || lastname.length < 1)
	{	
		alert("Please input your last name!");
		return false;
	}
	
	
	
	var email = document.forms["input"]["email"].value;
	var atpos = email.indexOf("@");
	var dotpos= email.lastIndexOf(".");
	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=emailtemp.length)
  	{
  		alert("Not a valid e-mail address");
  		return false;
  	}
}

function validateReset(){

	
		var username = document.forms["input"]["username"].value; 
	if(check(username) == false || username.length < 3)
	{	
		alert("Username must be at least 3 characters long and can only have letters, digits and underscores!");
		return false;
	}
		var email = document.forms["input"]["email"].value;
	var atpos = email.indexOf("@");
	var dotpos= email.lastIndexOf(".");
	if (atpos<1 || dotpos<atpos+2 || dotpos+2>=emailtemp.length)
  	{
  		alert("Not a valid e-mail address");
  		return false;
  	}

  }
function validEmail(){

	
		var email = document.forms["input"]["email"].value;
		var atpos = email.indexOf("@");
		var dotpos= email.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=emailtemp.length)
		{
  			alert("Not a valid e-mail address");
  			return false;
  		}
  	}
  	
function validPassword(){
	    var password = document.forms["input2"]["password"].value;
		var password_check =document.forms["input2"]["password_check"].value;
	
	
		if (password.length <5 || password.length > 15)
		{
			alert("Password should be at least 5 and at most 15 characters long!")
			return false;
		}
		if (password != password_check)
		{
			alert("Your password do not match. Please try again.")
			return false;
		}
}







