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
$nameErr = $emailErr = $genderErr = $websiteErr = "";
$Name = $email = $gender = $comment = $website = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $ReportName = $_POST["ReportName"];
  $ReportSQL = $_POST["ReportSQL"];
  $SortOrder = $_POST["SortOrder"];
  
  //Add report to database
  $result = mysqli_query($connection,"INSERT INTO customreports
	SET ReportName = '$ReportName',
	ReportSQL = '$ReportSQL',
	SortOrder = '$SortOrder'");
	
	if ($result == 1)
	{
		echo $ReportName . " was added successfully. Here's were we'll want to go back to the previous page.";
		echo '<script type="text/javascript">window.location = "' . $_POST["WebAddr"] .'"</script>';
	} else {
		echo "An error has occurred. Please review your submission.";
	};
  
} 


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"> 
	<div class="container1">
		<h2>Create a new report</h2>
		<input type="hidden" name="WebAddr" value="<?php echo htmlspecialchars($_SERVER["HTTP_REFERER"]);?>">
	</div>

	<div class="container3">
		<div class="row">
			<div class="col-25">
				<label>Name</label>
			</div>
			<div class="col-75">
				<input type="text" name="ReportName" placeholder="Write something..." value="" required>
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				<label>SQL Expression</label>
			</div>
			<div class="col-75">
				<textarea name="ReportSQL" rows="5" cols="40" placeholder="Write something..." required></textarea>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label>Sort Order</label>
			</div>
			<div class="col-75">
				<input type="text" name="SortOrder" placeholder="Numerical values only!" value="" required>
				<span class="error"><?php echo $websiteErr;?></span>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label><span class="error">* Required Field</span></label>
			</div>
			<div class="col-75">
				<input type="button" value="Cancel" onclick="history.back()">
				<input type="submit" name="submit" value="Submit">
			</div>
		</div>
	</div>	
</form>

</body>
</html>