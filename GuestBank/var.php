<?php
	include('class_lib.php');
	session_start();

	function load_data(){
	
	$trans = null;
	$accountnum = $_SESSION['client']->get_accountnum();
	$conn = oci_connect('mainbank', 'kayato1');
	
	$query = 'select count(*) as NUM_ROWS from trans where accountnum=:accountnum';
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
		
		}
		return $trans;
	
	}
	
?>

<html>
<head>
	<title></title>
</head>
<body>
</body>

</html>