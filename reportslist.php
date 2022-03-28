<!DOCTYPE html>
<html>
<head>
	<title>Software Asset Management - Reports List</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/topnav.css" type="text/css"> <!--Top Navigation-->
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/table.css" type="text/css"> <!--Tables-->
	<link rel=stylesheet href="css/form.css" type="text/css"> <!--Forms-->
</head>
<body>

<div class="topnav">
	<a href="../"><img src="images\house.png" alt="home" width="32" height="32"></a>
</div>

<?php
require_once('login.php');

//create connection
$connection = mysqli_connect($host, $user, $pass, $db_name);

//test if connection failed
if(mysqli_connect_errno()){
    die("connection failed: "
        . mysqli_connect_error()
        . " (" . mysqli_connect_errno()
        . ")");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	//Double check that the table doesn't exist
	$result = mysqli_query($connection,"SELECT SortOrder, ReportName, ID FROM customreports order by SortOrder, ReportName");

	if ($result == FALSE) {
		//Create Table
		$result = mysqli_query($connection,"CREATE TABLE customreports (ID INT PRIMARY KEY AUTO_INCREMENT, ReportName TEXT, ReportSQL TEXT, SortOrder INT)");
		
		//Create some starter reports
		$result = mysqli_query($connection,"INSERT INTO customreports SET ReportName = 'All Unique', ReportSQL = 'SELECT Name, FOSS, `Update Method` FROM discoveredapplications order by Name', SortOrder = '1'"); //All Unique
		$result = mysqli_query($connection,"INSERT INTO customreports SET ReportName = 'Continue Updating', ReportSQL = 'SELECT ID, Name, Version_Oldest, Version_Newest,(select count(*) from software_matrix.applicationsdump where applicationsdump.Name = discoveredapplications.Name and applicationsdump.Version = discoveredapplications.Version_Oldest) Oldest, (select count(*) from software_matrix.applicationsdump where applicationsdump.Name = discoveredapplications.Name and applicationsdump.Version = discoveredapplications.Version_Newest) Newest FROM software_matrix.discoveredapplications where Version_Oldest <> Version_Newest order by Name', SortOrder = '2'"); //Continue Updating
		$result = mysqli_query($connection,"INSERT INTO customreports SET ReportName = 'High Risk Apps', ReportSQL = 'SELECT Name, COUNT(Name), MAX(DateAdded), (select Computers from software_matrix.discoveredapplications where discoveredapplications.Name = highriskapps.Name) Computers FROM software_matrix.highriskapps where DateAdded > current_date() - interval 365 day GROUP BY Name ORDER BY count(Name) DESC, max(DateAdded) DESC, Computers DESC', SortOrder = '3'"); //High Risk Apps
		$result = mysqli_query($connection,"INSERT INTO customreports SET ReportName = 'Licensed Apps', ReportSQL = 'SELECT L.Name, L.Publisher, L.Amount, D.Computers FROM software_matrix.licensedapps as L inner join software_matrix.discoveredapplications as D on L.Name = D.Name order by L.Name', SortOrder = '4'"); //Licensed Apps
		$result = mysqli_query($connection,"INSERT INTO customreports SET ReportName = 'New Vulnerabilities', ReportSQL = 'SELECT * FROM software_matrix.highriskapps ORDER by DateAdded DESC LIMIT 10', SortOrder = '5'"); //New Vulnerabilities
		$result = mysqli_query($connection,"INSERT INTO customreports SET ReportName = 'Software with count &lt 5', ReportSQL = 'SELECT Name, Publisher, Version, FirstDiscovered, LastDiscovered, count(Name) Count FROM software_matrix.applicationsdump group by Name having count < 5 order by count, Name', SortOrder = '6'"); //Software with count > 5
	}
}

//get results from database
$result = mysqli_query($connection,"SELECT SortOrder, ReportName, ID FROM customreports order by SortOrder, ReportName");
$all_property = array();  //declare an array for saving property

if ($result !== FALSE) {
	//showing property
	echo '<table id="table3">'; //initialize table tag
	echo "\r\n<tr>";
	while ($property = mysqli_fetch_field($result)) {
		if ($property->name == "ID")
		{
			echo "\r\n\t<th>Edit</th>";  //Change the ID header to "Edit"
		} else {
			echo "\r\n\t<th>" . $property->name . "</th>";  //get field name for header
		}
		array_push($all_property, $property->name);  //save those to array
	}
	echo "\r\n</tr>\r\n"; //end tr tag

	//showing all data
	while ($row = mysqli_fetch_array($result)) {
		echo "\r\n<tr>";
		foreach ($all_property as $item) {
			if ($item == "ID") {
				echo "\r\n\t<td><a href=\"edit-reportinfo.php?id=" . $row[$item] . "\">Edit/Delete</a></td>"; //Make the ID clickable
			} elseif ($item == "ReportName") {
				echo "\r\n\t<td><a href=\"customreports.php?id=" . $row["ID"] . "\">" . $row[$item] . "</a></td>"; //Make the report name clickable	
			} else {
				echo "\r\n\t<td>" . $row[$item] . "</td>"; //get items using property value
			}
		}
		echo "\r\n</tr>\r\n"; //end tr tag
	}
	echo "<tr><td colspan=3><a href=\"add-newreport.php\">Add new report</a></td></tr>";
	echo "</table>";
} else {
	echo "<div class=\"container3\">\r\n\t";
	echo "<form method=\"post\" action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . ">\r\n\t";
	echo "<label>It doesn't look like the Reports table has been created yet. Would you like to create it?</label><br><br>\r\n\r\n\t";
	echo "<div class=\"row\">\r\n\t\t";
	echo "<div class=\"col-25\"><input type=\"button\" value=\"No\" onclick=\"history.back()\"></div>\r\n\t\t";
	echo "<div class=\"col-75\"><input type=\"submit\" name=\"submit\" value=\"Yes\"></div>\r\n\t";
	echo "</div>\r\n\t";
	echo "</form>\r\n";
	echo "</div>";
}
?>

</body>
</html>