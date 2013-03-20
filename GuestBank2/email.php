<?php

	require("phpmailer/class.phpmailer.php");
	$mail = new PHPMailer();
	$admin ='guestbankobs@gmail.com';	 
	$passwd = "kayangkayato1";
		
	$mail->Username =  $admin; //gmail gamit na account
	$mail->Password = $passwd; 

    $mail->Host = "ssl://smtp.gmail.com"; // eto pupuntahan..
    $mail->Port = 465;
    $mail->IsSMTP(); // use SMTP
    $mail->SMTPAuth = true; // turn on SMTP authentication
    $mail->Mailer = "smtp";
    $mail->From = $mail->Username;
    $mail->isHTML(true);
	
	//$mail->SMTPDebug = 1;
	function sendEmail($username, $subject, $body){
		global $mail,$admin;
		
	
			$mail->AddAddress('guestbankobs@gmail.com'); // recipients email
    		$mail->FromName = $username; // readable name
    		$mail->Subject = $subject;
    		$mail->Body    = $body; 
			
			
	if(!$mail->Send())
		echo "Mailer Error: " . $mail->ErrorInfo . "<br>";
    else
        echo "Message has been sent<br>";
		}
	
?>