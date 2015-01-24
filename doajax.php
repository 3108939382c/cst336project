<?php

include_once('dbinfo.php');

//function registerUsername is there to check and see if the username is taken.
if ($_POST['doFunction'] == "registerUsername") {
	# code...
	$usernameToCheck = $_POST['checkUsername'];
	$sql = "SELECT * FROM `user` WHERE `username` = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$usernameToCheck"));
	$rows_found = $result->rowCount();
	//echo $_POST['checkUsername'];

	if ($rows_found >= 1) {
		# code...
		echo 'fail';
	} else {
		echo 'okay';
	}
	
}





?>