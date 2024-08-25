<?php

	date_default_timezone_set('UTC');

	require_once('email.php');
	require_once("pdo.php");
	require_once("commonmethods.php");
	session_start();
	
	try {
		if(!(isset($_SESSION["user"]))) {
			echo "<script>alert('Please login to proceed with hotel room bookings!');</script>";
			echo "<script type='text/javascript'>location.href = 'index.php';</script>";
		}
		
	} catch(Exception $e) { 
		echo "<script>alert('Some issues while fetching profile, please try again after some time!');</script>";
		echo "<script type='text/javascript'>location.href = 'index.php';</script>";
	}
	
	function getRoomDetails() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM room_master ORDER BY type;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<option value='" . $curRow["room_id"]. "'>" . $curRow["type"]. " : $" . $curRow["price"]. " : " . $curRow["maximum_capacity"]. " Persons</option>";
			}
		} catch(Exception $e) { }
		return "";
	}
	
	function getBeddingDetails() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM bedding_master ORDER BY type;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<option value='" . $curRow["bedding_id"]. "'>" . $curRow["type"]. " : " . $curRow["ratio_with_room"]. "x</option>";
			}
		} catch(Exception $e) { }
		return "";
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST['btnBook'])) {
            bookRoom();
        }
	}
	
	function getStatusDiv($status) {
		if($status == "Approved") { return "<span class='approve'>Status : " . $status . "</span>"; }
		else if($status == "Pending") { return "<span class='pending'>Status : " . $status . "</span>"; }
		return "<span class='cancelled'>Status : " . $status . "</span>";
	}
	
	function checkIfCancelRequired($status, $booking_id) {
		if($status == "Pending" || $status == "Approved") { return "<a href='javascript:updateCancelByUserBooking(" . $booking_id . ")'><span class='redLink'>Cancel Request</span></a>"; }
		return "";
	}
	
	function getMyBookings() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "select TAB1.*, TAB2.type as room_type, TAB3.type as bedding_type, TAB4.*, TAB5.room_price, TAB5.ratio_with_room from bookings_details as TAB1 inner join bookings_billing_details as TAB4 on TAB1.booking_id = TAB4.booking_id inner join bookings_room_details as TAB5 on TAB1.booking_id = TAB5.booking_id inner join room_master as TAB2 on TAB5.room_id = TAB2.room_id inner join bedding_master as TAB3 on TAB5.bedding_id = TAB3.bedding_id where TAB1.user_id = ? order by booking_date desc;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->bindParam(1, $_SESSION["user"]['user_id']);
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<div id='bookingId" . $curRow['booking_id'] . "' class='bookingDiv mulColRow rightAligned'> <div class='colLeft'> <div class='text9'>Billing Details:</div> <div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>Booking Id : </span>" . $curRow['booking_id']. "</span> </div><div class='colRight'><span class='frmBookingsText'>" .getStatusDiv($curRow['status']). "</span> </div></div> <span class='frmBookingsText'><span class='label'>Booking Date : </span>" . getDateTimePrint($curRow['booking_date']). "</span> <span class='frmBookingsText'><span class='label'>Name : </span>" . getManneredUserName($curRow['first_name'], $curRow['last_name']) . "</span> <span class='frmBookingsText'><span class='label'>Mobile : </span>" . $curRow['mobile']. "</span> <span class='frmBookingsText'><span class='label'>Email : </span>" . $curRow['email']. "</span> </div> <div class='colRight'> <div class='text9'>Room Details:</div> <div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>Type Of Room : </span>" . $curRow['room_type']. "</span> </div> <div class='colRight'> <span class='frmBookingsText'><span class='label'>Bedding Type : </span>" . $curRow['bedding_type']. "</span> </div> </div><div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>No. Of Adults : </span>" . $curRow['no_of_adults']. "</span> </div> <div class='colRight'> <span class='frmBookingsText'><span class='label'>No. Of Childs : </span>" . $curRow['no_of_childs']. "</span> </div> </div> <div class='mulColRow'> <div class='colLeft'> <span class='frmBookingsText'><span class='label'>Check-In : </span>" . getDateTimePrint($curRow['check_in']) . "</span> </div> <div class='colRight'> <span class='frmBookingsText'><span class='label'>Check-Out : </span>" . getDateTimePrint($curRow['check_out']) . "</span> </div> </div> <span class='frmBookingsText'><span class='label'>Net-Amount : </span>$" . $curRow['net_amount']. "</span>" . checkIfCancelRequired($curRow['status'], $curRow['booking_id']) . "</div> </div>";
			}
		} catch(Exception $e) { }
	}
	
	function bookRoom() {
		try {
			$fname = filterString($_POST["fname"]);
			$lname = filterString($_POST["lname"]);
			$mobileNumber = filterString($_POST["mobileNumber"]);
			$emailId = filterString($_POST["emailId"]);
			$txtNetAmount = explode(" : $", filterString($_POST["frmNetAmount"]))[1];
			
			$frmTypeOfRoom = $_POST["frmTypeOfRoom"];
			$frmBedType = $_POST["frmBedType"];
			$frmTypeOfRoomValue = $_POST["frmTypeOfRoomValue"];
			$frmBedTypeValue = $_POST["frmBedTypeValue"];
			$frmTypeOfRoomName = $_POST["frmTypeOfRoomName"];
			$frmBedTypeName = $_POST["frmBedTypeName"];
			$nudNoOfAdults = $_POST["nudNoOfAdults"];
			$nudNoOfChilds = $_POST["nudNoOfChilds"];
			$dtCheckIn = $_POST["dtCheckIn"];
			$dtCheckOut = $_POST["dtCheckOut"];
			
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "insert into bookings_details(user_id, no_of_adults, no_of_childs, check_in, check_out, net_amount) values(?, ?, ?, ?, ?, ?);";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $_SESSION["user"]['user_id']);
			$sqlStatement->bindParam(2, $nudNoOfAdults);
			$sqlStatement->bindParam(3, $nudNoOfChilds);
			$sqlStatement->bindParam(4, $dtCheckIn);
			$sqlStatement->bindParam(5, $dtCheckOut);
			$sqlStatement->bindParam(6, $txtNetAmount);
			$sqlStatement->execute();
			
			$bookingId = $sqlConn->lastInsertId();
			
			$sqlQuery = "insert into bookings_billing_details(booking_id, first_name, last_name, mobile, email) values(?, ?, ?, ?, ?);";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $bookingId);
			$sqlStatement->bindParam(2, $fname);
			$sqlStatement->bindParam(3, $lname);
			$sqlStatement->bindParam(4, $mobileNumber);
			$sqlStatement->bindParam(5, $emailId);
			$sqlStatement->execute();
			
			$sqlQuery = "insert into bookings_room_details(booking_id, room_id, bedding_id, room_price, ratio_with_room) values(?, ?, ?, ?, ?);";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $bookingId);
			$sqlStatement->bindParam(2, $frmTypeOfRoom);
			$sqlStatement->bindParam(3, $frmBedType);
			$sqlStatement->bindParam(4, $frmTypeOfRoomValue);
			$sqlStatement->bindParam(5, $frmBedTypeValue);
			$sqlStatement->execute();
			
			$subject = 'New Room Booking - Stay Hub!';
			$body = '
				<center><h2>New Room Booking - Stay Hub!</h2></center>
				<p>Hi '.getManneredUserName().',</p>
				<p>Thank you for booking room with us. Below you can find room booking details. You have to wait until you get approval mail from us. Also, you can track your booking status by visiting <a href="http://localhost/finalproject/StayHub/home.php" target="_blank"><b>Stay Hub</b></a> this link.</p>
				<h3>Status : Pending</h3>
				<p>
				    <b>Billing Details : </b><br />
					<b>Booking Id : </b>'.$bookingId.'<br />
					<b>Booking Date : </b>'.strtoupper(date('d-m-Y h:i a')).'<br />
					<b>Name : </b>'.getManneredUserName($fname, $lname).'<br />
					<b>Mobile : </b>'.$mobileNumber.'<br />
					<b>Email : </b>'.$emailId.'
				</p>
				<p>
				    <b>Room Details : </b><br />
					<b>Type Of Room : </b>'.$frmTypeOfRoomName.'<br />
					<b>Bedding Type : </b>'.$frmBedTypeName.'<br />
					<b>No. Of Adults : </b>'.$nudNoOfAdults.'<br />
					<b>No. Of Childs : </b>'.$nudNoOfChilds.'<br />
					<b>Check-In : </b>'.getDateTimePrint($dtCheckIn).'<br />
					<b>Check-Out : </b>'.getDateTimePrint($dtCheckOut).'
				</p>
				<h3>Net Amount : $'.$txtNetAmount.'</h3>
				<p>In case if you have any difficulty please <a href = "mailto:ramreddy.yk17@gmail.com">eMail</a> us.</p>
				<p>Thank you,<br />
				<b>Stay Hub: Your Room Reservation Solution</b?</p>
			';
			
			try { send_email($_SESSION["user"]['email'], $subject, $body); } catch(Exception $e) {  }
			try { if($_SESSION["user"]['email'] != $emailId) { send_email($emailId, $subject, $body); } } catch(Exception $e) {  }
			
			echo "<script>alert('Congratulations, room have been booked successfully. Wait for confirmation mail or can also check booking status after every few moment!');</script>";
		} catch(Exception $e) { echo "<script>alert('Some issues while booking rooms, please try again after some time!');</script>"; }
		echo "<script type='text/javascript'>location.href = 'bookings.php';</script>";
	}
	
?>