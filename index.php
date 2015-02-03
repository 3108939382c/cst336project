<?php
session_start();
include_once('dbinfo.php');

?>
<!DOCTYPE html>
<html itemscope itemtype="http://schema.org/Organization">
<head>
	<title>NES Game Rental Store</title>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<script src="webcomponentsjs/webcomponents.js"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/bootstrap.js"></script>

	<link href="css/bootstrap.css" rel="stylesheet">
	<link rel="stylesheet" href="css/main.css">

	<link rel="import" href="http://www.polymer-project.org/components/polymer/polymer.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-scroll-header-panel/core-scroll-header-panel.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-header-panel/core-header-panel.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-drawer-panel/core-drawer-panel.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-toolbar/core-toolbar.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-menu/core-menu.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-item/paper-item.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-icon-button/core-icon-button.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-shadow/paper-shadow.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-input/paper-input.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-toast/paper-toast.html">

	<style>
		/* background for toolbar when it is at its full size */
		core-scroll-header-panel::shadow #headerBg {
			background-image: url(img/bg.jpg);
		}
	</style>

</head>

<body unresolved fullbleed>

	<core-drawer-panel id="drawerPanel" fit>

		<core-header-panel drawer id="navpanel">

			<?php 
				//paper toast if ?p= is set, show incorrect user/pword
				if (isset($_GET['p'])) {
					echo '<paper-toast id="toast2" text="Username and/or Password is incorrect" opened></paper-toast>';
				}	
			?>


			<?php
				if (isset($_SESSION['username'])) {
					# code... for logged in
					echo '
						<core-toolbar id="navheader">
							<span>Hello '.$_SESSION[username].'!</span>
						</core-toolbar>

						<core-menu>
							<span class="drawer">
								<paper-item id="home">
									Home
								</paper-item>
							</span>
							<span class="drawer" id="rentedTitles">
								<paper-item>
									Rented Titles
								</paper-item>
							</span>
							<span class="drawer" id="updatePassword">
								<paper-item>
									Update Password
								</paper-item>
							</span>
							<span class="drawer">
							<a href="dologout.php">
								<paper-item>
									Logout
								</paper-item>
							</a>
							<span>



						</core-menu>


					';
				} else {
					echo '
					<core-menu>
						<br>
						<h4>Login:</h4>

						<form action="dologin.php" method="post">
							<paper-input-decorator floatingLabel label="Username">
								<input type="text" name="loginUsername">
							</paper-input-decorator>

							<paper-input-decorator floatingLabel label="Password">
								<input type="password" name="loginPassword">
							</paper-input-decorator>
							<button class="btn btn-primary" type="submit">Login</button>
						</form>

						<hr>
						<h4>Register:</h4>

						<form action="doregister.php" method="post">
							<paper-input-decorator floatingLabel label="Username" id="registerUsernameDecorator">
								<input type="text" id="registerUsername" name="registerUsername">
							</paper-input-decorator>

							<paper-input-decorator floatingLabel label="Password" id="passwordDecorator">
								<input type="password" id="password" name="registerPassword">
							</paper-input-decorator>

							
							<button class="btn btn-primary disabled" id="registerButton">Register</button>
						</form>
					</core-menu>
					';
				}

			?>

		</core-header-panel>


		<core-scroll-header-panel condenses fit main keepCondensedHeader>
			<core-toolbar class="tall">
				<core-icon-button icon="menu" core-drawer-toggle></core-icon-button>
				<div flex></div>
				<div class="bottom indent title">NES Game Rental Store</div>
			</core-toolbar>
			<div class="content" flex>
				<br>

				<div id="output">

					<paper-shadow z="2" class="main" style="padding: 10px;">
						<div align="center">
							<?php

							$sql = "SELECT COUNT(*) FROM gamesNES";
							$stmt = $db -> prepare($sql);
							$stmt -> execute();
							$totalTitles = $stmt->fetchColumn();

							$sql = "SELECT COUNT(*) FROM gamesNES WHERE quantityAvail > 0";
							$stmt = $db -> prepare($sql);
							$stmt -> execute();
							$totalAvail = $stmt->fetchColumn();

							$sql = "SELECT COUNT(*) FROM gamesNES WHERE quantityAvail = 0";	
							$stmt = $db -> prepare($sql);
							$stmt -> execute();
							$totalOut = $stmt->fetchColumn();

							$sql = "SELECT AVG(quantityAvail) FROM gamesNES"; 
							$stmt = $db -> prepare($sql);
							$stmt -> execute();
							$avgAvail = $stmt->fetchColumn();
							$avgAvailFormatted = number_format($avgAvail); 

							echo '

							The NES Game Rental store features the most comprehensive selection of games available for old school Nintendo systems. 
							With '.$totalAvail.' currently available titles from our total inventory of '.$totalTitles.' distinct game titles, we are the #1 source for NES game rentals! 
							<br /><br />
							There is currently an average of '.$avgAvailFormatted.' copies in stock per game title, and many of our '.$totalOut.' rented titles are returned daily!


							';

							



							?>
						</div>
					</paper-shadow>

					<br>

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

						<?php

						$sql = "SELECT * FROM `gamesNES` ORDER BY `gameTitle` ASC LIMIT 0, 100 ";
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


						?>

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
					</paper-shadow>

				</div>

			</div>
		</core-scroll-header-panel>
	</core-drawer-panel>      


