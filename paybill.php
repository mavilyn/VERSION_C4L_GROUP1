<?php
		include("class_lib.php");
  		session_start();
		global $billeraccountnum;
		global $amountToBePayed;
	//	global $branchcode;
		
		/*function askAgain(){
			print "<script>var confirmation = confirm('Are you sure you want to pay this bill?');
						if(confirmation == true) return 1;
						else return 0;
					</script>
			";
		}
		*/
  		if (isset($_POST['paybills'])) {
			
			
			//	$ask = askAgain();

				//if($ask ==  1){

						$accountnumber = $_SESSION['client']->get_accountnum();

						//kinukuha nya yung data dun sa napiling babayaran
						$conn = oci_connect("mainbank", "kayato1");
							$servicenum = $_POST['paybills'];
							//echo $servicenum;
							$query = 'SELECT refnum, biller_accountnum, amountdue FROM bills WHERE servicenum = :servicenum';
							$stid = oci_parse($conn, $query);
							oci_bind_by_name($stid, ':servicenum', $servicenum);
							oci_execute($stid);

							while ($row = oci_fetch_array($stid, OCI_BOTH)) {
								$billeraccountnum = $row[1];
								$amountToBePayed = $row[2];
								$refnum = $row[0];
							}

							$query = 'select * from account where accountnum=:clientaccount';
								$stid = oci_parse($conn, $query);
								oci_bind_by_name($stid, ':clientaccount', $accountnumber);
								oci_execute($stid, OCI_DEFAULT);

								while ($row = oci_fetch_array($stid, OCI_BOTH)) {
									$branchcode = $row[5];	
								}

						//	echo $billeraccountnum;

						//gets the balance of the current user
						$query = 'select balance from account where accountnum=:accountnumber';
						$stid = oci_parse($conn, $query);
						oci_bind_by_name($stid, ':accountnumber', $accountnumber);
						oci_execute($stid);
						
						while ($row = oci_fetch_array($stid, OCI_BOTH)) {
							foreach ($row as $key) {
								$balance = $key;
							}
						}
						
						if($balance >= $amountToBePayed){

							//insert 
							$query = 'INSERT into trans values(trans__seq.nextval, :accountnum, :otheraccountnum,'."'credit'".','."'online'".','."'pay_bills'".', SYSDATE, :transactioncost, :branchcode)';
								$stid = oci_parse($conn, $query);
								oci_bind_by_name($stid, ':otheraccountnum', $billeraccountnum);
								oci_bind_by_name($stid, ':accountnum', $accountnumber);
								oci_bind_by_name($stid, ':transactioncost', $amountToBePayed);
								oci_bind_by_name($stid, ':branchcode', $branchcode);
								oci_execute($stid);

							$newbalance = $balance - $amountToBePayed;
							$query = 'UPDATE account set balance = :bal where accountnum = :accountnumber';
							$stid = oci_parse($conn, $query);
							oci_bind_by_name($stid, ':accountnumber', $accountnumber);
							oci_bind_by_name($stid, ':bal', $newbalance);
							oci_execute($stid);

							$query = 'INSERT into trans values(trans__seq.nextval, :accountnum, :otheraccountnum,'."'debit'".','."'online'".','."'pay_bills'".', SYSDATE, :transactioncost, :branchcode)';
								$stid = oci_parse($conn, $query);
								oci_bind_by_name($stid, ':otheraccountnum', $accountnumber);
								oci_bind_by_name($stid, ':accountnum', $billeraccountnum);
								oci_bind_by_name($stid, ':transactioncost', $amountToBePayed);
								oci_bind_by_name($stid, ':branchcode', $branchcode);
								oci_execute($stid);
							
							$query = 'UPDATE account set balance = balance + :amount where accountnum = :accountnumber';
							$stid = oci_parse($conn, $query);
							oci_bind_by_name($stid, ':accountnumber', $billeraccountnum);
							oci_bind_by_name($stid, ':amount', $amountToBePayed);
							oci_execute($stid);

							$query = 'DELETE from bills WHERE servicenum = :servicenum';
							$stid = oci_parse($conn, $query);
							oci_bind_by_name($stid, ':servicenum', $servicenum);
							oci_execute($stid);
							
							oci_close($conn);
								
						}

						else{
							echo 'You dont have enough balance';
						}
				//}
				
				//update balance of biller
					
			}
			
				
?>
<html>
	<head>
		<title>	Pay Bills </title>
		<script type="text/javascript" src="onlinebank.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_fname()." ".$_SESSION['client']->get_lname();?>
			<form name = "paybill_form" method ="post" action = "#" onSubmit = "return checkpaybill();">
			

			<!--

</head>
<body>-->
			<?php
				/*hindi ko maisip itsura pero dropdown tapos amount ay textfield*/
				

				
				$tempaccountnum = $_SESSION['client']->get_accountnum();
				$connMain = oci_connect("mainbank", "kayato1");
				
				$clientaccount = $_SESSION['client']->get_accountnum();
						$clientquery = 'SELECT * FROM account where accountnum =:clientaccount';
						$st = oci_parse($connMain, $clientquery);
						oci_bind_by_name($st, ':clientaccount', $tempaccountnum);
						oci_execute($st);
							
							while ($row = oci_fetch_array($st, OCI_BOTH)) {
								$clientbalance=$row[2];	//
							}	

					echo "<b>BALANCE: Php".$clientbalance."</b>";

				$query = 'select c.billername, b.servicenum, b.refnum, b.amountdue, b.dateissued from current_biller c, bills b where c.accountnum = :accountnum and b.biller_accountnum = c.billeraccountnum and b.refnum = c.refnum order by b.dateissued';
				$stid = oci_parse($connMain, $query);
				oci_bind_by_name($stid, ':accountnum', $tempaccountnum);
				oci_execute($stid, OCI_DEFAULT);

				print '<table border="1">';
				print '<tr>';
				print'<th>Biller Name'; echo'</th>';
				//print'<th>Service NUmber'; echo'</th>';
				print'<th>Reference Number'; echo'</th>';
				print'<th>Amount Due'; echo'</th>';
				print'<th>Date Issued'; echo'</th>';
				print'<th>Service Number'; echo'</th>';
				echo '</tr>';
						
				while ($row = oci_fetch_array($stid, OCI_BOTH)) {
							$servicenum=$row[1];
						
					print '<tr>';
					//foreach ($row as $item) {
						print'<td>'.$row[0]; echo'</td>';
						//print'<td>'.$row[1]; echo'</td>';
						print'<td>'.$row[2]; echo'</td>';
						print'<td>'.$row[3]; echo'</td>';
						print'<td>'.$row[4]; echo'</td>';
					//}
						print '<td>';
						print '<input type="submit" name="paybills" value="'.$servicenum.'"/>';
						echo '</td>';
					echo '</tr>';
				}
				
				oci_close($connMain);
				
			?>	
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
