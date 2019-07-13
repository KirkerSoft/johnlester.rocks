<?php
require '../db_connection.php';

function getStadiums(){
    global $dbConn;
    
    $sql = "SELECT stadiumId, stadiumName
            FROM nfl_stadium
            ORDER BY stadiumName";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
}

function getTeamNames(){
    global $dbConn;
    
    $sql = "SELECT teamId, teamName
            FROM nfl_team
            ORDER BY teamName";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
}

if (isset($_POST['delete'])) { //checks whether the delete button was clicked
   $sql = "DELETE FROM nfl_stadium
              WHERE stadiumId = :stadiumId";
   $stmt = $dbConn -> prepare($sql);
  // $stmt -> execute( array(":stadiumId"=> $_POST['stadiumId']));
   echo "Stadium Deleted! <br /><br />";      
 }
 
 
 if (isset ($_POST['addMatch'])) { //checks whether the "addMatch" button was clicked
     
     $sql = "INSERT INTO nfl_match
             (team1_id, team2_id, date)
             VALUES
             (:team1_id, :team2_id, :date)";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute ( array (":team1_id" => $_POST['team1'],
                              ":team2_id" => $_POST['team2'],
                              ":date" => $_POST['date']));    
    
    $matchId = $dbConn->lastInsertId();
    
    $sql = "INSERT INTO nfl_recap
            (matchId, recap)
            VALUES
            (:matchId, :recap)";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute ( array (":matchId" => $matchId,
                             ":recap"   => $_POST['recap']));
    
    echo "Record was added!";    
        
     
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>John Lester Lab 4b</title>
  <meta name="description" content="">
  <meta name="author" content="lara4594">
  <meta name="viewport" content="width=device-width; initial-scale=1.0">
  <link rel="shortcut icon" href="../favicon.ico">
  
  <script>
    
    function confirmDelete(stadiumName) {

      var remove = confirm("Are you sure you want to delete " + stadiumName + "?");

      if (!remove){   // remove == false
          event.preventDefault();
      }        
    }
      
  </script>

  <style>
      form {
          display: inline;
      }
  </style>
</head>

<body>
  <div>
      
      <h3> NFL Matches </h3>
      
      Select Team 1:
      <form method="post">
          
          <select name="team1">
              
              <?php
                
                $teamNames = getTeamNames();
                                          
              foreach ($teamNames as $team) {
                   echo "<option value='" . $team['teamId'] . "' >" . $team['teamName'] . "</option>";
              } 
              
              ?>
              
          </select>  <br />
          
          Select Team 2:
          <select name="team2">
              <?php
              foreach ($teamNames as $team) {
                   echo "<option value='" . $team['teamId'] . "' >" . $team['teamName'] . "</option>";
              }               
              ?>
          </select> <br />
        
        Date:    <input type="date" name="date"><br /><br />
        
        <textarea name="recap" rows="15" cols="60" placeholder="Enter Match Recap"></textarea><br />
        

          <input type="submit" name="addMatch">
          
      </form>
      

    <h3> NFL Stadiums </h3>
    
    <?php
    
    $stadiumList = getStadiums();
    
    foreach ($stadiumList as $stadium) { ?>
        
        <?=$stadium['stadiumName']?>
        <form action="lab4UpdateStadium.php" method="post">
            <input type="hidden" name="stadiumId" value="<?=$stadium['stadiumId']?>">
            <input type="submit" name="update" value="Update">
        </form>
        <form method="post" onsubmit="confirmDelete('<?=$stadium['stadiumName']?>')" >
            <input type="hidden" name="stadiumId" value="<?=$stadium['stadiumId']?>">            
            <input type="submit" name="delete" value="Delete">
        </form>
        <br />
   <?php
    } //end foreach
    ?>

  </div>
</body>
</html>

<?php
$dbConn = null; //closes the database connection.
?>
