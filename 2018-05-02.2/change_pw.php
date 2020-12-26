<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="includes/changepw/changepw.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel">
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
//Define variables and set to empty values.
$oldpass_err = $password_err = "";
$oldpass = $password1 = $password2 = "";

$form_error = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(empty($_POST["oldpass"]))
	{
		$oldpass_err = "Skriv ditt nuvarande lösenord här";
		$form_error = TRUE;
	}
	else
	{
		$oldpass = test_input($_POST["oldpass"]);
		$sql = "SELECT usertbl.userPW, usertbl.userLevel FROM usertbl WHERE usertbl.userID = '".$_SESSION["userid"]."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			$row = $result->fetch_assoc();
			if($row["userLevel"] != 0)
			{
				$password_hash_db = $row["userPW"];
			}
			else
			{
				$oldpass_err = "Du måste aktivera användaren, kolla efter ett mail från oss i din inbox(även skräp)";
				$form_error = TRUE;
			}
		}
		else
		{
			$oldpass_err = "Fel lösenord";
			$form_error = TRUE;
		}
	}

	
	if (empty($_POST["password1"]) || empty($_POST["password2"]))
	{
		$password_err = "Samma lösenord måste skrivas i båda fälten";
		$form_error = TRUE;
	}
	elseif (($_POST["password1"]) !== ($_POST["password2"]))
	{
		$password_err = "Lösenorden matchar inte";
		$form_error = TRUE;
	}
	else
	{
		$password1 = test_input($_POST["password1"]);
		$password2 = test_input($_POST["password2"]);
		if (strlen($password1) < 8 || strlen($password1) > 72)
		{
			$password_err = "Lösenordet måste vara mellan 8 och 72 tecken långt.";
			$form_error = TRUE;
		}
		else
		{
			$password1 = password_hash($password1, PASSWORD_DEFAULT);
		}
	}
}
else
{
	$form_error = TRUE;
}

function test_input($data)
{
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<div id="homebtn">
<a href="/index.php">ReadersVerdict</a>
</div>
<?php
echo "<h2>Tja ".$_SESSION["username"]."! Byt ditt lösenordet till något säkert och dela det aldrig med någon</h2>";
?>
<div id="all">
<div id="left">
<br>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for="oldpass">Nuvarande lösenord</label><span class="error">*<?php echo $oldpass_err;?></span>
	<input type ="password" name = "oldpass" value="">
	<br>
	
	<label for="password1">Nytt lösenord</label><span class="error">* <?php echo $password_err;?></span>
	<input type ="password" name="password1" value="">
	<br>
	
	<label for="password2">Nytt lösenord</label><span class="error">* <?php echo $password_err;?></span>
	<input type ="password" name="password2" value="">
	<br>
	<input type="submit" id="button" name="submit" value="Byt lösenord">
	<br>
</form>
</div>
</div>
<?php
?>

<?php
if($form_error == FALSE)
{
	if(password_verify ($oldpass, $password_hash_db))
	{
		$conn->query("UPDATE usertbl SET usertbl.userPW = '$password1' WHERE usertbl.userID = '".$_SESSION["userid"]."'");
		if($conn->affected_rows > 0)
		{
			echo '<script language="javascript">alert("Fett '.$_SESSION["username"].', nu har du ett nytt lösenord.")</script>';
			echo '<script language="javascript">window.location.href = "/index.php"</script>';
		}
		else
		{
			echo '<script language="javascript">alert("Oj något gick fel")</script>';
			echo '<script language="javascript">window.location.href = "/index.php"</script>';
		}
	}
	else
	{
		echo '<script language="javascript">alert("Du angav inte rätt lösenord.")</script>';
	}
	$conn->close();
}
?>

</body>
</html>
