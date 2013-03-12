<?php
		include("class_lib.php");
  		session_start();
		$billeraccountnum;
		
  		if (isset($_POST['Submit'])) {
			
				
				$conn = oci_connect("mainbank", "kayato1");
				
				$query = 'select balance from account where accountnum=:accountnumber';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':accountnumber', $_SESSION['client']->get_accountnum());
				oci_execute($stid);
				
				while ($row = oci_fetch_array($stid, OCI_RETURN_NULLS+OCI_ASSOC)) {
					foreach($row as $temp){
					}
						$balance = $temp;
				}
				
				if($balance >= $_POST['Amount']){
					$query = 'INSERT into trans values(:accountnum, trans_seq.nextval,'."'online'".', SYSDATE, :transactioncost, '."'credit'".')';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':accountnum', $_SESSION['client']->get_accountnum());
					oci_bind_by_name($stid, ':transactioncost', $_POST['Amount']);
					//oci_bind_by_name($stid, ':transtype', "online");
					//oci_bind_by_name($stid, ':transop', "credit");
					oci_execute($stid);
				}
				else{
					echo 'You dont have enough balance';
				}
				
				oci_close($conn);
				
				if($balance >= $_POST['Amount']){
					$conn = oci_connect("mainbank", "kayato1");
					$newbalance = $balance - $_POST['Amount'];
					$query = 'UPDATE account set balance = :bal where accountnum = :accountnumber';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':accountnumber', $_SESSION['client']->get_accountnum());
					oci_bind_by_name($stid, ':bal', $newbalance);
					oci_execute($stid);
				
				}
				
				//update balance of biller
					$query = 'INSERT into trans values(:accountnum, trans_seq.nextval,'."'online'".', SYSDATE, :transactioncost, '."'debit'".')';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':accountnum', $_POST['billers']);
					oci_bind_by_name($stid, ':transactioncost', $_POST['Amount']);
					//oci_bind_by_name($stid, ':transtype', "online");
					//oci_bind_by_name($stid, ':transop', "credit");
					oci_execute($stid);
					
					$query = 'UPDATE account set balance = balance + :amount where accountnum = :accountnumber';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':accountnumber', $_POST['billers']);
					oci_bind_by_name($stid, ':amount', $_POST['Amount']);
					//oci_bind_by_name($stid, ':bal', $newbalance);
					oci_execute($stid);
					
					oci_close($conn);
					
					//record transaction online
					$conn = oci_connect('guestbank', 'kayato1');
					$query = 'INSERT into transact_paybills values(:accountnum, :billeraccountnum,:refnum, SYSDATE, :transactioncost)';
					$stid = oci_parse($conn, $query);
					oci_bind_by_name($stid, ':accountnum', $_POST['billers']);
					oci_bind_by_name($stid, ':transactioncost', $_POST['Amount']);
					oci_bind_by_name($stid, ':refnum', $_POST['Amount']);
					//oci_bind_by_name($stid, ':transtype', "online");
					//oci_bind_by_name($stid, ':transop', "credit");
					oci_execute($stid);
					oci_close($conn);
				
				
			/*
				check if balance in account table (mainbank) is greater than or equal.
					if not, prompt message 
					else see comment below
			*/			
			/*
				insert to trans table (mainbank), insert to transact_transfer (guestbank) 
			*/
		}
			
				
?>
<html>
	<head>
		<title>	Pay Bills </title>
		<script type="text/javascript" src="onlinebank.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	</head>
	<body>
	<?php if(isset($_SESSION['loginclient'])){ echo "Welcome ".$_SESSION['client']->get_username()?>
		<form name = "paybill_form" method ="post" action = "#" >
			<!--

</head>
<body>-->
			<?php
				/*hindi ko maisip itsura pero dropdown tapos amount ay textfield*/
				$tempaccountnum = $_SESSION['client']->get_accountnum();
				$conn = oci_connect("guestbank", "kayato1");
				
				$query = 'select * from current_biller where accountnum=:accountnumber';
				$stid = oci_parse($conn, $query);
				oci_bind_by_name($stid, ':accountnumber', $tempaccountnum);
				oci_execute($stid, OCI_DEFAULT);
				
					print 'Billername - Reference Number: <select name="billers">';
						while ($row = oci_fetch_array($stid, OCI_BOTH)) {
							
							$billeraccountnum=$row[1];
							$billername=$row[2];
							$refnum=$row[3];
							print'<option value="'.$billeraccountnum.'">'.$billername." - ".$refnum; echo'</option>';
						}
						print '</select><br/>';
					
				
				oci_close($conn);
				
			?>
			<!--Amount<input type="text" name="Amount"/><br/>-->
			<script>
			$(document).ready(function(){
			  $("select").change(function(){

			      var billeraccountnum = $(this).val();  
      			  $("#bills").fadeIn(2000);	
			  });
			});
			</script>
				<div id="bills" style="width:80px;height:80px;display:none;">
						<?php 
						

						?>
						<!--
							function displayVals() {
      var singleValues = $("#single").val();
      var multipleValues = $("#multiple").val() || [];
      $("p").html("<b>Single:</b> " +
                  singleValues +
                  " <b>Multiple:</b> " +
                  multipleValues.join(", "));
    }
 
    $("select").change(displayVals);
    displayVals();
						-->
				</div>
			<input type="submit" name="Submit" value="Pay Bills" />	
		</form>
		<?php }else header('Location: login.php'); ?>
	</body>
</html>
