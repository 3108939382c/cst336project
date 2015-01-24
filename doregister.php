<?php
session_start();
include_once('dbinfo.php');

//post to db


//set session vars
$_SESSION['username'] = $_POST['registerUsername'];

//send user back into index.php
echo '<script>location.href="index.php"</script>';

?>