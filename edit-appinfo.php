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
  $Name = $_POST["Name"];
  $appid = $_POST["appid"];
  $Free = $_POST["Free"];
  $OpenSource = $_POST["OpenSource"];
  $ReasonForSoftware = $_POST["ReasonForSoftware"];
  $NeededOnMachines = $_POST["NeededOnMachines"];
  $PlansForRemoval = $_POST["PlansForRemoval"];
  $UpdateMethod = $_POST["UpdateMethod"];
  $UpdateURL = $_POST["UpdateURL"];
  
  if ($Free == "Y" or $OpenSource == "Y") 
  {
	  $FOSS = "Y";
  } else {
	  $FOSS = "N";
  }
    
  if (empty($_POST["UpdateURL"])) {
    $UpdateURL = "";
  } else {
    $UpdateURL = test_input($_POST["UpdateURL"]);
    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$UpdateURL)) {
      $websiteErr = "Invalid URL"; 
    }
  }

  if (empty($_POST["ReasonForSoftware"])) {
    $ReasonForSoftware = "";
  } else {
    $ReasonForSoftware = test_input($_POST["ReasonForSoftware"]);
  }

  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
  
  //write changes to database
  $result = mysqli_query($connection,"UPDATE discoveredapplications
	SET Free = '$Free',
	OpenSource = '$OpenSource',
	FOSS = '$FOSS',
	ReasonForSoftware = '$ReasonForSoftware',
	NeededOnMachines = '$NeededOnMachines',
	PlansForRemoval = '$PlansForRemoval',
	`Update Method` = '$UpdateMethod',
	UpdateURL = '$UpdateURL' where ID = $appid");
	
	if ($result == 1)
	{
		echo $Name . " was updated successfully. Here's were we'll want to go back to the previous page.";
		echo '<script type="text/javascript">window.location = "' . $_POST["WebAddr"] .'"</script>';
	} else {
		echo "An error has occurred. Please review your submission.";
	};
  
} else {
	
	//Software ID from database
	$appid = $_GET["id"];
	
	//get results from database
	$result = mysqli_query($connection,"SELECT * FROM discoveredapplications where ID = $appid");

	//showing all data
	while ($row = mysqli_fetch_array($result)) {
		$Name = $row["Name"];
		$Free = $row["Free"];
		$OpenSource = $row["OpenSource"];
		$ReasonForSoftware = $row["ReasonForSoftware"];
		$NeededOnMachines = $row["NeededOnMachines"];
		$PlansForRemoval = $row["PlansForRemoval"];
		$UpdateMethod = $row["Update Method"];
		$UpdateURL = $row["UpdateURL"];
	}
	echo "</table>";
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
		<h2>Editing: <?php echo $Name;?></h2>
		ID: <b><?php echo $appid;?></b>
		<input type="hidden" name="appid" value="<?php echo $appid;?>">
		<input type="hidden" name="Name" value="<?php echo $Name;?>">
		<input type="hidden" name="WebAddr" value="<?php echo htmlspecialchars($_SERVER["HTTP_REFERER"]);?>">
	</div>
	<br>
	<div class="container2">
		Free:
		<input type="radio" name="Free" <?php if (isset($Free) && $Free=="Y") echo "checked";?> value="Y">Yes
		<input type="radio" name="Free" <?php if (isset($Free) && $Free=="N") echo "checked";?> value="N">No
		<br>
		Open Source:
		<input type="radio" name="OpenSource" <?php if (isset($OpenSource) && $OpenSource=="Y") echo "checked";?> value="Y">Yes
		<input type="radio" name="OpenSource" <?php if (isset($OpenSource) && $OpenSource=="N") echo "checked";?> value="N">No
	</div>
	<br>
	<div class="container3">
		<div class="row">
			<div class="col-25">
				<label>Description of Software Use</label>
			</div>
			<div class="col-75">
				<textarea name="ReasonForSoftware" rows="5" cols="40" placeholder="Write something..."><?php echo $ReasonForSoftware;?></textarea>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label>Who Needs the Software (list devices or departments)</label>
			</div>
			<div class="col-75">
				<textarea name="NeededOnMachines" rows="5" cols="40" placeholder="Write something..."><?php echo $NeededOnMachines;?></textarea>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label>Plans for Removal</label>
			</div>
			<div class="col-75">
				<input type="text" name="PlansForRemoval" placeholder="Write something..." value="<?php echo $PlansForRemoval;?>">
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label>Update Method *</label>
			</div>
			<div class="col-75">
				<input type="radio" name="UpdateMethod" <?php if (isset($UpdateMethod) && $UpdateMethod=="Automatic") echo "checked";?> value="Automatic">Automatic
				<input type="radio" name="UpdateMethod" <?php if (isset($UpdateMethod) && $UpdateMethod=="Manual") echo "checked";?> value="Manual">Manual
				<input type="radio" name="UpdateMethod" <?php if (isset($UpdateMethod) && $UpdateMethod=="None") echo "checked";?> value="None">None  
				<span class="error"><?php echo $genderErr;?></span>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label>Update URL:</label>
			</div>
			<div class="col-75">
				<input type="text" name="UpdateURL" placeholder="Write something..." value="<?php echo $UpdateURL;?>">
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