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

	echo '

		<paper-shadow z="2" class="main" style="padding: 10px;">
			<div align="center">
			New Password:
			<input type="text" id="passwordInput">
			<button class="btn btn-default" id="passwordButton">Submit</button>


			</div>
		</paper-shadow>
		<script>
		$("#passwordButton").click(function() {

			var pword = $("#passwordInput").val();

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "doPasswordUpdate",
					postPassword: pword
				}, 
				success: function(result) {
					$("#output").html(result);
				}
			});
		});
		</script>
		';
	

	/*
	$newPassword = "abc123";
	$userID = $_SESSION['id'];

	$sql = "UPDATE `user` SET `password` = ? WHERE `id` = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$password", "$userID"));
	echo '<paper-toast id="toast2" text="Password has been updated." opened></paper-toast>';
	*/


}

if ($_POST['doFunction'] == "doPasswordUpdate") {
	$userID = $_SESSION['id'];
	$password = sha1($_POST['postPassword']);
	$sql = "UPDATE `user` SET `password` = ? WHERE `id` = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$password", "$userID"));

	echo '

		<paper-shadow z="2" class="main" style="padding: 10px;">
			<div align="center">
			New Password:
			<input type="text" id="passwordInput">
			<button class="btn btn-default" id="passwordButton">Submit</button>


			</div>
		</paper-shadow>
		<paper-toast id="toast2" text="Password has been updated." opened></paper-toast>
		<script>
		$("#passwordButton").click(function() {

			var pword = $("#passwordInput").val();

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "doPasswordUpdate",
					postPassword: pword
				}, 
				success: function(result) {
					$("#output").html(result);
				}
			});
		});
		</script>

		';
}

if ($_POST['doFunction'] == "rentGame") {
	$gameID = $_POST['rentalID'];
	$userID = $_SESSION['id'];

	//update gamesnes -1 quantity available
	$sql = "UPDATE gamesNES SET quantityAvail = quantityAvail - 1 WHERE id = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$gameID"));

	//insert into whatsout so it doesn't show as rented titles
	$sql = "INSERT INTO `whatsOut` (`user_id`, `game_id`) VALUES (?, ?)";
	$result = $db->prepare($sql);
	$result->execute(array("$userID", "$gameID"));

	//select all info for game, and echo it out
	$sql = "SELECT * FROM gamesNES WHERE id = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$gameID"));

	foreach ($result as $row) {
		# code...
		echo '

		<paper-shadow z="2" class="main" style="padding: 10px;">
			<div align="center">';

			//png
			$pngImg = "img/thumb/" . $row['id'] . ".png";

			//jpg
			$jpgImg = "img/thumb/" . $row['id'] . ".jpg";

			if (file_exists($pngImg)) {
				echo '<img src="'.$pngImg.'" data-toggle="modal" data-target="#modal'.$row['id'].'">';
			}
			if (file_exists($jpgImg)) {
				echo '<img src="'.$jpgImg.'" data-toggle="modal" data-target="#modal'.$row['id'].'">';
			}

			echo '
				<br>
				You Rented '.$row['gameTitle'].'!
				<br>
				<button class="btn btn-default" id="rentMoreButton">Rent More!</button>

			</div>
		</paper-shadow>

		<script>
		$("#rentMoreButton").click(function() {
			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "printMain",
					goPage: "1"
				}, 
				success: function(result) {
					$("#output").html(result);
				}
			});
		});

		</script>
		';

	}

}


