<?php
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>CST336 Assignment 3 by John Lester</title>
<style>
body { background-image:url(../media/background-library.jpg); }
table {
	width:640px;
	height:480px;
	position:fixed;
	margin-left:-320px;
	margin-top:-240px;
	left:50%;
	top:50%;
}
th { background-color:white; font-size:24px; height:40px; }
td { background-color:white; font-size:18px; }
</style>
<link rel="shortcut icon" href="../favicon.ico" />
</head>
<body>
<center>
<form method="post">
<table border="0" width="640">
<tr><th colspan="2">BOOK<i>ish</i> Mad Libs</th></tr>
<?php
if (isset($_POST['word1'])) { // Second page
	$words1 = array($_POST['word1'], $_POST['word2'], $_POST['word3'], $_POST['word4'], $_POST['word5'], $_POST['word6'], $_POST['word7'], $_POST['word8'], $_POST['word9'], $_POST['word10'], $_POST['word11']);
	$_SESSION['words1'] = $words1;
	echo "<tr><td>Letter:</td><td><input type=text name=word12></td></tr>";
	echo "<tr><td>Celebrity:</td><td><input type=text name=word13></td></tr>";
	echo "<tr><td>Plural Noun:</td><td><input type=text name=word14></td></tr>";
	echo "<tr><td>Adjective:</td><td><input type=text name=word15></td></tr>";
	echo "<tr><td>Place:</td><td><input type=text name=word16></td></tr>";
	echo "<tr><td>Body Part:</td><td><input type=text name=word17></td></tr>";
	echo "<tr><td>Adjective:</td><td><input type=text name=word18></td></tr>";
	echo "<tr><td>Adjective:</td><td><input type=text name=word19></td></tr>";
	echo "<tr><td>Verb:</td><td><input type=text name=word20></td></tr>";
	echo "<tr><td>Plural Noun:</td><td><input type=text name=word21></td></tr>";
	echo "<tr><td>Number:</td><td><input type=text name=word22></td></tr>";
	echo "<tr><td colspan=2 align=center><input type=submit value=\"Finish\"><input type=reset value=Reset></td></tr>";
}
elseif (isset($_POST['word12'])) { // Third Page
	$words2 = array($_POST['word12'], $_POST['word13'], $_POST['word14'], $_POST['word15'], $_POST['word16'], $_POST['word17'], $_POST['word18'], $_POST['word19'], $_POST['word20'], $_POST['word21'], $_POST['word22']);
	$words = array_merge($_SESSION['words1'], $words2);
	echo "<tr><td colspan=2 style=\"padding-right:20px; padding-left:20px;\" valign=top>There are many ".$words[0]." ways to choose a/an ".$words[1]." to read. First, you could ask for recommendations from your friends and ".$words[2].". Just don't ask Aunt ".$words[3]." - she only reads ".$words[4]." books with ".$words[5]."-ripping goddesses on the cover. If your friends and family are no help, try checking out the ".$words[6]." Review in <i>The ".$words[7]." Times</i>. If the ".$words[8]." featured there are too ".$words[9]." for your taste, try something a little more low-".$words[10].", like ".$words[11].": <i>The ".$words[12]." Magazine</i>, or <i>".$words[13]." Magazine</i>. You could also choose a book the ".$words[14]."-fashioned way. Head to your local library or ".$words[15]." and browse the shelves until something catches your ".$words[16].". Or, you could save yourself a whole lot of ".$words[17]." trouble and log on to <a href=\"http://www.bookish.com\" target=\"_blank\">www.bookish.com</a>, the ".$words[18]." new website to ".$words[19]." for books! With all the time you'll save not having to search for ".$words[20].", you can read ".$words[21]." more books!<br><br><br>The word of the moment is ".$words[array_rand($words)]."<br><br>The words submitted include ";
	asort($words);
	$count = 0;
	while(list($var, $val) = each($words)) {
		echo $val;
		$count++;
		if ($count==count($words)-1) {
			echo " and ";
		}
		elseif ($count!=count($words)) {
			echo ", ";
		}
		else {
			echo ".";
		}
	}
	echo "<br><br><a href=\"assignment3.php\">Restart Mad Lib</a></td></tr>";
}
else { // First Page
	echo "<tr><td>Adjective:</td><td><input type=text name=word1></td></tr>";
	echo "<tr><td>Noun:</td><td><input type=text name=word2></td></tr>";
	echo "<tr><td>Plural Noun:</td><td><input type=text name=word3></td></tr>";
	echo "<tr><td>Female Name:</td><td><input type=text name=word4></td></tr>";
	echo "<tr><td>Adjective:</td><td><input type=text name=word5></td></tr>";
	echo "<tr><td>Article of Clothing:</td><td><input type=text name=word6></td></tr>";
	echo "<tr><td>Noun:</td><td><input type=text name=word7></td></tr>";
	echo "<tr><td>City:</td><td><input type=text name=word8></td></tr>";
	echo "<tr><td>Plural Noun:</td><td><input type=text name=word9></td></tr>";
	echo "<tr><td>Adjective:</td><td><input type=text name=word10></td></tr>";
	echo "<tr><td>Body Part:</td><td><input type=text name=word11></td></tr>";
	echo "<tr><td colspan=2 align=center><input type=submit value=\"Next\"><input type=reset value=Reset></td></tr>";
}
?>
</table>
</form>
</center>
</body>
</html>