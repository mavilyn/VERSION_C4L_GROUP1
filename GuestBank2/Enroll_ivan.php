<?php
				if (isset($_POST['Submit'])) {
						$submit = true;
						$fname=$_POST['fname'];
						$mname=$_POST['mname'];
						$lname=$_POST['lname'];
						//$gender=;
						//$civilstat
				}
				else{
					$submit = false;
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
	</head>
	
	<body>
	
	<div id="top">
			
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
				
			</div>
			
			<div id="top_upper">
				
			</div>
			
			<div id="top_lower">
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
				<form name = "enroll_form" method ="post" action = "Enroll.php">
					<h1 class="form_section_header"> Bank Account Information</h1>
					<div class="section_rule"> </div>
					<div id="accountinfo_left">
						<label class="form_label" for="accountnum">ATM Account Number: </label>
						<br />
						<br />
						<br />
						
						<label class="form_label" for="atmpin">ATM Pin:</label>
					</div>	
					<div id="accountinfo_right">
						<input type = "text" id ="accountnum" name="accountnum" max="9999999999"/><br />
						<input type = "password" id="atmpin" name="atmpin" maxlength="4"><br />
					</div>
					
					<h1 class="form_section_header"> Personal Information</h1>
					<div class="section_rule"> </div>
					
					<div id="personalinfo1_left">
					<label for="fname">First Name:</label> 
					<br /> 
					<br /> 
					<br /> 
					<label for="mname">Middle Name:</label> 
					<br /> 
					<br /> 
					<br /> 
					<label for="lname">Last Name: </label>
					<br /> 
					<br /> 
					<label>Gender: </label>
					<br /> 
					<br />
					<label>Civil Status:</label> 
					<br /> 
					<br />
					<br />
					<label>Birthdate:</label>
					
					<br /> 
					<br />
					<br />
					</div>
					<div id="personalinfo1_right">
						<input type="text" id ="fname" name="fname" maxlength="50"/> *
							<?php if(empty($fname) && $submit == true){
								echo "Complete this field.";
							  }
							?>
						<br />		
						<input type="text" id="mname" name="mname" maxlength="50"/> *
							<?php if(empty($mname) && $submit == true){
								echo "Complete this field.";
							   }
							?>
						<br />
					<input type="text" id="lname" name="lname" maxlength="50"/> *
							<?php if(empty($lname) && $submit == true){
								echo "Complete this field.";
							   }
							?>
							
						<br />	
					<input type="radio" name="gender" value="male"/>
					<label for="male">Male</label>
					<input type="radio" name="gender" value="female" /> 
					<label for="female">Female</label> *
						<?php if(empty($_POST['gender']) && $submit == true){
							echo "Indicate gender.";}
						?>
						<br />
						<br />
					<select name="civilstat" id="civilstat">
						<option value="single"> Single </option>
						<option value="married"> Married </option>
						<option value="annuled/divorced"> Annulled/Divorced </option>
						<option value="Widowed"> Widowed </option>
					</select> *
						<?php /*if(empty($_POST['civilstat']) && $submit == true){
								echo "Complete this field.";
							   }*/
						?>
			
						<br />
						<br />
					
					<input type="number" name="month" min="1" max="12" placeholder="MM"/> - 
					<input type="number" name="day" min="1" max="31" placeholder="DD"/> - 						
					<input type="number" name="year" min="1900" max="2100" placeholder="YYYY"/><br />
					</div>
					<div id="personalinfo2_left">
					 
					
					<label for="email">Email Adress:</label>
					
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
					
					<label for="mother">Mother's Maiden Name: </label>
					<br /> 
					<br />
					<br />
					
					
					
					
					</div>
					<div id="personalinfo2_right">	
					
					<input type="email" id ="email" name="email" maxlength="50"/> <br />
					
					<input type="text" id ="contact"name="contact" maxlength="50"/> <br />
					
					<input type="text" id="spouse" name="spouse" maxlength="100"/> <br />
					
					<input type="text" id="mother" name="mother" maxlength="50"/> <br />
			
					
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
					</div>
					<div id="onlineinfo_right">
					<input type="text" id="username" name="username" maxlength="50"/> <br />
			
					<input type="password" id="password" name="password" maxlength="20"/> <br />
			
					<input type="password" id="confirmpassword" name="confirmpassword" maxlength="20"/> <br />
					
					<select id="secret" name="secret">
						<option value="q1"> Why do birds suddenly appear? </option>
						<option value="q2"> Why can't you see that you belong with me? </option>
						<option value="q3"> Do you know makes you beautiful? </option>
					</select> <br />
					<input type="text" id="answer" name="answer" maxlength="50"/> <br />
		
					</div>
					
					<h1 class="form_section_header"> GuestBank OBS General Terms and Conditions</h1>
					<div class="section_rule"> </div>
					
					<div class="scroll-pane-arrows">
					
					<p>I.   FEATURES OF iAccess SERVICES 
1.   Requirement - Fill-out and submit two (2) copies of iAccess Enrollment and Maintenance Agreement form and 
	iAccess General Terms and Conditions.
2.  Accessibility 
a. I acknowledge that iAccess is a facility granted by Land Bank of the Philippines (LANDBANK/Bank) which 
	I may avail of for as long as it is offered and provided that I continue to be qualified under its terms 
	and conditions. 
b. Only enrolled deposit accounts can be accessed via iAccess with the use of an internet enabled computer 
	(Microsoft Internet Explorer at least 5.5 version or higher) and its corresponding iAccess ID and 
	Password. I will have to formally enroll/nominate additional account/s that I want to access by 
	submitting a duly signed/filled out iAccess Enrollment and Maintenance Agreement. 
c. I understand that for my own safety, LANDBANK will LOCK my iAccess ID should the wrong Password be used 
	three (3) times. 
d. While iAccess is available twenty-four (24) hours a day, seven (7) days a week, some or all of the services 
	may not be available at certain times due to designated service periods, maintenance, computer, 
	telecommunication, electrical or network failure and/or any other reasons beyond the Bank’s control.
e. Online transactions initiated through iAccess before system downtime on a banking day shall be posted to 
	my account on the same day. All transactions completed after this system downtime on a banking day 
	will be posted on the next banking day. Banking days are from Monday through Friday, except for 
	banking holidays. 
3.  iAccess Online Security Policy 
a. At LANDBANK, you are always assured that all your Internet banking transactions are safe and secure. The 
	LANDBANK Retail Internet Banking facility, iAccess, takes great measures to ensure that our security 
	practices conform to the best banking standards and adequately respond to all your needs. 
b. To ensure that the privacy of your account information and banking transactions are maintained, LANDBANK 
	has set forth the following: 
Security Systems 
LANDBANK deploys intrusion detection systems, firewalls, encryption systems such as 128-bit Secure 
Sockets Layer (SSL) and other internal controls which are meant to safeguard, physically and logically, 
all our servers and information systems, including the data stored in these systems. Furthermore, it has an 
in house Network Operations Department that secures the maintenance of the whole facility. 
Website Authentication 
The LANDBANK iAccess facility is secure, using Verisign's Security Certificate for you to verify the authenticity 
of the iAccess site. 
At times, it may be necessary for you to verify the authenticity of the iAccess site in order not to fall victim 
to email scams. For example, directing clients to seemingly legitimate sites and mislead them    into providing 
vital account information to entities not authorized by the Bank. The Verisign Logo attached on all our iAccess 
pages, when clicked, securely authenticates the iAccess site. The best, safest and recommended way to access 
the iAccess website is by typing www.lbpiaccess.com at the browser address bar. 
Third-Party Agreements 
Certain transactions involving third parties – Third party Fund Transfers and Bills Payment, all require enrollment 
of accounts using the duly accomplished Enrollment and Maintenance Agreement form submitted to us for 
verification. With this policy, you are assured that LANDBANK will honor requests for transfers/payments only 
to and from those that you have signed for. 
Email 
All financial transactions made through the LANDBANK iAccess will generate a corresponding email which will be 
sent to your personal email address. We encourage you to continually check and verify your emails 
(including the one incorporated in the iAccess system) to assure that all your iAccess transactions are in order.  
Password Protection 
All clients visiting the iAccess website pass through the Log-in authentication process. Clients are advised to 
use a password that is easy to remember but hard for others to guess. Ensure to keep password confidential at 
all times by not writing or divulging it to anyone. Change password frequently, or change it immediately once 
password has been compromised. 
4. Services and Business Rules 
a. Account Information – I can view the “real-time” outstanding and available balance (including amounts on hold/float) 
	and transaction history of my enrolled peso savings (with ATM access) and/or current account/s. 
1) The additional account/s shall be enrolled in the LANDBANK iAccess facility through the branch of account 
	(or nominated servicing branch). 
