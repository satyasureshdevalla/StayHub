<?php	
	
	require_once("pdo.php");
	require_once("commonmethods.php");

	function send_email($receiver_email, $subject, $body)
	{
		try {
			require_once('smtp/PHPMailerAutoload.php');
			$mail = new PHPMailer(); 
			$mail->IsSMTP(); 
			$mail->SMTPAuth = true; 
			$mail->SMTPSecure = 'tls'; 
			$mail->Host = "smtp.gmail.com";
			$mail->Port = 587; 
			$mail->IsHTML(true);
			$mail->CharSet = 'UTF-8';
			$mail->Username = "suredevalla1000@gmail.com";
			$mail->Password = "jteq levw totw qvjw";
			$mail->From = 'suredevalla1000@gmail.com';
			$mail->FromName = 'Help@Stay Hub';
			$mail->Subject = $subject;
			$mail->Body =$body;
			$mail->AddAddress($receiver_email);
			$mail->WordWrap = 50;
			$mail->SMTPOptions=array('ssl'=>array('verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => false));
			$mail->Send();
			return true;
		} catch(Exception $e) { return false; }	
	}
	
	function updateStatusToUser($bookingid) {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "select TAB1.*, TAB2.type as room_type, TAB3.type as bedding_type, TAB4.*, TAB5.room_price, TAB5.ratio_with_room, TAB6.email as original_email from bookings_details as TAB1 inner join bookings_billing_details as TAB4 on TAB1.booking_id = TAB4.booking_id inner join bookings_room_details as TAB5 on TAB1.booking_id = TAB5.booking_id inner join room_master as TAB2 on TAB5.room_id = TAB2.room_id inner join bedding_master as TAB3 on TAB5.bedding_id = TAB3.bedding_id inner join user_master as TAB6 on TAB1.user_id = TAB6.user_id where TAB1.booking_id = ? limit 1";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $bookingid);
			$sqlStatement->execute();
			$bookingDetails = $sqlStatement->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($bookingDetails)) {
				$subject = 'Room Status Updated - Stay Hub!';
				$body = '
					<center><h2>Room Status Updated - Stay Hub!</h2></center>
					<p>Hi '.getManneredUserName($bookingDetails['first_name'], $bookingDetails['last_name']).',</p>
					<p>The room status have been updated as below mentioned, please check for further process. Also, you can track your booking status by visiting <a href="http://localhost/finalproject/StayHub/index.php" target="_blank"><b>Stay Hub</b></a> this link.</p>
					<h3>Status : ' . $bookingDetails['status'] . '</h3>
					<p>
						<b>Billing Details : </b><br />
						<b>Booking Id : </b>'. $bookingDetails['booking_id'] .'<br />
						<b>Booking Date : </b>'. getDateTimePrint($bookingDetails['booking_date']) .'<br />
						<b>Name : </b>'.getManneredUserName($bookingDetails['first_name'], $bookingDetails['last_name']).'<br />
						<b>Mobile : </b>'. $bookingDetails['mobile'] .'<br />
						<b>Email : </b>'. $bookingDetails['email'] .'
					</p>
					<p>
						<b>Room Details : </b><br />
						<b>Type Of Room : </b>'. $bookingDetails['room_type'] .'<br />
						<b>Bedding Type : </b>'. $bookingDetails['bedding_type'] .'<br />
						<b>No. Of Adults : </b>'. $bookingDetails['no_of_adults'] .'<br />
						<b>No. Of Childs : </b>'. $bookingDetails['no_of_childs'] .'<br />
						<b>Check-In : </b>'.getDateTimePrint($bookingDetails['check_in']).'<br />
						<b>Check-Out : </b>'.getDateTimePrint($bookingDetails['check_out']).'
					</p>
					<h3>Net Amount : $'. $bookingDetails['net_amount'] .'</h3>
					<p>In case if you have any difficulty please <a href = "mailto:ramreddy.yk17@gmail.com">eMail</a> us.</p>
					<p>Thank you,<br />
					<b>Stay Hub: Your Room Reservation Solution</b?</p>
				';
				
				try { send_email($bookingDetails['original_email'], $subject, $body); } catch(Exception $e) {  }
				try { if($bookingDetails['original_email'] != $bookingDetails['email']) { send_email($bookingDetails['email'], $subject, $body); } } catch(Exception $e) {  }
			}
		} catch(Exception $e) { }
	}
	
?>