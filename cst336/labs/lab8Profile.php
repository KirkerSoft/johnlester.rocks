<?php
/* STEP 2 *******************/
session_start();

 if (!isset ( $_SESSION['username']))  {
     if (isset ($_POST['loginForm'])) {
        require '../db_connection.php'; 
        $sql = "SELECT * 
                FROM lab9_user
                WHERE username = :username
                AND password = :password";
        $stmt = $dbConn -> prepare($sql);
        $stmt -> execute( array(":username" => $_POST['username'],
                                ":password" => sha1($_POST['password'])));
        $record = $stmt->fetch();
        if (!empty($record)) {
            $_SESSION['username'] = $record['username'];
            $_SESSION['profilePic'] = $record['profilePic'];
            echo "<h2> Welcome  " . $record['realName'] . "!</h2>";
            $location = "/var/www/johnlester.rocks/cst336/labs/lab8Images/" . $record['username'];
			$uold = umask(0);
			if (!file_exists($location)) {
				mkdir($location,0777,true);
				umask($uold);
			   //mkdir("/var/www/johnlester.rocks/cst336/labs/lab8Images/" . $record['username'], 0777, true);
            }
        } else {
            $error = " Wrong username / password";
            header("Location: lab8.html");
        }
     }  
 }
 
 
/* STEP 3 *******************/
  if (isset($_FILES['fileName'])) {
     $_SESSION['profilePic'] = $_FILES['fileName']['name'];
     move_uploaded_file($_FILES['fileName']['tmp_name'], "lab8Images/" . $_SESSION['username'] . "/" . $_FILES['fileName']['name'] );
    //Syntax move_uploaded_file ( string $filename , string $destination )
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Profile</title>
  <link rel="shortcut icon" href="/favicon.ico">
</head>

<body id="wrapper">
  <div>
<?php
   if (empty($_SESSION['profilePic'])) {
           echo "<img class='profilepic' src='lab8Default.png'><br/>";
   } else {
            echo "<h2> Welcome  " . $_SESSION['realName'] . "!</h2>";           
           echo "<img class='profilepic' src='./lab8Images/" . $_SESSION['username'] . "/" . $_SESSION['profilePic'] . "'><br/>";
   }
?>
  
  </div>
  <!--- Step 1 ****************-->
  <div align="left" class="upload">
  <form method="post" enctype="multipart/form-data">
      <br/>
      
      Select File to update profile picture:
      <br />
      <input type="file" name="fileName" />
      <br/>
      <input type="submit" name="loginForm">
      
  </form>
  
   </div>
</body>
</html>