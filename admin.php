<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>Admin - Stay Hub</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,300,700" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="main.css">
	<script type="text/javascript" src="main.js"></script>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php require_once('adminUpdate.php'); ?>
	<?php require_once('websitesettings.php'); ?>
</head>
<body>
	<div class="header">
		<div class="column2">
			<a href="admin.php">Home</a>
			<a href="admin.php#websitesettings">Website</a>
			<a href="admin.php#roomsettings">Rooms</a>
			<a href="admin.php#beddingsettings">Bedding</a>
			<a href="admin.php#contactususers">ContactUs</a>
			<a href="admin.php#gallerysettings">Gallery</a>
			<a href="admin.php#managebookings">Bookings</a>
			<a href="logout.php">Logout</a>
			<span style="width:20px;"></span>
		</div>
	</div>
	<div class="adminbody">
		<div id="websitesettings" class="websitesettings">
			<div class="text6 defaultWidth">Website Settings</div>
			<div class="blankSpace4">
				<div class="settingDiv settingRow">
					<?php getSettings(); ?>
				</div>
			</div>
		</div>
		<div id="roomsettings" class="roomsettings">
			<div class="text6 defaultWidth">Rooms Settings</div>
			<div class="blankSpace4">
				<div class="settingDiv settingRow">
					<?php getRoomsSettings(); ?>
				</div>
			</div>
		</div>
		<div id="beddingsettings" class="beddingsettings">
			<div class="text6 defaultWidth">Bedding Settings</div>
			<div class="blankSpace4">
				<div class="settingDiv settingRow">
					<?php getBeddingSettings(); ?>
				</div>
			</div>
		</div>
		<div id="contactususers" class="contactususers">
			<div class="text6 defaultWidth">ContactUs Users</div>
			<div class="blankSpace4">
				<div class="settingDiv settingRow">
					<section class="contactUsSection">
						<div class="tbl-header">
							<table cellpadding="0" cellspacing="0" border="0">
								<colgroup>
									<col span="1" style="width: 10%;">
									<col span="1" style="width: 30%;">
									<col span="1" style="width: 10%;">
									<col span="1" style="width: 35%;">
									<col span="1" style="width: 15%;">
								</colgroup>
								<thead>
									<tr>
										<th>Sr. No.</th>
										<th>Name</th>
										<th>Mobile</th>
										<th>Email</th>
										<th>Submitted On</th>
									</tr>
								</thead>
							</table>
						</div>
						<div class="tbl-content">
							<table cellpadding="0" cellspacing="0" border="0">
								<colgroup>
									<col span="1" style="width: 10%;">
									<col span="1" style="width: 30%;">
									<col span="1" style="width: 10%;">
									<col span="1" style="width: 35%;">
									<col span="1" style="width: 15%;">
								</colgroup>
								<tbody>
									<?php getContactUsDetails(); ?>
								</tbody>
							</table>
						</div>
					</section>
				</div>
			</div>
		</div>
		<div id="gallerysettings" class="gallerysettings">
			<div class="text6 defaultWidth">Gallery Settings</div>
			<div class="blankSpace4">
				<div class="galleryDiv galleryRow">
					<?php getGallerySettings(); ?>
				</div>
			</div>
		</div>
		<div id="managebookings" class="managebookings">
			<div class="text6 defaultWidth">Bookings</div>
			<div class="blankSpace3">
				<div class="searchDiv"><input type='text' onkeyup='searchBookingId("managebookings")' placeholder='Enter booking id to search' id='searchBookingId' name='searchBookingId' class='settingGalleryInput bookingSearchInput'><i class='fa fa-search updateButton galleryUpdateIcon'></i></div>
				<div class="blankSpace5"></div>
				<?php getBookings(); ?>
			</div>
		</div>
	</div>
	<div id="galleryModal" class="galleryModal">
		<a href="javascript:popUpCloseLearnMore()" class="galleryClose">&times;</a>
		<img class="galleryFullImage" id="zoomImage">
		<div id="galleryCaption" class="galleryCaption"></div>
	</div>
</body>
</html>