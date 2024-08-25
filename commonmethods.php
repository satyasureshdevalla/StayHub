<?php
	
	function getManneredUserName($fname = "", $lname = "") {
		try {
			if(empty($fname) && empty($lname)) {
				if(isset($_SESSION["user"])) {
					return trim(ucwords(strtolower($_SESSION["user"]['first_name'].' '.$_SESSION["user"]['last_name'])));
				}
			}
			else {
				return trim(ucwords(strtolower($fname.' '.$lname)));
			}
		} catch(Exception $e) { }
		return "";
	}
	
	function filterString($input) { return strtoupper(trim(htmlspecialchars(strip_tags($input)))); }
	
	function getDateTimePrint($input) { return strtoupper(date("d-m-Y h:i a", strtotime($input))); }
	
?>