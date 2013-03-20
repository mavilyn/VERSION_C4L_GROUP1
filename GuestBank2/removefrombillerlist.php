<?php
	$conn = oci_connect('guestbank', 'kayato1');
	$query='delete from billerlist where accountnum=:accountnum';
	$parsedQuery = oci_parse($conn, $query);
	oci_bind_by_name($parsedQuery, ':accountnum', $_REQUEST['accountnum']);
	oci_execute($parsedQuery);
	oci_commit($conn);
	header('Location: modifybillerlist.php')
?>