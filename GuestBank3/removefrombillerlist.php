<?php
	$conn = oci_connect('mainbank', 'kayato1');
	$query='delete from billerlist where blist_accountnum=:accountnum';
	$parsedQuery = oci_parse($conn, $query);
	oci_bind_by_name($parsedQuery, ':accountnum', $_REQUEST['accountnum']);
	oci_execute($parsedQuery);
	oci_commit($conn);
	header('Location: modify_billerlist.php')
?>