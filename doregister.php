<?php
session_start();
include_once('dbinfo.php');

$postUsername = $_POST['registerUsername'];
$postPassword = $_POST['registerPassword'];

//post to db
$sql = "INSERT INTO `user` (`id`, `username`, `password`) VALUES (NULL, ?, ?)";
$result = $db->prepare($sql);
$result->execute(array("$postUsername", "$postPassword"));



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
	
}

//echo out location
echo '<script>location.href="index.php"</script>';


?>