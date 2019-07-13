<html>
<head>
<title>CST336 Assignment 2 by John Lester</title>
<link rel="shortcut icon" href="../favicon.ico" />
<style>
body { 
    background-image: url(../media/background-casino.jpg);
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-position: center;
    background-color: black;
}
td.top {
	text-align: left;
	background-color: khaki;
}
table.top {
	position: absolute;
	top: 0px;
	border: none;
	width: 1000;
	left: 50%;
	margin-left: -500px;
}
table.main {
    position: relative;
    top: 450px;
    border: none;
}
</style>
<?php
$die1 = rand(1, 6);
$die2 = rand(1, 6);
$rollTotal = $die1 + $die2;
if (isset($_POST['roll'])) { $rollNum = $_POST['roll'] + 1; }
else { $rollNum = 1; }
if (isset($_POST['point'])) { $point = $_POST['point']; }
else { $point = 0; }
if (isset($_POST['wins'])) { $wins = $_POST['wins']; }
else { $wins = 0; }
if (isset($_POST['losses'])) { $losses = $_POST['losses']; }
else { $losses = 0; }
?>
</head>
<body>
<center>
<table class="main">
	<tr>
<?php
echo "<td><img src='../media/dice-" . $die1 . ".png' border=0></td>";
echo "<td><img src='../media/dice-" . $die2 . ".png' border=0></td>";
?>
	</tr>
	<tr>
		<td colspan=2 align=center><form method=post>
<?php
if ((($rollNum == 1) && (($rollTotal == 2) || ($rollTotal == 3) || ($rollTotal == 12))) || ($rollNum >= 2) && ($rollTotal == 7)) {
	echo "<h2>You Lose</h2>";
	$losses++;
	echo "<input type=submit value='Play Again'><input type=hidden name=roll value=0><input type=hidden name=wins value=" . $wins . "><input type=hidden name=losses value=" . $losses . ">";
}
elseif (($rollNum == 1) && (($rollTotal == 7) || ($rollTotal == 11)) || ($rollTotal == $point)) {
	echo "<h2>You Win!</h2>";
	$wins++;
	echo "<input type=submit value='Play Again'><input type=hidden name=roll value=0><input type=hidden name=wins value=" . $wins . "><input type=hidden name=losses value=" . $losses . ">";
}
elseif ($rollNum == 1) {
	echo "<h2>Point is " . $rollTotal . "</h2>";
	echo "<input type=submit value='Roll Again'><input type=hidden name=roll value=" . $rollNum . "><input type=hidden name=point value=" . $rollTotal . "><input type=hidden name=wins value=" . $wins . "><input type=hidden name=losses value=" . $losses . ">";
}
else  {
	echo "<h2>Point is " . $point . "</h2>";
	echo "<input type=submit value='Roll Again'><input type=hidden name=roll value=" . $rollNum . "><input type=hidden name=point value=" . $point . "><input type=hidden name=wins value=" . $wins . "><input type=hidden name=losses value=" . $losses . ">";
}
?>
		</form></td>
	</tr>
</table>
<table class="top">
	<tr>
		<td class="top" width="250">
<?php
	echo "Roll: <br>";
	for ($i=1; $i<=$rollNum; $i++) { echo "*"; }
	echo "<br>";
	echo "Wins: <br>";
	for ($i=1; $i<=$wins; $i++) { echo "*"; }
	echo "<br>";
	echo "Losses: <br>";
	for ($i=1; $i<=$losses; $i++) { echo "*"; }
?>
		</td>
		<td class="top"><center><b><u>BASIC CRAPS</u></b></center>This game is played in rounds consisting of two phases: come out and point.<br>
Come Out – to start a round, the shooter makes a “come out” roll. If the come out roll is a 2, 3, or 12, then the round ends in a loss. If the come out roll is a 7 or 11, this results in a win. The shooter continues to make come out rolls until he rolls 4, 5, 6, 8, 9, or 10. This number becomes the point and in turn the point phase begins.<br>
Point – during this phase, if the shooter rolls a point number then it’s a win. If the shooter rolls a 7, it’s a loss and the round is over.</td>
	</tr>

</table>
</center>
</body>
</html>
