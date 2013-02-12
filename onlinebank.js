function checkAddBiller(){
  var valid = true;
	var refnum = document.forms["addBiller_form"]["refnum"].value;
	
	if(refnum == ""){
		document.getElementById("refNumErr").innerHTML = "Please fill in reference number";
		valid = false
	}
	else if((isNaN(refnum))){
		document.getElementById("refNumErr").innerHTML = "Please enter a valid reference number.";
		valid = false;
	}
	else{
		document.getElementById("refNumErr").innerHTML = "";
	}
	if(valid == false){
		return false;
	}
}
