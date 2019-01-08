<?php
$WebAddr = $_POST["WebAddr"] . "?";

if (!empty ($_POST["AppName"])) {$WebAddr = $WebAddr . "name=" . $_POST["AppName"];}
if (!empty ($_POST["Computer"])) {$WebAddr = $WebAddr . "computer=" . $_POST["Computer"];}
if (!empty ($_POST["Publisher"])) {$WebAddr = $WebAddr . "publisher=" . $_POST["Publisher"];}

//clean up if nothing was searched for
if (substr($WebAddr,-1,1) == "?") {
	$WebAddr = substr($WebAddr,0,-1);
}

//return to previous page with results
echo '<script type="text/javascript">window.location = "' . $WebAddr .'"</script>';
?>