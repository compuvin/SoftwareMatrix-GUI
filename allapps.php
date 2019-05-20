<!DOCTYPE html>
<html>
<head>
	<title>Software Asset Management - All Applications</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/topnav.css" type="text/css"> <!--Top Navigation-->
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/table.css" type="text/css"> <!--Tables-->
	<link rel=stylesheet href="css/search.css" type="text/css"> <!--Search Inputs-->
</head>
<body>

<div class="topnav">
	<span id="title">All Applications</span>
	<a href="../"><img id="home" src="images\house.png" alt="home"></a>
</div>

<?php
//Search variables
$AppName = $Computer = $Publisher = "";

//Basic SQL query
$SQLstr ="SELECT Name, Publisher, Computer, Version, FirstDiscovered \"First Discovered\", LastDiscovered \"Last Discovered\" FROM applicationsdump";

//Get search variables
if (!empty ($_GET["name"])) {$AppName = $_GET["name"];}
if (!empty ($_GET["publisher"])) {$Publisher = $_GET["publisher"];}
if (!empty ($_GET["computer"])) {$Computer = $_GET["computer"];}

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
echo "<input type=\"text\" name=\"AppName\" placeholder=\"Application Name\" value=\"$AppName\">\r\n\t";
echo "<label>Publisher</label>\r\n\t";
echo "<input type=\"text\" name=\"Publisher\" placeholder=\"Publisher\" value=\"$Publisher\">\r\n\t";
echo "<label>Computer</label>\r\n\t";
echo "<input type=\"text\" name=\"Computer\" placeholder=\"Computer Name\" value=\"$Computer\">\r\n\t";
echo "<input type=\"submit\" name=\"submit\" value=\"Search\">\r\n\t";
echo "</form>\r\n";
echo "</div>";

//add search parameters
if ($AppName !== "") {
	if (strpos($SQLstr,"where",0) == FALSE) {$SQLstr = $SQLstr . " where ";}
	$SQLstr = $SQLstr . "Name like '%$AppName%'";
	$SQLstr = $SQLstr . " and ";
}
if ($Computer !== "") {
	if (strpos($SQLstr,"where",0) == FALSE) {$SQLstr = $SQLstr . " where ";}
	$SQLstr = $SQLstr . "Computer like '%$Computer%'";
	$SQLstr = $SQLstr . " and ";
}
if ($Publisher !== "") {
	if (strpos($SQLstr,"where",0) == FALSE) {$SQLstr = $SQLstr . " where ";}
	$SQLstr = $SQLstr . "Publisher like '%$Publisher%'";
	$SQLstr = $SQLstr . " and ";
}

//clean up and add sort
if (substr($SQLstr,-5,5) == " and ") {
	$SQLstr = substr($SQLstr,0,-5);
}
$SQLstr = $SQLstr . " order by Name, Computer";

if (strpos($SQLstr,"where",0) == FALSE) {
	echo '<script>alert("This report cannot be run without search criteria as it will return too many results");</script>'; //initialize table tag
} else {
	//get results from database
	$result = mysqli_query($connection, $SQLstr);
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
			if ($item == "Name") {
				echo "\r\n\t<td><a href=\"allapps.php?name=" . $row[$item] . "\">" . $row[$item] . "</a></td>"; //Make the report name clickable
			} elseif ($item == "Publisher") {
				echo "\r\n\t<td><a href=\"allapps.php?publisher=" . $row[$item] . "\">" . $row[$item] . "</a></td>"; //Make the report name clickable	
			} elseif ($item == "Computer") {
				echo "\r\n\t<td><a href=\"allapps.php?computer=" . $row[$item] . "\">" . $row[$item] . "</a></td>"; //Make the report name clickable	
			} else {
				echo "\r\n\t<td>" . $row[$item] . "</td>"; //get items using property value
			}
		}
		echo "\r\n</tr>\r\n"; //end tr tag
	}
	echo "</table>";
}
?>

</body>
</html>