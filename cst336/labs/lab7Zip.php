<?php

require '../db_connection.php';

         $sql = "SELECT * FROM zip_code " .
                " WHERE zip = " . $_GET['zip_code'];
         $stmt = $dbConn->query($sql);
         $results = $stmt->fetchAll();
         
         echo "{";
         foreach ($results as $record){
             echo "\"city\":" . "\"" . $record['city'] . "\",";
             echo "\"state\":" . "\"" . $record['state'] . "\"," ;
             //echo "\"county\":" . "\"" . $record['county'] . "\"," ;
             //echo "\"area\":" . "\"" . $record['areaCode'] . "\"," ;
             echo "\"latitude\":" . "\"" . $record['latitude'] . "\"," ;
             echo "\"longitude\":" . "\"" . $record['longitude'] . "\"" ;

              ;
         }
         echo "}";

?>