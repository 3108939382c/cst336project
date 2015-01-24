<?php
session_start();
include_once('dbinfo.php');

$sql = "SELECT * FROM `gamesNES` LIMIT 30";
$result = $db->prepare($sql);
$result->execute();
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

	<link rel="import" href="http://www.polymer-project.org/components/polymer/polymer.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-scroll-header-panel/core-scroll-header-panel.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-list/core-list.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-header-panel/core-header-panel.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-drawer-panel/core-drawer-panel.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-toolbar/core-toolbar.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-menu/core-menu.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-item/paper-item.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-icon-button/core-icon-button.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-icons/core-icons.html">
	<link rel="import" href="http://www.polymer-project.org/components/core-icons/social-icons.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-shadow/paper-shadow.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-button/paper-button.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-input/paper-input.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-dialog/paper-dialog.html">
	<link rel="import" href="http://www.polymer-project.org/components/paper-dialog/paper-action-dialog.html">

	<link rel="stylesheet" href="css/main.css">

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
				if (isset($_SESSION['username'])) {
					# code... for logged in
					echo '
						<core-toolbar id="navheader">
							<span>Hello '.$_SESSION[username].'!</span>
						</core-toolbar>
						<core-menu>

							<a href="dologout.php"><button class="btn btn-primary">Logout</button></a>


						</core-menu>


					';
				} else {
					echo '
					<core-menu>
						<br>
						<h4>Login:</h4>

						<form action="dologin.php">
							<paper-input-decorator floatingLabel label="Username">
								<input type="text">
							</paper-input-decorator>

							<paper-input-decorator floatingLabel label="Password" isInvalid="true">
								<input type="password">
							</paper-input-decorator>
							<button class="btn btn-primary">Login</button>
						</form>

						<hr>
						<h4>Register:</h4>

						<form action="doregister.php" method="post">
							<paper-input-decorator floatingLabel label="Username" id="registerUsernameDecorator">
								<input type="text" id="registerUsername" name="registerUsername">
							</paper-input-decorator>

							<paper-input-decorator floatingLabel label="Password" id="password1Decorator">
								<input type="password" id="password1" name="registerPassword">
							</paper-input-decorator>

							<paper-input-decorator floatingLabel label="Retype Password" id="password2Decorator">
								<input type="password" id="password2">
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

				<paper-shadow z="2" class="main" style="padding: 10px;">
					<div align="center">
						<h2>something</h2>
						<br>
					</div>
				</paper-shadow>

				<br>

				<paper-shadow z="2" class="main">
					

						<div>

							<table class="table table-striped" width="100%">
								<tr>
									<th>Image</th>
									<th>Game Title</th>
									<th>Release Date</th>
									<th>Genre</th>
								</tr>

							<?php

							foreach ($result as $row) {
								echo '<tr>';

								//png
								$pngImg = "img/thumb/" . $row[id] . ".png";

								//jpg
								$jpgImg = "img/thumb/" . $row[id] . ".jpg";

								if (file_exists($pngImg)) {
									# code...
									echo '<td><img src="'.$pngImg.'" data-toggle="modal" data-target="#modal'.$row[id].'"></td>';
								} elseif (file_exists($jpgImg)) {
									# code...
									echo '<td><img src="'.$jpgImg.'" data-toggle="modal" data-target="#modal'.$row[id].'"></td>';
								} else {
									echo '<td>&nbsp;</td>';
								}


								echo '<td class="text-left"><span data-toggle="modal" data-target="#modal'.$row[id].'">'.$row[gameTitle] . '</span>';

								//modal
								echo '
								<div class="modal fade" id="modal'.$row[id].'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
												<h4 class="modal-title" id="myModalLabel">'.$row[gameTitle].'</h4>
											</div>
											<div class="modal-body">
												'.$row[description].'
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

								echo '<td>'.$row[releaseDate].'</td>';
								echo '<td>'.$row[genre].'</td>';
						
								echo '</tr>';
								
							}


							?>

							</table>
							


							<br>
							<div id="output"></div>
						</div>

					
				</paper-shadow>

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

		$.ajax({
			url: "doajax.php",
			type: "POST",
			data: {
				doFunction: "registerUsername",
				checkUsername: x
			},
			success: function(result) {
				//on sucess
				if (result == 'okay') {
					//if result == okay, username is available to register
					$("#output").html(result);
					setUsernameOK(true);
					
					$("#registerUsernameDecorator").attr("isInvalid", "false");


				} else {
					//else, username is already taken
					$("#output").html(result);
					setUsernameOK(false);
					$("#registerUsernameDecorator").attr("isInvalid", "true");
					$("#registerUsernameDecorator").attr("error", "Username is already taken.");
					

				}
				checkButton();
				
			}
		});
	});

	//check if passwords match
	$("#password1").keyup(function() {
		var p1 = $("#password1").val();
		var p2 = $("#password2").val();

		if ( (p1 == p2) && (p1 != '') ) {
			//passwords are the same, enable button
			setPassworkOK(true);

			$("#password1Decorator").attr("isInvalid", "false");
			$("#password2Decorator").attr("isInvalid", "false");

		} else {
			//passwords are different, show error message, disable register button
			setPassworkOK(false);

			$("#password1Decorator").attr("isInvalid", "true");
			$("#password2Decorator").attr("isInvalid", "true");
			$("#password1Decorator").attr("error", "Passwords don't match.");
			$("#password2Decorator").attr("error", "Passwords don't match.");
			
		}
		checkButton();
	});

	$("#password2").keyup(function() {
		var p1 = $("#password1").val();
		var p2 = $("#password2").val();

		if ( (p1 == p2) && (p1 != '') ) {
			//passwords are the same, enable button
			setPassworkOK(true);

			$("#password1Decorator").attr("isInvalid", "false");
			$("#password2Decorator").attr("isInvalid", "false");

		} else {
			//passwords are different, show error message, disable register button
			setPassworkOK(false);

			$("#password1Decorator").attr("isInvalid", "true");
			$("#password2Decorator").attr("isInvalid", "true");
			$("#password1Decorator").attr("error", "Passwords don't match.");
			$("#password2Decorator").attr("error", "Passwords don't match.");
			
		}
		checkButton();
	});
	
});


</script>

</body>
</html>




<?php
/*


echo '<td class="text-left" onclick="document.querySelector(\'#dialog'.$row[id].'\').toggle()">'.$row[gameTitle];

								echo '

									<paper-action-dialog backdrop autoCloseDisabled layered="false" id="dialog'.$row[id].'">
										<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>

										<paper-button affirmative autofocus>Tap me to close</paper-button>
									</paper-action-dialog>


								';


*/




?>