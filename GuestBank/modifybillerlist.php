<?php
	session_start();
	
	function insert_biller($accountnum, $billername){
		$conn = oci_connect('guestbank', 'kayato1');
		$query='insert into billerlist values(:accountnum, :billername)';
		$parsedQuery = oci_parse($conn, $query);
		oci_bind_by_name($parsedQuery, ':accountnum', $accountnum);
		oci_bind_by_name($parsedQuery, ':billername', $billername);
		$good=@oci_execute($parsedQuery);
		
		if (!$good) {
			$e = oci_error($parsedQuery);  // For oci_parse errors pass the connection handle
			
			echo "<script type='text/javascript'>alert('Biller already exists.');</script>";
		}
		else{
			echo "<script type='text/javascript'>alert('Biller successfully added.');</script>";
		}
		oci_commit($conn);
		oci_free_statement($parsedQuery);
		oci_close($conn);
		
	}
	
	function display_billerlist(){
		$conn = oci_connect('mainbank', 'kayato1');
		$query='select * from billerlist';
		$parsedQuery = oci_parse($conn, $query);
		oci_execute($parsedQuery);
		while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
			echo $row[1]."&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
			//echo "<a href='removefrombillerlist.php?accountnum=".$row[0]."'>Remove</a><br />";
			echo "<input type='button' value='Remove' onclick= 'removebiller(".$row[0].",".'"'.$row[1].'"'.");' />";
			
			echo "<br />";
		}
		
		oci_free_statement($parsedQuery);
		oci_close($conn);
		
	}
	
	
	if(isset($_POST['addbiller'])){
		$conn = oci_connect('mainbank', 'kayato1');
		$query='select * from account where accountype=\'compsavings\'';
		$parsedQuery = oci_parse($conn, $query);
		oci_execute($parsedQuery);
		$exists = false;
		while ($row = oci_fetch_array ($parsedQuery, OCI_BOTH)) {
			if($row[0] == $_POST['accountnum']){
				
				$exists = true;
				insert_biller($_POST['accountnum'], $row[4]);
				
			}
		}
		if(!$exists)
			echo "<script type='text/javascript'>alert('Account does not exist.');</script>";
	
		oci_free_statement($parsedQuery);
		oci_close($conn);
	}
	
?>
<html>
<head>
	<title>Modify Biller List</title>
	<script type='text/javascript'>
		
		function removebiller(accountnum,accountname){
			var yes= confirm("Are you sure you want to remove "+accountname+"?");
			if (yes){
				window.location = "removefrombillerlist.php?accountnum="+accountnum;

			}
		}
		
	
	</script>
</head>
<body>
	<form name="modify_biller_list" action=# method="POST">
		
		<?php
			display_billerlist();
		?>
	
		<p>
		<label for="accountnum">Account Number: </label>
		<input type="text" id="accountnum" name="accountnum" required/>
		<input type="submit" name="addbiller" value="Add"/>
		</p>
		
		
		
	</form>

</body>
</html>