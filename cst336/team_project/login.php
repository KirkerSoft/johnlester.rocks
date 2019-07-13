<?php 
session_start();
$error = null;

if (isset($_POST['username'])){
  require '../db_connection.php';

  $sql = "SELECT *
          FROM library_admin
          WHERE username = :username
          AND password = :password";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute(array(":username" => $_POST['username'], 
                         ":password" => hash("sha1", $_POST['password'])));
  $record = $stmt -> fetch();

  if (empty($record)) {
    $error = "Incorrect username or password!";
  } else {
    // Add login attempt for user in the database.
    $sql = "INSERT INTO library_admin_login_attempts (library_admin_id, login_date)
            VALUES (:library_admin_id, :login_date)";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute(array(":library_admin_id" => $record['id'],
                           ":login_date" => date('Y-m-d H:i:s')));

    // Store user information in the session.
    $_SESSION['user_id'] = $record['id'];
    $_SESSION['username'] = $record['username'];
    $_SESSION['name'] = $record['firstname'] . " " . $record['lastname'];
    header("Location: index.php");
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="author" content="Team 6 - Bitsoft">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <!-- Support John's goofy directory structure -->
  <!-- <link rel="shortcut icon" href="../media/assign4-favicon.ico">
  <style>
    body {
      background-image: url("../media/background-bookcase.jpg");
    } 
  </style> -->

  <!-- Support Brittany's directory structure -->
  <link rel="shortcut icon" href="favicon.ico">
  <style>
    body {
      background-image: url("background-library.jpg");
    }
  </style> -->

  <!-- Support Ashley's directory structure (Ashley -> Update as needed) -->
  <!-- <link rel="shortcut icon" href="favicon.ico">
  <style>
    body {
      background-image: url("background-library.jpg");
    }
  </style> -->

  <style>
    h1, p {
      text-align: center;
    }
    
    form {
      max-width: 200px;
      margin: auto;
    }
    
    label {
      display: block;
      text-align: right;
    }

    li {
      list-style: none;
    }
    
    p.error {
      color: red;
    }
    
    .submit {
      padding-top: 20px;
      text-align: center;
    }
    
    .content {
      max-width: 900px;
      margin: auto;
      background-color: white;
    }
  </style>
</head>

<body>
  <div class="content">
    <h1>Login</h1>
    <form method="post">
      <label>Username: <input type="text" name="username" /></label>
      <label>Password: <input type="password" name="password" /></label>
      <div class="submit"><input type="submit" value="Login" /></div>
    </form>
<?php
  if (!empty($error)) {
?>
    <p class="error"><?= $error ?></p>
<?php
  }
?>
    <p>Login information for "General User":</p>
    <ul>
      <li><p>Username: "admin"</p></li>
      <li><p>Password: "password"</p></li>
    </ul>
  </div>
</body>
</html>
