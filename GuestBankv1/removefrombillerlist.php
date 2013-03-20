<?php
	$conn = oci_connect('mainbank', 'kayato1');
	$query='delete from bills where biller_accontnum=:accountnum';
	$parsedQuery = oci_parse($conn, $query);
	oci_bind_by_name($parsedQuery, ':accountnum', $_REQUEST['accountnum']);
	oci_execute($parsedQuery);
	//oci_commit($conn);
	
	$query='delete from biller_cust where biller_accontnum=:accountnum';
	$parsedQuery = oci_parse($conn, $query);
	oci_bind_by_name($parsedQuery, ':accountnum', $_REQUEST['accountnum']);
	oci_execute($parsedQuery);
	//oci_commit($conn);
	
	$query='delete from current_biller where biller_accontnum=:accountnum';
	$parsedQuery = oci_parse($conn, $query);
	oci_bind_by_name($parsedQuery, ':accountnum', $_REQUEST['accountnum']);
	oci_execute($parsedQuery);
	oci_commit($conn);

	$query='delete from billerlist where blist_accountnum=:accountnum';
	$parsedQuery = oci_parse($conn, $query);
	oci_bind_by_name($parsedQuery, ':accountnum', $_REQUEST['accountnum']);
	oci_execute($parsedQuery);
	oci_commit($conn);
	header('Location: modify_billerlist.php')
?>