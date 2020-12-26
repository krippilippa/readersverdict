<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="includes/index/login.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel">
<?php
include ("meta.php");
?>
<title>ReadersVerdict Sverige</title>
<style>
.error {color: #FF0000;}
</style>
<?php
require('includes/db_connect.php');

?>
</head>
<body>
<?php
$email_err = $password_err = "";
$email = $password = "";

$form_error = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(empty($_POST["email"]))
	{
		$email_err = "Måste fylla i";
		$form_error = TRUE;
	}
	else
	{
		$email = test_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
  			$email_err = "Fel format ";
  			$form_error = TRUE;
		}
		else
		{
			$sql = "SELECT usertbl.userPW, usertbl.userLevel FROM usertbl WHERE usertbl.userEmail = '".$email."'";
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
					$email_err = "Kolla din ";
					$form_error = TRUE;
				}
			}
			else
			{
				$email_err = "Fel lösen/";
				$form_error = TRUE;
			}
		}
	}
	
	if(empty($_POST["password"]))
	{
		$password_err = "Krävs";
		$form_error = TRUE;
	}
	else
	{
		$password = test_input($_POST["password"]);
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
<h2>Logga in</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

	<span class="error"> <?php echo $email_err;?></span>
	<p>E-mail</p><input type="text" name="email" value="">
	<br>
	
	<span class="error"> <?php echo $password_err;?></span>
	<p>Lösenord</p><input type ="password" name="password" value="">
	<br>
	
	<input type="submit" id="submit" name="submit" value="logga in">
	<br>
</form>
<a href="/newpass.php">Glömt lösenord</a>
<a href="/register_user.php">Skapa användare</a>
<?php

if ($form_error == FALSE)
{
	if(password_verify ($password, $password_hash_db))
	{
		$sql = "SELECT usertbl.userID, usertbl.userNick FROM usertbl WHERE usertbl.userEmail = '".$email."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$temp = $row["userID"];
				$temp2 = $row["userNick"];
			}
		}
		session_start();
		$_SESSION["loggedin"] = TRUE;
		$_SESSION["userid"] = $temp;
		$_SESSION["username"] = $temp2;
		//print_r($_SESSION);
		//echo '<script language="javascript">window.top.location.reload();</script>';
		echo '<script language="javascript">window.location.href = "/index.php"</script>';
	}
	else
	{
		echo "wrong password or email";
	}
	$conn->close();
}

?>
</body>
</html>


