<?php
		include("class_lib.php");
		session_start();
		
		if(isset($_SESSION['client']))
		header("Location: client_home.php");
		if(isset($_SESSION['admin']))
		header("Location: admin_home.php");
		
		/*************************************/
		//initialize globals
			$fname = "";
			$mname = "";
			$lname = "";
			$month = "";
			$day = "";
			$year = "";
			$email = "";
			$contact = "";
			$spouse = "";
			$mother = "";
			$username = "";
			$brgy= "";
			$city= "";
			$province= "";
			$accountnum= "";
		/******************************************/
		if(isset($_POST['Submit'])){
			/**************************************/
			//Variables for enrollment
				$fname = $_POST['fname'];
				$mname = $_POST['mname'];
				$lname = $_POST['lname'];
				$month = $_POST['month'];
				$day = $_POST['day'];
				$year = $_POST['year'];
				$email = $_POST['email'];
				$contact = $_POST['contact'];
				$spouse = $_POST['spouse'];
				$mother = $_POST['mother'];
				$username = $_POST['username'];
				$password=md5(md5($_POST['password']));
				$brgy = $_POST['brgy'];
				$city = $_POST['city'];
				$province = $_POST['province'];					
				$gender=$_POST['gender'];
				$civilstat=$_POST['civilstat'];
				$secret= md5(md5($_POST['secret']));
				$answer=md5(md5($_POST['answer']));
				$activation = 1;
				$accountnum = $_POST['accountnum'];
				$atmpin = $_POST['atmpin'];
			
			/**************************************/

			/**************************************/
			//initialize connections
				$connGuest = oci_connect("guestbank", "kayato1");
				$connMain = oci_connect("mainbank", "kayato1");
			/**************************************/

			/******************************************************************/
			//check if account is already listed in the online bank's accounts
				$stid = oci_parse($connGuest,
					'SELECT COUNT(*) AS NUM_ROWS
					FROM client
					WHERE accountnum = :accountnum'
				);
				
				oci_define_by_name($stid, 'NUM_ROWS', $num_rows);
				oci_bind_by_name($stid, ':accountnum', $accountnum);
				oci_execute($stid);
				oci_fetch($stid);
			/******************************************************************/

			if($num_rows > 0) {		//if already enrolled
				echo '<script type=text/javascript>alert("Account number is already registered in the Guestbank Online Solutions!");return false;</script>';
			}
			
			else{		//if can enroll
				/******************************************************************/
				//check if is valid atmpin and accountnum	

					$stid2 = oci_parse($connMain,
						'SELECT COUNT(*) AS NUM_ROWS2 FROM account
						WHERE accountnum = :accountnum and atmpin = :atmpin'
					);

					oci_define_by_name($stid2, 'NUM_ROWS2', $num_rows2);
					oci_bind_by_name($stid2, ':atmpin', $atmpin);
					oci_bind_by_name($stid2, ':accountnum', $accountnum);
					oci_execute($stid2);
					oci_fetch($stid2);
				/******************************************************************/
				if($num_rows2>0){		
							/******************************************************************/
							//check  if there is a duplicate username
								$stid = oci_parse($connGuest,
									"SELECT COUNT(*) AS NUM_ROWS3
									FROM users
									WHERE username = :username"
								);
								oci_define_by_name($stid, 'NUM_ROWS3', $num_rows3);
								oci_bind_by_name($stid, ':username', $username);
								oci_execute($stid);
								oci_fetch($stid);
							/******************************************************************/

						if($num_rows3>0){
							echo "<script>alert('Username already exist. Please choose another username.');</script>";
						}
						else{		//VALID Enrollment, insert in the database 
							$birthday = $_POST['month']."/".$_POST['day']."/".$_POST['year'];
							//echo $birthday;
							$homeaddress = $_POST['brgy'].", ".$_POST['city'].", ".$_POST['province'];
										
										$query = 'select * from account where accountnum=:accountnum';
										$stid = oci_parse($connMain, $query);
										oci_bind_by_name($stid, ':accountnum', $accountnum);
										oci_execute($stid, OCI_DEFAULT);
				
										while ($row = oci_fetch_array($stid, OCI_BOTH)) {
											$branchcode = $row[5];	
										}	
										
										/******************************************************************/
										//insert at "users" table
										$stid4 = oci_parse($connGuest,
										'INSERT INTO users VALUES(:username, :password,'."'client'".')'
												);
										oci_bind_by_name($stid4, ':username', $username);
										oci_bind_by_name($stid4, ':password', $password);
										oci_execute($stid4);
										/******************************************************************/

										/******************************************************************/
										//insert at "client" table
										$stmt = "INSERT INTO client VALUES(:username, :password, :accountnum, :fname, :mname, :lname, :gender, :homeaddress, :civilstat, to_date(:birthday,'mm/dd/yyyy'), :email, :contact, :spouse, :mother, :secret, :answer, :activation, :branchcode)";
										$stid3 = oci_parse($connGuest, $stmt);
										oci_bind_by_name($stid3, ':username', $username);
										oci_bind_by_name($stid3, ':password', $password);
										oci_bind_by_name($stid3, ':accountnum', $accountnum);
										oci_bind_by_name($stid3, ':fname', $fname);
										oci_bind_by_name($stid3, ':mname', $mname);
										oci_bind_by_name($stid3, ':lname', $lname);
										oci_bind_by_name($stid3, ':gender', $gender);
										oci_bind_by_name($stid3, ':homeaddress', $homeaddress);
										oci_bind_by_name($stid3, ':civilstat', $civilstat);
										oci_bind_by_name($stid3, ':birthday', $birthday);
										oci_bind_by_name($stid3, ':email', $email);
										oci_bind_by_name($stid3, ':contact', $contact);
										oci_bind_by_name($stid3, ':spouse', $spouse);
										oci_bind_by_name($stid3, ':mother', $mother);
										oci_bind_by_name($stid3, ':secret', $secret);
										oci_bind_by_name($stid3, ':answer', $answer);
										oci_bind_by_name($stid3, ':branchcode', $branchcode);
										oci_bind_by_name($stid3, ':activation', $activation);
										oci_execute($stid3);
										/******************************************************************/
								
										$_SESSION['loginclient']=1;
										$_SESSION['client'] = new Client($_POST['fname'],$_POST['lname'],$_POST['mname'],$_POST['accountnum'],$_POST['username'],md5(md5($_POST['password'])));
										$_SESSION['new'] = 1;
										oci_commit($connGuest);
										oci_commit($connMain);
										/****************************************/	
										//close connections
										oci_close($connGuest);
										oci_close($connMain);
										/****************************************/

										header("Location: client_home.php");
										exit;
						}
				}
				else{
					echo '<script type=text/javascript>alert("Incorrect account number or pin!");return false;</script>';
				}
			}
		}
		if(isset($_POST['Cancel'])){
			header("Location: login.php");
			session_destroy();
		}
			
				
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
		
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>	GuestBank Online Banking Solutions | Enroll</title>
		<link rel="shortcut icon" href="favicon.ico" />
		<link rel="stylesheet" href="stylesheets/enroll_style.css"/>  
		<link type="text/css" href="stylesheets/jquery.jscrollpane.css" rel="stylesheet" media="all" />
		<link type="text/css" href="stylesheets/jquery.jscrollpane.lozenge.css" rel="stylesheet" media="all" />
		
		
		<script type="text/javascript" src="scripts/jquery-1.8.1.min.js"></script>
		<script type="text/javascript" src="scripts/jquery.jscrollpane.min.js"></script>
		<script type="text/javascript" src="scripts/jquery.mousewheel.js"></script>
		<script type="text/javascript" src="scripts/onlinebank.js"></script>
	
		<link href="stylesheets/jquery-ui-1.10.1.custom.css" rel="stylesheet">
		<script src="scripts/jquery-ui.min.js"></script>

		<!-- keyboard widget css & script -->
		<link href="Keyboard/css/keyboard.css" rel="stylesheet">
		<script src="Keyboard/js/jquery.keyboard.js"></script>
		<script src="Keyboard/js/jquery.keyboard.extension-typing.js"></script>
	
		<!-- modal css & script -->
		<script src="scripts/jquery.simplemodal.js"></script>
		<link rel="stylesheet" href="stylesheets/basic.css" />
	</head>
	
	<body>
	
	
	<script type="text/javascript">
		$(function(){$('#atmpin') .keyboard({ layout: 'custom', 
			customLayout: { 
				'default' : [ 
				'7 8 9'				, 
				'4 5 6', 
				'1 2 3', 
				'0 {a} {b}' ]}, 
		restrictInput : true, // Prevent keys not in the displayed keyboard from being typed in 
		preventPaste : true,  // prevent ctrl-v and right click 
		autoAccept : true ,
		maxLength : 4
		}) 
 .addTyping();});


	</script>
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
						<div id="home_link" class="toplink_div_dark"  onclick="location.href='index.php';" style="cursor: pointer;">
								HOME
						</div>
						
						<div id="enroll_toplink" class="toplink_div"  onclick="location.href='Enroll.php';" style="cursor: pointer;">
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
		<div id="body_outer">
		</div>
		<div id="body_center">
			<div id="form_header">
				<img src="images/enrollment_header.png" alt="" />
			</div>
			
			<div id="form_wrapper">
			
				
				<form name = "enroll_form" method ="post" action = "Enroll.php" onSubmit="return checkEnroll();">
				
				<h1 class="form_section_header"> Bank Account Information</h1>
				<div class="section_rule"> </div>
				
				<div id="accountinfo_left">
						<label class="form_label" for="accountnum">*ATM Account Number: </label>
						<br />
						<br />
						<br />
						
						<label class="form_label" for="atmpin">*ATM Pin:</label>
					</div>	
				<div id="accountinfo_right">
						<input type = "text" id ="accountnum" name="accountnum" max="9999999999"/>
						<span id="accountnumErr"></span>
						<br />
						<input type = "password" id="atmpin" name="atmpin" maxlength="4">
						<span id="atmpinErr"></span>
						<br />
				</div>
				
				<h1 class="form_section_header"> Personal Information</h1>
				<div class="section_rule"> </div>
				
				<div id="personalinfo1_left">
					<label for="fname">*First Name:</label> 
					<br /> 
					<br /> 
					<br /> 
					<label for="mname">*Middle Name:</label> 
					<br /> 
					<br /> 
					<br /> 
					<label for="lname">*Last Name: </label>
					<br /> 
					<br /> 
					<label>*Gender: </label>
					<br /> 
					<br />
					<label>*Home Address:</label> 
					<br />
					<br />
					<label>House Number, Brgy./Subdivision:</label> 
					<br />
					<br />
					<label>City/Town:</label> 
				
					<br />
					<br />
					<label>Province, Zip code:</label> 
					<br />
					<br />
					<br />
					<label>*Civil Status:</label> 
					<br /> 
					<br />
					<br />
					<label>*Birthdate:</label>
					
					<br /> 
					<br />
					<br />
				</div>
				<div id="personalinfo1_right">
					<input type="text" name="fname" maxlength="30" value="<?php echo $fname;?>"/>
					<span  id="fnameErr" ></span>
					<br />		
					<input type="text" name="mname" maxlength="30" value="<?php echo $mname;?>"/>
					<span  id="mnameErr"></span>		
					<br />
					<input type="text" name="lname" maxlength="30" value="<?php echo $lname;?>"/> 
					<span  id="lnameErr"></span>	 
					<br />	
					<input type="radio" name="gender" id="male" value="male" checked/>
					<label for="male">Male</label>
					<input type="radio" name="gender" id="female"value="female"/> 
					<label for="female">Female</label> 
					<span  id="genderErr"></span>	
					<br />
					<br />
					
					<span id="homeaddressErr" ></span>	
					<br />
					<br />
					<input type="text" id="brgy" name="brgy" maxlength="30" value="<?php echo $brgy;?>"/>
					<br />
					<input type="text" id="city" name="city" maxlength="30" value="<?php echo $city;?>"/>
					<br />
					<input type="text" id="province" name="province" maxlength="20" value="<?php echo $province;?>"/>
					<br />
					
					
					<select name="civilstat" id="civilstat">
						<option value="single"> Single </option>
						<option value="married"> Married </option>
						<option value="annuled/divorced"> Annulled/Divorced </option>
						<option value="Widowed"> Widowed </option>
					</select>
					
			
						<br />
						<br />
					
					<input type="text" id="mon" name="month" min="1" max="12" placeholder="MM" maxlength="2"/> - 
					<input type="text" id="day" name="day" min="1" max="31" placeholder="DD" maxlength="2"/> - 						
					<input type="text" id="year" name="year" min="1900" max="2100" placeholder="YYYY" maxlength="4"/>
					<span id="birthErr"></span>		
				</div>
					
				<div id="personalinfo2_left">
					 
					
					<label for="email">*Email Adress:</label>
					
					<br /> 
					<br />
					<br />
					
					<label for="contact">Contact Number: </label>
					<br /> 
					<br />
					<br />
					
					<label for="spouse">Spouse's Name:</label>
					<br /> 
					<br />
					<br />
					
					<label for="mother">*Mother's Maiden Name: </label>
					<br /> 
					<br />
					<br />
					
					
					
					
				</div>
					
				<div id="personalinfo2_right">	
					
					<input type="email" name="email" maxlength="50" value="<?php echo $email;?>"/> 
					<span id="emailErr" ></span>	
					<br />
					
					<input type="text" name="contact" maxlength="50" value="<?php echo $contact;?>"/> 
					<span id="contactErr" ></span>
					<br />
					
					<input type="text" name="spouse" maxlength="100" value="<?php echo $spouse;?>"/> 
					<span id="spouseErr" ></span>
					<br />
					
					<input type="text" name="mother" maxlength="100" value="<?php echo $mother;?>"/> 
					<span id="motherErr" ></span>
					<br />
					
					</div>
					
				<h1 class="form_section_header"> Online Account Information</h1>
				<div class="section_rule"> </div>
					
				<div id="onlineinfo_left">
					<label for="username">Username:</label>
					
					<br /> 
					<br />
					<br />
					
					
					<label for="password">Password:</label>
					
					<br /> 
					<br />
					<br />
					
					<label for="confirmpassword">Confirm Password:</label>
					<br />
					<br />
					<br />
					<label>Secret Question:</label>
					
					<br />
					<br />
					<br />
					<label for="answer">Answer:</label>
					
					<br />
					<br />
					<br />
					<label for="answer">Confirm Answer:</label>
					</div>
				
				<div id="onlineinfo_right">
					<input type="text" name="username" maxlength="20" value="<?php echo $username;?>"/> 
					<span id="usernameErr" ></span>
					<br />	
					<input type="password" name="password" id = "password" maxlength="20"/> 
					<span id="passwordErr" ></span>
					<br />
					<input type="password" name="confirmpassword" id = "confirmpassword"maxlength="20"/> 
					<span id="confirmpasswordErr" ></span>
					<br />
					<select name="secret" id="secret">
						<option value="Why do birds suddenly appear?"> Why do birds suddenly appear? </option>
						<option value="How can I leave without you?"> How can I leave without you? </option>
						<option value="What makes you beautiful?"> What makes you beautiful? </option>
					</select> 
					<span id="secretErr" ></span>
					<br />
					<input type="text" name="answer" maxlength="50"/> 
					<span id="answerErr" ></span>
					<br />
					<input type="text" name="confirmanswer" maxlength="50"/> 
					<span id="confirmanswerErr" ></span>
					
					<br />
					
					</div>
					
					
					<div id="almost_there">
						
						<input type="checkbox" name="agreed" id="agreed"/>
						<label>I have read & agreed to the <span id="tnc">Terms and Conditions</span></label> <br />
						<input type="submit" id="submit" name="Submit" value="Enroll!" />
					
						
		
					</div>
				</form>
			</div>	
			
		</div>
	
	</div>
	
	<div id="bottom_bottom">
					<p class="bot_text" id="copy">&copy;2013 <span id="span_baba">GuestBank</span ></p>
					<p class="bot_text" id="botlinks">
						<a href="home.php">Home</a> | 
						<a href="Enroll.php">Enroll</a> | 
						<a href="aboutus.php">About Us</a> |
						<a href="features.php">Features</a> |
						<a href="faqs.php">FAQS</a> |
						<a href="sikyo.php">Security Policy</a>
					</p>
	</div>
	
	
	</body>
</html>
