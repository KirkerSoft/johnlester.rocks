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

/*
  Get the average cost of all of the books in the database.
*/
function getAvgBookCost() {
  global $dbConn;
  $sql = "SELECT AVG(price) 
          FROM books;";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  return $stmt -> fetchColumn();
}

/*
  Get the most recently released book. More than one book
  may be returned if there were multiple that were released 
  the same day that's the latest.
*/
function getLatestReleases() {
  global $dbConn;
  $sql = "SELECT * 
          FROM books 
          WHERE release_date = (
          SELECT MAX(release_date) 
          FROM books);";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  return $stmt -> fetchAll();
}

/*
  Get all categories.
*/
function getCategories() {
  global $dbConn;
  $sql = "SELECT * 
          FROM categories;";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  return $stmt -> fetchAll();
}

/*
  Get all authors.
*/
function getAuthors() {
  global $dbConn;
  $sql = "SELECT * 
          FROM authors;";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  return $stmt -> fetchAll();
}

/*
  Get the title from a book.
*/
function get_title($i) {
  return $i['title'];
}


/*
  Check for form submission for adding a book. If submitted,
  add book and book details requested.
*/
$book_added = false;
if (isset($_POST['add_book'])) {
  global $dbConn;
  // Insert publisher information.
  $sql = "INSERT INTO publishers (name, address, city, state, zip) 
          VALUES (:name, :address, :city, :state, :zip);";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute(array(":name" => $_POST['publisher_name'],
                         ":address" => $_POST['publisher_address'],
                         ":city" => $_POST['publisher_city'], 
                         ":state"=> $_POST['publisher_state'], 
                         ":zip" => $_POST['publisher_zip']));
  $publisher_id = $dbConn -> lastInsertId();
  // Insert author.
  $sql = "INSERT INTO authors (name) 
          VALUES (:name);";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute(array(":name" => $_POST['author']));
  $author_id = $dbConn -> lastInsertId();
  // Insert book.
  $sql = "INSERT INTO books (author_id, publisher_id, title, release_date, isbn, synopsis, price) 
          VALUES (:author_id, :publisher_id, :title, :release_date, :isbn, :synopsis, :price);";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute(array(":author_id" => $author_id, 
                         ":publisher_id" => $publisher_id, 
                         ":title" => $_POST['title'], 
                         ":release_date" => $_POST['release_date'], 
                         ":isbn" => $_POST['isbn'], 
                         ":synopsis" => $_POST['synopsis'], 
                         ":price" => $_POST['price']));
  $book_id = $dbConn -> lastInsertId();
  // Add book categories.
  foreach($_POST['categories'] as $category_id) {
    $sql = "INSERT INTO book_categories (book_id, category_id) 
            VALUES (:book_id, :category_id);";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute(array(":book_id" => $book_id,
                           ":category_id" => $category_id));
  }
  $book_added = true;
}

/*
  Check for form submission for filtering results. If submitted,
  filter results by category.
*/
$min_set = isset($_POST['filter_min_date']) && !empty($_POST['filter_min_date']);
$max_set = isset($_POST['filter_max_date']) && !empty($_POST['filter_max_date']);
$book_list = getBooks();
if (isset($_POST['filter_submit']) && !empty($_POST['filter'])) {
  $new_list = implode(", ", $_POST['filter']);
  $sql = "SELECT books.*,
            authors.name AS author, 
            publishers.name AS publisher
          FROM books
          LEFT JOIN authors ON authors.id = books.author_id
          LEFT JOIN publishers ON publishers.id = books.publisher_id
          INNER JOIN book_categories ON books.id = book_categories.book_id 
          INNER JOIN categories ON categories.id = book_categories.category_id
          WHERE categories.id IN (" . $new_list . ");";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  $book_list = $stmt -> fetchAll();
}

/*
  Check for form submission for filtering results. If submitted,
  filter results by author.
*/
elseif (isset($_POST['filter_author_submit']) && isset($_POST['filter_author'])) { 
  $sql = "SELECT books.*,
            authors.name AS author, 
            publishers.name AS publisher
          FROM books
          LEFT JOIN authors ON authors.id = books.author_id
          LEFT JOIN publishers ON publishers.id = books.publisher_id
          WHERE " . $_POST['filter_author'] . " = books.author_id;";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  $book_list = $stmt -> fetchAll();
}

