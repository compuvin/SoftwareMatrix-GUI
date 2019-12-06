<?php
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
?>