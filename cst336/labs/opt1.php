<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame 
Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>Lab 3 PHP Array Functions</title>
<meta name="description" content="">
<meta name="author" content="su5196">

<meta name="viewport" content="width=device-width; initial-scale=1.0">

<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
<link rel="shortcut icon" href="/favicon.ico">
</head>

<body>
<div>

<h1> PHP Array Functions</h1>

<table border="1">
<tr>
<th>Code</th>
<th>Description</th> 
<th>Output</th>
</tr>
<tr>
<td>$states = array("AZ", "CA", "IL", "IN", "NY");</td>
<td> Initializes an array with five elements</td> 
<td>
<?php
$states = array("AZ", "CA", "IL", "IN", "NY");
?>
</td>
</tr>
<tr>
<td>echo count($states);</td>
<td> Displays the number of elements in the array</td> 
<td>
<?php
echo count($states);
?>
</td>
</tr>
<tr>
<td>
<pre>
foreach ($states as $state) { 
echo $state . ", ";
} 
</pre>
</td>
<td> Loops through an array and displays all values</td> 
<td>
<?php
foreach ($states as $state) {
echo $state . ", ";
}
?>
</td>
</tr> 
<tr>
<td> $states[] = "NM"; </td>
<td>Adds one more element to the list</td> 
<td>
<?php
$states[] = "NM";
foreach ($states as $state) {
echo $state . ", ";
}
?>
</td>
</tr>

</table>

</div>
</body>
</html>