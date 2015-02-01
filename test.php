<?php
session_start();
include_once('dbinfo.php');
?>

	<script src="js/bootstrap.js"></script>

	<link href="css/bootstrap.css" rel="stylesheet">

<ul class="pagination">
	<li id="page1"><a>1</a></li>
	<li id="page2"><a>2</a></li>
	<li id="page3"><a>3</a></li>
	<li id="page4"><a>4</a></li>
	<li id="page5"><a>5</a></li>
	<li id="page6"><a>6</a></li>
	<li id="page7"><a>7</a></li>
	<li id="page8"><a>8</a></li>
	<li id="page9"><a>9</a></li>
	<li id="page10"><a>10</a></li>
</ul>

<?php

$sql = "SELECT * FROM `gamesNES`";
$result = $db->prepare($sql);
$result->execute();
$rows_found = $result->rowCount();

echo $rows_found;




?>