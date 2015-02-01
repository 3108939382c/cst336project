<?php
session_start();
include_once('dbinfo.php');

$postUsername = $_POST['loginUsername'];
$postPassword = $_POST['loginPassword'];

$sql = "SELECT `id` FROM `user` WHERE `username` = ? AND `password` = ? LIMIT 1";
$result = $db->prepare($sql);
$result->execute(array("$postUsername", "$postPassword"));
$rows_found = $result->rowCount();

if ($rows_found >= 1) {
	//correct username/password
	$row = $result->fetch(PDO::FETCH_ASSOC);

	//set session variable for id, set session variable for username
	$_SESSION['username'] = $postUsername;
	$_SESSION['id'] = $row['id'];

	//loginLog, insert date and user id when they logged in
	$timestamp = time();
	$userID = $row['id'];
	$sql = "INSERT INTO `loginLog` (`user_id`, `timestamp`) VALUES (?, ?)";
	$result = $db->prepare($sql);
	$result->execute(array("$userID", "$timestamp"));

	//echo out location
	echo '<script>location.href="index.php"</script>';

} else {
	//incorrect username/password
	echo '<script>location.href="index.php?p="</script>';
}

?>