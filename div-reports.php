<?php
//Common Reports - Referred by index.php

//get results from database
$result = mysqli_query($connection,"SELECT ID, ReportName FROM customreports order by SortOrder limit 20");
$all_property = array();  //declare an array for saving property

echo "<ul class=\"bullet\">\r\n\t";
	while ($row = mysqli_fetch_array($result)) {
        echo "<li><a href=\"customreports.php?id=" . $row["ID"] . "\">" . $row["ReportName"] . "</a></li>\r\n\t"; //Make the report name clickable
	}
echo "</ul>";
echo "<br>";
echo "<a href=\"reportslist.php\">View More / Edit List</a>";
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