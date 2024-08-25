<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>Bookings - Stay Hub</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="main.css">
	<script type="text/javascript" src="main.js"></script>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once('roomdetailsUpdate.php'); ?>
</head>
<body onload="loadBookingsScripts()">
	<div class="header">
		<div class="column1"><a href="home.php"><span class="usertitle">Welcome, <?php echo getManneredUserName(); ?></span></a></div>
		<div class="column2">
			<a href="home.php">Home</a>
			<a href="home.php#gallery">Gallery</a>
			<a href="home.php#roomsandrates">Rooms & Rates</a>
			<a class="active" href="bookings.php">Bookings</a>
			<a href="home.php#contactus">Contact Us</a>
			<a href="profile.php">Profile</a>
			<a href="logout.php">Logout</a>
			<span style="width:20px;"></span>
		</div>
	</div>
	<div class="bookingsbody">
		<div class="switchTabs">
			<a id="leftSwitchTab" class="active" href="javascript:switchBookingTabs('left')">New Booking</a>
			<a id="rightSwitchTab" href="javascript:switchBookingTabs('right')">My Bookings</a>
		</div>
		<div id="newbooking" class="newbooking">
			<div class="blankSpace2">
				<form class="frmBookings" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
					<div class="mulColRow">
						<div class="colLeft">
							<div class="inputDiv">
								<div class="text7">Billing Details</div>
								<label class="frmlabel" for="fname">First Name:</label>
								<div class="frminputBox"><input onkeyup="filterName('fname')" value="<?php echo $_SESSION["user"]['first_name']; ?>" class="frminput" pattern="[A-Za-z]{1,25}" type="text" maxlength="25" placeholder="Enter First Name" id="fname" name="fname" required></div>
								<label class="frmlabel" for="lname">Last Name:</label>
								<div class="frminputBox"><input onkeyup="filterName('lname')" value="<?php echo $_SESSION["user"]['last_name']; ?>" class="frminput" pattern="[A-Za-z]{1,25}" type="text" maxlength="25" placeholder="Enter Last Name" id="lname" name="lname" required></div>
								<label class="frmlabel" for="mobileNumber">Mobile Number:</label>
								<div class="frminputBox"><input type="tel" value="<?php echo $_SESSION["user"]['mobile']; ?>" onkeyup="filterTelephone('mobileNumber')" class="frminput" type="text" placeholder="Enter Mobile Number" onfocusout="checkTelephone('mobileNumber')" name="mobileNumber" id="mobileNumber" maxlength="10" pattern="[0-9]{10}" required></div>
								<label class="frmlabel" for="emailId">Email Address:</label>
								<div class="frminputBox"><input class="frminput" value="<?php echo $_SESSION["user"]['email']; ?>" type="email" onkeyup="filterEmailID('emailId')" onfocusout="checkEmailID('emailId')" maxlength="64" placeholder="Enter Email Address" id="emailId" name="emailId" required></div>
							</div>
						</div>
						<div class="colRight">
							<div class="inputDiv">
								<div class="text7">Room Details</div>
								<label class="frmlabel" for="frmTypeOfRoom">Type Of Room</label>
								<div class="frminputBox">
									<select name="frmTypeOfRoom" onchange="calculateBill()" required class="frminput" id="frmTypeOfRoom">
										<option selected value="">Select Type Of Room</option>
										<?php getRoomDetails(); ?>
									</select>
								</div>
								<label class="frmlabel" for="frmBedType">Bedding Type</label>
								<div class="frminputBox">
									<select name="frmBedType" onchange="calculateBill()" required class="frminput" id="frmBedType">
										<option selected value="">Select Bedding Type</option>
										<?php getBeddingDetails(); ?>
									</select>
								</div>
								<div class="mulColRow">
									<div class="colLeft">
										<label class="frmlabel" for="nudNoOfAdults">No. Of Adults</label>
										<div class="frminputBox"><input type="number" onchange="calculateBill()" id="nudNoOfAdults" name="nudNoOfAdults" class="frminput" value="1" min="1" max="10"></div>
									</div>
									<div class="colRight">
										<label class="frmlabel" for="nudNoOfChilds">No. Of Childs</label>
										<div class="frminputBox"><input type="number" onchange="calculateBill()" id="nudNoOfChilds" name="nudNoOfChilds" class="frminput" value="0" min="0" max="10"></div>
									</div>
								</div>
								<div class="mulColRow">
									<div class="colLeft">
										<label class="frmlabel" for="dtCheckIn">Check-In</label>
										<div class="frminputBox"><input type="datetime-local" id="dtCheckIn" class="frminput" min="<?php echo date('Y-m-d', strtotime('+6 hours')) . 'T' . date('H:i', strtotime('+6 hours')); ?>" max="<?php echo date('Y-m-d', strtotime('+30 days')) . 'T' . date('H:i', strtotime('+30 days')); ?>" required onchange="checkInTimeChange()" name="dtCheckIn"></div>
									</div>
									<div class="colRight">
										<label class="frmlabel" for="dtCheckOut">Check-Out</label>
										<div class="frminputBox"><input type="datetime-local" required id="dtCheckOut" class="frminput" min="<?php echo date('Y-m-d', strtotime('+7 hours')) . 'T' . date('H:i', strtotime('+7 hours')); ?>" max="<?php echo date('Y-m-d', strtotime('+30 days')) . 'T' . date('H:i', strtotime('+30 days')); ?>" onchange="calculateBill()" name="dtCheckOut"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="txtNetAmount" class="mulColRow txtNetAmount autowidth">NET AMOUNT : -NILL</div>
					<div class="mulColRow">
						<div class="colLeft"><input class="frmbutton btnBookingReset" type="reset" name="btnReset" value="Reset Form"/></div>
						<div class="colRight"><input class="frmbutton btnBooking" type="submit" name="btnBook" value="Confirm Booking"/></div>
					</div>
					<input type="hidden" name="frmNetAmount" id="frmNetAmount" value="NET AMOUNT : -NILL" />
					<input type="hidden" name="frmTypeOfRoomValue" id="frmTypeOfRoomValue" value="" />
					<input type="hidden" name="frmBedTypeValue" id="frmBedTypeValue" value="" />
					<input type="hidden" name="frmTypeOfRoomName" id="frmTypeOfRoomName" value="" />
					<input type="hidden" name="frmBedTypeName" id="frmBedTypeName" value="" />
				</form>
			</div>
		</div>
		<div id="mybookings" class="mybookings">
			<div class="blankSpace2">
				<div class="searchDiv"><input type='text' onkeyup='searchBookingId("mybookings")' placeholder='Enter booking id to search' id='searchBookingId' name='searchBookingId' class='settingGalleryInput bookingSearchInput'><i class='fa fa-search updateButton galleryUpdateIcon'></i></div>
				<div class="blankSpace5"></div>
				<?php getMyBookings(); ?>
			</div>
		</div>
	</div>
</body>
</html>