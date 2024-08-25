<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>Profile - Stay Hub</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
	<link rel="stylesheet" href="main.css">
	<script type="text/javascript" src="main.js"></script>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once('profileUpdate.php'); ?>
</head>
<body>
	<div class="header">
		<div class="column1"><a href="home.php"><span class="usertitle">Welcome, <?php echo getManneredUserName(); ?></span></a></div>
		<div class="column2">
			<a href="home.php">Home</a>
			<a href="home.php#gallery">Gallery</a>
			<a href="home.php#roomsandrates">Rooms & Rates</a>
			<a href="bookings.php">Bookings</a>
			<a href="home.php#contactus">Contact Us</a>
			<a class="active" href="profile.php">Profile</a>
			<a href="logout.php">Logout</a>
			<span style="width:20px;"></span>
		</div>
	</div>
	<div class="profilebody">
		<div class="profile-box-container">
			<form id="frmProfile" class="profileForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="profilehead">Profile</div><br />
				<div class="divMultiColumns">
					<div class="profileContainer divLeft">
						<input name="fname" class="profileInput" onkeyup="filterName('fname')" id="fname" value="<?php echo $_SESSION["user"]['first_name']; ?>" pattern="[A-Za-z]{1,25}" type="text" maxlength="25" required><br>
						<label class="profileLabel" for="fname">First Name</label>
					</div>
					<div class="profileContainer divRight">
						<input name="lname" class="profileInput" onkeyup="filterName('lname')" id="lname" value="<?php echo $_SESSION["user"]['last_name']; ?>" pattern="[A-Za-z]{1,25}" type="text" maxlength="25" required><br>
						<label class="profileLabel" for="lname">Last Name</label>
					</div>
				</div>
				<div class="divMultiColumns divLeft">
					<div class="profileContainer divLeft">
						<input type="email" class="profileEmailInput" readonly onfocusout="checkEmailID('email')" value="<?php echo $_SESSION["user"]['email']; ?>" onkeyup="filterEmailID('email')" maxlength="64" id="email" name="email" required><br>
						<label class="profileLabel" for="email">Email-ID</label>
					</div>
					<div class="profileContainer divRight">
						<input type="tel" class="profileInput" onkeyup="filterTelephone()" onfocusout="checkTelephone('telephone')" name="telephone" value="<?php echo $_SESSION["user"]['mobile']; ?>" id="telephone" maxlength="10" pattern="[0-9]{10}" required>
						<label class="profileLabel" for="telephone">Mobile Number</label>
					</div>
				</div>
				<div class="divMultiColumns divLeft" style="padding-bottom:30px;">
					<div class="profileContainer">
						<input type="submit" class="btnProfile" name="btnProfile" value="Update Profile"/>
						<div style="width:10px;height:10px;float:right;"></div>
						<a href="profile.php"><div class="btnReset">Reset</div></a>
					</div>
				</div>
			</form>
			<form id="frmPassword" class="profileForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<div class="profilehead">Password</div><br />
				<div class="divMultiColumns">
					<div class="profileContainer divLeft">
						<input type="password" onfocusout="checkPassword('oldpassword')" class="profileInput" maxlength="25" id="oldpassword" name="oldpassword" pattern="[A-Za-z0-9@]{1,25}" required>
						<label class="profileLabel" for="oldpassword">Old Password</label>
						<i class="far fa-eye" onclick="togglePassword('oldpassword', 'toggleOldPassword')" id="toggleOldPassword"></i>
					</div>
					<div class="profileContainer divRight">
						<input type="password" onfocusout="checkPassword('newpassword')" class="profileInput" maxlength="25" id="newpassword" name="newpassword" pattern="[A-Za-z0-9@]{1,25}" required>
						<label class="profileLabel" for="newpassword">New Password</label>
						<i class="far fa-eye" onclick="togglePassword('newpassword', 'toggleNewPassword')" id="toggleNewPassword"></i>
					</div>
				</div>
				<div class="divMultiColumns divLeft">
					<div class="profileContainer">
						<input type="submit" class="btnPassword" name="btnPassword" value="Update Password"/>
					</div>
				</div>
			</form>
			<div class="profileForm">
				<div class="divMultiColumns">
					<a href="javascript:popUpLearnMore()" class="btnDeleteAccount">Delete Account</a>
				</div>
			</div>
		</div>
	</div>
	<div id="myModal" class="popupModal">
		<div class="modalContainer modalAlertDialog">
			<div class="modalHeader">
				<a href="javascript:popUpCloseLearnMore()" class="modalclose alertmodalclose">&times;</a>
			</div>
			<div class="modalBody">
				<div class="text3">Are you sure you want to delete this account?</div>
				<div class="text4">Remember:</div>
				<div class="text5">
					Once you proceed with this process of account deletion, you cannot undo this. You will not be able to login again. Also, all the room bookings and contact-us details will be cleared permanently. Be sure before proceeding!
				</div>
				<form id="frmDeleteAccount" class="profileForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<input type="submit" class="btnDelete" name="btnDelete" value="I understood, delete this account"/>
				</form>
			</div>
		</div>
	</div>
</body>
</html>