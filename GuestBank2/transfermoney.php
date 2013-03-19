<?php
	include("class_lib.php");
	
	session_start();
	
	if(isset($_POST['Submit'])){
			/************************************************************/
			//establish connections
				$connGuest = oci_connect("guestbank", "kayato1"); //cnnect with the guestbank
				$connMain = oci_connect("mainbank", "kayato1");
			/************************************************************/
			
			/************************************************************/
				$otheraccountnum = $_POST['accountconnected'];
				$clientaccount = $_SESSION['client']->get_accountnum();	 
				$amount = $_POST['amount'];
			/************************************************************/
				
			/***********************************************************************************/
			//get balance of the client
					$clientquery = 'SELECT * FROM account where accountnum = :clientaccount';
					$st = oci_parse($connMain, $clientquery);
					oci_bind_by_name($st, ':clientaccount', $clientaccount);
					oci_execute($st);
						
					while ($row = oci_fetch_array($st, OCI_BOTH)) {
						$clientbalance=$row[2];	//
					}	
			/***********************************************************************************/

				if($clientbalance < $_POST['amount']){
					echo "Account balance is not enough.";
				}
				
				else{
					/***********************************************************************************/
					//get branchcode of the account just to include in the record
						$query = 'select * from account where accountnum=:clientaccount';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':clientaccount', $clientaccount);
						oci_execute($stid, OCI_DEFAULT);

						while ($row = oci_fetch_array($stid, OCI_BOTH)) {
							$branchcode = $row[5];	
						}
					/***********************************************************************************/	
						
					/***********************************************************************************/	
						//record transaction  for the client side
						$query = 'INSERT into trans values(trans__seq.nextval, :accountnum,:otheraccountnum,'."'credit'".','."'online'".','."'transfer_fund'".', SYSDATE, :transactioncost, :branchcode)';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':accountnum', $clientaccount);
						oci_bind_by_name($stid, ':otheraccountnum', $otheraccountnum);
						oci_bind_by_name($stid, ':transactioncost', $amount);
						oci_bind_by_name($stid, ':branchcode', $branchcode);
						oci_execute($stid);	
					/***********************************************************************************/

					/***********************************************************************************/
						//update balance of the client
						$query = 'UPDATE account set balance = balance - :amount where accountnum = :accountnum';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':accountnum', $clientaccount);
						oci_bind_by_name($stid, ':amount', $amount);
						oci_execute($stid);
					/***********************************************************************************/	
					
					/***********************************************************************************/	
						//transfer other account's
						//record transaction for the other account's side
						$query = 'INSERT into trans values(trans__seq.nextval, :otheraccountnum,:accountnum,'."'dedit'".','."'online'".','."'transfer_fund'".', SYSDATE, :transactioncost, :branchcode)';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':otheraccountnum', $otheraccountnum);
						oci_bind_by_name($stid, ':accountnum', $clientaccount);
						oci_bind_by_name($stid, ':transactioncost', $amount);
						oci_bind_by_name($stid, ':branchcode', $branchcode);
						oci_execute($stid);	
					/***********************************************************************************/

					/***********************************************************************************/
					//update balance of other account
						$query = 'UPDATE account set balance = balance + :amount where accountnum = :otheraccountnum';
						$stid = oci_parse($connMain, $query);
						oci_bind_by_name($stid, ':otheraccountnum', $otheraccountnum);
						oci_bind_by_name($stid, ':amount', $amount);
						oci_execute($stid);
					/***********************************************************************************/
						echo "<script>alert('Fund has been successfully transferred.'); </script>";
					}

					oci_close($connGuest);
					oci_close($connMain);
		}
	
	else if (isset($_POST['Submit_2'])) {
			$preferredname = $_POST['preferredname'];
			$clientAccNum = $_SESSION['client']->get_accountnum();
			if($clientAccNum == $_POST['otheraccountnum']){		//check if own account
					echo "<script>alert('Cannot add your own account!');</script>";
			}
			else{
				$connMain = oci_connect('mainbank','kayato1');
				
				function askAgain(){
					print "<script>var confirmation = confirm('Request was disapprove earlier. Want to request again?');
								if(confirmation == true) return true;
								else return false;
							</script>
					";
				}
				
				$parsed = oci_parse($connMain, 'SELECT COUNT(*) AS NUM_ROWS
						FROM account
						WHERE accountnum = '.$_POST['otheraccountnum'].'and compName IS NULL');  //check if compname is null
				oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
				oci_execute($parsed);
				oci_fetch($parsed);
				if($num_rows == 0 ){											//if account is company or not exist
					echo "<script>alert('Account does not exist!');</script>";
				}
				else{
					$conn = oci_connect('guestbank', 'kayato1');
					$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
						FROM addaccntconnect_request
						WHERE accountnum = '.$clientAccNum.'and otheraccountnum  = '.$_POST['otheraccountnum'].'and appDisflag is NULL');
					oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
					oci_execute($parsed);
					oci_fetch($parsed);
					if($num_rows > 0){
						echo "<script>alert('Request to get connected to this account is already pending.');</script>";
					}
					else{
						$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
							FROM accountconnected
							WHERE accountnum = '.$clientAccNum.'and otheraccountnum  = '.$_POST['otheraccountnum']);
						oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);
						oci_execute($parsed);
						oci_fetch($parsed);
						if($num_rows > 0){
							echo "<script>alert('You are already connected to this account.');</script>";
						}
						else{
							//checking if dissapprove and ask user if he/she wants to request again
							$parsed = oci_parse($conn, 'SELECT COUNT(*) AS NUM_ROWS
								FROM addaccntconnect_request
								WHERE accountnum = '.$clientAccNum.'and otheraccountnum  = '.$_POST['otheraccountnum'].'and appDisflag = 0');
							oci_define_by_name($parsed, 'NUM_ROWS', $num_rows);		
							oci_execute($parsed);
							oci_fetch($parsed);
							if($num_rows > 0){
									$askRet = askAgain();

									if($askRet == false){
										$parsed = oci_parse($conn, 'UPDATE addaccntconnect_request set preferredname = :preferredname, appDisflag = null, appdisdate = null where accountnum=:accountnum and otheraccountnum=:otheraccountnum');
										oci_bind_by_name($parsed, ':accountnum', $clientAccNum);
										oci_bind_by_name($parsed, ':otheraccountnum', $_POST['otheraccountnum']);
										//oci_bind_by_name($parsed,':preferredname', $_POST['preferredname']);
										if($preferredname != ""){
											oci_bind_by_name($parsed, ':preferredname', $preferredname);
										}
										else{
											$unnamed = "<unnamed>";
											echo $unnamed;
											oci_bind_by_name($parsed, ':preferredname', $unnamed);
										}
										oci_execute($parsed);
										echo "<script>alert('Request has been made successfully.');</script>";
									}
									
							}
							else{
								$query = oci_parse($conn, 'INSERT INTO addaccntconnect_request(accountnum, otheraccountnum,preferredname,requestdate) VALUES(:accountnum, :otheraccountnum, :preferredname, SYSDATE)');
								oci_bind_by_name($query, ':accountnum', $clientAccNum);
								oci_bind_by_name($query, ':otheraccountnum', $_POST['otheraccountnum']);
								if($preferredname != ""){
									oci_bind_by_name($query, ':preferredname', $preferredname);
								}
								else{
									$unnamed = "<unnamed>";
									echo $unnamed;
									oci_bind_by_name($query, ':preferredname', $unnamed);
								}
								oci_execute($query);
								oci_close($conn);
								echo "<script>alert('Request to add account successfully made!');</script>";
							//}
						}
					}
				}
			}
		}
	}
	
	/* get account_balance */
	$accountbalance = 0;

	$conn = oci_connect('mainbank', 'kayato1');
	$sql='select balance from account where accountnum = :accountnum';
	$stmt = oci_parse($conn, $sql);
	oci_bind_by_name($stmt, ':accountnum', $_SESSION['client']->accountnum);
	oci_execute($stmt, OCI_DEFAULT);
	while ($row = oci_fetch_array($stmt, OCI_BOTH)){
		$accountbalance = $row[0];
	}	
	
	unset($_POST['Submit']);
	unset($_POST['Submit_2']);
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<title>GuestBank Online Banking Solutions | Client Dashboard</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="stylesheets/clientdashboard_style.css"/>  
		<link rel="stylesheet" href="stylesheets/slider_style.css"/>  
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
		
		<script type="text/javascript" src="scripts/onlinebank.js"></script>
	
		<link type="text/css" href="stylesheets/jquery.jscrollpane.css" rel="stylesheet" media="all" />
		<link type="text/css" href="stylesheets/jquery.jscrollpane.lozenge.css" rel="stylesheet" media="all" />
		<script type="text/javascript" src="scripts/jquery.jscrollpane.min.js"></script>
		<script type="text/javascript" src="scripts/jquery.mousewheel.js"></script>
	</head>

	<body>
		<div id="top">
			<div id="top_upper">
			</div>
			<div id="top_lower">
			</div>
			
			<div id="top_center">
				<div id="logo" style="cursor: pointer;">
					<img id="logo_img" src="images/logo_small.png" alt ="" />
				</div>
				
				<div id="top_center_right">
				
					<div id="link_area">
						<div id="home_link" class="toplink_div"  style="cursor: pointer;">
								CLIENT DASHBOARD
						</div>
						
						<div id="logout_link" class="toplink_div_dark" style="cursor: pointer;">
								LOGOUT
						</div>
						
						
				    </div>
					
					<div id="slogan_div">
						<img id="slogan" src="images/slogan.png" alt ="" />
					</div>
				</div>
				<div id="top_upper_inside">
				</div>
				<div id="top_lower_inside">
				</div>
				
			</div>
		
		
		</div>
		
		<div id="body">
			
			<div id="body_center">
				<div id="content_outerbox">
					<div id="content_header">
					
					<div id="back_button">
						
						<img src="images/back_2.png" id="back_button_img" />
					</div>
					<h1 id="functionality_header">Transfer Money</h1>
					</div>
					
					<div id="content_wrapper">
						<div id = "tm_tabs">
							
							<div id = "transfer_tab_light" class="tab_dark">
								<p class="tab_text">Transfer</p>
							</div>
							
							<div id = "connect_tab_light" class="tab_light" >
								<p class="tab_text">Connect Accounts</p>
							</div>
							
							<div id = "status_tab_light" class="tab_light" >
								<p class="tab_text">Account Connection Status</p>
							</div>
							
						
							
						</div>
						<div class="rule"></div>
						<div id="transfer_maincontent">
							<h1 id="transfer_bal">Account Balance: &nbsp;&nbsp;&nbsp;<span id="bal">Php<?= $accountbalance?></span>
							</h1>
							
							<form name = "transferFund_form" method ="post" action = "transfermoney.php" onSubmit = "return checkTransfer();">
							<?php
								$conn = oci_connect("guestbank", "kayato1");
								$accountnum =  $_SESSION['client']->get_accountnum();
								$query = 'select * from accountconnected where accountnum = :accountnum';
								$stid = oci_parse($conn, $query);
								oci_bind_by_name($stid, ':accountnum',$accountnum);
								oci_execute($stid, OCI_DEFAULT);
				
								print '<p id="account_connected_p"> Account Connected: <select name="accountconnected" id="connect_drop">';
								
								
									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
									$otheraccountnum=$row[1];
									$preferred=$row[2];
									print'<option value="'.$otheraccountnum.'">'.$preferred." - ".$otheraccountnum; echo'</option>';
									
								}
							print '</select><p>';
							oci_close($conn);
							?>
			
							<p id="amount_p">Amount: <input id = "amount" type="text" name="amount" maxlength="10" value=""/></p>
						
							<input type="submit" name="Submit" value="Transfer Funds" id="transfer_button"/>
						</form>
						</div>
						
						<div id="connect_maincontent">
						
							<form name = "addOther_form" method ="post" action = "transfermoney.php" onSubmit="return checkAddAccount();">
								<p id="oan_p">
								<label class="label_2" for="otheraccountnum">Account Number:</label> 
								<input type = "text" name="otheraccountnum" id="otheraccountnum" maxlength="10"/>
								<span id="otheraccountnumErr" class="err_span"> </span>
								</p>
								<p id="pn_p">
								<label class="label_2" for="preferredname">Preferred Name:</label>
								<input type = "text" name="preferredname" id="preferredname" maxlength="20"/>
								<span id="preferredNameErr" class="err_span"> </span><br/>
								</p>
								<input type="submit" name="Submit_2" value="Submit" id="conn"/>	
							</form>
							
						</div>
						
						<div id="status_maincontent">
							
							<?php
							
								$conn = oci_connect("guestbank", "kayato1");
				
								$accountnum = $_SESSION['client']->get_accountnum();
				
								$query = 'select * from addaccntconnect_request where accountnum = :accountnum';
								$stid = oci_parse($conn, $query);
								oci_bind_by_name($stid, ':accountnum', $accountnum);
				
								oci_execute($stid);
				
								if($stid == NULL){
									echo "Execution Failed";
								}
								
								echo '<div class="scroll-pane-arrows">';
								print '<table>';
								print '<tr>';
								print'<th class="header_row">Other Account Number'; echo'</th>';
								print'<th class="header_row">Preferred Name'; echo'</th>';
								print'<th class="header_row">Request Date'; echo'</th>';
								print'<th class="header_row">Status'; echo'</th>';
								echo '</tr>';
						
								while ($row = oci_fetch_array($stid, OCI_BOTH)) {

									$preferred=$row[2];
									$otheraccountnum=$row[1];
									$requestdate=$row[3];
									if(isset($row[5]))
										$st = $row[5];
									else $st = "";
									if($st!=""){
									if($st==1) $status = "Approved";
									else if($st==0) $status = "Dispproved";
									}else $status = "Pending";
						
								print '<tr>';
								//foreach ($row as $item) {
								print'<td class="ac_data"> '.$otheraccountnum; echo'</td>';
								print'<td class="ac_data">'.$preferred; echo'</td>';
								print'<td class="ac_data">'.$requestdate; echo'</td>';
								print'<td class="ac_data">'.$status; echo'</td>';
								//}
								echo '</tr>';
								
								}
							print '</table></div>';
							
							?>
							
						</div>
						
						<div class="rule"></div>	
					</div>
					
				</div>
			</div>
			</div>
				
		</div>
		
		<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					
		</div>
		
		<script type="text/javascript">
			$(function(){
						$('.scroll-pane').jScrollPane();
						$('.scroll-pane-arrows').jScrollPane(
						{
							showArrows: true,
						}
						);
						});	
		
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {window.location = "client_home.php";});
			});
			
			$('#logout_link').click(function() {
					$('#content_outerbox').fadeOut('slow', function() {window.location = "logout.php";});
				});
			
			$('#transfer_maincontent').show();
			$('#connect_maincontent').hide();
			$('#status_maincontent').hide();

			
			$('#transfer_tab_light').click(function(){
				$('#transfer_tab_light').addClass('tab_dark');
				$('#transfer_tab_light').removeClass('tab_light');
				$('#connect_tab_dark').attr('id', 'connect_tab_light');
				$('#status_tab_dark').attr('id', 'status_tab_light');
	
				$('#transfer_maincontent').slideDown();
				$('#connect_maincontent').hide();
				$('#status_maincontent').hide();
				
				});
			
			
			$('#connect_tab_light').click(function(){
				$('#transfer_tab_light').removeClass('tab_dark');
				$('#transfer_tab_light').addClass('tab_light');
				$('#transfer_tab_dark').attr('id', 'transfer_tab_light');
				$('#status_tab_dark').attr('id', 'status_tab_light');
				$('#connect_tab_light').attr('id', 'connect_tab_dark');
				
				$('#transfer_maincontent').hide();
				$('#status_maincontent').hide();
				$('#connect_maincontent').slideDown();
				
				});
				
			$('#status_tab_light').click(function(){
				$('#transfer_tab_light').removeClass('tab_dark');
				$('#transfer_tab_light').addClass('tab_light');
				$('#transfer_tab_dark').attr('id', 'transfer_tab_light');
				$('#connect_tab_dark').attr('id', 'connect_tab_light');
				$('#status_tab_light').attr('id', 'status_tab_dark');
				
				$('#transfer_maincontent').hide();
				$('#connect_maincontent').hide();
				$('#status_maincontent').slideDown();
				
				});	
				
			
			
		</script>
	</body>
</html>