if ($_POST['doFunction'] == "rentedTitles") {
	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
			<h2>Rented Titles</h2>
		</div>
	</paper-shadow>
	<br>';

	$userID = $_SESSION['id'];
	$sql = "SELECT * FROM gamesNES g JOIN whatsOut w ON w.game_id = g.id WHERE w.user_id = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$userID"));

	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
		<table class="table table-striped">
			<tr>
				<th>Image</th>
				<th>Game Title</th>
				<th>Year Released</th>
				<th>Genre</th>
				<th>&nbsp;</th>
			</tr>
		';

	foreach ($result as $row) {
		echo '
			<tr>';
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
		echo '
				<td>'.$row['gameTitle'].'</td>
				<td>'.$row['year'].'</td>
				<td>'.$row['genre'].'</td>
				<td><button class="btn btn-default returnButton" id="'.$row['game_id'].'">Return</button></td>
			</tr>

			';
	}
	echo '
		</table>
		</div>
	</paper-shadow>

	<script>

	$(".returnButton").click(function() {
		var buttonID = this.id;

		$.ajax({
			url: "doajax.php",
			type: "POST",
			data: {
				doFunction: "returnRental",
				rentalID: buttonID
			},
			success: function(result) {
				$("#output").html(result);
			}
		});
	});

	</script>';

/*
SELECT * FROM gamesNES g JOIN whatsOut w ON w.game_id = g.id WHERE w.user_id = '4'
*/

}

if ($_POST['doFunction'] == "returnRental") {
	$gameID = $_POST['rentalID'];
	$userID = $_SESSION['id'];

	//update gamesnes +1 quantity available
	$sql = "UPDATE gamesNES SET quantityAvail = quantityAvail + 1 WHERE id = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$gameID"));

	//delete from whatsout so it doesn't show as rented titles
	$sql = "DELETE FROM whatsOut WHERE user_id = ? AND game_id = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$userID", "$gameID"));

	//print out new list
	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
			<h2>Rented Titles</h2>
		</div>
	</paper-shadow>
	<br>';

	$sql = "SELECT * FROM gamesNES g JOIN whatsOut w ON w.game_id = g.id WHERE w.user_id = ?";
	$result = $db->prepare($sql);
	$result->execute(array("$userID"));

	echo '<paper-toast id="toast2" text="Game was returned, Thank you!." opened></paper-toast>';

	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
		<table class="table table-striped">
			<tr>
				<th>Image</th>
				<th>Game Title</th>
				<th>Year Released</th>
				<th>Genre</th>
				<th>&nbsp;</th>
			</tr>
		';

	foreach ($result as $row) {
		echo '
			<tr>';
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
		echo '
				<td>'.$row['gameTitle'].'</td>
				<td>'.$row['year'].'</td>
				<td>'.$row['genre'].'</td>
				<td><button class="btn btn-default returnButton" id="'.$row['game_id'].'">Return</button></td>
			</tr>

			';
	}
	echo '
		</table>
		</div>
	</paper-shadow>

	<script>

	$(".returnButton").click(function() {
		var buttonID = this.id;

		$.ajax({
			url: "doajax.php",
			type: "POST",
			data: {
				doFunction: "returnRental",
				rentalID: buttonID
			},
			success: function(result) {
				$("#output").html(result);
			}
		});
	});

	</script>';

}

