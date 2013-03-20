<?php
	include("class_lib.php");
	session_start();

	$username = "";

	if(isset($_POST['login'])){
		$conn = oci_connect('guestbank', 'kayato1');

		/*********************************************/
		//variable declarations
			$username = $_POST['username'];
			$encryptedPW = md5(md5($_POST['password']));
		/*********************************************/
		/***********************************************************/
		//check if correct login credentials by tracking through $num_rows	
			$stid = oci_parse($conn,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM users
					WHERE  username= :username and password= :password'
				);

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_bind_by_name($stid, ':username', $username);
			oci_bind_by_name($stid, ':password', $encryptedPW);
			oci_execute($stid);
			oci_fetch($stid);
		/***********************************************************/

			if($num_rows == 0){
				?>
				<script>
					$(document).ready(function()
					{
					  	$( "#loginErr" ).fadeIn();
					 });
				</script>
				<?php
			}							
			else{
				/******************************************************/
				//distinguish type if administrator or client
				$query='select * from users where username = :username';
				$parsedQuery = oci_parse($conn, $query);
				oci_bind_by_name($parsedQuery, ':username', $username);
				oci_execute($parsedQuery);

				while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
						$type=$row[2];
				}
				/******************************************************/

				if($type == 'admin'){
					$sql='select * from admins where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $username);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						$username=$row[0];
						$password=$row[1];
						$empid=$row[2];
						$mgrflag=$row[3];
						$branchcode=$row[4];
					}

					/*****************************************************************************/
						//initialize admin session, go to admin_home
						$_SESSION['loginadmin']=1;
						$_SESSION['admin'] = new Admin($username,$password,$empid,$mgrflag,$branchcode);

						header("Location: admin_home.php");
						exit;
					/*****************************************************************************/
				}

				else{
					$sql='select * from client where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $username);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						//if(isset($row[3])){
							$fname=$row[3];
							$lname=$row[5];
							$mname = $row[4];
							$accountnum=$row[2];
							$username=$row[0];
							$password=$row[1];
							$flag = $row[16];	
					}

					oci_close($conn);		//close connection
					/*****************************************************************************/
					//initialize admin session, go to client_home
					if($flag == 1){
						$_SESSION['loginclient']=1;
						$_SESSION['client'] = new Client($fname,$lname,$mname,$accountnum,$username,$password);
						header("Location: client_home.php");
					}
					else{
						?><script>
							$(document).ready(function()
							{
							  	$( "#loginErr" ).fadeIn();
							 });
						</script>
<?php
					}
					/*****************************************************************************/

				}
			}
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
	
	<head>
		
		<title>GuestBank Online Banking Solutions | Home</title>
		
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="stylesheets/index_style.css"/>  
		<link rel="stylesheet" href="stylesheets/slider_style.css"/>  
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		<script type="text/javascript" src="scripts/jquery_validations.js"></script>
		<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
		
	</head>

	<body>
		<div id="top">
		
			<div id="top_upper">
			</div>
			<div id="top_lower">
			</div>
			
			<div id="top_center">
			
				
				
				<div id="logo" onclick="location.href='index.php';" style="cursor: pointer;">
					<img id="logo_img" src="images/logo_small.png" alt ="" />
				</div>
				
				<div id="top_center_right">
				
					<div id="link_area">
						<div id="home_link" class="toplink_div"  onclick="location.href='index.php';" style="cursor: pointer;">
								HOME
						</div>
						
						<div id="enroll_toplink" class="toplink_div_dark"  onclick="location.href='Enroll.php';" style="cursor: pointer;">
								ENROLL
						</div>
						
						<div id="aboutus_link" class="toplink_div_dark" onclick="location.href='aboutus.php';" style="cursor: pointer;">
								ABOUT US
						</div>
						
						<div id="features_link" class="toplink_div_dark" onclick="location.href='features.php';" style="cursor: pointer;">
								FEATURES
						</div>
						
						<div id="faqs_link" class="toplink_div_dark" onclick="location.href='faqs.php';" style="cursor: pointer;">
								FAQS
						</div>
						
						<div id="sekyu_link" class="toplink_div_dark" onclick="location.href='sikyo.php';" style="cursor: pointer;">
								SECURITY POLICY
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
				<div id="slider_area">
					
					<div id="slider">
						<a href="enroll.php">
							<img src="slider/pane2.png" alt=""/>
						</a>
						<img src="slider/pane3.png" alt=""/>
					</div>
					<script type="text/javascript">
						$(window).load(function() {$('#slider').nivoSlider();});
					</script>
				</div>
				
				<div id="user_area">
					<div id="login_header">
						<img src="images/login.png" alt="" />
					</div>
					<div id="login_body">
					<form name="loginform" action="#" method="post" >
					<div id="form_wrapper">
						<script>
							$(function()
							{
								$( "#loginErr" ).hide();			
							 });
						</script>
						<p id="loginErr" style="color:red;font-weight:bold; font-size: 15px;">Invalid username or password.</p>		
						<label for="username">Username</label>
						<br />
						<input type="text" id="username" name="username" value="<?php echo $username;?>"/>
						<br />
						<label for="password">Password</label>
						<br />
						<input type="password" id="password" name="password"/>
						<br />
						<input type="submit" id="login_button" name="login" value="Login"/>
						<br />
						<br />
						<p class="form_text">Don't have an online account? <br />Click <a id="enroll_link" href="Enroll.php">here</a> to enroll.</p>
					</div>
					</form>
					
				</div>
			    </div>
				</div>
				
				<div id="bottom_top">
					<div id="bottom_top_content">
						<div id="bottom_top_content_left">
								<img class="side_img" src="images/security.png" alt="" />
								<h1>Security Tip:</h1>
								<p class="bottom_info">For your own protection, we encourage you to avoid using public computers in 
								accessing your account. In instances when you have to, please make sure to logout properly after completing
								your transaction/s.</p>

						</div>	
						<div id="bottom_top_content_right">
								<img class="side_img" src="images/assistance.png" alt="" />
								<h1>Do you need assistance?</h1>
								<p class="bottom_info">Please call our Helpdesk at 405-7801 <br />
								 (1-800-10-405-7001 for domestic toll-free calls)</p>

						</div>	
					</div>
				</div>
				
				<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span>GuestBank</span></p>
					<p class="bot_text" id="botlinks">
						<a href="home.php">Home</a> | 
						<a href="Enroll.php">Enroll</a> | 
						<a href="aboutus.php">About Us</a> |
						<a href="features.php">Features</a> |
						<a href="faqs.php">FAQS</a> |
						<a href="sikyo.php">Security Policy</a>
					</p>
				</div>
				
		</div>
		<script type="text/javascript">
			/*function checkLogin(){
				var username = document.forms['loginform']['username'].value;
				var password = document.forms['loginform']['password'].value;
				
				if(username == "" || username == null || password == "" || password ==null){
					
					alert("Kulang dre");
					return false;
					
					}
				else 
					return true;
			}*/
		</script>
		
				<?php
					if(isset($_POST['login'])){
						if(isset($num_rows) && $num_rows==0){
					?>
					<script>
					$(document).ready(function()
					{
					  	$( "#loginErr" ).fadeIn();
					 });
					</script>
					<?php }}
					?>
				
	
	</body>
</html>