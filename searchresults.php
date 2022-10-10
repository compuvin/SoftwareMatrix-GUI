<?php
//OmniBox search from main page or a traditional search
if (empty ($_POST["OmniBox"]))
{
	$WebAddr = $_POST["WebAddr"] . "?";

	if (!empty ($_POST["AppName"])) {if (strpos($WebAddr,"=",0) !== FALSE) {$WebAddr = $WebAddr . "&";}; $WebAddr = $WebAddr . "name=" . $_POST["AppName"];}
	if (!empty ($_POST["Computer"])) {if (strpos($WebAddr,"=",0) !== FALSE) {$WebAddr = $WebAddr . "&";}; $WebAddr = $WebAddr . "computer=" . $_POST["Computer"];}
	if (!empty ($_POST["Publisher"])) {if (strpos($WebAddr,"=",0) !== FALSE) {$WebAddr = $WebAddr . "&";}; $WebAddr = $WebAddr . "publisher=" . $_POST["Publisher"];}

	//Change "+" to hex value
	$WebAddr = str_replace("+","%2B",$WebAddr);

	//clean up if nothing was searched for
	if (substr($WebAddr,-1,1) == "?") {
		$WebAddr = substr($WebAddr,0,-1);
	}

	//return to previous page with results
	echo '<script type="text/javascript">window.location = "' . $WebAddr .'"</script>';
} else {
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
	
	//Application
	$result = mysqli_query($connection,"SELECT Name FROM discoveredapplications where Name like '%" . $_POST["OmniBox"] . "%' limit 1");
	if (mysqli_num_rows($result) == 1) {
		echo '<script type="text/javascript">window.location = "discoveredapps.php?name=' . str_replace("+","%2B",$_POST["OmniBox"]) . '"</script>';
	} else {
		
		//Publisher
		$result = mysqli_query($connection,"SELECT Publisher FROM applicationsdump where Publisher like '%" . $_POST["OmniBox"] . "%' limit 1");
		if (mysqli_num_rows($result) == 1) {
			echo '<script type="text/javascript">window.location = "allapps.php?publisher=' . $_POST["OmniBox"] . '"</script>';
		} else {
		
			//Computer
			$result = mysqli_query($connection,"SELECT Computer FROM applicationsdump where Computer like '%" . $_POST["OmniBox"] . "%' limit 1");
			if (mysqli_num_rows($result) == 1) {
				echo '<script type="text/javascript">window.location = "allapps.php?computer=' . $_POST["OmniBox"] . '"</script>';
			} else {
				//Nothing found
				echo '<script>alert("Your search criteria returned no results");</script>';
				echo '<script type="text/javascript">window.location = "' . $_POST["WebAddr"] . '"</script>';
			}
		}
	}
}
?>