<?php
//Common Reports - Referred by index.php

//Variables
$LimitR = 20; //Changable limit of reports to display
$i = 0; //counter

//get results from database
$result = mysqli_query($connection,"SELECT ID, ReportName FROM customreports order by SortOrder limit $LimitR");

if ($result !== FALSE) {
	echo "<ul class=\"bullet\">\r\n\t";
	while ($row = mysqli_fetch_array($result)) {
        echo "<li><a href=\"customreports.php?id=" . $row["ID"] . "\">" . $row["ReportName"] . "</a></li>\r\n\t"; //Make the report name clickable
		$i++; //increase counter
	}
	echo "</ul>";
	echo "<br>";
	if ($i < $LimitR)
	{
		echo "<center><a href=\"reportslist.php\">Edit List</a></center>";
	} else {
		echo "<center><a href=\"reportslist.php\">View More</a></center>";
	}
} else {
	echo "<center><a href=\"reportslist.php\">Reports Table hasn't been created yet.<br>\r\nClick here to create it</a></center>";
}


?>
