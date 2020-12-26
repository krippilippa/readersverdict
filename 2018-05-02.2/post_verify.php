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
session_start();
?>
<?php
if(isset($_GET["a"]) && isset($_GET["b"]) && isset($_GET["c"]))
{
	$a = mysqli_real_escape_string($conn, $_GET["a"]);
	$b = mysqli_real_escape_string($conn, $_GET["b"]);
	$c = mysqli_real_escape_string($conn, $_GET["c"]);
	//kolla att det är rätt användare
	if($a == $_SESSION["userid"])
	{
		echo "user e samma";
		$postID = $c;
		$postID .= "kallejohanson";
		$postID .= $postID;
		//kolla att tokens överenstämmer
		if(password_verify ($postID, $b))
		{
			//kolla så att posten inte är verifierad redan
			$result = $conn->query("SELECT posttbl.postVer FROM posttbl WHERE posttbl.postID = $c");
			$row = $result->fetch_assoc();
			if($row["postVer"] == 0)
			{
				$conn->query("UPDATE usertbl, posttbl SET usertbl.userNrPost = usertbl.userNrPost + 1, usertbl.userCredPoints = usertbl.userCredPoints + 50, posttbl.postVer = 1 WHERE usertbl.userID = $a AND posttbl.postID = $c");
				echo '<script language="javascript">window.location.href = "/index.php"</script>';
			}
			else
			{
				echo '<script language="javascript">window.location.href = "/index.php"</script>';
			}
		}
		else
		{
			echo "fel token.";
		}
	}
	else
	{
		echo "fel användare för posten. Logga in i denna webbläsare och tryck på verifieringslänken igen.";
	}
	
}
else
{
	echo "kolla din mail.";
}
$conn->close;
?>

</body>
</html>