if ($_POST['doFunction'] == "home") {

	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
			<table class="table">
				<tr>
					<td>Title:</td>
					<td>Genre:</td>
					<td>Release Year:</td>
					<td>Availability:</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="searchText" id="searchText">
					</td>
					<td>
						<select name="searchGenre" id="searchGenre">
							<option value="All">All</option>
							<option value="Action">Action</option>
							<option value="Adventure">Adventure</option>
							<option value="Construction and Man">Construction and Man</option>
							<option value="Fighting">Fighting</option>
							<option value="Flight Simulator">Flight Simulator</option>
							<option value="Life Simulation">Life Simulation</option>
							<option value="Platform">Platform</option>
							<option value="Puzzle">Puzzle</option>
							<option value="Racing">Racing</option>
							<option value="Role-Playing">Role-Playing</option>
							<option value="Sandbox">Sandbox</option>
							<option value="Shooter">Shooter</option>
							<option value="Sports">Sports</option>
							<option value="Strategy">Strategy</option>
							<option value="Vehicle Simulation">Vehicle Simulation</option>
						</select>
					</td>
					<td>
						<select name="searchYears" id="searchYears">
							<option value="All">All</option>
							<option value="1970">1970</option>
							<option value="1982">1982</option>
							<option value="1983">1983</option>
							<option value="1984">1984</option>
							<option value="1985">1985</option>
							<option value="1986">1986</option>
							<option value="1987">1987</option>
							<option value="1988">1988</option>
							<option value="1989">1989</option>
							<option value="1990">1990</option>
							<option value="1991">1991</option>
							<option value="1992">1992</option>
							<option value="1993">1993</option>
							<option value="1994">1994</option>
							<option value="1995">1995</option>
							<option value="1996">1996</option>
							<option value="1999">1999</option>
							<option value="2010">2010</option>
							<option value="2014">2014</option>
							<option value="2015">2015</option>
						</select>
					</td>
					<td>
						<select name="searchStock" id="searchStock">
							<option value="All">All</option>
							<option value="InStock">In Stock</option>
							<option value="OutOfStock">Out of Stock</option>
						</select>
					</td>
					<td>
						<button id="clickButton">Submit</button>
					</td>
				</tr>

			</table>

			<br>
		</div>
	</paper-shadow>

	<br>

	<paper-shadow z="2" class="main">
		<table class="table table-striped" width="100%">
			<tr>
				<th>Image</th>
				<th>Game Title</th>
				<th>Year<br>Released</th>
				<th>Genre</th>
				<th>Quantity<br>Available</th>
				<th>&nbsp;</th>
			</tr>

		';

		$sql = "SELECT * FROM `gamesNES` LIMIT 0, 100";
		$result = $db->prepare($sql);
		$result->execute();

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

			echo '<td>'.$row['year'].'</td>';
			echo '<td>'.$row['genre'].'</td>';
			echo '<td>'.$row['quantityAvail'].'</td>';

			if (isset($_SESSION['username'])) {
								
				if ($row['quantityAvail'] >= 1) {

					$userID = $_SESSION['id'];
					$gameID = $row['id'];

					$sql2 = "SELECT * FROM `whatsOut` WHERE user_id = ? AND game_id = ?";
					$result2 = $db->prepare($sql2);
					$result2->execute(array("$userID", "$gameID"));
					$rows_found2 = $result2->rowCount();

					if ($rows_found2 >= 1) {
						echo '<td><button disabled class="btn btn-default">Already Rented</button></td>';
					} else {
						echo '<td><button class="btn btn-default rentButton" id="'.$row['id'].'">Rent!</button></td>';
					}


				} else {
					echo '<td><button disabled class="btn btn-default">Out of Stock</button></td>';
				}
			} else {
				echo '<td>&nbsp;</td>';
			}

			
	
			echo '</tr>';
			
		}

	echo '
		</table>

		<div align="center">
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
		</div>	
		<script>
		';

		for ($i=1; $i < 11; $i++) { 
			# code...
			echo '
			$("#page'.$i.'").click(function() {
				$.ajax({
					url: "doajax.php",
					type: "POST",
					data: {
						doFunction: "printMain",
						goPage: "'.$i.'"
					}, 
					success: function(result) {
						$("#output").html(result);
					}
				});
			});


			';
		}

		

		echo '

		$("#clickButton").click(function() {

			var searchText = $("#searchText").val();
			var searchGenre = $("#searchGenre").val();
			var searchYears = $("#searchYears").val();
			var searchStock = $("#searchStock").val();

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "makeSearch",
					theText: searchText,
					theGenre: searchGenre,
					theYear: searchYears,
					theStock: searchStock
				},
				success: function(result) {
					$("#output").html(result);
				}
			});
		});

		$(".rentButton").click(function() {
			var buttonID = this.id;

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "rentGame",
					rentalID: buttonID
				},
				success: function(result) {
					$("#output").html(result);
				}
			});
		});
		</script>
	</paper-shadow>
	';
}