2) The account balances and history downloaded or printed are for reference purposes only. Official bank statement 
	shall be requested from the depository branch. 
3) Today’s Transaction only includes over-the-counter transactions for the day. 
b. Check Status Inquiry – I can view the status of checks issued (encashed/negotiated). Checks issued within the last 60 
	days may be inquired. 
c. Returned Check Inquiry – I can view the total amount and number of checks I deposited but subsequently returned 
	within the last 60 days.
d. Bills Payment – I am allowed to pay bills online for enrolled merchants. 
1) Enrollment of subscriber’s account number through the Branch is required. 
2) Service fees shall be debited on the account of the merchant or charged against Average Daily Balance (ADB) on 
	deposit float. 
3) Service Period is up to 11:00 PM daily. 
e. Fund Transfer – I am allowed to transfer funds from one enrolled deposit account to another account. I may choose 
	either own accounts or a third party account as destination accounts. 
1) Enrollment of source and destination accounts through the Branch is required. 
2) Source accounts can be enrolled as destination accounts and vice-versa. 
3) Third party accounts cannot be enrolled as source accounts. 
4) Only a maximum of 5 third party accounts can be enrolled as destination accounts. 
5) Service Period is up to 11:00 PM daily. 
6) Future-dated fund transfer up to 365 days from date of transaction may be initiated daily. 
7) Cancellation of future-dated fund transfer must be made a day before, up to   11:00 PM  from the specified 
	date of transfer. 
