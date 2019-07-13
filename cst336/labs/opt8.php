<?php
if (isset($_POST['uploadForm'])) {
    if ($_FILES["fileName"]["error"] > 0) {
		echo "Error: " . $_FILES["fileName"]["error"] . "<br>";
    } else {
        echo "Upload: " . $_FILES["fileName"]["name"] . "<br>";
        echo "Type: " . $_FILES["fileName"]["type"] . "<br>";
        echo "Size: " . ($_FILES["fileName"]["size"] / 1024) . " KB<br>";
        echo "Stored in: " . $_FILES["fileName"]["tmp_name"];
        require '../db_connection.php';
        $binaryData = file_get_contents($_FILES["fileName"]["tmp_name"]);
        $sql = "INSERT INTO up_files (fileName, fileType, fileData ) " .
               "  VALUES (:fileName, :fileType, :fileData) ";
        $stm=$dbConn->prepare($sql);
        $stm->execute(array (":fileName"=>$_FILES["fileName"]["name"],
                             ":fileType"=>$_FILES["fileName"]["type"],
                             ":fileData"=>$binaryData));
        echo "<br />File saved into database <br /><br />";
    }
} //endIf form submission
?>
<form method="POST" enctype="multipart/form-data"> 
Select file: <input type="file" name="fileName" /> <br />
<input type="submit"  name="uploadForm" value="Upload File" /> 
</form>