if ($_POST['doFunction'] == "printMain") {

	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
			<table class="table">
				<tr>
					<td>Title:</td>
					<td>Genre:</td>
					<td>Release Year:</td>
					<td>Availability:</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="searchText" id="searchText">
					</td>
					<td>
						<select name="searchGenre" id="searchGenre">
							<option value="All">All</option>
							<option value="Action">Action</option>
							<option value="Adventure">Adventure</option>
							<option value="Construction and Man">Construction and Man</option>
							<option value="Fighting">Fighting</option>
							<option value="Flight Simulator">Flight Simulator</option>
							<option value="Life Simulation">Life Simulation</option>
							<option value="Platform">Platform</option>
							<option value="Puzzle">Puzzle</option>
							<option value="Racing">Racing</option>
							<option value="Role-Playing">Role-Playing</option>
							<option value="Sandbox">Sandbox</option>
							<option value="Shooter">Shooter</option>
							<option value="Sports">Sports</option>
							<option value="Strategy">Strategy</option>
							<option value="Vehicle Simulation">Vehicle Simulation</option>
						</select>
					</td>
					<td>
						<select name="searchYears" id="searchYears">
							<option value="All">All</option>
							<option value="1970">1970</option>
							<option value="1982">1982</option>
							<option value="1983">1983</option>
							<option value="1984">1984</option>
							<option value="1985">1985</option>
							<option value="1986">1986</option>
							<option value="1987">1987</option>
							<option value="1988">1988</option>
							<option value="1989">1989</option>
							<option value="1990">1990</option>
							<option value="1991">1991</option>
							<option value="1992">1992</option>
							<option value="1993">1993</option>
							<option value="1994">1994</option>
							<option value="1995">1995</option>
							<option value="1996">1996</option>
							<option value="1999">1999</option>
							<option value="2010">2010</option>
							<option value="2014">2014</option>
							<option value="2015">2015</option>
						</select>
					</td>
					<td>
						<select name="searchStock" id="searchStock">
							<option value="All">All</option>
							<option value="InStock">In Stock</option>
							<option value="OutOfStock">Out of Stock</option>
						</select>
					</td>
					<td>
						<button id="clickButton">Submit</button>
					</td>
				</tr>

			</table>

			<br>
		</div>
	</paper-shadow>

	<br>

	<paper-shadow z="2" class="main">
		<table class="table table-striped" width="100%">
			<tr>
				<th>Image</th>
				<th>Game Title</th>
				<th>Year<br>Released</th>
				<th>Genre</th>
				<th>Quantity<br>Available</th>
				<th>&nbsp;</th>
			</tr>

		';

		$page = $_POST['goPage'];
		$page = ($page - 1) * 100;


		$sql = "SELECT * FROM `gamesNES` LIMIT ".$page.", 100";
		$result = $db->prepare($sql);
		$result->execute();

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

			echo '<td>'.$row['year'].'</td>';
			echo '<td>'.$row['genre'].'</td>';
			echo '<td>'.$row['quantityAvail'].'</td>';

			if (isset($_SESSION['username'])) {
								
				if ($row['quantityAvail'] >= 1) {

					$userID = $_SESSION['id'];
					$gameID = $row['id'];

					$sql2 = "SELECT * FROM `whatsOut` WHERE user_id = ? AND game_id = ?";
					$result2 = $db->prepare($sql2);
					$result2->execute(array("$userID", "$gameID"));
					$rows_found2 = $result2->rowCount();

					if ($rows_found2 >= 1) {
						echo '<td><button disabled class="btn btn-default">Already Rented</button></td>';
					} else {
						echo '<td><button class="btn btn-default rentButton" id="'.$row['id'].'">Rent!</button></td>';
					}


				} else {
					echo '<td><button disabled class="btn btn-default">Out of Stock</button></td>';
				}
			} else {
				echo '<td>&nbsp;</td>';
			}

			
	
			echo '</tr>';
			
		}

	echo '
		</table>

		<div align="center">
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
		</div>	
		<script>
		';

		for ($i=1; $i < 11; $i++) { 
			# code...
			echo '
			$("#page'.$i.'").click(function() {
				$.ajax({
					url: "doajax.php",
					type: "POST",
					data: {
						doFunction: "printMain",
						goPage: "'.$i.'"
					}, 
					success: function(result) {
						$("#output").html(result);
					}
				});
			});


			';
		}

		

		echo '

		$("#clickButton").click(function() {

			var searchText = $("#searchText").val();
			var searchGenre = $("#searchGenre").val();
			var searchYears = $("#searchYears").val();
			var searchStock = $("#searchStock").val();

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "makeSearch",
					theText: searchText,
					theGenre: searchGenre,
					theYear: searchYears,
					theStock: searchStock
				},
				success: function(result) {
					$("#output").html(result);
				}
			});
		});

		$(".rentButton").click(function() {
			var buttonID = this.id;

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "rentGame",
					rentalID: buttonID
				},
				success: function(result) {
					$("#output").html(result);
				}
			});
		});
		</script>
	</paper-shadow>
	';
}


