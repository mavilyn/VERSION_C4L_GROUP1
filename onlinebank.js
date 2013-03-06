function checkEnroll(){
	var accountnum=document.forms["enroll_form"]["accountnum"].value;
	var atmpin=document.forms["enroll_form"]["atmpin"].value;
	var fname=document.forms["enroll_form"]["fname"].value;
	var mname=document.forms["enroll_form"]["mname"].value;
	var lname=document.forms["enroll_form"]["lname"].value;
	var male = document.getElementById('male');
   	var female = document.getElementById('female');
	var brgy=document.forms["enroll_form"]["brgy"].value;
	var city=document.forms["enroll_form"]["city"].value;
	var province=document.forms["enroll_form"]["province"].value;
	var month = document.forms["enroll_form"]["month"].value;
	var day = document.forms["enroll_form"]["day"].value;
	var year = document.forms["enroll_form"]["year"].value;
	var email=document.forms["enroll_form"]["email"].value;
	var atpos=email.indexOf("@");
	var dotpos=email.lastIndexOf(".");
	var contact=document.forms["enroll_form"]["contact"].value;
	var spouse=document.forms["enroll_form"]["spouse"].value;
	var mother=document.forms["enroll_form"]["mother"].value;
	var username=document.forms["enroll_form"]["username"].value;
	var password=document.forms["enroll_form"]["password"].value;
	var confirmpassword=document.forms["enroll_form"]["confirmpassword"].value;
	var secret=document.forms["enroll_form"]["secret"].value;
	var answer=document.forms["enroll_form"]["answer"].value;
	var confirmanswer=document.forms["enroll_form"]["confirmanswer"].value;
	var valid = true;	
					//	document.forms["enroll_form"][0].disabled=false;
						if((isNaN(accountnum))||accountnum.length!=10){
							//timeMsg();
							//document.write("Please verify your account number.");
							document.getElementById("accountnumErr").innerHTML = "Please verify your account number.";
							valid = false;
						}
						else{
							document.getElementById("accountnumErr").innerHTML = "";
							//return true;
						}
						
						if((isNaN(atmpin))||atmpin.length!=4){
							document.getElementById("atmpinErr").innerHTML = "Please verify your ATM pin number.";
							valid = false;
						}
						else{
							document.getElementById("atmpinErr").innerHTML = "";
						}
						
						if(fname == "" || checkletter(fname)==false){
							document.getElementById("fnameErr").innerHTML = "Please verify your first name.";
							valid = false;
						}
						
						else{
							document.getElementById("fnameErr").innerHTML = "";
						}
						
						if(mname == "" || checkletter(mname)==false){
							document.getElementById("mnameErr").innerHTML = "Please verify your middle name.";
							valid = false;
						}
						
						else{
							document.getElementById("mnameErr").innerHTML = "";
						}
						
						if(lname == "" || checkletter(lname)==false){
							document.getElementById("lnameErr").innerHTML = "Please verify your last name.";
							valid = false;
						}
						
						else{
							document.getElementById("lnameErr").innerHTML = "";
						}
						
						if(!(male.checked)&&!(female.checked)){
							document.getElementById("genderErr").innerHTML = "Please verify your gender.";
							valid = false;
					    }
						
						else{
							document.getElementById("genderErr").innerHTML = "";
						}
						
					    if(brgy==""||city==""||province==""){
							document.getElementById("homeaddressErr").innerHTML = "Please verify your address.";
							valid = false;
						}
						
						else{
							document.getElementById("homeaddressErr").innerHTML = "";
						}
						
						if(month==""||day==""||year==""){
						    document.getElementById("birthErr").innerHTML = "Please verify your birthday.";
							valid = false;
					     }
						 
						 else{
							document.getElementById("birthErr").innerHTML = "";
						 }
					    
						if(month!=""&&day!=""&&year!=""){
								if(isNaN(month)||isNaN(day)||isNaN(year)){
									document.getElementById("birthErr").innerHTML = "Please verify your birthday.";
									valid = false;
								 }
								 
							else{
								if(month==4||month==6||month==9||month==11){
									if(day>30){			
										//echo "Invalid date! Please enter your correct birth date.";
										document.getElementById("birthErr").innerHTML = "Please verify your birthday.";
										valid = false;
									}
								}
								else{
									document.getElementById("birthErr").innerHTML = "";
								 }
								if(year%4==0&&day>29){
									//echo "Invalid date! Please enter your correct birth date.";
									document.getElementById("birthErr").innerHTML = "Please verify your birthday.";
									valid = false;
								}	
								else{
									document.getElementById("birthErr").innerHTML = "";
								 }
								if(year%4!=0&&day>28){
									document.getElementById("birthErr").innerHTML = "Please verify your birthday.";
									valid = false;		
								}
								else{
									document.getElementById("birthErr").innerHTML = "";
								 }
							}
						}
						
						if (email!="" && (atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length)){
						    document.getElementById("emailErr").innerHTML = "Please verify your email address.";
							valid = false;	
				      	}
						else{
							document.getElementById("emailErr").innerHTML = "";
						 }
						 
						 /*if(contact==""){
							document.getElementById("contactErr").innerHTML = "Please verify your contact number.";
							valid = false;	
						 }
						 
						 else{
							document.getElementById("contactErr").innerHTML = "";
						 }*/
						
						if(mother==""){
							document.getElementById("motherErr").innerHTML = "Please verify your mother's maiden name.";
							valid = false;
						}
						
						else{
							document.getElementById("motherErr").innerHTML = "";
						}
						
						if(username==""){
							document.getElementById("usernameErr").innerHTML = "Please verify your username.";
							valid = false;
						}
						
						else{
							//document.getElementById("usernameErr").innerHTML = "";
							if(checkUsername(username)==false){
								document.getElementById("usernameErr").innerHTML = "Username must not contain spaces.";
								valid = false;
							}
							
							else{
								document.getElementById("usernameErr").innerHTML = "";
							}
						}
				
						if(password==""){
							document.getElementById("passwordErr").innerHTML = "Please verify your password.";
							valid = false;
						}
						
						else{
							if(password.length<4){
								document.getElementById("passwordErr").innerHTML = "Password length should be greater than 4 for your security.";
								valid = false;
							}
							else{
								document.getElementById("passwordErr").innerHTML = "";
								if(confirmpassword!=password){
									document.getElementById("confirmpasswordErr").innerHTML = "Please verify password confirmation.";
									valid = false;
								}
								
								else{
										document.getElementById("confirmpasswordErr").innerHTML = "";
								}
							}
						}
						
						
						if(answer==""){
							document.getElementById("answerErr").innerHTML = "Please add answer to secret question.";
							valid = false;
						}
						
						else{
							if(answer.length<4){
								document.getElementById("answerErr").innerHTML = "Answer length should be greater than 4 for your security.";
								valid = false;
							}
							else{
								if(confirmanswer!=answer){
									document.getElementById("confirmanswerErr").innerHTML = "Please verify answer confirmation.";
									valid = false;
								}
								
								else{
									document.getElementById("confirmanswerErr").innerHTML = "";
								}
								document.getElementById("answerErr").innerHTML = "";
							}
						}
						
						 if(valid == false){
							return false;
						}
						else{
								var confirmation = confirm('Are you sure you want to enroll in Guestbank Online Solutions?');
								if(confirmation == false){
									return false;
								}
						}
						
}

	function checkChangePassword(){
		var currentpassword = document.forms['change_password_form']['currentpassword'].value;
		var newpassword = document.forms['change_password_form']['newpassword'].value;
		var newpasswordconfirm = document.forms['change_password_form']['newpasswordconfirm'].value;
		var valid=true;
		if(currentpassword==""){
			document.getElementById("currentpasswordErr").innerHTML = "Please verify your current password.";
			valid = false;
		}
		
		else{
			document.getElementById("currentpasswordErr").innerHTML = "";
		}
		
		if(newpassword==""){
			document.getElementById("newpasswordErr").innerHTML = "Please verify your new password.";
			valid = false;
		}
						
		else{
			document.getElementById("newpasswordErr").innerHTML = "";
		}
		
		if(newpasswordconfirm!=newpassword){
			document.getElementById("confirmpasswordErr").innerHTML = "Please verify password confirmation.";
			valid = false;
		}
		
		else{
			document.getElementById("confirmpasswordErr").innerHTML = "";
		}
						
		if(valid==false){
			return false;
		}
		else{
			var confirmation = confirm('Are you sure you want to change your password?');
				if(confirmation == false){
					return false;
				}
		}
	}
	
	function checkActivation(){
	
		var accountnum = document.forms["activation_form"]["accountnum"].value;
		var valid = true;
	
		if((isNaN(accountnum))||accountnum.length!=10){
				document.getElementById("accountnumErr").innerHTML = "Please verify your account number.";
				valid = false;
		}
		
		else{
			document.getElementById("accountnumErr").innerHTML = "";
		}
		
		if(valid == false){
				return false;
		}
		/*
						if((isNaN(accountnum))||accountnum.length!=10){
							//timeMsg();
							//document.write("Please verify your account number.");
							document.getElementById("accountnumErr").innerHTML = "Please verify your account number.";
							valid = false;
						}
						else{
							document.getElementById("accountnumErr").innerHTML = "";
							//return true;
						}
						
		}*/
	}

		function check_addAdmin(){
						var valid = true;
						var empid=document.forms["addadmin_form"]["empid"].value;
						var username=document.forms["addadmin_form"]["username"].value;
						var password=document.forms["addadmin_form"]["password"].value;
						var confirmpassword=document.forms["addadmin_form"]["confirmpassword"].value;
						
						if((isNaN(empid))||empid.length<1){
							//timeMsg();
							//document.write("Please verify your account number.");
							document.getElementById("empidErr").innerHTML = "Please verify your account number.";
							valid = false;
						}
						else{
							document.getElementById("empidErr").innerHTML = "";
							//return true;
						}
						if(username==""){
							document.getElementById("usernameErr").innerHTML = "Please verify your username.";
							valid = false;
						}
						
						else{
							//document.getElementById("usernameErr").innerHTML = "";
							if(checkUsername(username)==false){
								document.getElementById("usernameErr").innerHTML = "Username must not contain spaces.";
								valid = false;
							}
							
							else{
								document.getElementById("usernameErr").innerHTML = "";
							}
						}
						
						if(password==""){
							document.getElementById("passwordErr").innerHTML = "Please verify your password.";
							valid = false;
						}
						
						else{
							document.getElementById("passwordErr").innerHTML = "";
						}
						
						if(confirmpassword!=password){
							document.getElementById("confirmpasswordErr").innerHTML = "Please verify password confirmation.";
							valid = false;
						}
						
						else{
							document.getElementById("confirmpasswordErr").innerHTML = "";
						}
						
						if(valid == false){
							return false;
						}
						else{
								var confirmation = confirm('Are you sure you want to add admin to Guestbank Online Solutions?');
								if(confirmation == false){
									return false;
								}
						}
		}
		
		function checkRemove(){
			var confirmRemove = confirm('Are you sure you want to remove administrator account? ');
			if(confirmRemove == true){
			
				return true;
			}
			else{
				alert('Administrator account has been not removed.');
				return false;
			}
		}
	
		function checkletter(field){
			    var counter = 0;
			    var alphabet = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz";
			    for(var i=0; i<field.length; i++){
					for(var j=0; j<54; j++){
						if(field.charAt(i)==alphabet.charAt(j)||field.charAt(i)==" "){
							    counter++;
						}
					}
				}
				if(counter<field.length){
					return false;
				}
		}

		function checkUsername(field){
			    var counter = 0;
			    var alphabet = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz1234567890";
			    for(var i=0; i<field.length; i++){
					for(var j=0; j<62; j++){
						if(field.charAt(i)==alphabet.charAt(j)){
							    counter++;
						}
					}
				}
				if(counter<field.length){
					return false;
				}
		}
		
		function checkLogin(){
			var username=document.forms["login"]["username"].value;
			var password=document.forms["login"]["password"].value;
			if(username==""||password==""){
				alert('Fill username and password fields correctly.');
				return false;
			}
			
		}
		
		function checkAddBiller(){
			var refnum = document.forms["addBiller_form"]["refnum"].value;
			var valid = true;
			if(refnum==""){
					document.getElementById("refNumErr").innerHTML = "Please verify reference number.";
					valid = false;
			}
			else{
					document.getElementById("refNumErr").innerHTML = "";
						if(isNaN(refnum)){
								document.getElementById("refNumErr").innerHTML = "Please enter valid reference number.";
								valid = false;
						}
						else{
								document.getElementById("refNumErr").innerHTML = "";
						}
			}
			if(valid==false){
				return false;
			}
			else{
				var confirmation = confirm('Are you sure you want to add biller to your account?');
								if(confirmation == false){
									return false;
								}
			}
		}
