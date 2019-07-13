<?php
session_start();

if(!isset($_SESSION['username'])){
header("Location: lab5-login.php");
}

echo "Welcome " . $_SESSION['name'];
?>

<form method="post" action="lab5-logout.php" onsubmit="confirmLogout()">
<input type="submit" value="Logout" />
</form>

<?php

require '../db_connection.php';

function getStadiums() {
global $dbConn;

$sql = "SELECT stadiumId, stadiumName
FROM nfl_stadium
ORDER BY stadiumName";

$stmt = $dbConn -> prepare($sql);
$stmt -> execute();
return $stmt -> fetchAll();
}

function getTeamNames() {
global $dbConn;

$sql = "SELECT teamId, teamName
FROM nfl_team
ORDER BY teamName";

$stmt = $dbConn -> prepare($sql);
$stmt -> execute();
return $stmt -> fetchAll();
}

if (isset($_POST['delete'])) {//checks whether the "delete" button was clicked

$sql = "DELETE FROM nfl_stadium
WHERE stadiumId = :stadiumId";
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":stadiumId"=>$_POST['stadiumId']));
echo "Stadium has been deleted!";
}

if (isset($_POST['add'])) {//checks whether the "Add" button was clicked

$sql = "INSERT INTO nfl_match
(team1_id, team2_id, date, time, stadiumId, team1_score, team2_score)
VALUES
(:team1_id, :team2_id, :date, :time, :stadiumId, :team1_score, :team2_score)";
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":team1_id" => $_POST['team1'], ":team2_id" => $_POST['team2'], ":date" => $_POST['date'], ":time" => $_POST['time'], ":stadiumId" => $_POST['stadiumId'], ":team1_score" => $_POST['team1_score'], ":team2_score" => $_POST['team2_score']));
$matchId = $dbConn -> lastInsertId();

$sql = "INSERT INTO nfl_matchRecap
(matchId, recap)
VALUES
(:matchId, :recap)";
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":matchId" => $matchId, ":recap" => $_POST['recap']));

echo "RECORD ADDED!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>Lab 5 John Lester</title>
<meta name="description" content="">
<meta name="author" content="su5196">

<meta name="viewport" content="width=device-width; initial-scale=1.0">

<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
<link rel="shortcut icon" href="/favicon.ico">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<script>
function confirmDelete(stadiumName) {
var remove = confirm("Do you really want to delete " + stadiumName + "?");
if (!remove) {
event.preventDefault();
}
}

function confirmLogout(event) {
var logout = confirm("Do you really want to log out?");
if (!logout) {
event.preventDefault();
}
}
</script>

<link rel="stylesheet" type="text/css" href="../../css/style.css" />

<style>
form {
display: inline;
}

</style>

</head>

<body>
<div>

<h3> NFL Matches </h3>

<form method='post'>

Select Team 1:
<?php $teamNames = getTeamNames(); ?>

<select name="team1">
<?php
foreach ($teamNames as $team) {
echo "<option value='" . $team['teamId'] . "'>" . $team['teamName'] . "</option>";
}
?>
</select>

<br />
<br />

Select Team 2:
<select name="team2">
<?php
foreach ($teamNames as $team) {
echo "<option value='" . $team['teamId'] . "'>" . $team['teamName'] . "</option>";
}
?>
</select>
<br>
<br>
Date:
<input type="date" name="date" />
<br />
<br />

Time:
<input type="time" name="time" />
<br />
<br />
Stadium:
<select name="stadiumId">
<?php $stadiumList = getStadiums();
foreach ($stadiumList as $stadium) {
echo "<option value='" . $stadium['stadiumId'] . "'>" . $stadium['stadiumName'] . "</option>";
}
?>
</select>
<br />
<br />
Team 1 Score:
<input type="number" name="team1_score" />
<br />
<br />
Team 2 Score:
<input type="number" name="team2_score" />
<br />
<br />
<textarea name="recap" rows="15" cols="60" placeholder="Match Recap"></textarea> 
<br />
<br />
<input type="submit" name="add" value="Add match"/>

</form>

<h3> NFL Stadiums </h3>

<?php
foreach ($stadiumList as $stadium) { ?>
<?php echo $stadium['stadiumName']?>
<form action="lab5UpdateStadium.php" method="post">
<input type="hidden" name="stadiumId" value="<?php echo $stadium['stadiumId']?>" />
<input type="submit" name="update" value="Update" />
</form>
<form method="post" onsubmit="confirmDelete('<?php echo $stadium['stadiumName']?>')">
<input type="hidden" name="stadiumId" value="<?php echo $stadium['stadiumId'] ?>" />
<input type="submit" name="delete" value="Delete" />
</form>
<br />
<?php }//end foreach ?>

</div>
</body>
</html>
