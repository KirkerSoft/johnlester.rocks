<?php
require '../../db_connection.php';

function getFemaleStudents() {
    global $dbConn;
    $sql = "SELECT *
            FROM m_students
			WHERE gender='F'
            ORDER BY lastName ASC";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
}
function getFailingStudents() {
    global $dbConn;
    $sql = "SELECT * 
			FROM m_students
			JOIN m_gradebook
			WHERE m_gradebook.grade <50
            ORDER BY m_gradebook.grade ASC";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
}
function getUngradedAssignments() {
    global $dbConn;
    $sql = "SELECT * 
			FROM m_assignments a
			WHERE NOT EXISTS (
				SELECT 1 
				FROM m_gradebook g
				WHERE a.assignmentId = g.assignmentId)
			ORDER BY a.dueDate";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
}
function getGradebook() {
    global $dbConn;
    $sql = "SELECT g.grade 'grade', 
					s.firstName 'firstName', 
					s.lastName 'lastName', 
					a.title 'title'
			FROM m_gradebook g, m_students s, m_assignments a
			WHERE g.studentId=s.studentId AND g.assignmentId=a.assignmentId
			ORDER BY s.lastName, a.title;";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
}
function getAverages() {
	global $dbConn;
	$sql = "SELECT s.firstName 'firstName', 
					s.lastName 'lastName', 
					AVG(g.grade) 'grade'
			FROM m_students s, m_gradebook g
			WHERE s.studentId=g.studentId
			GROUP BY s.studentId
			ORDER BY AVG(g.grade) DESC;";
    $stmt = $dbConn -> prepare($sql);
    $stmt -> execute();
    return $stmt->fetchAll();
}
?>

<HTML>
<HEAD>
	<TITLE>John Lester Final Exam</TITLE>
	<style>
	th {
		color: blue;
	}
	</style>
</HEAD>
<BODY>
Jump to: <b><a href="#fem">Female Students</a> - <a href="#fail">Failed Assignments</a> - 
<a href="#ung">Ungraded Assignments</a> - <a href="#grad">Gradebook</a> - 
<a href="#avg">Student Averages</a></b><br /><br />
<a name=fem></a><!--
List of all female students (5 points)
Must be ordered by last name (ascending - from A to Z).-->
<table id=table1><th colspan=2>Female Students</th>
<?php
$femaleStudent = getFemaleStudents();
foreach ($femaleStudent as $student) {
	echo "<tr><td>" . $student['firstName'] . "</td><td>" . $student['lastName'] . "</td></tr>";
}
?>
</table>
<br /><br />
<a name=fail></a><!--
List of students that have assignments with a grade lower than 50 (10 points)
Ordered by grade (ascending).-->
<table id=table2><th colspan=3>Failed Assignments (less than 50%)</th>
<?php
$failingStudent = getFailingStudents();
foreach ($failingStudent as $fStudent) {
	echo "<tr><td>" . $fStudent['firstName'] . "</td><td>" . $fStudent['lastName'] . "</td><td>" . $fStudent['grade'] . "%</td></tr>";
}
?>
</table>
<br /><br />
<a name=ung></a><!--
List of assignments that have not been graded (10 points)
Ordered by due date (ascending). Note: It should display those assignments that do not have any records in the Gradebook table.-->
<table id=table3><th colspan=2>Ungraded Assignments</th>
<?php
$ungraded = getUngradedAssignments();
foreach ($ungraded as $ungradedAssignment) {
	echo "<tr><td>" . $ungradedAssignment['title'] . "</td><td>" . $ungradedAssignment['dueDate'] . "</td></tr>";
}
?>
</table>
<br /><br />
<a name=grad></a><!--
Gradebook (10 points)
Ordered by last name and title.-->
<table id=table4><th colspan=4>Gradebook</th>
<?php
$gradebook = getGradebook();
foreach ($gradebook as $entry) {
	echo "<tr><td>" . $entry['firstName'] . "</td><td>" . $entry['lastName'] . "</td><td>" . $entry['title'] . "</td><td>" . $entry['grade'] . "</td></tr>";
}
?>
</table>
<br /><br />
<a name=avg></a><!--
List of average grade per student (average of the three assignments) (10 points)
Ordered by average, from highest to lowest.-->
<table id=table5><th colspan=3>Student Averages</th>
<?php
$studentAverages = getAverages();
foreach ($studentAverages as $avg) {
	echo "<tr><td>" . $avg['firstName'] . "</td><td>" . $avg['lastName'] . "</td><td>" . $avg['grade'] . "</td></tr>";
}
?>
</body>

</html>
