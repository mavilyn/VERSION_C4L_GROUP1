$(function()
{
		var username = $( "#username" ),
    	  password = $( "#password" );
     $( "#login_button" ).click(function() {
     	$( "#loginErr" ).fadeOut();
     	if( username.val().length != 0 && password.val().length !=0){
		  	$( "#loginErr" ).fadeOut();
		  	//$( "#loginErr" ).text( "" );
     	}
     	else{
		  	//$( "#loginErr" ).text( "Invalid username or password." );
		  //	$( "#loginErr" ).fadeOut();
		  	$( "#loginErr" ).fadeIn();
			return false;
		}
	});
});