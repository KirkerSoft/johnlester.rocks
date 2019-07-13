<?php

if (isset($_POST['username']))
{
require '../db_connection.php';
$sql = "SELECT username
        FROM lab9_user
        WHERE username = :username";
        
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":username" => $_POST['username']));
$record = $stmt->fetch();

$output = array();

if (empty($record))
{
    //echo "{\"exists\":\"true\"}";
    $output["exists"] = "false";
    
}
else
    {
        //echo "Username already taken";
        $output["exists"] = "true";
    }
    
    echo json_encode($output);
}

?>