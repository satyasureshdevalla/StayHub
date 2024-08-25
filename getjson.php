<?php
	require_once("pdo.php");
	require_once("email.php");
	header('Content-Type: application/json');
    $response = "failed";
	try {
		$database = new Database();
		$sqlConn = $database->getConnection();
		if(isset($_GET['cancelbyuserid'])) {
			$sqlQuery = "update bookings_details set status = 'Cancelled By User' where booking_id = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$curValue = htmlspecialchars(strip_tags($_GET['cancelbyuserid']));
			$sqlStatement->bindParam(1, $curValue);
			$sqlStatement->execute();
			$response = 'passed';
		}
		else if(isset($_GET['cancelbyadminid'])) {
			$sqlQuery = "update bookings_details set status = 'Cancelled By Admin' where booking_id = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$curValue = htmlspecialchars(strip_tags($_GET['cancelbyadminid']));
			$sqlStatement->bindParam(1, $curValue);
			$sqlStatement->execute();
			$response = 'passed';
		}
		else if(isset($_GET['approveid'])) {
			$sqlQuery = "update bookings_details set status = 'Approved' where booking_id = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$curValue = htmlspecialchars(strip_tags($_GET['approveid']));
			$sqlStatement->bindParam(1, $curValue);
			$sqlStatement->execute();
			$response = 'passed';
		}
		else if(isset($_GET['updatename']) && isset($_GET['updatevalue'])) {
			$sqlQuery = "update website_settings set value = ? where name = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$curValue1 = htmlspecialchars(strip_tags($_GET['updatevalue']));
			$sqlStatement->bindParam(1, $curValue1);
			$curValue2 = htmlspecialchars(strip_tags($_GET['updatename']));
			$sqlStatement->bindParam(2, $curValue2);
			$sqlStatement->execute();
			$response = 'passed';
		}
		else if(isset($_GET['updateprice']) && isset($_GET['updateroom'])) {
			$sqlQuery = "update room_master set price = ? where room_id = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$curValue1 = htmlspecialchars(strip_tags($_GET['updateprice']));
			$sqlStatement->bindParam(1, $curValue1);
			$curValue2 = htmlspecialchars(strip_tags($_GET['updateroom']));
			$sqlStatement->bindParam(2, $curValue2);
			$sqlStatement->execute();
			$response = 'passed';
		}
		else if(isset($_GET['updateratio']) && isset($_GET['updatebedding'])) {
			$sqlQuery = "update bedding_master set ratio_with_room = ? where bedding_id = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$curValue1 = htmlspecialchars(strip_tags($_GET['updateratio']));
			$sqlStatement->bindParam(1, $curValue1);
			$curValue2 = htmlspecialchars(strip_tags($_GET['updatebedding']));
			$sqlStatement->bindParam(2, $curValue2);
			$sqlStatement->execute();
			$response = 'passed';
		}
		else if(isset($_GET['updatecaption']) && isset($_GET['imageid'])) {
			$sqlQuery = "update gallery_images set caption = ? where image_id = ?";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$curValue1 = htmlspecialchars(strip_tags($_GET['updatecaption']));
			$sqlStatement->bindParam(1, $curValue1);
			$curValue2 = htmlspecialchars(strip_tags($_GET['imageid']));
			$sqlStatement->bindParam(2, $curValue2);
			$sqlStatement->execute();
			$response = 'passed';
		}
		else if(isset($_GET['curstatus']) && isset($_GET['bookingid'])) {
			$curValue1 = htmlspecialchars(strip_tags($_GET['curstatus']));
			$curValue2 = htmlspecialchars(strip_tags($_GET['bookingid']));
			if($curValue1 == "updateStatusToUser") { updateStatusToUser($curValue2); }
		}
		else {
			$response = 'failed';
		}
	} catch(Exception $e) { }
    echo json_encode($response);
	
?>