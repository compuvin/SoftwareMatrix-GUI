<html>
<head>
	<title>Software Asset Management - Reports List</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/table.css" type="text/css"> <!--Tables-->
</head>
<body>

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

//get results from database
$result = mysqli_query($connection,"SELECT SortOrder, ReportName, ID FROM customreports order by SortOrder");
$all_property = array();  //declare an array for saving property

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
?>

</body>
</html>