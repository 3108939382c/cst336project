<?php 

try {
	$db = new PDO('mysql:host=localhost;dbname=cst',
		'root',
		'root'
	);
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>