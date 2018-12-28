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
	} else {
		echo "An error has occurred. Please review your submission.";
	};
  
} else {
	
	//Software ID from database
	$appid = $_GET["id"];
	
	//get results from database
	$result = mysqli_query($connection,"SELECT * FROM discoveredapplications where ID = $appid");
	//$all_property = array();  //declare an array for saving property

	//showing property
	//echo '<table id="table2">
	//		<tr>';  //initialize table tag
	//while ($property = mysqli_fetch_field($result)) {
	//	echo '<th>' . $property->name . '</th>';  //get field name for header
	//	array_push($all_property, $property->name);  //save those to array
	//}
	//echo '</tr>
	//	'; //end tr tag

	//showing all data
	while ($row = mysqli_fetch_array($result)) {
		//echo "<tr>";
		//foreach ($all_property as $item) {
		//	echo '
		//<td>' . $row[$item] . '</td>'; //get items using property value
		//}
		//echo '
		//</tr>
		//';
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

<h2>Editing: <?php echo $Name;?></h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"> 
  ID: <b><?php echo $appid;?></b>
  <input type="hidden" name="appid" value="<?php echo $appid;?>">
  <br>
  Name: <b><?php echo $Name;?></b>
  <input type="hidden" name="Name" value="<?php echo $Name;?>">
  <br>
  Free:
  <input type="radio" name="Free" <?php if (isset($Free) && $Free=="Y") echo "checked";?> value="Y">Yes
  <input type="radio" name="Free" <?php if (isset($Free) && $Free=="N") echo "checked";?> value="N">No
  <br>
  Open Source:
  <input type="radio" name="OpenSource" <?php if (isset($OpenSource) && $OpenSource=="Y") echo "checked";?> value="Y">Yes
  <input type="radio" name="OpenSource" <?php if (isset($OpenSource) && $OpenSource=="N") echo "checked";?> value="N">No
  <br><br>
  Description of Software use:<br>
  <textarea name="ReasonForSoftware" rows="5" cols="40"><?php echo $ReasonForSoftware;?></textarea>
  <br><br>
  Who Needs the Software (list devices or departments):<br>
  <textarea name="NeededOnMachines" rows="5" cols="40"><?php echo $NeededOnMachines;?></textarea>
  <br><br>
  Plans for Removal: <input type="text" name="PlansForRemoval" value="<?php echo $PlansForRemoval;?>">
  <br><br>
  Update Method:
  <input type="radio" name="UpdateMethod" <?php if (isset($UpdateMethod) && $UpdateMethod=="Automatic") echo "checked";?> value="Automatic">Automatic
  <input type="radio" name="UpdateMethod" <?php if (isset($UpdateMethod) && $UpdateMethod=="Manual") echo "checked";?> value="Manual">Manual
  <input type="radio" name="UpdateMethod" <?php if (isset($UpdateMethod) && $UpdateMethod=="None") echo "checked";?> value="None">None  
  <span class="error">* <?php echo $genderErr;?></span>
  <br><br>
  Update URL: <input type="text" name="UpdateURL" value="<?php echo $UpdateURL;?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
