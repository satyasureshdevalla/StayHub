<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>Login - Stay Hub</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<link rel="stylesheet" href="main.css">
	<script type="text/javascript" src="main.js"></script>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once('loginCheck.php'); ?>
	<?php require_once('websitesettings.php'); ?>
</head>
<body onload="loadLoginScripts()">
	<div class="box-form">
		<div class="left" id="loginleftpanel">
			<div class="overlay">
				<span class="head1">Stay Hub</span><br />
				<span class="head2">Stay Hub: Your Room Reservation Solution</span>
				<hr>
				<p>&#8220;<?php getSettingValue('WelcomeMessage'); ?>&#8221;</p>
			</div>
		</div>
		<div class="right">
			<form id="frmLogin" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="head3">Login</div>
				<p>Don't have an account? <a href="javascript:switchForm('signup')">Create Your Account</a><br />It takes less than a minute!</p>
				<div class="inputs">
					<input type="email" id="username" name="username" maxlength="64" placeholder="Username" required>
					<br>
					<input type="password" id="lgnpassword" name="lgnpassword" maxlength="25" placeholder="Password" required>
					<i class="far fa-eye" onclick="togglePassword('lgnpassword', 'loginPassword')" id="loginPassword"></i>
				</div>
				<br>
				<div class="remember-me--forget-password">
					<a class="forgotPassword" href="javascript:switchForm('forgetpassword')">Forget Password?</a>
				</div>
				<br>
				<input type="submit" class="btnSubmit" name="btnLogin" value="Login"/>
			</form>
			<form id="frmSignup" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="head3">Signup</div>
				<p>Already have an account? <a href="javascript:switchForm('login')">Sign-in</a> here!</p>
				<div class="inputs">
					<input name="fname" onkeyup="filterName('fname')" id="fname" pattern="[A-Za-z]{1,25}" type="text" maxlength="25" placeholder="Enter First Name" required><br>
					<input name="lname" onkeyup="filterName('lname')" id="lname" pattern="[A-Za-z]{1,25}" type="text" maxlength="25" placeholder="Enter Last Name" required><br>
					<input type="email" onkeyup="filterEmailID('email')" onfocusout="checkEmailID('email')" maxlength="64" id="email" name="email" placeholder="Enter Email ID" required><br>
					<input type="tel" onkeyup="filterTelephone('telephone')" name="telephone" id="telephone" onfocusout="checkTelephone('telephone')" placeholder="Enter Mobile Number" maxlength="10" pattern="[0-9]{10}" required><br>
					<input type="password" maxlength="25" id="password" onfocusout="checkPassword('password')" name="password" placeholder="Enter Password" pattern="[A-Za-z0-9@]{1,25}" required>
					<i class="far fa-eye" onclick="togglePassword('password', 'togglePassword')" id="togglePassword"></i>
				</div>
				<br>
				<div class="remember-me--forget-password">
					<a class="forgotPassword" href="javascript:switchForm('signup')">Reset Form</a>
				</div>
				<br>
				<input type="submit" class="btnSubmit" name="btnSignup" value="Signup"/>
			</form>
			<form id="frmForgetPassword" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="head3">Forget Password</div>
				<p>Already have an account? <a href="javascript:switchForm('login')">Sign-in</a> here!</p>
				<div class="inputs">
					<input type="email" onkeyup="filterEmailID()" maxlength="64" id="femail" name="femail" placeholder="Enter Email ID" required><br>
				</div>
				<br>
				<br>
				<input type="submit" class="btnSubmit" name="btnRequest" value="Request"/>
			</form>
		</div>
	</div>
</body>
</html>
