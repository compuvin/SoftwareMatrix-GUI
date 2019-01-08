<!DOCTYPE html>
<html>
<head>
	<title>Software Asset Management - Discovered Applications</title>
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
	<span id="title">Discovered Applications</span>
	<a href="../"><img id="home" src="images\house.png" alt="home"></a>
</div>

<?php
//Search variables
$AppName = $something = "";

//Basic SQL query
$SQLstr ="SELECT Name, FOSS, `Update Method`, ID FROM discoveredapplications";

//Get search variables
if (!empty ($_GET["name"])) {$AppName = $_GET["name"];}

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

//Search options
echo "<div class=\"searchoptions\">\r\n\t";
echo "<form method=\"post\" action=\"searchresults.php\">\r\n\t";
echo "<input type=\"hidden\" name=\"WebAddr\" value='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>\r\n\t"; //Post page is the same for multiple searches to this tell us how to get back
echo "<label>Application</label>\r\n\t";
echo "<input type=\"text\" class=\"searchinput\" name=\"AppName\" placeholder=\"Application Name\" value=\"$AppName\">\r\n\t";
echo "<div class=\"row\">\r\n\t\t";
echo "<div class=\"col-25\"></div>\r\n\t\t";
echo "<div class=\"col-75\"><input type=\"submit\" name=\"submit\" value=\"Search\"></div>\r\n\t";
echo "</div>\r\n\t";
echo "</form>\r\n";
echo "</div>";

//add search parameters
if ($AppName !== "") {
	if (strpos($SQLstr,"where",0) == FALSE) {$SQLstr = $SQLstr . " where ";}
	$SQLstr = $SQLstr . "Name like '%$AppName%'";
	$SQLstr = $SQLstr . " and ";
}

//clean up and add sort
if (substr($SQLstr,-5,5) == " and ") {
	$SQLstr = substr($SQLstr,0,-5);
}
$SQLstr = $SQLstr . " order by Name";

//get results from database
$result = mysqli_query($connection, $SQLstr);
$all_property = array();  //declare an array for saving property

//showing property
echo '<table id="table3">'; //initialize table tag
echo "\r\n<tr>";
while ($property = mysqli_fetch_field($result)) {
	if ($property->name == "ID")
	{
		echo "\r\n\t<th>Options</th>";  //Change the ID header to "Edit"
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
			echo "\r\n\t<td><a href=\"edit-appinfo.php?id=" . $row[$item] . "\">Edit</a></td>"; //Make the ID clickable
		} elseif ($item == "Name") {
			echo "\r\n\t<td><a href=\"allapps.php?name=" . $row[$item] . "\">" . $row[$item] . "</a></td>"; //Make the report name clickable	
		} else {
			echo "\r\n\t<td>" . $row[$item] . "</td>"; //get items using property value
		}
	}
	echo "\r\n</tr>\r\n"; //end tr tag
}
echo "</table>";
?>

</body>
</html>