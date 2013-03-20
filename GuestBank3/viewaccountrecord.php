<?php
	include('class_lib.php');
	session_start();
	
	if(!isset($_REQUEST['mode'])){
		header("Location: viewaccountrecord.php?mode=all");
	}
	
	function displayUserInfo(){
		echo $_SESSION['client']->get_fname()." ".$_SESSION['client']->get_lname();
		echo "<br />";
		echo "Account No. : ".$_SESSION['client']->get_accountnum();
		echo "<br />";
		echo "<br />";
		
	
	}
	
	function load_data(){
	
	$i=0;
	$conn = oci_connect('guestbank', 'kayato1');
		$query='select * from transact_paybills where accountnum=:accountnum and months_between(SYSDATE, to_date(transactdate)) <=2';
		$parsedQuery = oci_parse($conn, $query);
		oci_bind_by_name($parsedQuery, ':accountnum', $_SESSION['client']->accountnum);
		oci_execute($parsedQuery);
		while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
			
			$transactions[$i] = new Transaction($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],"bill");
			$i++;
		}
		
		oci_free_statement($parsedQuery);
		
		$query='select * from transact_transfer where accountnum=:accountnum and months_between(SYSDATE, to_date(transactdate)) <=2';
		$parsedQuery = oci_parse($conn, $query);
		oci_bind_by_name($parsedQuery, ':accountnum', $_SESSION['client']->accountnum);
		oci_execute($parsedQuery);
		while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
			
			$transactions[$i] = new Transaction($row[0],$row[1],$row[2],$row[3],$row[4],"credit",$row[6],"transfer");
	
			$i++;
		}
		
		oci_free_statement($parsedQuery);
		
		$query='select * from transact_transfer where otheraccountnum=:accountnum and months_between(SYSDATE, to_date(transactdate)) <=2';
		$parsedQuery = oci_parse($conn, $query);
		oci_bind_by_name($parsedQuery, ':accountnum', $_SESSION['client']->accountnum);
		oci_execute($parsedQuery);
		while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
			
			$transactions[$i] = new Transaction($row[0],$row[1],$row[2],$row[3],$row[4],"debit",$row[6],"receive");
	
			$i++;
		}
		
		oci_free_statement($parsedQuery);
		oci_close($conn);
		
		
		
		usort($transactions, function($b, $a) {
			return strtotime($a->transactiondate) - strtotime($b->transactiondate);});
	
		echo "<hr />";
		return $transactions;
	}
	
	function display_data($transactions, $mode){
	
		
		
			foreach($transactions as $item){
				if($item->classify == 'bill'){
					if($mode == "all" || $item->transactionop == $mode){
						echo $item->transactiondate." ".$item->transactionop." You paid ".$item->otheraccountnum." ".$item->transactcost;
						echo "<br />";
					}
				}
				
				else if($item->classify =='transfer'){
					if($mode == "all" ||  $item->transactionop == $mode){
						echo $item->transactiondate." ".$item->transactionop. " You transferred ".$item->transactcost." to ".$item->otheraccountnum;
						echo "<br />";
					}
				}
				
				else if($item->classify =='receive'){
					if($mode == "all" ||  $item->transactionop == $mode){
						echo $item->transactiondate." ".$item->transactionop. " You received ".$item->transactcost." from ".$item->accountnum;
						echo "<br />";
					}
				}
			}
		
		
		
	}
	
?>

<html>
	<head>
		<title>GuestBank OBS | View Account Record</title>
	</head>

	<body>
	<?php
		displayUserInfo();
	?>
	
	<a href="viewaccountrecord.php?mode=all">All</a>
	<a href="viewaccountrecord.php?mode=debit">Debit</a>
	<a href="viewaccountrecord.php?mode=credit">Credit</a>
	<?php
		
		
		$t = load_data();
		display_data($t,$_REQUEST['mode']);
	?>
	
	
	
	</body>
</html>