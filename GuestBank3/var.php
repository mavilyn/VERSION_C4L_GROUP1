<?php
	include('class_lib.php');
	session_start();
	//global $billername;
	
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
	
		$query2 = 'select * from trans where accountnum=:accountnum';
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
	
	function show_data($trans,$type){
		
		echo "<table border='1'>";
		if($type == "all"){
		
			foreach($trans as $x){
				
			}
		}
		
		echo "</table>";
	}
	
?>

<html>
<head>
	<title></title>
</head>
<body>

<?php
	$trans = load_data();
	$trans_edited = prepare_data($trans);
	
	//show_data($trans, "all");
	
	var_dump($trans);
	
	echo "<hr />";
	var_dump($trans_edited);
?>
</body>

</html>