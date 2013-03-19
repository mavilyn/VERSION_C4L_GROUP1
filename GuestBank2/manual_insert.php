<?php
$conn = oci_connect('guestbank', 'kayato1');	
			$stid = oci_parse($conn,
					'insert into users values(:username, :password, :type)'
				);

			$uname="ivan";
			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_bind_by_name($stid, ':username', $uname);
			$encrypt = md5(md5('1234'));
			echo $encrypt;
			$u = 'user';
			oci_bind_by_name($stid, ':password', $encrypt);
			oci_bind_by_name($stid, ':type', $u);
			oci_execute($stid);
			oci_commit($conn);
?>