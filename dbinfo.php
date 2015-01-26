<?php 
//my localhost
//*
try {
	$db = new PDO('mysql:host=localhost;dbname=cst',
		'root',
		'root'
	);
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
    die();
}
//*/

//csumb

/*
try {
	$db = new PDO('mysql:host=localhost;dbname=guti2809',
		'guti2809',
		'62597037c75367c'
	);
} catch (PDOException $e) {
	print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

*/


?>