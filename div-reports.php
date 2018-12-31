<?php
//Common Reports - Referred by index.php

//Variables
$LimitR = 20; //Changable limit of reports to display
$i = 0; //counter

//get results from database
$result = mysqli_query($connection,"SELECT ID, ReportName FROM customreports order by SortOrder limit $LimitR");
$all_property = array();  //declare an array for saving property

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
<!--	<ul class="bullet">
		<li><a href="discoveredapps.php">All Unique</a></li>
		<li><a href="continueupdating.php">Continue Updating</a></li>
		<li><a href="highriskapps.php">High Risk Apps</a></li>
		<li><a href="licensedapps.php">Licensed Apps</a></li>
		<li><a href="newvulnerabilities.php">New vulnerabilities</a></li>
		<li><a href="oneoffapps.php">Software with count &lt 5</a></li>
	</ul>
-->