f. Checkbook Reorder – I am allowed to request for checkbook online. 
1) Service Period is up to 11:00 PM daily. 
2) Manual inquiry from the client’s branch of account, the status of requisition shall be after three (3) weeks from 
	date of request approval. 
3) Maximum of nine (9) checkbooks per account per week is allowed. 
4) Payment for the cost of checkbook shall be automatically debited by the System upon approval by the 
	Branch of account. 
 
II. GENERAL TERMS AND CONDITIONS 
1.     LANDBANK shall provide us the iAccess internet banking services in accordance with existing laws, rules and 
	regulations and Republic Act 8792 (e-Commerce Law) as well as LANDBANK’s business rules and regulations 
	relative to the operation of the iAccess internet banking facility. 
2.     Either party may terminate this Agreement by giving ten (10) banking days advance written notice. 
3.     Effectivity of deletion shall be within five (5) days from the Branch’s receipt of the request for deletion. 
4.     Any transaction initiated on an enrolled account prior to its deletion is considered eligible transaction. 
5.     I recognize LANDBANK’s proprietary interest in iAccess and I shall use its modules for their intended purpose only. 
6.     I shall have sole access to my iAccess account/s by taking the necessary steps to keep my iAccess ID and Password 
	confidential. 
7.     I authorize the Bank to act upon any instructions which are identified by the use of my iAccess ID and Password. I 
	hereby accept full responsibility and accountability for all transactions executed via iAccess. 
8.     I undertake to change my Password from time to time as I deem necessary. Resetting of my password shall be 
	officially recognized by the Bank upon my written request. 
9.     LANDBANK shall consider as valid and binding any instruction given or transaction made using my iAccess ID and 
	Password. LANDBANK shall not be liable for any unauthorized action or transaction using my iAccess ID and 
	Password. The Bank shall not be obliged to investigate the authenticity of instructions sent via iAccess. 
	However, LANDBANK, as it deems necessary, is entitled to verify any instructions given through sending e-mail 
	online or via telephone or any other means. 
10. LANDBANK may cancel or refuse to execute any of my instruction/s at any time without incurring any liability if these 
	are against bank policies and iAccess business rules, deemed illegal and/or detrimental to the bank without prior 
	notice. 
11. For multiple transactions coming from one account with insufficient balance, LANDBANK, in its sole discretion, may 
	determine which of the transaction requests to complete. 
12. LANDBANK shall not be held liable for outstanding charges payable to the destination account by reason of the posting 
	of outstanding checks drawn against the source account which earlier remained unposted for whatever reason 
	thereby creating a temporary source account balance undiminished by the amount of the unposted checks. 
13. I shall verify, check and validate all my iAccess transactions and maintenance if these have been processed by iAccess. 
	If not, I shall notify LANDBANK immediately by sending e-mail online or via telephone or any other means. 
14. A Reference Number shall be assigned to me for every submitted transaction. However, a financial transaction may be 
	denied for non-compliance of terms and conditions and business rules of the iAccess (e.g. if the designated account 
	is insufficiently funded, Account/Subscriber Number is incorrect). 
15. Confirmation for every transaction conducted through iAccess shall be through the Acknowledgment/Notification Page 
	or Transaction History function of iAccess which I can print from my own computer terminal. Otherwise, I can 
	verify through the monthly bank statements issued by my branch of account. 
