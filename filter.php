<?php
session_start();
include_once('dbinfo.php');


//$originalDate = "10/31/1985";
//$newDate = date("Y", strtotime($originalDate));


//UPDATE `gamesNES` SET releaseDate=? WHERE id=?

/*
$sql = "SELECT * FROM `gamesNES`";
$result = $db->prepare($sql);
$result->execute();


foreach ($result as $row) {
	# code...
	$originalDate = $row['releaseDate'];
	$newDate = date("Y", strtotime($originalDate));
	$myID = $row['id'];

	$sql2 = "UPDATE `gamesNES` SET `releaseDate` = ? WHERE `id` = ?";
	$result2 = $db->prepare($sql2);
	$result2->execute(array("$newDate", "$myID"));
}

<option value="volvo">All</option>

Years:
<select>
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

<select>
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

<select>
	<option value="All">All</option>
	<option value="InStock">In Stock</option>
	<option value="OutOfStock">Out of Stock</option>
</select>



SELECT DISTINCT `year` FROM `gamesNES`

*/

?>

<table>
	<tr>
		<td>Search:</td>
		<td>Year:</td>
		<td>Genre:</td>
		<td>Stock:</td>
		<td>&nbsp;</td>
	<tr>
		<td><input type="text" name="fname"></td>
		<td>
			<select>
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
			<select>
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
			<select>
				<option value="All">All</option>
				<option value="InStock">In Stock</option>
				<option value="OutOfStock">Out of Stock</option>
			</select>
		</td>
		<td>
			<button>Submit</button>
		</td>
	</tr>

</table>


