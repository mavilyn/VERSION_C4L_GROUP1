<?php
	include("class_lib.php");
	session_start();
	/* get account_balance */
	$balance = 0;

	$conn = oci_connect('mainbank', 'kayato1');
	$sql='select balance from account where accountnum = :accountnum';
	$stmt = oci_parse($conn, $sql);
	oci_bind_by_name($stmt, ':accountnum', $_SESSION['client']->accountnum);
	oci_execute($stmt, OCI_DEFAULT);
	while ($row = oci_fetch_array($stmt, OCI_BOTH)){
		$balance = $row[0];
	}
	
	function load_data(){
	
	$trans = null;
	
	$accountnum = $_SESSION['client']->get_accountnum();
	$conn = oci_connect('mainbank', 'kayato1');
	
	$query = 'select count(*) as NUM_ROWS from trans where accountnum=:accountnum or otheraccountnum=:accountnum';
	$parsed_query = oci_parse($conn, $query);
	oci_define_by_name($parsed_query, 'NUM_ROWS', $num_rows);
	oci_bind_by_name($parsed_query, ':accountnum', $accountnum);
	oci_execute($parsed_query);
	oci_fetch($parsed_query);
	
	if($num_rows !=0){
	
		$query2 = 'select * from trans where accountnum=:accountnum order by transactdate desc';
		$parsed_query2 = oci_parse($conn, $query2);
		oci_bind_by_name($parsed_query2, ':accountnum', $accountnum);
		oci_execute($parsed_query2);
		
		while($row = oci_fetch_array($parsed_query2, OCI_BOTH)){
			
			$trans[] = new TransactionModel($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8]);

		}
		
		
		//var_dump($billername);
		}
		return $trans;
	
	}
	
	//functions for viewing the account record
	function prepare_data($trans){
		$count = 0;
		$billername =null;
		
		$conn = oci_connect('mainbank', 'kayato1');
		$query3 = 'select * from billerlist';
		$parsed_query3 = oci_parse($conn, $query3);
		oci_execute($parsed_query3);
		
		while($row = oci_fetch_array($parsed_query3, OCI_BOTH)){
			
			$billername[$row[0]] = $row[1]; 

		}
		
		foreach($trans as $x){
			$trans_edited[$count]['date'] = $trans[$count]->transactdate;		
			$trans_string = "wala pa";
			if($trans[$count]->transactionop == "pay_bills"){
				$trans_string = "You paid Php".$trans[$count]->transactcost." to ".$billername[(string)$trans[$count]->otheraccountnum]; 
			}
			else if($trans[$count]->transactionop == "transfer_fund"){
				if($trans[$count]->transactiontype == 'debit' || $trans[$count]->transactiontype == 'dedit'){
				$trans[$count]->transactiontype ='debit';
				$trans_string = "You received Php".$trans[$count]->transactcost." from ".$trans[$count]->otheraccountnum; 
				
				}
				else{
				
				$trans_string = "You transferred Php".$trans[$count]->transactcost." to ".$trans[$count]->otheraccountnum; 
				
				}
			}
			
			else if($trans[$count]->transactionop == "deposit"){
				$trans_string = "You deposited Php".$trans[$count]->transactcost."on your account."; 
			}
			$trans_edited[$count]['trans_string'] = $trans_string;
			$trans_edited[$count]['type'] = $trans[$count]->transactiontype;
			$trans_edited[$count]['medium'] = $trans[$count]->transactmedium;
			
			$count++;
		}
		return $trans_edited;
	}
	
	$trans = load_data();
	$trans_edited = prepare_data($trans);
	$trans_json = json_encode($trans_edited);
	$_POST['trans_json'] = $trans_json;	
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
					<h1 id="functionality_header">View Account Record</h1>
					</div>
					
					<div id="content_wrapper">
						<div id = "var_tabs">
							
							<div id = "ai_tab_light" class="tab_dark">
								<p class="tab_text">Account Information</p>
							</div>
							
							<div id = "notif_tab_light" class="tab" >
								<p class="tab_text">Notifications</p>
							</div>
							
							<div id = "trans_tab_light" class="tab">
								<p class="tab_text">Transaction History</p>
							</div>
							
						</div>
						<div class="rule"></div>
						<div id="ai_maincontent">
							<div id="ai_left">
							<h1 id="client_name">
								<?php 
								echo $_SESSION['client']->lname.", ".
								$_SESSION['client']->fname." ".
								$_SESSION['client']->mname;
								
								?>
							</h1>
							
							<h1 id="acc_num">Account Number: 
								<?= $_SESSION['client']->accountnum?>
							</h1>
							
							<h1 id="acc_bal">Account Balance: <span id="bal">P<?= $balance?></span>
							</h1>
							</div>
						<div id="ai_right">
							<img src="images/avatar.png" alt="" />
						</div>
						</div>
						
						<div id="notif_maincontent">
						</div>
						
						<div id="trans_maincontent">
						
							<input type="hidden" name="jsonarray" value="<?php echo htmlspecialchars($trans_json,ENT_QUOTES); ?>" id="jsonarray">
							<script type="text/javascript">
								var obj = eval ("(" + document.getElementById("jsonarray").value + ")");
							</script>
							
							
							
							<div id = "trans_maincontent_left">
								
								<div id = "all_tab" class="transaction_tabs">
									<span class="type_tab">All Transactions</span>
								</div>
								
								<div id = "debit" class="transaction_tabs">
									<span class="type_tab">Debit</span>
								</div>
								
								<div id = "credit" class="transaction_tabs">
									<span class="type_tab">Credit</span>
								</div>
								
								<div id = "online" class="transaction_tabs">
									<span class="type_tab">Online</span>
								</div>
								
								<div id = "offline" class="transaction_tabs">
									<span class="type_tab">Offline</span>
								</div>
							</div>
							
							<div id = "trans_maincontent_right">
								<form name = "paybill_form" method ="post" action = "#" onSubmit = "return checkpaybill();">
								
								<div class="scroll-pane-arrows4">
								<table id="account_record">
									
								<script type="text/javascript">
								
								function print_data(arg){
									var tr_string ="<tr><th class='date_header'>Date</th><th class='trans_header'>Transaction</th><th class='medium_header'>Medium</th><th class='type_header'>Type</th></tr>";
									
									if (arg == "all"){
										
									for(i=0; i<obj.length; i++){
										tr_string+="<tr>";
										tr_string+="<td class='date_data'>"+obj[i].date+"</td>";
										tr_string+="<td class='trans_data'>"+obj[i].trans_string+"</td>";
										tr_string+="<td class='medium_data'>"+obj[i].medium+"</td>";
										tr_string+="<td class='type_data'>"+obj[i].type+"</td>";
										tr_string+="</tr>";
									}
									}
									else{
										for(i=0; i<obj.length; i++){
										if(obj[i].medium == arg || obj[i].type == arg){
										tr_string+="<tr>";
										tr_string+="<td class='date_data'>"+obj[i].date+"</td>";
										tr_string+="<td class='trans_data'>"+obj[i].trans_string+"</td>";
										tr_string+="<td class='medium_data'>"+obj[i].medium+"</td>";
										tr_string+="<td class='type_data'>"+obj[i].type+"</td>";
										tr_string+="</tr>";
										
										}
									}
									}
									
									document.getElementById("account_record").innerHTML=tr_string;
								}
								print_data("all");
								$('#all_tab').click(function(){	
									
									print_data("all");
								});
								
								$('#debit').click(function(){	
									
									print_data("debit");
								});
								
								$('#credit').click(function(){	
									print_data("credit");
								});
								
								$('#online').click(function(){	
									print_data("online");
								});
								
								$('#offline').click(function(){	
									print_data("offline");
								});
								</script>
								</table>
								</form>
								</div>
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
			$(window).load(function(){
						$('.scroll-pane4').jScrollPane();
						$('.scroll-pane-arrows4').jScrollPane(
						{
						
							showArrows: true,
							horizontalGutter: 10,
							autoReinitialise: true
						}
						);
						});	
			
			$('#ai_maincontent').show();
			$('#trans_maincontent').hide();
			$('#notif_maincontent').hide();
				
			$('#back_button_img, #logo, #home_link').click(function() {
				$('#content_outerbox').fadeOut('slow', function() {location.href = "client_home.php";});
			});
			
			$('#logout_link').click(function() {
					$('#content_outerbox').fadeOut('slow', function() {location.href = "logout.php";});
				});
			
			$('#ai_tab_light').click(function(){
				$('#ai_tab_light').addClass('tab_dark');
				$('#ai_tab_light').removeClass('tab_light');
				$('#trans_tab_dark').attr('id', 'trans_tab_light');
				$('#notif_tab_dark').attr('id', 'notif_tab_light');
			
				$('#ai_maincontent').slideDown();
				$('#trans_maincontent').hide();
				$('#notif_maincontent').hide();
				
				});
			
			
			$('#notif_tab_light').click(function(){
				$('#ai_tab_light').removeClass('tab_dark');
				$('#ai_tab_light').addClass('tab_light');
				$('#ai_tab_dark').attr('id', 'ai_tab_light');
				$('#trans_tab_dark').attr('id', 'trans_tab_light');
				$('#notif_tab_light').attr('id', 'notif_tab_dark');
				
				$('#ai_maincontent').hide();
				$('#trans_maincontent').hide();
				$('#notif_maincontent').slideDown();
				
				});
			
			$('#trans_tab_light').click(function(){
				$('#ai_tab_light').removeClass('tab_dark');
				$('#ai_tab_light').addClass('tab_light');
				$('#ai_tab_dark').attr('id', 'ai_tab_light');
				$('#trans_tab_light').attr('id', 'trans_tab_dark');
				$('#notif_tab_dark').attr('id', 'notif_tab_light');
				
				$('#ai_maincontent').hide();
				$('#trans_maincontent').slideDown();
				$('#notif_maincontent').hide();
				
				});
				
			
				
		</script>
	</body>
</html>