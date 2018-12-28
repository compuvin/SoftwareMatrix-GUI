<?php
//Software Needing Review - Referred by index.php

//get results from database
$result = mysqli_query($connection,"SELECT ID, Name, FOSS, (select Publisher from software_matrix.applicationsdump where discoveredapplications.Name = applicationsdump.Name LIMIT 1) Publisher, FirstDiscovered \"First Discovered\" FROM discoveredapplications where ReasonForSoftware = '' or ReasonForSoftware is null order by FirstDiscovered DESC");
$all_property = array();  //declare an array for saving property

//showing property
echo '<table id="table1">'; //initialize table tag
echo "\r\n<tr>";
while ($property = mysqli_fetch_field($result)) {
    echo "\r\n\t<th>" . $property->name . "</th>";  //get field name for header
    array_push($all_property, $property->name);  //save those to array
}
echo "\r\n</tr>\r\n"; //end tr tag

//showing all data
while ($row = mysqli_fetch_array($result)) {
    echo "\r\n<tr>";
    foreach ($all_property as $item) {
        if ($item == "ID") {
			echo "\r\n\t<td><a href=\"edit-appinfo.php?id=" . $row[$item] . "\">" . $row[$item] . "</a></td>"; //Make the ID clickable
		} else {
			echo "\r\n\t<td>" . $row[$item] . "</td>"; //get items using property value
		}
    }
    echo "\r\n</tr>\r\n"; //end tr tag
}
echo "</table>";
?>