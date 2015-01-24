<?php

session_start();

$_SESSION['username'] = "testUsername";

echo '<script>location.href="index.php"</script>';

?>