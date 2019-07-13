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

function getStadium($stadiumId){
global $dbConn;

$sql = "SELECT * 
FROM nfl_stadium
WHERE stadiumId = :stadiumId";
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":stadiumId"=>$stadiumId));
return $stmt->fetch(); 
}

if (isset($_POST['save'])) { //checks whether we're coming from "save data" form

$sql = "UPDATE nfl_stadium
SET stadiumName = :stadiumName,
street = :street,
city = :city
WHERE stadiumId = :stadiumId";
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":stadiumName"=>$_POST['stadiumName'],
":street"=>$_POST['street'],
":city"=>$_POST['city'],
":stadiumId"=>$_POST['stadiumId']
)); 

echo "RECORD UPDATED!! <br> <br>"; 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>John Lester Lab 5updateStadium</title>
<meta name="description" content="">
<meta name="author" content="lara4594">

<meta name="viewport" content="width=device-width; initial-scale=1.0">

<link rel="shortcut icon" href="../../favicon.ico">
</head>

<body>
<div>

<?php
if (isset($_POST['stadiumId'])) {
$stadiumInfo = getStadium($_POST['stadiumId']); ?>

<form method="post">
Stadium Name: <input type="text" name="stadiumName" value="<?php echo $stadiumInfo['stadiumName']; ?>" /><br />
Street: <input type="text" name="street" value="<?php echo $stadiumInfo['street']; ?>" /><br />
City: <input type="text" name="city" value="<?php echo $stadiumInfo['city']; ?>" /><br />
State: <input type="text" name="state" value="<?php echo $stadiumInfo['state']; ?>" /><br />
Zip: <input type="text" name="zip" value="<?php echo $stadiumInfo['zip']; ?>" /><br />
<input type="hidden" name="stadiumId" value="<?php echo $stadiumInfo['stadiumId']; ?>">
<input type="submit" name="save" value="Save"> 
</form>

<?php
 }
?>
<br /><br />
<a href="lab5.php"> Go back to main page </a>

</div>
</body>
</html>