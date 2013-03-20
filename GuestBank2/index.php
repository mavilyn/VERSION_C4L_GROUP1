<?php
	include("class_lib.php");
	
	session_start();
	
	//code for handling login
	if(isset($_POST['login'])){
		
		if($_POST['username']==NULL || $_POST['password'] == NULL){
		?>
			<script>
				alert("Do not leave the username or password null.");
			</script>
		<?php
		}
		else{
			$conn = oci_connect('guestbank', 'kayato1');	
			$stid = oci_parse($conn,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM users
					WHERE  username= :username and password= :password'
				);

			oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
			oci_bind_by_name($stid, ':username', $_POST['username']);
			$encrypt = md5(md5($_POST['password']));
			echo $encrypt;
			oci_bind_by_name($stid, ':password', $encrypt);
			oci_execute($stid);
			oci_fetch($stid);

			
			if($num_rows == 0){
				?>
				
				<?php
			}							
			else{
				$query='select * from users where username = :username';
				$parsedQuery = oci_parse($conn, $query);
				oci_bind_by_name($parsedQuery, ':username', $_POST['username']);
				oci_execute($parsedQuery);
				/*$parsedQuery=($conn, 'select * from users where username = :username');
				oci_bind_by_name($parsedQuery, ':username', $_POST['username']);
				oci_execute($parsedQuery);
				*/

				while ($row = oci_fetch_array($parsedQuery, OCI_BOTH)) {
						$type=$row[2];
				}
				
				if($type == 'admin'){
					$sql='select * from admins where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $_POST['username']);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						$username=$row[0];
						$password=$row[1];
						$empid=$row[2];
						$mgrflag=$row[3];
					}
					
					
					$_SESSION['loginadmin']=1;
					$_SESSION['admin'] = new Admin($username,$password,$empid,$mgrflag);
					
					header("Location: admin_home.php");
						
					exit;
				}
				else{
					$sql='select * from client where username = :username';
					$stmt = oci_parse($conn, $sql);
					oci_bind_by_name($stmt, ':username', $_POST['username']);
					oci_execute($stmt, OCI_DEFAULT);

					while ($row = oci_fetch_array($stmt, OCI_BOTH)) {
						$fname=$row[3];
						$lname=$row[5];
						$accountnum=$row[2];
						$username=$row[0];
						$password=$row[1];
					}
					
					oci_close($conn);
					
					$_SESSION['loginclient']=1;
					$_SESSION['client'] = new Client($fname,$lname,$accountnum,$username,$password);
					
					header("Location: client_home.php");
					
					exit;
				}
				//$username = $_POST['username'];
				
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
		<script src="scripts/jquery.nivo.slider.js" type="text/javascript"></script>
		
	</head>

	<body>
		<div id="daya">
		<div id="top">
			
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
				
			</div>
			
			<div id="top_upper">
				
			</div>
			
			<div id="top_lower">
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
					<form name="loginform" action="#" method="post" onSubmit="return checkLogin()">
					<div id="form_wrapper">
						<label for="username">Username</label>
						<br />
						<input type="text" id="username" name="username"/>
						<br />
						<label for="password">Password</label>
						<br />
						<input type="password" id="password" name="password"/>
						<br />
						<input type="submit" src="images/loginbutton.png" id="login_button" name="login" />
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
			function checkLogin(){
				var username = document.forms['loginform']['username'].value;
				var password = document.forms['loginform']['password'].value;
				
				if(username == "" || username == null || password == "" || password ==null){
					
					alert("Kulang dre");
					return false;
					
					}
				else 
					return true;
			}
		</script>
		
				<?php
					if(isset($_POST['login'])){
						if(isset($num_rows) && $num_rows==0){
					?>
					<script>
					alert("Username and password did not match");
					</script>
					<?php }}
					?>
				
	
	</body>
</html>