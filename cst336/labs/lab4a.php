<?php

require '../db_connection.php';

/**** Getting all team names and their stadium Id ****/
$sql = "SELECT teamName, stadiumId 
        FROM nfl_team 
        ORDER BY teamName ASC";

$stmt = $dbConn -> prepare($sql); //prepares a statement for execution and returns a statement object
$stmt -> execute(); //execute the prepared statement
$teamNames = $stmt->fetchAll(); //store the obtained data into an array variable

/**** Getting stadiumInfo based on stadium Id ****/
if (isset ($_GET['stadiumId'])) {
   $stadiumId = $_GET['stadiumId'];
   $sql = "SELECT * 
           FROM nfl_stadium 
           WHERE stadiumId = :stadiumId ";
        
   $stmt = $dbConn -> prepare($sql);
   $stmt -> execute( array (':stadiumId' => $stadiumId));
   $stadiumInfo = $stmt->fetch();
}

/******************* STEP 2 ***************************/
/****  Top 5 states with the largest combined number of seats in NFL stadiums ***/
   function largestCombinedCapacity() {
       global $dbConn, $stmt;  //it uses the variables declared previously 
    $sql = "SELECT state, SUM( capacity ) combinedCapacity
            FROM nfl_stadium 
            GROUP BY state
            ORDER BY combinedCapacity DESC
            LIMIT 5";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
   }

   
/******************* STEP 5 ***************************/
/****  NFL teams and their home stadiums ***/
   function teamsAndStadiums() {
       global $dbConn, $stmt;  //it uses the variables declared previously 
    //NOTE: field names MUST match the ones in database, they are case sensitive!
    $sql = "SELECT teamName, stadiumName, state
            FROM nfl_team t
            JOIN nfl_stadium s ON t.stadiumId = s.stadiumId
            ORDER BY teamname";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
   }
      

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title> John Lester - Lab 4a</title>
</head>

<body>
  <div>

   <h2> NFL Teams & Stadiums Queries </h2>

     <form>
         <select name="stadiumId">
           <option value="-1"> - Select Team -</option>
           <?php
                foreach ($teamNames as $team) {
                    echo "<option  value=" . $team['stadiumId'] . ">" . $team['teamName'] . "</option>";
                
                //foreach provides an easy way to iterate over arrays. foreach works only on arrays and objects.

                //In the above statement, it loops over $teamNames array. On each iteration, the value of the current element is assigned to $team and the internal array pointer is advanced by one (so on the next iteration, you'll be looking at the next element).
                
                }         
             
           ?>   
         </select>
         <input type="submit" value="Get Stadium Info!" />
     </form>

    <?php
    
        if (isset($stadiumInfo) && !empty($stadiumInfo)) {
            echo $stadiumInfo['stadiumName'] . "<br />";
            echo $stadiumInfo['street'] . "<br />";
            echo $stadiumInfo['city'] . ", " . $stadiumInfo['state'] . " " . $stadiumInfo['zip']  . " <br />";
        }
    
    ?>

    <!-------------- STEP 1 ----------------->
    <br />
    <strong>Top 5 states with the largest combined number of seats in NFL stadiums</strong>
    <br />

    <!-------------- STEP 3 ----------------->
    <?php
        $records = largestCombinedCapacity();
        foreach ($records as $record) {
          echo $record['state'] . " - " . number_format($record['combinedCapacity'])  . "<br />";
        } 
    ?>
    

    <!-------------- STEP 4 ----------------->
    <br />
    <strong>List of all teams and their home stadium</strong>
    <br />

    <!-------------- STEP 6 ----------------->
    <?php
        $records = teamsAndStadiums();
        foreach ($records as $record) {
          echo $record['teamName'] . " - " . $record['stadiumName'] . "  - " . $record['state'] . "<br />";
        } 
    ?>
 
  </div>
</body>
</html>

   <?php
    
    $dbConn = null; //closes the database connection.
    
    ?>
