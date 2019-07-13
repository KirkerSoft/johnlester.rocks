<?php
require '../db_connection.php';

session_start();
$error = null;
$new_password = false;

// Redirect user to log in page if they aren't already logged in.
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
}

// Update username if requested.
if (isset($_POST['updatePassword']) && isset($_POST['newPassword']) 
    && strlen($_POST['newPassword']) > 0){
  $sql = "UPDATE library_admin
          SET password = :password
          WHERE id = :id";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute(array(":password" => hash('sha1', $_POST['newPassword']),
                         ":id" => $_SESSION['user_id']));
  $new_password = true;
}

// Get login history for user.
$sql = "SELECT * FROM library_admin_login_attempts
        WHERE library_admin_id = :user_id
        ORDER BY login_date DESC";
$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":user_id" => $_SESSION['user_id']));
$login_attempts = $stmt -> fetchAll();

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
  </style>

  <!-- Support Ashley's directory structure (Ashley -> Update as needed) -->
  <!-- <link rel="shortcut icon" href="favicon.ico">
  <style>
    body {
      background-image: url("background-library.jpg");
    }
  </style> -->

  <style>
    body {
      margin: 0;
    }

    h1, p {
      text-align: center;
      color: #E6E8E6;
    }

    h5 {
      color: #FFE0B5;
      margin: 0;
    }

    ul {
      padding: 0;
    }

    ul li {
      list-style: none;
      text-align: center;
    }
    
    p.error {
      color: red;
    }

    .nav_bar {
      background-color: #1E2D24;
      text-align: right;
      padding: 10px;
    }

    .nav_bar a {
      color: #E6E8E6;
      padding: 0 10px;
    }

    .nav_bar a:hover {
      color: #FFE0B5;
    }
    
    .content {
      max-width: 900px;
      margin: 40px auto 0 auto;
      background-color: #334139;
      padding: 20px;
      border: solid 1px #1E2D24;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <div class="nav_bar">
    <a href="index.php">Book Collection</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="content">
    <h1>Update Password</h1>
    <form method="post">
      <input type="password" name="newPassword">
      <input type="submit" name="updatePassword">
    </form>
    <?php
      if ($new_password) {
    ?>
      <p>Password has been updated!</p>
    <?php
      }
    ?>
    <h1>Login History</h1>
    <ul>
    <?php
      foreach ($login_attempts as $login_attempt) {
    ?>
      <li><h5><?= $login_attempt['login_date'] ?></h5></li>
    <?php
      }
    ?>
    </ul>
  </div>
</body>
</html>