16. I understand that any online transaction initiated through iAccess before system downtime on a banking day shall be 
	posted to my account on the same day. All transactions after system downtime on a banking day or completed 
	on a Saturday, Sunday or legal/special holidays, will be posted on the next banking day. Banking days are from 
	Monday to Friday, except for legal/special holidays. 
17. In case of system failure: all pending future-dated transactions for the day shall be processed once the system is ready; 
	cancellation of transactions initiated in iAccess shall not be allowed. If system failure lasted until the next banking 
	day, all pending transactions from the previous day shall be automatically cancelled by the system. In this case, 
	LANDBANK shall coordinate with me through sending e-mail online or via telephone or any other means. 
18. All information given by the caller, when matched with the challenge questions asked by the helpdesk administrators 
	shall be considered as valid. And that the caller shall be treated as the truthful owner/user of the account. Thus, 
	the LANDBANK Helpdesk Administrator shall not be held liable for any information given by the caller. 
19. I shall provide LANDBANK with a correct and operational e-mail address. The Bank shall not be liable for any undelivered 
	e-mail communication or from unauthorized interception or use of data relating to me or to my account(s). I shall 
	promptly notify the Bank of any change in our e-mail address, contact numbers, business address or any other 
	information which may affect communication by sending e-mail online or via telephone or any other means. 
20. I shall notify LANDBANK immediately upon receipt of any data or information through iAccess not intended for me. I 
	shall delete such data or information from my terminal immediately. I shall ensure the strict confidentiality of 
	such information. 
21. I shall promptly report any discrepancies, omissions, inaccuracies or incorrect entries in LANDBANK’s statement, any 
	unauthorized transactions made and instructions not implemented through e-mail, telephone or any other means. 
22. LANDBANK shall not be liable if my bill remains unpaid and the biller discontinues/cancels my coverage. 
23. If in case my deposit account is tagged with special instructions, I shall hold LANDBANK free from any obligation and 
	liability on the effects of these special instructions on my transactions. 
24. LANDBANK reserves the right to determine the scope of iAccess, change the daily cut-off time, modify, restrict, 
	withdraw, cancel or disconnect any service without prior notice. In this case, LANDBANK shall coordinate with 
	us through sending e-mail online or via telephone or any other means. It may also deactivate, suspend or 
	discontinue any service due to mishandling of accounts as defined by the Bank’s standard operating procedures 
	or, if in the Bank’s judgment, my continued access of iAccess may adversely affect the security of the system 
	without prior notice. 
25. LANDBANK shall not be liable for any cause beyond its control such as problems due to maintenance, telecommunication, 
	electrical, network failure, computer hardware or software (including viruses and bugs) or related/incidental 
	problems that may be attributed to the services of an information service provider. 
26. LANDBANK may amend/supplement this Agreement from time to time with effectivity date as specified in the e-mail 
	notice. Notice of the amendment/supplement sent through e-mail at the address shown on my account records shall 
	suffice. Thereafter, continued use of the iAccess will constitute acceptance of the modification/supplement to the 
	Agreement. 
27. LANDBANK may limit my use of the services or terminate this Agreement once my account becomes dormant, closed, 
	garnished, escheated, or has violated any of the terms and conditions and business rules of the iAccess. 
28. LANDBANK may, in the future, impose charges on this arrangement within legal and regulatory limits and I hereby 
	authorize the Bank to impose the said charges accordingly upon notice through sending e-mail online or via 
	telephone or any 	other means without need for further demand, notice or consent. The Bank shall not be held 
	liable for the failure of transactions due to insufficient funds resulting from the deduction of authorized charges. 
29. LANDBANK shall not be liable for any loss or damage in connection with any unauthorized interception or use of data 
	relating to me or my account(s), including the missending thereof. 
30. I agree to be bound by the laws, rules, regulations and official issuances applicable to iAccess now existing or which may 
	later be issued, as well as such other terms and conditions governing the use of other facilities, benefits or services 
	the Bank may make available to me in connection with iAccess. 
         
I hereby certify to have read and understood the foregoing terms & conditions.  Further, I agree to be governed by the provisions of these terms & conditions.

					</div>
					<script type="text/javascript">
					
					$(function{
						$('.scroll-pane').jScrollPane();
						$('.scroll-pane-arrows').jScrollPane(
						{
							showArrows: true,
							horizontalGutter: 10
						}
						);
						});
	
		
					</script>
			 
						 
			
			<div id="agree_wrap">
		 	<input type="checkbox" name="agreed" id="agreed"/>
			<label for="agreed">I have read & agreed to the Terms and Conditions presented</label>	
			</div>
			
			<input type="image" src="images/enrollbutton.png" name="Submit" value="Submit" />
		</form>
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
	</body>
</html>
