<?php

require '../db_connection.php';
    
         $sql = "SELECT DISTINCT(county) FROM zip_code " .
                " WHERE state = :state AND county !='' ORDER BY county";
         $stmt = $dbConn->prepare($sql);
         $stmt->execute( array (":state"=>$_GET['state']));
         $results = $stmt->fetchAll();
         
         echo "{ \"counties\": [ ";
         foreach ($results as $record){
             echo "{\"county\":" . "\"" . $record['county'] . "\"},";
         }
         echo "{\"county\":" . "\"\"}";
          echo "] }";

?>
