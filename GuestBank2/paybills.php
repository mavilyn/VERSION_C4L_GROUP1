<?php
	include("class_lib.php");
	session_start();
	
	global $billeraccountnum;
	global $amountToBePayed;
	
	if (isset($_POST['paybills'])) {
			
			
			//	$ask = askAgain();

				//if($ask ==  1){

						$accountnumber = $_SESSION['client']->get_accountnum();

						//kinukuha nya yung data dun sa napiling babayaran
						$conn = oci_connect("mainbank", "kayato1");
							$servicenum = $_POST['servicenum'];
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
	
	//code for adding a biller
	else if (isset($_POST['Submit'])) {
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
								$accountnum = $_SESSION['client']->get_accountnum();
							
								//INSERT THE BILLER REQUEST TO THE ADDBILLER_REQUEST
								$query =  'INSERT into addbiller_request(accountnum, billeraccountnum, billername, refnum, requestDate) values(:accountnum, :billeraccountnum, :billername, :refnum, SYSDATE)';
								$compiled = oci_parse($conn, $query);
								oci_bind_by_name($compiled, ':accountnum', $accountnum);
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
		
		<script src="scripts/onlinebank.js" type="text/javascript"></script>
		
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
					<h1 id="functionality_header">Pay Bills</h1>
					</div>
					
					<div id="content_wrapper">
						<div id = "var_tabs">
							
							<div id = "paybills_tab_light" class="tab_dark">
								<p class="tab_text">Pay Bills!</p>
							</div>
							
							<div id = "addbiller_tab_light" class="tab" >
								<p class="tab_text">Add Billers</p>
							</div>
							
							<div id = "bcs_tab_light" class="tab" >
								<p class="tab_text">Biller Request Status</p>
							</div>
							
						</div>
						<div class="rule"></div>
						<div id="pb_maincontent">
							<form name = "paybill_form" method ="post" action = "#" >
							
							<?php
				
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

								echo "<h1 id='transfer_bal'>Account Balance: &nbsp;&nbsp;&nbsp;<span id='bal'>Php".$clientbalance."</span>
							</h1>";
							
							$query = 'select c.billername, b.servicenum, b.refnum, b.amountdue, b.dateissued from current_biller c, bills b where c.accountnum = :accountnum and b.biller_accountnum = c.billeraccountnum and b.refnum = c.refnum order by b.dateissued';
							$stid = oci_parse($connMain, $query);
							oci_bind_by_name($stid, ':accountnum', $tempaccountnum);
							oci_execute($stid, OCI_DEFAULT);
							print '<div class="scroll-pane-arrows2">';
							print '<table>';
							print '<tr>';
								print'<th class="header_row">Biller Name'; echo'</th>';
								print'<th class="header_row">Reference Number'; echo'</th>';
								print'<th class="header_row">Amount Due'; echo'</th>';
								print'<th class="header_row">Date Issued'; echo'</th>';
								print'<th class="header_row">&nbsp;'; echo'</th>';
							echo '</tr>';
						
							while ($row = oci_fetch_array($stid, OCI_BOTH)) {
								$servicenum=$row[1];
						
							print '<tr>';
								print'<td class="ac_data">'.$row[0]; echo'</td>';
								print'<td class="ac_data">'.$row[2]; echo'</td>';
								print'<td class="ac_data">'.$row[3]; echo'</td>';
								print'<td class="ac_data">'.$row[4]; echo'</td>';
								print '<td class="ac_data">';
								print '<input type="submit" name="paybills" value="Pay Bill" class="pb_submit"/>';
								print '<input type="hidden" name="servicenum" value="'.$servicenum.'"/>';
								echo '</td>';
							echo '</tr>';
							
							
							}
					
							oci_close($connMain);
							echo '</table></div>';
							?>
							
							</form>
						</div>
							
						<div id="ab_maincontent">
						<form name = "addBiller_form" method ="post" action = "paybills.php" onsubmit="return checkAddBiller();">
								<p id="rn_p">
								<label class="label_2" for="refnum">Reference Number: </label>
								<input type = "text" name="refnum" maxlength="11" id="refnum"/>
								<span id="refNumErr" class="err_span"> </span>
								</p>
								<p id="bn_p">
								<?php
									$conn = oci_connect("mainbank", "kayato1");
				
									$query = 'select * from billerlist';
									$stid = oci_parse($conn, $query);
									oci_execute($stid, OCI_DEFAULT);
			
									print 'Biller Name: <select name="billers" id="billers">';
									while ($row = oci_fetch_array($stid, OCI_BOTH)) {
									$billeraccountnum=$row[0];
									$billername=$row[1];
									echo $billeraccountnum;
									print'<option value="'.$billeraccountnum.'">'.$billername; echo'</option>';
								}
								print '</select>';
								?>
								</p>
							<input type="submit" name="Submit" value="Submit" id="ab_submit"/>	
						</form>
						</div>
						<div id="bcs_maincontent">
						
						<div class="scroll-pane-arrows3">
						<?php
						$conn = oci_connect("guestbank", "kayato1");
		
						$query = 'select * from addbiller_request where accountnum = '.$_SESSION['client']->get_accountnum();
						$stid = oci_parse($conn, $query);
						oci_execute($stid);
				
						if($stid == NULL){
							echo "Execution Failed";
						}
						
						print '<table>';
						print '<tr>';
						print'<th class="header_row">Biller Name'; echo'</th>';
						print'<th class="header_row">Reference Number'; echo'</th>';
						print'<th class="header_row">Request Date'; echo'</th>';
						print'<th class="header_row">Status'; echo'</th>';
						echo '</tr>';
						
						while ($row = oci_fetch_array($stid, OCI_BOTH)) {

							$billername=$row[2];
							$ref=$row[3];
							$requestdate=$row[4];
							if(isset($row[6]))
								$st = $row[6];
							else $st = "";
							if($st!=""){
								if($st==1) $status = "Approved";
								else if($st==0) $status = "Dispproved";
							}else $status = "Pending";
						
						print '<tr>';
						print'<td class="ac_data">'.$billername; echo'</td>';
						print'<td class="ac_data">'.$ref; echo'</td>';
						print'<td class="ac_data">'.$requestdate; echo'</td>';
						print'<td class="ac_data">'.$status; echo'</td>';
						
						echo '</tr>';
						print '<tr>';
						print'<td class="ac_data">'.$billername; echo'</td>';
						print'<td class="ac_data">'.$ref; echo'</td>';
						print'<td class="ac_data">'.$requestdate; echo'</td>';
						print'<td class="ac_data">'.$status; echo'</td>';
						echo '</tr>';
						print '<tr>';
						print'<td class="ac_data">'.$billername; echo'</td>';
						print'<td class="ac_data">'.$ref; echo'</td>';
						print'<td class="ac_data">'.$requestdate; echo'</td>';
						print'<td class="ac_data">'.$status; echo'</td>';
						echo '</tr>';
						}
						print '</table>';
						?>
						</div>
						
						
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
						$('.scroll-pane2').jScrollPane();
						$('.scroll-pane-arrows2').jScrollPane(
						{
						
							showArrows: true,
						
						}
						);
						});	
								
		
			$('#pb_maincontent').show();
			$('#ab_maincontent').hide();
			$('#bcs_maincontent').hide();
			
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {window.location = "client_home.php";});
			});
			
			$('#logout_link').click(function() {
					$('#content_outerbox').fadeOut('slow', function() {window.location = "logout.php";});
				});
			
			$('#paybills_tab_light').click(function(){
				$('#paybills_tab_light').addClass('tab_dark');
				$('#paybills_tab_light').removeClass('tab_light');
				$('#bcs_tab_dark').attr('id', 'bcs_tab_light');
				$('#addbiller_tab_dark').attr('id', 'addbiller_tab_light');
			
				$('#pb_maincontent').slideDown();
				$('#bcs_maincontent').hide();
				$('#ab_maincontent').hide();
				
				});
			
			
			$('#addbiller_tab_light').click(function(){
				$('#paybills_tab_light').removeClass('tab_dark');
				$('#paybills_tab_light').addClass('tab_light');
				$('#paybills_tab_dark').attr('id', 'paybills_tab_light');
				$('#bcs_tab_dark').attr('id', 'bcs_tab_light');
				$('#addbiller_tab_light').attr('id', 'addbiller_tab_dark');
				
				$('#pb_maincontent').hide();
				$('#bcs_maincontent').hide();
				$('#ab_maincontent').slideDown();
				
				});
			
			$('#bcs_tab_light').click(function(){
				$('#paybills_tab_light').removeClass('tab_dark');
				$('#paybills_tab_light').addClass('tab_light');
				$('#paybills_tab_dark').attr('id', 'paybills_tab_light');
				$('#bcs_tab_light').attr('id', 'bcs_tab_dark');
				$('#addbiller_tab_dark').attr('id', 'addbiller_tab_light');
				
				$('#pb_maincontent').hide();
				$('#bcs_maincontent').slideDown();
				$('#ab_maincontent').hide();
				
				});
				
			
						
					
		
		</script>
	</body>
</html>