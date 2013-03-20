function checkManageBiller(){
			var confirmation = confirm('Please confirm mananagement of biller requests.');
			if(confirmation == false){
					return false
			}
	}

function checkManageAccount(){
			var confirmation = confirm('Please confirm mananagement of account connection requests.');
			if(confirmation == false){
					return false
			}
}

function checkActivate(){
		var accountnum = document.forms['activation_form']['accountnum'].value;
		var valid=true;

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
						

		if(valid==false){
			return false;
		}
		else{
			var confirmation = confirm('Are you sure you want to activate or deactivate accountnumber '+accountnum+'?');
				if(confirmation == false){
					return false;
				}
		}	
	}	