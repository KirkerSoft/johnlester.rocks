<!--
  Last Updated: BM 31-Jan @ 1812
-->

<?php
/*
  Open database connection and import the new database 
  connection as $dbConn.
*/
require '../db_connection.php';

session_start();

/*
  Redirect user to log in page if they aren't already logged in.
*/
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
}

/*
  Get list of all books for the database. Books are sorted by
  ID by default and can optionally be sorted by title, author,
  or price by defining the query parameter "sort" as "title",
  "author", or "price".
*/
function getBooks() {
  global $dbConn;
  $sort = isset($_GET['sort']) ? $_GET['sort'] : null;
  $sql = "SELECT books.id, 
            books.title, 
            books.synopsis, 
            books.price,  
            books.isbn,   
            books.release_date, 
            authors.name AS author, 
            publishers.name AS publisher
          FROM books 
          LEFT JOIN authors 
          ON books.author_id = authors.id 
          LEFT JOIN publishers 
          ON books.publisher_id = publishers.id 
          ORDER BY ";
  switch ($sort) {
    case "title":
      $sql .= "books.title;";
      break;
    case "author":
      $sql .= "authors.name;";
      break;
    case "price":
      $sql .= "books.price;";
      break;
    default:
      $sql .= "books.id;";
      break;
  }
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  return $stmt->fetchAll();
}

?>

<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="author" content="Team 6 - Bitsoft">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book Collection</title>

  <!-- Support John's goofy directory structure -->
  <link rel="stylesheet" type="text/css" href="assign4-library.css">
  <link rel="shortcut icon" href="../media/assign4-favicon.ico">
  <style>
    body {
      background-image: url("../media/background-bookcase.jpg");
    } 
  </style>

  <!-- Support Brittany's directory structure -->
  <!-- <link rel="stylesheet" type="text/css" href="library.css">
  <link rel="shortcut icon" href="favicon.ico">
  <style>
    body {
      background-image: url("background-library.jpg");
    }
  </style> -->

  <!-- Support Ashley's directory structure (Ashley -> Update as needed) -->
  <!-- <link rel="stylesheet" type="text/css" href="library.css">
  <link rel="shortcut icon" href="favicon.ico">
  <style>
    body {
      background-image: url("background-library.jpg");
    }
  </style> -->
</head>

<body>
  <div class="nav_bar">
    <a href="accountSettings.php">Account Settings</a>
    <a href="logout.php">Logout</a>
  </div>
  <div class="content">
    <h1>BITsoft Book Collection</h1>
    <table class="book_collection">
      <tr>
        <th class="header_id" onClick="reloadAndSort('id')">
          <h4>ID</h4><a class="down_arrow" href="#">&#9660;</a>
        </th>
        <th class="header_title" onClick="reloadAndSort('title')">
          <h4>Title</h4><a class="down_arrow" href="#">&#9660;</a>
        </th>
        <th class="header_author" onClick="reloadAndSort('author')">
          <h4>Author</h4><a class="down_arrow" href="#">&#9660;</a>
        </th>
        <th>
          <h4>Synopsis</h4>
        </th>
        <th class="header_price" onClick="reloadAndSort('price')">
          <h4>Price</h4><a class="down_arrow" href="#">&#9660;</a>
        </th>
        <th></th>
      </tr>

      <?php
        // Populate table with books that exist in the database.
        $bookList = getBooks();
        foreach ($bookList as $book) {
      ?>
        <tr>
          <td class="book_id"><?= $book['id'] ?></td>
          <td class="book_title">
            <a href="#" onclick='showBook(<?= json_encode($book, JSON_HEX_APOS) ?>)'><?= $book['title'] ?></a>
          </td>
          <td class="book_author"><?= $book['author'] ?></td>
          <td class="book_synopsis ellipsis"><?= $book['synopsis'] ?></td>
          <td class="book_price">$<?= $book['price'] ?></td>
          <td><a href=".?edit="<?= $book['id']?>"">Edit</a></td>
        </tr>
      <?php
        } // close foreach
      ?>
    </table>

    <table class="add_book">
      <tr>
        <th colspan=2>New Book</th>
      </tr>
      <tr>
        <td class="col_header">Title:</td>
        <td><input type=text name=title size=25 maxlength=50></td>
      </tr>
      <tr>
        <td class="col_header">Author:</td>
        <td><!-- TODO: Add select input here with option of other to create new author. --></td>
      </tr>
      <tr>
        <td class="col_header">Synopsis:</td>
        <td><textarea cols=25 rows=5></textarea></td>
      </tr>
      <tr>
        <td class="col_header">Release Date:</td>
        <td><input type=text name=release_date size=25></td>
      </tr>
      <tr>
        <td class="col_header">ISBN:</td>
        <td><input type=text name=isbn size=15 maxlength=10></td>
      </tr>
      <tr>
        <td class="col_header">Publisher:</td>
        <td><!-- TODO: See author comment above. --></td>
      </tr>
      <tr>
        <td class="col_header">Price:</td>
        <td><input type=text name=price size=15 maxlength=10></td>
      </tr>
    </table>
  </div>

  <div id="popup">
    <div class="popup_header">
      <div class="popup_nav">
        <a href="#" onclick="hidePopup()">X</a>
      </div>
      <div class="popup_description">
        <h2>Book Details</h2>
      </div>
    </div>
    <table class="book_details">
      <tr>
        <td>Title:</td>
        <td id="popup_title"></td>
        <td rowspan=5>picOfCover</td>
      </tr>
      <tr>
        <td>ISBN:</td>
        <td id="popup_isbn"></td>
      </tr>
      <tr>
        <td>Author:</td>
        <td id="popup_author"></td>
      </tr>
      <tr>
        <td>Date:</td>
        <td id="popup_release_date"></td>
      </tr>
      <tr>
        <td>Pub:</td>
        <td id="popup_publisher"></td>
      </tr>
      <tr>
        <td>Synopsis:</td>
        <td colspan=2 id="popup_synopsis"></td>
      </tr>
    </table>
  </div>

  <div id="fade" class="black_overlay"></div>

  <script>
    function reloadAndSort(sortOpt) {
      javascript:location.href='?sort=' + sortOpt;
    };
    
    function showBook(book) {
      var releaseDate = new Date(Date.parse(book.release_date.replace('-','/','g')));
      var strReleaseDate = (releaseDate.getMonth() + 1) + '/' + releaseDate.getDate() 
        + '/' + releaseDate.getFullYear();
      document.getElementById('popup_title').innerHTML = book.title;
      document.getElementById('popup_isbn').innerHTML = book.isbn;
      document.getElementById('popup_author').innerHTML = book.author;
      document.getElementById('popup_release_date').innerHTML = strReleaseDate;
      document.getElementById('popup_publisher').innerHTML = book.publisher;
      document.getElementById('popup_synopsis').innerHTML = book.synopsis;
      showPopup();
    }
    
    function showPopup() {
      document.getElementById('popup').style.display='block';
      document.getElementById('fade').style.display='block';
    };
    
    function hidePopup() {
      document.getElementById('popup').style.display='none';
      document.getElementById('fade').style.display='none';
    };
  </script>
</body>
</html>
