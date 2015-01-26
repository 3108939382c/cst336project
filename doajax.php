<?php
session_start();
include_once('dbinfo.php');

//function registerUsername is there to check and see if the username is taken.
if ($_POST['doFunction'] == "registerUsername") {
	# code...
	$usernameToCheck = $_POST['checkUsername'];
	$sql = "SELECT * FROM `user` WHERE `username` = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$usernameToCheck"));
	$rows_found = $result->rowCount();

	if ($rows_found >= 1) {
		echo 'fail';
	} else {
		echo 'okay';
	}
	
}

if ($_POST['doFunction'] == "updatePassword") {
	echo 'update password';
}

if ($_POST['doFunction'] == "rentedTitles") {
	echo 'rented titles';
}

if ($_POST['doFunction'] == "home") {


	$sql = "SELECT * FROM `gamesNES` LIMIT 30";
	$result = $db->prepare($sql);
	$result->execute();




	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
			<h2>something</h2>
			<br>
		</div>
	</paper-shadow>

	<br>

	<paper-shadow z="2" class="main">
		<table class="table table-striped" width="100%">
			<tr>
				<th>Image</th>
				<th>Game Title</th>
				<th>Release Date</th>
				<th>Genre</th>
			</tr>

		';

		foreach ($result as $row) {
			echo '<tr>';

			//png
			$pngImg = "img/thumb/" . $row['id'] . ".png";

			//jpg
			$jpgImg = "img/thumb/" . $row['id'] . ".jpg";

			if (file_exists($pngImg)) {
				# code...
				echo '<td><img src="'.$pngImg.'" data-toggle="modal" data-target="#modal'.$row['id'].'"></td>';
			} elseif (file_exists($jpgImg)) {
				# code...
				echo '<td><img src="'.$jpgImg.'" data-toggle="modal" data-target="#modal'.$row['id'].'"></td>';
			} else {
				echo '<td>&nbsp;</td>';
			}


			echo '<td class="text-left"><span data-toggle="modal" data-target="#modal'.$row['id'].'">'.$row['gameTitle'] . '</span>';

			//modal
			echo '
			<div class="modal fade" id="modal'.$row['id'].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="myModalLabel">'.$row['gameTitle'].'</h4>
						</div>
						<div class="modal-body">
							'.$row['description'].'
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
			';
			//end modal
			echo '</td>';

			echo '<td>'.$row['releaseDate'].'</td>';
			echo '<td>'.$row['genre'].'</td>';
	
			echo '</tr>';
			
		}

	echo '
		</table>			
	</paper-shadow>
	';
}


if ($_POST['doFunction'] == "buildQuery") {
	echo $_POST['theText'];
	echo '<br>';
	echo $_POST['theGenre'];
	echo '<br>';
	echo $_POST['theYear'];
	echo '<br>';
	echo $_POST['theAvail'];
	echo '<br>';
	echo 'chimichanga';
	echo '<br><br><br>';

	$stringStart = "SELECT * FROM gamesNES";
	$myArray = array();

	if ($_POST['theText'] != '') {
		$string = '`gameTitle` LIKE %$_POST[theText]%';
		array_push($myArray, $string);
	}

	if ($_POST['theGenre'] != 'All') {
		$string = '`genre` LIKE %$_POST[theGenre]%';
		array_push($myArray, $string);
	}

	if ($_POST['theYear'] != 'All') {
		$string = '`year` LIKE %$_POST[theYear]%';
		array_push($myArray, $string);
	}

	// get array length
	$arrayLength = sizeof($myArray);

	//echo $arrayLength;
	if ($arrayLength == 0) {
		echo $stringStart;
	} elseif ($arrayLength == 1) {
		$stringStart = $stringStart . ' WHERE ' . $myArray[0];
		echo $stringStart;
	} elseif ($arrayLength > 1) {
		# code...
		$stringStart = $stringStart . ' WHERE ' . $myArray[0];
		for ($i=1; $i < $arrayLength; $i++) { 
			# code...
			$stringStart = $stringStart . ' AND ' . $myArray[$i];
		}
		echo $stringStart;
	}



	//SELECT * FROM gamesNES WHERE 
}






?>