<script>
$(document).ready(function(){

	var usernameOK = false;
	var passwordOK = false;

	function checkButton() {
		if ((usernameOK == true) && (passwordOK == true)) {
			$("#registerButton").prop("disabled", false);
			$("#registerButton").removeClass("disabled");
		} else {
			$("#registerButton").prop("disabled", true);
		}
	}

	function setUsernameOK(a) {
		usernameOK = a;
	}

	function setPassworkOK(a) {
		passwordOK = a;
	}

	//check if username is available
	$("#registerUsername").keyup(function() {

		var x = $("#registerUsername").val();

		if (x == '') {
			setUsernameOK(false);
			$("#registerUsernameDecorator").attr("isInvalid", "true");
			$("#registerUsernameDecorator").attr("error", "Username cannot be empty.");
		} else {
			$.ajax({
				url: "doajax.php",
				type: "POST",
				data: {
					doFunction: "registerUsername",
					checkUsername: x
				},
				success: function(result) {
					if (result == 'okay') {
						//if result == okay, username is available to register
						//$("#output").html(result);
						setUsernameOK(true);
						$("#registerUsernameDecorator").attr("isInvalid", "false");

					} else {
						//else, username is already taken
						//$("#output").html(result);
						setUsernameOK(false);
						$("#registerUsernameDecorator").attr("isInvalid", "true");
						$("#registerUsernameDecorator").attr("error", "Username is already taken.");
						
					}
					checkButton();
					
				}
			});

		}

		
	});

	$("#password").keyup(function() {
		var p1 = $("#password").val();
		
		if (p1 == '') {
			//echo password cannot be left empty
			setPassworkOK(false);
			$("#passwordDecorator").attr("isInvalid", "true");
			$("#passwordDecorator").attr("error", "Password cannot be empty.");

		} else {
			//password field isn't empty, everything ok
			setPassworkOK(true);
			$("#passwordDecorator").attr("isInvalid", "false");

		}
		checkButton();
	});

	$("#updatePassword").click(function() {
		$.ajax({
			url: "doajax.php",
			type: "POST",
			data: {
				doFunction: "updatePassword",
				someData: "x"
			},
			success: function(result) {
				$("#output").html(result);
				
			}
		});

	});

	$("#rentedTitles").click(function() {
		$.ajax({
			url: "doajax.php",
			type: "POST",
			data: {
				doFunction: "rentedTitles",
				someData: "x"
			},
			success: function(result) {
				$("#output").html(result);
				
			}
		});

	});

	$("#home").click(function() {
		$.ajax({
			url: "doajax.php",
			type: "POST",
			data: {
				doFunction: "home",
				someData: "x"
			},
			success: function(result) {
				$("#output").html(result);
			}
		});

	});

	$('#clickButton').click(function() {

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

	<?php

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

	?>

	
	
});


</script>

</body>
</html>



<?php
/*




whats out:

SELECT * 
FROM gamesNES g
JOIN whatsOut w ON w.game_id = g.id
WHERE w.user_id = '4'



*/




?>