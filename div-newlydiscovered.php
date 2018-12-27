<?php
//Newly Discovered Software - Referred by index.php

//get results from database
$result = mysqli_query($connection,"SELECT ID, Name, FOSS, ReasonForSoftware \"Usage\", FirstDiscovered \"First Discovered\" FROM discoveredapplications where FirstDiscovered > current_date()-7 order by FirstDiscovered DESC");
$all_property = array();  //declare an array for saving property

//showing property
echo '<table id="table2">
        <tr>';  //initialize table tag
while ($property = mysqli_fetch_field($result)) {
    echo '<th>' . $property->name . '</th>';  //get field name for header
    array_push($all_property, $property->name);  //save those to array
}
echo '</tr>
	'; //end tr tag

//showing all data
while ($row = mysqli_fetch_array($result)) {
    echo "<tr>";
    foreach ($all_property as $item) {
        echo '
	<td>' . $row[$item] . '</td>'; //get items using property value
    }
    echo '
	</tr>
	';
}
echo "</table>";
?>