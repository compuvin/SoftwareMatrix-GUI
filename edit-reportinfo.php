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
  $reportid = $_POST["reportid"];
  $ReportName = $_POST["ReportName"];
  $ReportSQL = $_POST["ReportSQL"];
  $SortOrder = $_POST["SortOrder"];
  
  //Add report to database
  $result = mysqli_query($connection,"UPDATE customreports
	SET ReportName = '$ReportName',
	ReportSQL = '$ReportSQL',
	SortOrder = '$SortOrder' where ID = $reportid");
	
	if ($result == 1)
	{
		echo $ReportName . " was added successfully. Here's were we'll want to go back to the previous page.";
		echo '<script type="text/javascript">window.location = "' . $_POST["WebAddr"] .'"</script>';
	} else {
		echo "An error has occurred. Please review your submission.";
	};
  
} else {
	//Software ID from database
	$reportid = $_GET["id"];
	
	//get results from database
	$result = mysqli_query($connection,"SELECT * FROM customreports where ID = $reportid");

	//showing all data
	while ($row = mysqli_fetch_array($result)) {
		$ReportName = $row["ReportName"];
		$ReportSQL = $row["ReportSQL"];
		$SortOrder = $row["SortOrder"];
	}
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
		<h2>Editing report: <?php echo $ReportName;?></h2>
		<input type="hidden" name="reportid" value="<?php echo $reportid;?>">
		<input type="hidden" name="WebAddr" value="<?php echo htmlspecialchars($_SERVER["HTTP_REFERER"]);?>">
	</div>
	<br>
	<div class="container3">
		<div class="row">
			<div class="col-25">
				<label>Name</label>
			</div>
			<div class="col-75">
				<input type="text" name="ReportName" placeholder="Write something..." value="<?php echo $ReportName;?>">
			</div>
		</div>
		<div class="row">
			<div class="col-25">
				<label>SQL Expression</label>
			</div>
			<div class="col-75">
				<textarea name="ReportSQL" rows="5" cols="40" placeholder="Write something..."><?php echo $ReportSQL;?></textarea>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label>Sort Order</label>
			</div>
			<div class="col-75">
				<input type="text" name="SortOrder" placeholder="Numerical values only!" value="<?php echo $SortOrder;?>">
				<span class="error"><?php echo $websiteErr;?></span>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label><span class="error">* Required Field</span></label>
			</div>
			<div class="col-75">
				<input type="submit" name="submit" value="Submit">
			</div>
		</div>
	</div>	
</form>

</body>
</html>