<?php
	if(isset($_POST['Submit'])){
		$conn = oci_connect("mainbank","kayato1"); //connection
		$query = oci_parse($conn,'INSERT INTO employee VALUES(:empid, :fname,:mname, :lname, :gender, :manager)');
		oci_bind_by_name($query, ':empid', $_POST['empid']);
		oci_bind_by_name($query, ':fname', $_POST['fname']);
		oci_bind_by_name($query, ':mname', $_POST['mname']);
		oci_bind_by_name($query, ':lname', $_POST['lname']);
		oci_bind_by_name($query, ':gender', $_POST['gender']);
		oci_bind_by_name($query, ':manager', $_POST['manager']);
		oci_execute($query);
		oci_close($conn);
	}
?>

<html>
	<head>
		<title> Add Employee </title>
	</head>
	<body>
		<form name="addEmp" action="#" method="post">
			Employee id: <input type="text" name="empid" /> <br/>
			First name: <input type="text" name="fname" /> <br/>
			Middle name: <input type="text" name="mname" /> <br/>
			Last name: <input type="text" name="lname" /> <br/>
			Gender: <input type="radio" name="gender" id="male" value="male"/>
					<label for="male">Male</label>
					<input type="radio" name="gender" id="female"value="female"/> 
					<label for="female">Female</label> 
					<span id="genderErr" style="color:red;font-weight:bold;"></span>	
						<br />
			Manager: <input type="text" name="manager" /> <br/>
			<input type="submit" name="Submit" value="Add Employee" />
		</form>
	</body>
</html>