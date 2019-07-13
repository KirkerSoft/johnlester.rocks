<?php

require '../db_connection.php'; //calling the database connection file that I placed in current file's parent directory

$conference = $_GET['conference'];
$sql = "SELECT teamName 
FROM nfl_team
WHERE 1"; // putting in WHERE 1 leads to simpler logic for dynamic conditional clauses, meaning you can just keep adding AND to the end of the query and it won't break the syntax. 

$namedParameters = array(); //array to prevent SQL Injection

//if (!empty($conference)) {
if (isset($_GET['conference'])) { //only if user has selected a Conference
//$sql .= " AND conference = '$conference'"; //appending this condition directly allows SQL injection -- NOT GOOD!! 
$sql .= " AND conference = :conf "; //using Named Parameters to avoid SQL Injection 
$namedParameters[":conf"] = $conference;
}


if (isset($_GET['division'])) { //only if user has checked a Division
$division = $_GET['division'];
$sql .= " AND division = :div";
$namedParameters[":div"] = $division; 

} 

$sql .= " ORDER BY teamName";

//You could remove the comment sign from next line to see the final SQL statement in the browser.

//echo $sql;

$stmt = $dbConn -> prepare($sql);
$stmt -> execute($namedParameters);
$records = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>NFL Teams Report</title>
<meta name="description" content="">
<meta name="author" content="lara4594">

<meta name="viewport" content="width=device-width; initial-scale=1.0">

<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
<link rel="shortcut icon" href="/favicon.ico">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
</head>


<body>
<div>

<h2> NFL Team Reports</h2>

Select Conference: 

<form>
<select name="conference">
<option value=""> All </option>
<option value="A"> American </option>
<option value="N"> National </option> 
</select>

<br /><br />

Select Division: <br />

<input type="radio" name="division" value="E"> East 
<input type="radio" name="division" value="W"> West
<input type="radio" name="division" value="N"> North 
<input type="radio" name="division" value="S"> South
<br /><br />
<input type="submit" value="Get Teams!!">
</form>

<?php

if (isset($records)) {

foreach($records as $record) {
echo $record['teamName'] . "<br />";
} 

}

?> 


</div>
</body>
</html>