<html>
<head>
	<title>Software Asset Management - Custom Report</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/topnav.css" type="text/css"> <!--Top Navigation-->
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

//ID passed to us in URL
$reportid = $_GET["id"];

//First pull the SQL from the custom reports table
$result = mysqli_query($connection,"SELECT ReportName, ReportSQL FROM customreports where ID=$reportid");
$getID = mysqli_fetch_assoc($result);
$ReportName = $getID["ReportName"];
$ReportSQL = $getID["ReportSQL"];

//Put the name in the Top Navigation
echo "<div class=\"topnav\">\r\n\t";
	echo "<span id=\"title\">" . $ReportName . "</span>\r\n\t";
	echo "<a href=\"../\"><img id=\"home\" src=\"images\house.png\" alt=\"home\"></a>\r\n";
echo "</div>";

//get results from database
$result = mysqli_query($connection,$ReportSQL);
$all_property = array();  //declare an array for saving property

//showing property
echo '<table id="table3">'; //initialize table tag
echo "\r\n<tr>";
while ($property = mysqli_fetch_field($result)) {
    echo "\r\n\t<th>" . $property->name . "</th>";  //get field name for header
    array_push($all_property, $property->name);  //save those to array
}
echo "\r\n</tr>\r\n"; //end tr tag

//showing all data
while ($row = mysqli_fetch_array($result)) {
    echo "\r\n<tr>";
    foreach ($all_property as $item) {
        echo "\r\n\t<td>" . $row[$item] . "</td>"; //get items using property value
    }
    echo "\r\n</tr>\r\n"; //end tr tag
}
echo "</table>";
?>

</body>
</html>