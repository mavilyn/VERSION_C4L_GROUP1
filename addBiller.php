<?php
		include("class_lib.php");
  		session_start();
  		if (isset($_POST['Submit'])) {
					$conn = oci_connect('guestbank', 'kayato1');
					
					$sql="select * from billerlist where accountnum ='".$_POST['billers']."'";

						$stmt = oci_parse($conn, $sql);
						oci_execute($stmt, OCI_DEFAULT);

						while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
							$billername=$row[1];
						}
					/*check if request is pending*/
					$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
						FROM addbiller_request
						WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and billeraccountnum ='.$_POST['billers'].' and refnum ='.$_POST['refnum'].'and appDisFlag IS NULL');
					oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
					oci_execute($parsed);
					oci_fetch($parsed);
					if($num_rows > 0){
						echo "<script>alert('Unsuccessful! Request is already pending.');</script>";
					}
					else{
							//check wheter biller is already connected to your account
							$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
								FROM biller
								WHERE accountnum = '.$_SESSION['client']->get_accountnum().'and billeraccountnum ='.$_POST['billers'].' and refnum ='.$_POST['refnum']);
							oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
							oci_execute($parsed);
							oci_fetch($parsed);
							if($num_rows > 0){
								echo "<script>alert('Biller already connected to your account.');</script>";
							}
							else{
									//$connMain = oci_connect("mainbank","kayato1");	
										//$conn = oci_connect('guestbank', 'kayato1');			
										/*$query =  'SELECT accountnum FROM billerlist where billername = :billername';
										$compiled = oci_parse($conn, $query);
										oci_bind_by_name($compiled, ':billername', $billername);
										oci_execute($compiled);
										$result = oci_fetch_array($compiled, OCI_RETURN_NULLS+OCI_ASSOC);
											foreach ($result as $num){*/
												$query =  'INSERT into addbiller_request(accountnum, billeraccountnum, billername, refnum, requestDate) values(:accountnum, :billeraccountnum, :billername, :refnum, SYSDATE)';
												$compiled = oci_parse($conn, $query);
												oci_bind_by_name($compiled, ':accountnum', $_SESSION['client']->get_accountnum());
												oci_bind_by_name($compiled, ':billername', $billername);
												oci_bind_by_name($compiled, ':billeraccountnum', $_POST['billers']);
												oci_bind_by_name($compiled, ':refnum', $_POST['refnum']);
												oci_execute($compiled);
										//	}
										echo "<script>alert('Biller successfully requested to get connected to your account.');</script>";
							}
					}
		}
			/*add biller for customer*/
				
?>
<html>
	<head>
		<title>	Add Biller </title>
		<script type="text/javascript" src="onlinebank.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username()?>
		<form name = "addBiller_form" method ="post" action = "addBiller.php" onsubmit="return checkAddBiller();">
			Reference Number: <input type = "text" name="refnum" maxlength="10"/><span id="refNumErr" style="color: red"> </span><br/>
			<?php
				$conn = oci_connect("guestbank", "kayato1");
				
				$query = 'select * from billerlist';
				$stid = oci_parse($conn, $query);
				oci_execute($stid, OCI_DEFAULT);
				
			/*if($stid == NULL){
				echo "Execution Failed";
			}
			print 'Billername: <select name="billerlist">';
			while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
				foreach ($row as $item) {
					print'<option value="'.$item.'">'.$item; echo'</option>';
			}
			}		
			print '</select><br/>';*/
			
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
