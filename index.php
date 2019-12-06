<html>
<head>
	<title>Software Asset Management</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/div.css" type="text/css"> <!--Home Page Divs-->
	<link rel=stylesheet href="css/hpage.css" type="text/css"> <!--Home Page-->
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/table.css" type="text/css"> <!--Tables-->
	
	<style>
		input[type=text], select, textarea
		{
			border: 1px solid #CCCCCC;
			box-sizing: border-box;
			border-radius: 4px;
			resize: vertical;
			color: #000000;
			width: 70%;
			}

		input[type=submit]:hover, input[type=button]:hover
		{
			background-color: #333333;
			color: #FFFFFF;
			}
			
		input[type=submit]
		{
			background-color: #1D1D1D;
			#padding: 12px 20px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			color: #FFFFFF;
			}
	</style>
</head>
<body>

<?php
//Include database login info
require_once('login.php');

//Database - create connection
$connection = mysqli_connect($host, $user, $pass, $db_name);

//Database - test if connection failed
if(mysqli_connect_errno()){
    die("connection failed: "
        . mysqli_connect_error()
        . " (" . mysqli_connect_errno()
        . ")");
}

//Reports - includes reports.php
echo "<div class=\"a1\">\r\n";
echo "	<h1>Reports</h1>\r\n";
	require_once('div-reports.php');
echo "</div>";
echo "\r\n\r\n"; //Line breaks for cleaner code

//Horizontal spacing
echo '<div class="HorizontalSpace"></div>';
echo "\r\n\r\n"; //Line breaks for cleaner code

//Future use
echo "<div class=\"a4\">\r\n";
echo "<center>\r\n";
//echo "Search test - <a href=\"discoveredapps.php\">Unique</a> or <a href=\"allapps.php\">All</a>";
echo "<form method=\"post\" action=\"searchresults.php\">\r\n\t";
echo "<input type=\"hidden\" name=\"WebAddr\" value='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>\r\n\t"; //Post page is the same for multiple searches to this tell us how to get back
echo "<input type=\"text\" name=\"OmniBox\" placeholder=\"Search\" value=\"\">\r\n\t";
echo "<input type=\"submit\" name=\"submit\" value=\"Go\">\r\n\t";
echo "</form>\r\n";
echo "</center>\r\n";
echo "</div>";
echo "\r\n\r\n"; //Line breaks for cleaner code
	
//Software Needing Review';
echo "<div class=\"a2\">\r\n";
echo "		<h1>Software Needing Review</h1>\r\n";
	require_once('div-needsreview.php');
echo "</div>";
echo "\r\n\r\n"; //Line breaks for cleaner code

//Vertical Spacing
echo '<div class="VerticalSpace"></div>';
echo "\r\n\r\n"; //Line breaks for cleaner code

//Newly Discovered Software
echo "<div class=\"a3\">\r\n";
echo "	<h1>Newly Discovered Software</h1>\r\n";
	require_once('div-newlydiscovered.php');
echo "</div>";
echo "\r\n\r\n"; //Line breaks for cleaner code

?>

</body>
</html>