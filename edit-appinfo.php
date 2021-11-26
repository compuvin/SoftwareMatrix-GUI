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
	
	<script>
		function showlicensedapps(freeyn)
		{
			if (freeyn == "N")
			{
				document.getElementById("LicensedApp").style.display = "block";
			}
			else
			{
				document.getElementById("LicensedApp").style.display = "none";
				document.getElementById("LicenseAmt").value = "";
				document.getElementById("LicenseComments").value = "";
			}
		}
	</script>
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
$VersionStr = "";
$LicenseAmt = $LicenseComments = ""; //Initialize license variables

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
		if (!empty($_POST["LicenseAmt"])) {
			$LicenseAmt = $_POST["LicenseAmt"];
			$LicenseComments = $_POST["LicenseComments"];
			//$result = mysqli_query($connection,"select Publisher from software_matrix.applicationsdump where applicationsdump.Name = '$Name' LIMIT 1");
			$Publisher = mysqli_fetch_assoc(mysqli_query($connection,"select Publisher from applicationsdump where Name = '$Name' LIMIT 1"))['Publisher'];
			$result = mysqli_query($connection,"INSERT INTO licensedapps SET Name = '$Name', Publisher = '$Publisher', Amount = '$LicenseAmt', Comments = '$LicenseComments' ON DUPLICATE KEY UPDATE Amount = '$LicenseAmt', Comments = '$LicenseComments'"); //Add or Update entry
		}
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
		$Version_Oldest = $row["Version_Oldest"];
		$Version_Newest = $row["Version_Newest"];
		$Free = $row["Free"];
		$OpenSource = $row["OpenSource"];
		$ReasonForSoftware = $row["ReasonForSoftware"];
		$NeededOnMachines = $row["NeededOnMachines"];
		$PlansForRemoval = $row["PlansForRemoval"];
		$UpdateMethod = $row["Update Method"];
		$UpdateURL = $row["UpdateURL"];
	}
	if ($Version_Oldest == $Version_Newest) {
		$VersionStr = $Version_Newest;
	} else {
		$VersionStr = $Version_Newest . ' (oldest: ' . $Version_Oldest . ')';
	}
	
	//Check to see if there is license information entered
	$result = mysqli_query($connection,"SELECT * FROM licensedapps where Name = '$Name' limit 1");

	//showing all data
	while ($row = mysqli_fetch_array($result)) {
		$LicenseAmt = $row["Amount"];
		$LicenseComments = $row["Comments"];
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
		<h2>Editing: <?php echo $Name;?></h2>
		ID: <b><?php echo $appid;?></b>
		<br>
		Version: <b><?php echo $VersionStr;?></b>
		<input type="hidden" name="appid" value="<?php echo $appid;?>">
		<input type="hidden" name="Name" value="<?php echo $Name;?>">
		<input type="hidden" name="WebAddr" value="<?php if (!empty($_SERVER["HTTP_REFERER"])) {echo htmlspecialchars($_SERVER["HTTP_REFERER"]);}?>">
	</div>
	
	<div class="container2">
		<div class="row">
			<div class="col-25">
				Free *
			</div>
			<div class="col-75">
				<input type="radio" name="Free" <?php if (isset($Free) && $Free=="Y") echo "checked";?> required value="Y" onclick="showlicensedapps('Y')">Yes
				<input type="radio" name="Free" <?php if (isset($Free) && $Free=="N") echo "checked";?> required value="N" onclick="showlicensedapps('N')">No
			</div>
		</div>
		
		<div class="row">
			<div class="col-25">
				Open Source *
			</div>
			<div class="col-75">
				<input type="radio" name="OpenSource" <?php if (isset($OpenSource) && $OpenSource=="Y") echo "checked";?> required value="Y">Yes
				<input type="radio" name="OpenSource" <?php if (isset($OpenSource) && $OpenSource=="N") echo "checked";?> required value="N">No
			</div>
		</div>
	</div>
	
	<div id="LicensedApp" class="container2" <?php if (isset($Free) && $Free=="Y") echo "style=\"display:none\"";?>>
		<div class="row">
			<div class="col-25">
				<label>Number of licenses</label>
			</div>
			<div class="col-75">
				<input type="text" name="LicenseAmt" id="LicenseAmt" placeholder="Numerical values only!" value="<?php echo $LicenseAmt;?>">
			</div>
		</div>
		
		<div class="row">
			<div class="col-25">
				<label>Purchased from/comments</label>
			</div>
			<div class="col-75">
				<input type="text" name="LicenseComments" id="LicenseComments" placeholder="(Optional)" value="<?php echo $LicenseComments;?>">
			</div>
		</div>
	</div>

	<div class="container3">
		<div class="row">
			<div class="col-25">
				<label>Description of Software Use *</label>
			</div>
			<div class="col-75">
				<textarea name="ReasonForSoftware" id= "ReasonForSoftware" rows="5" cols="40" placeholder="Write something..." required><?php echo $ReasonForSoftware;?></textarea>
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
				<select id="update" name="UpdateMethod" required>
					<option></option>
					<option <?php if (isset($UpdateMethod) && $UpdateMethod=="Automatic") echo "selected";?> value="Automatic">Automatic</option>
					<option <?php if (isset($UpdateMethod) && $UpdateMethod=="Manual") echo "selected";?> value="Manual">Manual</option>
					<option <?php if (isset($UpdateMethod) && $UpdateMethod=="None") echo "selected";?> value="None">None</option>
				</select>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label>Update URL</label>
			</div>
			<div class="col-75">
				<input type="text" name="UpdateURL" id="UpdateURL" placeholder="Write something..." value="<?php echo $UpdateURL;?>">
				<span class="error"><?php echo $websiteErr;?></span>
			</div>
		</div>

		<div class="row">
			<div class="col-25">
				<label><span class="error">* Required Field</span></label>
			</div>
			<div class="col-75">
				<input type="button" value="Back" onclick="history.back()">
				<input type="submit" name="submit" value="Submit">
			</div>
		</div>
	</div>
</form>

</body>
</html>