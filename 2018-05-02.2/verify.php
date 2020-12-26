<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?php
include ("meta.php");
?>
<title>ReadersVerdict Sverige</title>
<style>
.error {color: #FF0000;}
</style>
</head>

<body>
<?php
require('includes/db_connect.php');
?>
<?php
if(isset($_GET["t"]) && isset($_GET["u"]))
{
	$t = mysqli_real_escape_string($conn, $_GET["t"]);
	$u = mysqli_real_escape_string($conn, $_GET["u"]);
	
	$token = $conn->query("SELECT usertbl.activeToken FROM usertbl WHERE usertbl.userID = $u");
	if($token->num_rows > 0)
	{
		$row = $token->fetch_assoc();
		if(strcmp($row["activeToken"],$t) == 0)
		{
			$conn->query("UPDATE usertbl SET userLevel = 1 WHERE userID = $u");
			echo "<a href=\"/login.php\">Klicka Här för att logga in</a>";
		}
		else
		{
			echo "Något gick fel, kontakta team@readersverdict.com så hjälper vi dig";
		}
	}
	else
	{
		echo "Något gick fel, kontakta team@readersverdict.com så hjälper vi dig";
	}
}
$conn->close;
?>

</body>
</html>

