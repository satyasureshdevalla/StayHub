<?php

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
	
	function getGalleryImages() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM gallery_images order by image_id;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<div class='galleryDiv galleryColumn'><a href='javascript:zoomImage(galleryImage" . $curRow['image_id'] . ")'><img id='galleryImage" . $curRow['image_id'] . "' alt='" . $curRow['caption'] . "' src='images/gallery/" . $curRow['file_name'] . "' class='galleryImage'></a></div>";
			}
		} catch(Exception $e) { }
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
				echo "<div class='singleRoom'> <div class='roomDiv roomColumn'> <img id='roomImage" . $curRow['room_id'] . "' src='images/rooms/" . $curRow['image'] . "' class='roomImage'> <div class='roomTitleCover'> <div class='roomTitle'> " . $curRow['type'] . " </div> </div> <div class='roomPriceCover'> <span class='buyButton'><span class='buttonSign'>$</span> " . $curRow['price'] . "</span> <a class='homelearnmore' href='javascript:bookCurrentRoom(" . $curRow['room_id'] . ")'>Book Now</a> </div> </div> </div>";
			}
		} catch(Exception $e) { }
		return "";
	}
	
	function updateProfile() {
		$fname = filterString($_POST["fname"]);
		$lname = filterString($_POST["lname"]);
		$telephone = filterString($_POST["telephone"]);
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "update user_master set first_name = ?, last_name = ?, mobile = ? where user_id = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $fname);
			$sqlStatement->bindParam(2, $lname);
			$sqlStatement->bindParam(3, $telephone);
			$sqlStatement->bindParam(4, $_SESSION["user"]['user_id']);
			$sqlStatement->execute();
			loadProfile();
			echo "<script>alert('Profile updated successfully!');</script>";
			echo "<script type='text/javascript'>location.href = 'profile.php';</script>";
		} 
		catch(Exception $e) { 
			echo "<script>alert('Some issues while updating profile, try again after some time!');</script>";
			echo "<script type='text/javascript'>location.href = 'profile.php';</script>";
			return;
		}
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST['btnProfile'])) {
            updateProfile();
        }
		else if(isset($_POST['btnPassword'])) {
            updatePassword();
        }
		else if(isset($_POST['btnSend'])) {
            submitContactUs();
        }
		else if(isset($_POST['btnDelete'])) {
            deleteAccount();
        }
	}
	
	function deleteAccount() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			$sqlQuery = "delete from user_master where user_id = ?;";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $_SESSION["user"]['user_id']);
			$sqlStatement->execute();
		}
		catch(Exception $e) { 
			echo "<script>alert('Some issues while deleting account. If problem still persists, try again after some time!');</script>";
			echo "<script type='text/javascript'>location.href = 'profile.php';</script>";
			return;
		}
		echo "<script>alert('Your account is deleted permanently!');</script>";
		
		$subject = 'Stay Hub Account Deletion!';
		$body = '
			<center><h2>Stay Hub Account Deletion!</h2></center>
			<p>Hi '.getManneredUserName().',</p>
			<p>It&#39;s really sad to say Good-Bye, it has been great journey till now. We will miss you!</p>
			<p>Just in case if you change your mind and choose to join us again, visit <a href="http://localhost/finalproject/StayHub/home.php" target="_blank"><b>Stay Hub</b></a> to create new account. Wishing you best regards.</p>
			<p>Thank you,<br />
			<b>Stay Hub: Your Room Reservation Solution</b></p>
		';
		
		send_email($_SESSION["user"]['email'], $subject, $body);
		echo "<script type='text/javascript'>location.href = 'logout.php';</script>";
	}
	
	function submitContactUs() {
		$fullName = filterString($_POST["fullName"]);
		$mobileNumber = filterString($_POST["mobileNumber"]);
		$emailId = filterString($_POST["emailId"]);
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			$sqlQuery = "insert into contactus(user_id, full_name, mobile, email) values(?, ?, ?, ?);";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $_SESSION["user"]['user_id']);
			$sqlStatement->bindParam(2, $fullName);
			$sqlStatement->bindParam(3, $mobileNumber);
			$sqlStatement->bindParam(4, $emailId);
			$sqlStatement->execute();
			echo "<script>alert('Contact-us details submitted successfully!');</script>";
		}
		catch(Exception $e) { 
			echo "<script>alert('Some issues while submitting contact-us. Check all the fields, if problem still persists, try again after some time!');</script>";
		}
		echo "<script type='text/javascript'>location.href = 'home.php#contactus';</script>";
	}
	
	function updatePassword() {
		$oldpassword = $_POST["oldpassword"];
		$newpassword = $_POST["newpassword"];
		if(!($_SESSION["user"]['password'] == base64_encode($oldpassword))) {
			echo "<script>alert('You have entered incorrect old password, please try again!');</script>";
			echo "<script type='text/javascript'>location.href = 'profile.php';</script>";
		}
		else if($newpassword == $oldpassword) {
			echo "<script>alert('Old and new password cannot be same, please try again!');</script>";
			echo "<script type='text/javascript'>location.href = 'profile.php';</script>";
		}
		else {
			try {
				$database = new Database();
				$sqlConn = $database->getConnection();
				
				$sqlQuery = "update user_master set password = ? where user_id = ?";
				$sqlStatement = $sqlConn->prepare($sqlQuery);
				$sqlStatement->bindParam(1, base64_encode($newpassword));
				$sqlStatement->bindParam(2, $_SESSION["user"]['user_id']);
				$sqlStatement->execute();
				loadProfile();
				echo "<script>alert('Password updated successfully!');</script>";
				echo "<script type='text/javascript'>location.href = 'profile.php';</script>";
			} 
			catch(Exception $e) { 
				echo "<script>alert('Some issues while updating password, try again after some time!');</script>";
				echo "<script type='text/javascript'>location.href = 'profile.php';</script>";
				return;
			}
		}
	}
	
	function loadProfile() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM user_master WHERE user_id = ? limit 1";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $_SESSION["user"]['user_id']);
			$sqlStatement->execute();
			$userDetails = $sqlStatement->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($userDetails)) { $_SESSION["user"] = $userDetails; }
			return;
		} catch(Exception $e) { }
		if(isset($_SESSION["user"])) {
			session_unset();
			session_destroy();
		}
	}

?>