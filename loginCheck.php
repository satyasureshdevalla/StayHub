<?php
	require_once('email.php');
	require_once("pdo.php");
	require_once("commonmethods.php");
	
	session_start();
	try {
		if(isset($_SESSION["user"]) || isset($_SESSION["admin"])) {
			session_unset();
			session_destroy();
		}
	} catch(Exception $e) { }
	
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST['btnLogin'])) {
            validateLogin();
        }
		else if(isset($_POST['btnSignup'])) {
            validateSignUp();
        }
		else if(isset($_POST['btnRequest'])) {
            sendPassword();
        }
	}
	
	function sendPassword() {
		$email = filterString($_POST["femail"]);
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM user_master WHERE email = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $email);
			$sqlStatement->execute();
			$userDetails = $sqlStatement->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($userDetails)) {
				$subject = 'Forget Password - Stay Hub!';
				$body = '
					<center><h2>Forget Password - Stay Hub!</h2></center>
					<p>Hi '.getManneredUserName($userDetails['first_name'], $userDetails['last_name']).',</p>
					<p>Below you can find login credentials. You can login into this Stay Hub: Your Room Reservation Solution by visiting <a href="http://localhost/finalproject/StayHub/home.php" target="_blank"><b>Stay Hub</b></a> this link.</p>
					<p><b>Username : </b>'.$userDetails['email'].'<br /><b>Password : </b>'.base64_decode($userDetails['password']).'</p>
					<p>In case if you have any difficulty please <a href = "mailto:suredevalla1000@gmail.com">eMail</a> us.</p>
					<p>Thank you,<br />
					<b>Stay Hub: Your Room Reservation Solution</b?</p>
				';
				
				$response = send_email($email, $subject, $body);
				if(!$response) {
					echo "<script>alert('Some issues while sending the password mail. Please try again after some time!');</script>";
					echo "<script type='text/javascript'>location.href = 'index.php';</script>";
					return;
				}
			}
			
			echo "<script>alert('The email containing password will be sent if the email-id is registered with us!');</script>";
		} 
		catch(Exception $e) { 
			echo "<script>alert('Some issues while fetching the password. If problem still persists, try again after some time!');</script>";
		}
		echo "<script type='text/javascript'>location.href = 'index.php';</script>";
	}
	
	function validateLogin() {
		$email = filterString($_POST["username"]);
		$password = $_POST["lgnpassword"];
		
		if($email == "ADMIN@GMAIL.COM" && $password == "admin1234") { 
			$_SESSION["admin"] = "admin";
			echo "<script>alert('Welcome Admin!');</script>";
			echo "<script type='text/javascript'>location.href = 'admin.php';</script>";
			return;
		}
		
		try {
			$password = base64_encode($password);
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM user_master WHERE email = ? and password = ? limit 1";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $email);
			$sqlStatement->bindParam(2, $password);
			$sqlStatement->execute();
			$userDetails = $sqlStatement->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($userDetails)) {
				$_SESSION["user"] = $userDetails;
				echo "<script>alert('Login successfull. Welcome back, ".getManneredUserName()."!');</script>";
				echo "<script type='text/javascript'>location.href = 'home.php';</script>";
			}
			else {
				echo "<script>alert('No user found with provided credentials. Please try again with correct login details!');</script>";
				echo "<script type='text/javascript'>location.href = 'index.php';</script>";
			}
			return;
		} 
		catch(Exception $e) { 
			echo "<script>alert('Some issues while logging-in. If problem still persists, try again after some time!');</script>";
			echo "<script type='text/javascript'>location.href = 'index.php';</script>";
			return;
		}
	}
	
	function validateSignUp() {
		$fname = filterString($_POST["fname"]);
		$lname = filterString($_POST["lname"]);
		$email = filterString($_POST["email"]);
		$telephone = filterString($_POST["telephone"]);
		$password = $_POST["password"];
		
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT count(*) as Counts FROM user_master WHERE email like ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $email);
			$sqlStatement->execute();
			$dataRow = $sqlStatement->fetch(PDO::FETCH_ASSOC);
			if(!($dataRow['Counts'] == 0)) {
				echo "<script>alert('The entered email-id has already been used, please try again with different email-id!');</script>";
				echo "<script type='text/javascript'>location.href = 'index.php';</script>";
				return;
			}
			
			$sqlQuery = "insert into user_master(first_name, last_name, email, mobile, password) values(?, ?, ?, ?, ?);";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $fname);
			$sqlStatement->bindParam(2, $lname);
			$sqlStatement->bindParam(3, $email);
			$sqlStatement->bindParam(4, $telephone);
			$sqlStatement->bindParam(5, base64_encode($password));
			$sqlStatement->execute();
		} 
		catch(Exception $e) { 
			echo "<script>alert('Some issues while creating new account. Check all the fields, if problem still persists, try again after some time!');</script>";
			echo "<script type='text/javascript'>location.href = 'index.php';</script>";
			return;
		}
		
		$subject = 'Welcome to Stay Hub!';
		$body = '
			<center><h2>Welcome to Stay Hub!</h2></center>
			<p>Hi '.getManneredUserName($fname, $lname).',</p>
			<p>Your account is created successfully, so now you can login into this Stay Hub: Your Room Reservation Solution by visiting <a href="http://localhost/finalproject/StayHub/home.php" target="_blank"><b>Stay Hub</b></a> this link. Below you can find login credentials.</a></p>
			<p><b>Username : </b>'.$email.'<br /><b>Password : </b>'.$password.'</p>
			<p>In case if you have any difficulty please <a href = "mailto:ramreddy.yk17@gmail.com">eMail</a> us.</p>
			<p>Thank you,<br />
			<b>Stay Hub: Your Room Reservation Solution</b?</p>
		';
		
		$response = send_email($email, $subject, $body);
		if($response) {
			echo "<script>alert('Congratulations, account has been created successfully!');</script>";
		}
		else {
			echo "<script>alert('Some issues while creating new account. Check mail-id, if problem still persists, try again after some time!');</script>";
		}
		echo "<script type='text/javascript'>location.href = 'index.php';</script>";
	}
?>