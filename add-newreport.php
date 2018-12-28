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
		echo '<script type="text/javascript">
           window.location = "reportslist.php"
		</script>';
	} else {
		echo "An error has occurred. Please review your submission.";
	};
  
} else {
	
}  


function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Create new report</h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>"> 
  Name:
  <input type="text" name="ReportName" value="">
  <br><br>
  SQL Expression:<br>
  <textarea name="ReportSQL" rows="5" cols="40"></textarea>
  <br><br>
  Sort Order: <input type="text" name="SortOrder" value="10">
  <span class="error"><?php echo $websiteErr;?></span>
  <br><br>
  <input type="submit" name="submit" value="Submit">  
</form>