/*
  Check for form submission for filtering results. If submitted,
  filter results by date.
*/
elseif (isset($_POST['filter_date_submit']) && ($min_set == true || $max_set == true)) {
  $condition = "";
  if ($min_set && $max_set) {
    $condition = "WHERE books.release_date > '" . $_POST['filter_min_date'] . "' AND books.release_date < '" . $_POST['filter_max_date'] . "'";
  } else if (!$min_set && $max_set) {
    $condition = "WHERE books.release_date < '" . $_POST['filter_max_date'] . "'";
  } else if ($min_set && !$max_set) {
    $condition = "WHERE books.release_date > '" . $_POST['filter_min_date'] . "'";
  }
  
  $sql = "SELECT books.*,
            authors.name AS author, 
            publishers.name AS publisher
          FROM books
          LEFT JOIN authors ON authors.id = books.author_id
          LEFT JOIN publishers ON publishers.id = books.publisher_id " . $condition . ";";
  $stmt = $dbConn -> prepare($sql);
  $stmt -> execute();
  $book_list = $stmt -> fetchAll();
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
  <link rel="stylesheet" type="text/css" href="library.css">
  <link rel="shortcut icon" href="../media/favicon.ico">
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
  	<?php 
  	  if ($book_added == true) {
  	?>
  	  <div class="fun_facts"><p>Book added!</p></div>
  	<?php 
  	  }
  	?><div class="fun_facts"><h2>Fun facts</h2>
      <ul>
        <li>The average price of the books in this collection is $<?php echo number_format(getAvgBookCost(), 2, '.', '') ?>.</li>
        <li>Most recently released book(s):
          <?php
            echo implode(", ", array_map("get_title", getLatestReleases()));
          ?>
        </li>
      </ul>
    </div>
    
    <div class ="filter">  
      <table>
        <form method="post">
          <tr>
            <th colspan=2>
              Filter Results
            </th>
          </tr>
          <tr>
            <td>Select Category:</td>
            <td>
              <input type="checkbox" name='filter[]' value="1">Mystery<br />
              <input type="checkbox" name='filter[]' value="2">Fiction<br />
              <input type="checkbox" name='filter[]' value="3">Young Adult<br />
              <input type="checkbox" name='filter[]' value="4">Fantasy<br />
              <input type="checkbox" name='filter[]' value="5">Romance<br />
              <input type="checkbox" name='filter[]' value="6">Science Fiction<br />
              <input type="checkbox" name='filter[]' value="7">Non-Fiction<br />
              <input type="checkbox" name='filter[]' value="8">Humor<br />
              <input type="checkbox" name='filter[]' value="9">New Adult<br />
              <input type="checkbox" name='filter[]' value="10">Adult<br />
              <input type="submit" name="filter_submit" value="Filter by Categories">
            </td>
          </tr>
          <tr>
            <td>
              Select Date Range:
            </td>
            <td>
              Min: <input type="date" name='filter_min_date'><br />
              Max: <input type="date" name='filter_max_date'><br />
              <input type="submit" name="filter_date_submit" value="Filter by Date">
            </td>
          </tr>
          <tr>
            <td>
              Select Author:
            </td>
            <td>
              <select name='filter_author'>
                <option value="-1"> - Any Author - </option>
                <?php 
                    $authors = getAuthors();
                    foreach($authors as $author) {
                      echo "<option value =" . $author[id] . ">" . $author[name] . "</option>";
                    }
                ?>
              </select>
              <input type="submit" name="filter_author_submit" value="Filter by Author">
            </td>
          </tr>
          </form>
        </table>
    </div><br><br>
    
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
        foreach ($book_list as $book) {
      ?>
        <tr>
          <td class="book_id"><?php echo $book['id']; ?></td>
          <td class="book_title">
            <a href="#" onclick='showBook(<?php echo json_encode($book, JSON_HEX_APOS); ?>)'><?php echo $book['title']; ?></a>
          </td>
          <td class="book_author"><?php echo $book['author']; ?></td>
          <td class="book_synopsis ellipsis"><?php echo $book['synopsis']; ?></td>
          <td class="book_price">$<?php echo $book['price']; ?></td>
        </tr>
      <?php
        } // close foreach
      ?>
    </table>

    <form method="post">
      <table class="add_book">
        <tr>
          <th colspan=2>New Book</th>
        </tr>
        <tr>
          <td class="col_header">Title:</td>
          <td><input type="text" name="title" size=25 maxlength=50></td>
        </tr>
        <!-- If time allows, add drop-down selection for author here 
             (instead of filling in author data). -->
        <tr>
          <td class="col_header">Author:</td>
          <td><input type="text" name="author"></td>
        </tr>
        <tr>
          <td class="col_header">Synopsis:</td>
          <td><textarea name="synopsis" cols=25 rows=5></textarea></td>
        </tr>
        <tr>
          <td class="col_header">Release Date:</td>
          <td><input type="date" name="release_date" size=25></td>
        </tr>
        <tr>
          <td class="col_header">ISBN:</td>
          <td><input type="text" name="isbn" size=15 maxlength=10></td>
        </tr>
        <!-- If time allows, add drop-down selection for publisher here 
             (instead of filling in publisher ). -->
        <div class="form_publisher_section">
          <tr>
            <td class="col_header">Publisher Name:</td>
            <td><input type="text" name="publisher_name"></td>
          </tr>
          <tr>
            <td class="col_header">Publisher Address:</td>
            <td><input type="text" name="publisher_address"></td>
          </tr>
          <tr>
            <td class="col_header">Publisher City:</td>
            <td><input type="text" name="publisher_city"></td>
          </tr>
          <tr>
            <td class="col_header">Publisher State:</td>
            <td><input type="text" name="publisher_state" size="2"></td>
          </tr>
          <tr>
            <td class="col_header">Publisher Zip:</td>
            <td><input type="text" name="publisher_zip" size="5"></td>
          </tr>
        </div>
        <tr>
          <td class="col_header">Price:</td>
          <td><input type="text" name="price" size=15 maxlength=10></td>
        </tr>
        <tr>
          <td class="col_header">Categories (select all that apply):<br>
        <?php
          foreach(getCategories() as $category) {
        ?>
          <input type='checkbox' name='categories[]' value='<?php echo $category['id']; ?>'><?php echo $category['name']; ?><br>
        <?php 
          }
        ?>
        </td><td></td></tr>
        <tr><td colspan="2"><input type="submit" name="add_book" value="Add Book"></td></tr>
      </table>
    </form>
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
