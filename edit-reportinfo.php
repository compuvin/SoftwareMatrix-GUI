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
		echo '<script type="text/javascript">
           window.location = "reportslist.php"
		</script>';
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

<h2>Editing report: <?php echo $ReportName;?></h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"> 
  <input type="hidden" name="reportid" value="<?php echo $reportid;?>">
  Name:
  <input type="text" name="ReportName" value="<?php echo $ReportName;?>">
  <br><br>
  SQL Expression:<br>
  <textarea name="ReportSQL" rows="5" cols="40"><?php echo $ReportSQL;?></textarea>
  <br><br>
  Sort Order: <input type="text" name="SortOrder" value="<?php echo $SortOrder;?>">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
