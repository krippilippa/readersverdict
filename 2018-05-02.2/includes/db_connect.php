<?php
$servername = "localhost";
$username = "fakenews";
$password = "NQm5lrzvhSRgXfgY";
$dbname = "fakenewsdb";

//setup con
$conn = new mysqli($servername, $username, $password, $dbname);
$conn->set_charset("utf8");
//check con
if($conn->connect_error)
{
	die("the connection is fucked up: " . $conn->connect_error);
}
?>