if ($_POST['doFunction'] == "makeSearch" ) {
	# code...
	$stringStart = "SELECT * FROM gamesNES";
	$myArray = array();

	if ($_POST['theText'] != '') {
		$string = '`gameTitle` LIKE \'%'.$_POST['theText'].'%\'';
		array_push($myArray, $string);
	}

	if ($_POST['theGenre'] != 'All') {
		$string = '`genre` LIKE \'' . $_POST['theGenre'] . '\'';
		array_push($myArray, $string);
	}

	if ($_POST['theYear'] != 'All') {
		$string = '`year` LIKE \'' . $_POST['theYear'] . '\'';
		array_push($myArray, $string);
	}

	if ($_POST['theStock'] == 'InStock') {
		$string = '`quantityAvail` > 0';
		array_push($myArray, $string);
	}

	if ($_POST['theStock'] == 'OutOfStock') {
		$string = '`quantityAvail` = 0';
		array_push($myArray, $string);
	}

	// get array length
	$arrayLength = sizeof($myArray);

	//echo $arrayLength;
	if ($arrayLength == 0) {
		$sql = $stringStart;
	} elseif ($arrayLength == 1) {
		$stringStart = $stringStart . ' WHERE ' . $myArray[0];
		$sql =  $stringStart;
	} elseif ($arrayLength > 1) {
		# code...
		$stringStart = $stringStart . ' WHERE ' . $myArray[0];
		for ($i=1; $i < $arrayLength; $i++) { 
			# code...
			$stringStart = $stringStart . ' AND ' . $myArray[$i];
		}
		$sql =  $stringStart;
	}

	//$sql = "SELECT * FROM `gamesNES` LIMIT 0, 100";
	$result = $db->prepare($sql);
	$result->execute();

	echo '
	<paper-shadow z="2" class="main" style="padding: 10px;">
		<div align="center">
			<table class="table">
				<tr>
					<td>Title:</td>
					<td>Genre:</td>
					<td>Release Year:</td>
					<td>Availability:</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<input type="text" name="searchText" id="searchText">
					</td>
					<td>
						<select name="searchGenre" id="searchGenre">
							<option value="All">All</option>
							<option value="Action">Action</option>
							<option value="Adventure">Adventure</option>
							<option value="Construction and Man">Construction and Man</option>
							<option value="Fighting">Fighting</option>
							<option value="Flight Simulator">Flight Simulator</option>
							<option value="Life Simulation">Life Simulation</option>
							<option value="Platform">Platform</option>
							<option value="Puzzle">Puzzle</option>
							<option value="Racing">Racing</option>
							<option value="Role-Playing">Role-Playing</option>
							<option value="Sandbox">Sandbox</option>
							<option value="Shooter">Shooter</option>
							<option value="Sports">Sports</option>
							<option value="Strategy">Strategy</option>
							<option value="Vehicle Simulation">Vehicle Simulation</option>
						</select>
					</td>
					<td>
						<select name="searchYears" id="searchYears">
							<option value="All">All</option>
							<option value="1970">1970</option>
							<option value="1982">1982</option>
							<option value="1983">1983</option>
							<option value="1984">1984</option>
							<option value="1985">1985</option>
							<option value="1986">1986</option>
							<option value="1987">1987</option>
							<option value="1988">1988</option>
							<option value="1989">1989</option>
							<option value="1990">1990</option>
							<option value="1991">1991</option>
							<option value="1992">1992</option>
							<option value="1993">1993</option>
							<option value="1994">1994</option>
							<option value="1995">1995</option>
							<option value="1996">1996</option>
							<option value="1999">1999</option>
							<option value="2010">2010</option>
							<option value="2014">2014</option>
							<option value="2015">2015</option>
						</select>
					</td>
					<td>
						<select name="searchStock" id="searchStock">
							<option value="All">All</option>
							<option value="InStock">In Stock</option>
							<option value="OutOfStock">Out of Stock</option>
						</select>
					</td>
					<td>
						<button id="clickButton">Submit</button>
					</td>
				</tr>

			</table>

			<br>
		</div>
	</paper-shadow>

	<br>

	<paper-shadow z="2" class="main">
		<table class="table table-striped" width="100%">
			<tr>
				<th>Image</th>
				<th>Game Title</th>
				<th>Year<br>Released</th>
				<th>Genre</th>
				<th>Quantity<br>Available</th>
				<th>&nbsp;</th>
			</tr>

		';

		/*
		$page = $_POST['goPage'];
		$page = ($page - 1) * 100;


		$sql = "SELECT * FROM `gamesNES` LIMIT ".$page.", 100";
		$result = $db->prepare($sql);
		$result->execute();
		*/

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

			echo '<td>'.$row['year'].'</td>';
			echo '<td>'.$row['genre'].'</td>';
			echo '<td>'.$row['quantityAvail'].'</td>';

			if (isset($_SESSION['username'])) {
								
				if ($row['quantityAvail'] >= 1) {

					$userID = $_SESSION['id'];
					$gameID = $row['id'];

					$sql2 = "SELECT * FROM `whatsOut` WHERE user_id = ? AND game_id = ?";
					$result2 = $db->prepare($sql2);
					$result2->execute(array("$userID", "$gameID"));
					$rows_found2 = $result2->rowCount();

					if ($rows_found2 >= 1) {
						echo '<td><button disabled class="btn btn-default">Already Rented</button></td>';
					} else {
						echo '<td><button class="btn btn-default rentButton" id="'.$row['id'].'">Rent!</button></td>';
					}


				} else {
					echo '<td><button disabled class="btn btn-default">Out of Stock</button></td>';
				}
			} else {
				echo '<td>&nbsp;</td>';
			}

			
	
			echo '</tr>';
			
		}

	echo '
		</table>

		
		<script>
		';

		for ($i=1; $i < 11; $i++) { 
			# code...
			echo '
			$("#page'.$i.'").click(function() {
				$.ajax({
					url: "doajax.php",
					type: "POST",
					data: {
						doFunction: "printMain",
						goPage: "'.$i.'"
					}, 
					success: function(result) {
						$("#output").html(result);
					}
				});
			});


			';
		}

		

		echo '

		$("#clickButton").click(function() {

			var searchText = $("#searchText").val();
			var searchGenre = $("#searchGenre").val();
			var searchYears = $("#searchYears").val();
			var searchStock = $("#searchStock").val();

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "makeSearch",
					theText: searchText,
					theGenre: searchGenre,
					theYear: searchYears,
					theStock: searchStock
				},
				success: function(result) {
					$("#output").html(result);
				}
			});
		});

		$(".rentButton").click(function() {
			var buttonID = this.id;

			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "rentGame",
					rentalID: buttonID
				},
				success: function(result) {
					$("#output").html(result);
				}
			});
		});
		</script>
	</paper-shadow>
	';
}





?>