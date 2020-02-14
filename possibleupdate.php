<!DOCTYPE html>
<html>
<head>
	<title>Software Asset Management - Edit App Info</title>
	<meta charset="UTF-8">
	
	<!--Favicons-->
	<link rel="icon" type="image/ico" href="WhattheFOSS.ico"></link> 
	<link rel="shortcut icon" href="WhattheFOSS.ico"></link>
	
	<!--Link Stylesheets-->
	<link rel=stylesheet href="css/topnav.css" type="text/css"> <!--Top Navigation-->
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/form.css" type="text/css"> <!--Forms-->
</head>
<body>

<div class="topnav">
	<a href="../"><img src="images\house.png" alt="home" width="32" height="32"></a>
</div>

<?php
//Edit software information

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


?>

<?php
// define variables and set to empty values
$websiteErr = "";
$Name = $email = $gender = $comment = $website = "";
$LicenseAmt = $LicenseComments = ""; //Initialize license variables

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $appid = $_POST["appid"];
  $Name = $_POST["Name"];
  $UpdatePageQTH = $_POST["UpdatePageQTH"];

  //write changes to database
  $result = mysqli_query($connection,"UPDATE discoveredapplications
	SET UpdatePageQTH = '$UpdatePageQTH' where ID = $appid");
	
	if ($result == 1)
	{
		echo $Name . " was updated successfully. Here's were we'll want to go back to the previous page.";
		echo "'<script type=\"text/javascript\">window.location = \"" . str_replace("/possibleupdate.php", "/", htmlspecialchars($_SERVER["PHP_SELF"])) . "\"</script>";
	} else {
		echo "An error has occurred. Please review your submission.";
	}
  
} else {
	
	//Software ID from database
	$appid = $_GET["id"];
	$wpQTH = $_GET["qth"];
	
	//get results from database
	$result = mysqli_query($connection,"SELECT * FROM discoveredapplications where ID = $appid");

	//showing all data
	while ($row = mysqli_fetch_array($result)) {
		$Name = $row["Name"];
		$Version_Newest = $row["Version_Newest"];
		$UpdateURL = $row["UpdateURL"];
		$UpdatePageQTH = $row["UpdatePageQTH"];		
		$UpdatePageQTHVarience = $row["UpdatePageQTHVarience"];		
	}
}


echo "<form method=\"post\" action=" . htmlspecialchars($_SERVER["PHP_SELF"]) . ">\r\n\t";
	echo "<div class=\"container1\">\r\n\t";
		echo "<h2>" . $Name . "</h2>\r\n";
		echo "Visit the website below and verify that the version listed below is the latest version. If it is, click Fix below. This will update the database with the new location of the version on the page.\r\n";
		echo "<br><br>\r\n\r\n";
		echo "Version: <b>" . $Version_Newest . "</b>\r\n";
		echo "<br><br>\r\n\r\n";
		echo "<b><a href=" . $UpdateURL . ">" . $UpdateURL . "</a></b>\r\n";
		echo "<br><br>\r\n\r\n";
		echo "<b>Tech Info:</b>\r\n";
		echo "<br>\r\n";
		echo "Previous location: " . $UpdatePageQTH . ", Current location: " . $wpQTH . ", Allowed varience: " . $UpdatePageQTHVarience . "\r\n";
		echo "<input type=\"hidden\" name=\"appid\" value=" . $appid . ">\r\n";
		echo "<input type=\"hidden\" name=\"UpdatePageQTH\" value=" . $wpQTH . ">\r\n";
		echo "<input type=\"hidden\" name=\"UpdatePageQTHVarience\" value=" . $UpdatePageQTHVarience . ">\r\n";
		echo "<input type=\"hidden\" name=\"Name\" value=" . $Name . ">\r\n";
		if (!empty($_SERVER["HTTP_REFERER"])) {
			echo "<input type=\"hidden\" name=\"WebAddr\" value=\"" . htmlspecialchars($_SERVER["HTTP_REFERER"]) . "\">\r\n";
		} else {
			echo "<input type=\"hidden\" name=\"WebAddr\" value=\"\">\r\n";
		}
		
		echo "<div class=\"row\">";
			echo "<input type=\"button\" value=\"Cancel\" onclick=\"window.close()\">\r\n";
			echo "<input type=\"submit\" name=\"Submit\" value=\"Fix\">\r\n";
		echo "</div>";
	echo "</div>";
echo "</form>";
?>


</body>
</html>