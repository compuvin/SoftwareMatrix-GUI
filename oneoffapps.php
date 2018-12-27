<html>
<head>
<head>
	<link rel=stylesheet href="css/ipage.css" type="text/css"> <!--Interior Pages-->
	<link rel=stylesheet href="css/table.css" type="text/css"> <!--Tables-->
</head>
</head>
<body>

<?php
require_once('login.php');

//create connection
$connection = mysqli_connect($host, $user, $pass, $db_name);

//test if connection failed
if(mysqli_connect_errno()){
    die("connection failed: "
        . mysqli_connect_error()
        . " (" . mysqli_connect_errno()
        . ")");
}

//get results from database
$result = mysqli_query($connection,"SELECT Name, Publisher, Version, FirstDiscovered, LastDiscovered, count(Name) Count FROM software_matrix.applicationsdump group by Name having count < 5 order by count, Name;");
$all_property = array();  //declare an array for saving property

//showing property
echo '<table id="table3">
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

</body>
</html>