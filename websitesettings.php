<?php
	
	require_once("pdo.php");
	
	function getSettingValue($name) {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT value FROM website_settings WHERE name = ? limit 1;";
			$sqlStatement = $sqlConn->prepare($sqlQuery);
			$sqlStatement->bindParam(1, $name);
			$sqlStatement->execute();
			$result = $sqlStatement->fetch(PDO::FETCH_ASSOC);
			
			if(!empty($result)) { echo $result['value']; }
		} catch(Exception $e) { }
	}
	
	function getSettings() {
		try {
			$database = new Database();
			$sqlConn = $database->getConnection();
			
			$sqlQuery = "SELECT * FROM website_settings ORDER BY setting_id;";
			$sqlStatement = $sqlConn->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$sqlStatement->execute();
			$result = $sqlStatement->fetchAll();
			foreach($result as $curRow) {
				echo "<div class='singleDiv'><div class='settingDiv settingColumn mulColRow'><div class='colLeft'><span class='settingName'>" . $curRow["name"]. "</span></div><div class='colRight'><a class='webup' href='javascript:updateSetting(" . $curRow['name'] . ")'><i class='fa fa-pencil-square-o updateButton'></i></a></div><input placeholder='Enter setting value' type='text' id='" . $curRow["name"] . "' class='settingInput' value='" . $curRow["value"]. "'></div></div>";
			}
		} catch(Exception $e) { }
	}
	
?>