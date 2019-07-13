<html>
<head>
<title>CST336 Lab 2 by John Lester</title>
</head>
<body>
<center>
<table border=1 cellpadding=10>
<?php
$odd = 0;
$even = 0;
$sum = 0;
for ($i=0; $i<=9; $i++) {
	echo "<tr>";
	for ($j=0; $j<=9; $j++) {
		$num = rand(1, 100);
		echo "<td style=\"text-align:center\" bgcolor=";
		if ($num%2 > 0) {
			echo "cyan";
			$odd++;
		}
		else {
			echo "pink";
			$even++;
		}
		echo ">" . $num . "</td>\n";
		$sum += $num;
	}
	echo "</tr>\n";
}
echo "</table>";
echo "<h3>There are " . $odd . " odd numbers and " . $even . " even numbers.</h3>";
echo "<h3>The total of the random numbers is " . number_format($sum) . ".</h3>";
?>
</center>
</body>
</html>
