<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>Logout - Stay Hub</title>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="main.css">
	<script type="text/javascript" src="main.js"></script>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script type="text/javascript">
		let remSeconds = 6;
		let myVar = setInterval(updateTimer, 1000);
		function updateTimer() {
		remSeconds = remSeconds - 1;
		document.getElementById("remSeconds").innerHTML = String(remSeconds);
		if(remSeconds === 0) { clearInterval(myVar); location.href = 'index.php'; }
	}
	</script>
</head>
<body onload="loadLogoutScripts()">
	<div class="box-form">
		<div class="left" id="loginleftpanel">
			<div class="overlay">
				<span class="head1">Stay Hub</span><br />
				<span class="head2">Stay Hub: Your Room Reservation Solution</span>
				<hr>
				<p>&#8220;“When you get into a hotel room, you lock the door, and you know there is a secrecy, there is a luxury, there is fantasy. There is comfort. There is reassurance.”&#8221;</p>
			</div>
		</div>
		<div class="right">
			<div class="head3">Logout</div>
			<p>You have been logged-out successfully.<br /><br />After <b><span id="remSeconds">few</span> seconds</b>, you will be automatically redirected to login page!<br /><br />Click <a href="index.php">here</a> to manually go to login page.</p>
			<h4>Thanks for visiting us. See you soon!!</h4>
		</div>
	</div>
</body>
</html>