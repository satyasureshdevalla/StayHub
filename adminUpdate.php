<?php

	require_once('email.php');
	require_once("pdo.php");
	require_once("commonmethods.php");
	session_start();
	
	try {
		if(!(isset($_SESSION["admin"]))) {
			echo "<script>alert('Please login to proceed with hotel room bookings!');</script>";
			echo "<script type='text/javascript'>location.href = 'index.php';</script>";
		}
		
	} catch(Exception $e) { 
		echo "<script>alert('Some issues while fetching profile, please try again after some time!');</script>";
		echo "<script type='text/javascript'>location.href = 'index.php';</script>";
	}
	
	function getStatusDiv($status) {
		if($status == "Approved") { return "<span class='approve'>Status : " . $status . "</span>"; }
		if($status == "Pending") { return "<span class='pending'>Status : " . $status . "</span>"; }
		return "<span class='cancelled'>Status : " . $status . "</span>";
	}
	
	function checkIfCancelRequired($status, $booking_id) {
		if($status == "Approved") {  return "<a class='redLink' href='javascript:updateCancelByAdminBooking(" . $booking_id . ")'>Cancel</a>"; }
		if($status == "Pending") {
			return "<div class='mulColRow'><div class='colLeft'><a class='greenLink' href='javascript:updateApproveBooking(" . $booking_id . ")'>Accept</a></div><div class='colRight'><a class='redLink' href='javascript:updateCancelByAdminBooking(" . $booking_id . ")'>Cancel</a></div></div>";
		}
		return "";
	}
	
	function getBookings() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "select TAB1.*, TAB2.type as room_type, TAB3.type as bedding_type, TAB4.*, TAB5.room_price, TAB5.ratio_with_room from bookings_details as TAB1 inner join bookings_billing_details as TAB4 on TAB1.booking_id = TAB4.booking_id inner join bookings_room_details as TAB5 on TAB1.booking_id = TAB5.booking_id inner join room_master as TAB2 on TAB5.room_id = TAB2.room_id inner join bedding_master as TAB3 on TAB5.bedding_id = TAB3.bedding_id order by booking_date desc;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<div id='bookingId" . $curRow['booking_id'] . "' class='bookingDiv mulColRow rightAligned'> <div class='colLeft'> <div class='text9'>Billing Details:</div> <div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>Booking Id : </span>" . $curRow['booking_id']. "</span> </div><div class='colRight'><span class='frmBookingsText'>" .getStatusDiv($curRow['status']). "</span> </div></div> <span class='frmBookingsText'><span class='label'>Booking Date : </span>" . getDateTimePrint($curRow['booking_date']). "</span> <span class='frmBookingsText'><span class='label'>Name : </span>" . getManneredUserName($curRow['first_name'], $curRow['last_name']) . "</span> <span class='frmBookingsText'><span class='label'>Mobile : </span>" . $curRow['mobile']. "</span> <span class='frmBookingsText'><span class='label'>Email : </span>" . $curRow['email']. "</span> </div> <div class='colRight'> <div class='text9'>Room Details:</div> <div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>Type Of Room : </span>" . $curRow['room_type']. "</span> </div> <div class='colRight'> <span class='frmBookingsText'><span class='label'>Bedding Type : </span>" . $curRow['bedding_type']. "</span> </div> </div><div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>No. Of Adults : </span>" . $curRow['no_of_adults']. "</span> </div> <div class='colRight'> <span class='frmBookingsText'><span class='label'>No. Of Childs : </span>" . $curRow['no_of_childs']. "</span> </div> </div> <div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>Check-In : </span>" . getDateTimePrint($curRow['check_in']) . "</span> </div> <div class='colRight'> <span class='frmBookingsText'><span class='label'>Check-Out : </span>" . getDateTimePrint($curRow['check_out']) . "</span> </div> </div> <span class='frmBookingsText'><span class='label'>Net-Amount : </span>$" . $curRow['net_amount']. "</span>" . checkIfCancelRequired($curRow['status'], $curRow['booking_id']) . "</div> </div>";
			}
		} catch(Exception $e) { }
	}
	
	function getRoomsSettings() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM room_master ORDER BY type;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<div class='singleDiv'><div class='settingDiv settingColumn mulColRow'><div class='colLeft'><span class='settingName'>" . $curRow["type"]. "</span></div><div class='colRight'><a class='webup' href='javascript:updateRoomPrice(room" . $curRow["room_id"] . ")'><i class='fa fa-pencil-square-o updateButton'></i></a></div><input placeholder='Enter room price' onkeyup='filterNumber(room" . $curRow["room_id"] . ")' onfocusout='filterNumber(room" . $curRow["room_id"] . ")' type='text' id='room" . $curRow["room_id"] . "' class='settingInput' value='" . $curRow["price"]. "'></div></div>";
			}
		} catch(Exception $e) { }
	}
	
	function getBeddingSettings() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM bedding_master ORDER BY type;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<div class='singleDiv'><div class='settingDiv settingColumn mulColRow'><div class='colLeft'><span class='settingName'>" . $curRow["type"]. "</span></div><div class='colRight'><a class='webup' href='javascript:updateBeddingRatio(bedding" . $curRow["bedding_id"] . ")'><i class='fa fa-pencil-square-o updateButton'></i></a></div><input placeholder='Enter bedding ratio' onkeyup='filterDecimal(bedding" . $curRow["bedding_id"] . ")' onfocusout='filterDecimal(bedding" . $curRow["bedding_id"] . ")' type='text' id='bedding" . $curRow["bedding_id"] . "' class='settingInput' value='" . $curRow["ratio_with_room"]. "'></div></div>";
			}
		} catch(Exception $e) { }
	}
	
	function getContactUsDetails() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM contactus ORDER BY received_on desc;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			$counter = 1;
			foreach($result as $curRow) {
				echo "<tr><td>" . $counter++ . "</td><td>" . $curRow["full_name"] . "</td><td>" . $curRow["mobile"] . "</td><td><a href = 'mailto:" . $curRow["email"] . "'>" . $curRow["email"] . "</a></td><td>" . getDateTimePrint($curRow["received_on"]) . "</td></tr>";
			}
		} catch(Exception $e) { }
	}
	
	function getGallerySettings() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM gallery_images ORDER BY image_id;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<div class='galleryDiv settingGalleryColumn'><a href='javascript:zoomImage(settingImage" . $curRow['image_id'] . ")'><img id='settingImage" . $curRow['image_id'] . "' alt='" . $curRow['caption'] . "' src='images/gallery/" . $curRow['file_name'] . "' class='settingImage'></a><input type='text' placeholder='Enter caption for image' id='gallery" . $curRow["image_id"] . "' class='settingGalleryInput' value='" . $curRow["caption"]. "'><a class='webup' href='javascript:updateGallerySetting(gallery" . $curRow["image_id"] . ")'><i class='fa fa-pencil-square-o updateButton galleryUpdateIcon'></i></a></div>";
			}
		} catch(Exception $e) { }
	}
	
?>