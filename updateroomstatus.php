<!DOCTYPE html>
<html lang="en" >
<head>
	<meta charset="UTF-8">
	<title>Updating Room Status - Stay Hub</title>
	<link rel="icon" type="image/x-icon" href="images/favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript">
		if(!(sessionStorage.getItem("bookingId") == "") && !(sessionStorage.getItem("sessionOf") == "")) { 
			var bookingId = sessionStorage.getItem("bookingId");
			var sessionOf = sessionStorage.getItem("sessionOf");
			sessionStorage.setItem("bookingId", "");
			sessionStorage.setItem("sessionOf", "");
			$.getJSON('getjson.php', {curstatus: 'updateStatusToUser', bookingid: bookingId}, function (data, textStatus, jqXHR) { })
			.fail(function (jqxhr,settings,ex) { });
			
			if(sessionOf == "admin") { location.href = 'admin.php'; } else { location.href = 'bookings.php'; }
		} else { location.href = 'index.php'; }
	</script>
</head>
</html>