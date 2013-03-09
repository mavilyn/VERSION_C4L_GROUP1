<?php
		include("class_lib.php");
  		session_start();
  		if (isset($_POST['Submit'])) {
					//CHECKS IF THE REFNUM ALREADY EXISTS IN BILLER_CUST
					$conn = oci_connect('mainbank', 'kayato1');
					$sql="select COUNT(*) AS NUM_ROWS from biller_cust where refnum ='".$_POST['refnum']."'";
					$stmt = oci_parse($conn, $sql);
					oci_define_by_name($stmt, 'NUM_ROWS', $num_rows);
					oci_execute($stmt);
					oci_fetch($stmt);
					
					//PUTS THE BILLER NAME TO A VARIABLE FOR INSERTION TO ACCOUNT REQUEST
					$sql="select billername from billerlist where blist_accountnum ='".$_POST['billers']."'";
					$stmt = oci_parse($conn, $sql);
					oci_execute($stmt);
					while($row = oci_fetch_array($stmt, OCI_BOTH)){
						$billername = $row[0];
					}
					
					oci_close($conn);
					
					if($num_rows > 0){
					
                        //CHECKS IF THE BILLER REQUEST IS ALREADY IN THE ADDBILLER_REQUEST AND IT IS JUST PENDING				
						$conn = oci_connect('guestbank', 'kayato1');
						$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
						FROM addbiller_request
						WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and billeraccountnum ='.$_POST['billers'].' and refnum ='.$_POST['refnum'].'and appDisFlag IS NULL');
						oci_define_by_name($parsed, 'NUM_ROWS', $numRequest);
						oci_execute($parsed);
						oci_fetch($parsed);
						
						if($numRequest > 0){
							//UNSUCESSFUL
							echo "<script>alert('Unsuccessful! Request is already pending.');</script>";
						}
						else{
							
							//CHECKS IF THE BILLER IS ALREADY IN YOUR ACCOUNT
							$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
								FROM current_biller
								WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and billeraccountnum ='.$_POST['billers'].' and refnum ='.$_POST['refnum']);
							oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
							oci_execute($parsed);
							oci_fetch($parsed);
							
							if($num_rows > 0){
								echo "<script>alert('Biller already connected to your account.');</script>";
							}
							else{
							
								//INSERT THE BILLER REQUEST TO THE ADDBILLER_REQUEST
								$query =  'INSERT into addbiller_request(accountnum, billeraccountnum, billername, refnum, requestDate) values(:accountnum, :billeraccountnum, :billername, :refnum, SYSDATE)';
								$compiled = oci_parse($conn, $query);
								oci_bind_by_name($compiled, ':accountnum', $_SESSION['client']->get_accountnum());
								oci_bind_by_name($compiled, ':billername', $billername);
								oci_bind_by_name($compiled, ':billeraccountnum', $_POST['billers']);
								oci_bind_by_name($compiled, ':refnum', $_POST['refnum']);
								oci_execute($compiled);
							}
						}
						
					}
					else{
						echo "<script>alert('Unsuccessful Request! Reference Number not found.');</script>";
					}
	}
				
?>
<html>
	<head>
		<title>	Add Biller </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username()?>
		<form name = "addBiller_form" method ="post" action = "addBiller.php" onsubmit="return checkAddBiller();">
			Reference Number: <input type = "text" name="refnum" maxlength="11"/><span id="refNumErr" style="color: red"> </span><br/>
			<?php
				$conn = oci_connect("mainbank", "kayato1");
				
				$query = 'select * from billerlist';
				$stid = oci_parse($conn, $query);
				oci_execute($stid, OCI_DEFAULT);
			
					print 'Billername: <select name="billers">';
						while ($row = oci_fetch_array($stid, OCI_BOTH)) {
							$billeraccountnum=$row[0];
							$billername=$row[1];
							echo $billeraccountnum;
							print'<option value="'.$billeraccountnum.'">'.$billername; echo'</option>';
						}
					print '</select><br/>';
			
			
			?>
			<input type="submit" name="Submit" value="Submit" />	
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
