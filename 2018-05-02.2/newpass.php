<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
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
session_start();
?>
</head>
<body>
<?php
$email_err = "";
$email = "";

$form_error = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	if(empty($_POST["email"]))
	{
		$email_err = "Fyll i din emailadress";
		$form_error = TRUE;
	}
	else
	{
		$email = test_input($_POST["email"]);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
  			$email_err = "inte en emailadress";
  			$form_error = TRUE;
		}
		else
		{
			$sql = "SELECT usertbl.userID, usertbl.userEmail FROM usertbl WHERE usertbl.userEmail = '".$email."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					$userID = $row["userID"];
					$userEmail = $row["userEmail"];
				}
			}
			else
			{
				$email_err = "Emailadressen finns inte, försök med en annan eller skapa en användare med denna email.";
				$form_error = TRUE;
			}
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
<h2>Få nytt lösenord</h2>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<span class="error"> <?php echo $email_err;?></span>
	<p>E-mail</p><input type="text" name="email" value="">
	<br>
		
	<input type="submit" name="submit" value="Få nytt lösenord">
	<br>
</form>

<?php

if($form_error == FALSE)
{
	//skapa nytt lösen och hasha det.
	$pwd = bin2hex(openssl_random_pseudo_bytes(8));
	$hash = password_hash($pwd, PASSWORD_DEFAULT);
	
	$conn->query("UPDATE usertbl SET usertbl.userPW = '$hash' WHERE usertbl.userID = $userID");
	
	if($conn->affected_rows > 0)
	{
		$message = 
		"<html>
		<head>
		<style>
		div.container {
		    width: 100%;
		    border: 1px solid gray;
		}
		
		header, footer {
		    padding: 1em;
		    color: black;
		    background-color: #E7E7E7;
		    clear: left;
		    text-align: center;
		}
		
		</style>
		</head>
		<body>
		
		<div class=\"container\">
		
		<header>
		   <h1>ReadersVerdict</h1>
		</header>
		<p>Ditt nya lösenord:</p>
		
		<br><br>
		<p>".$pwd."</p>
		<br><br><br>
		
		<p>Team ReadersVerdict <br>- <i>Let the community decide</i></p>
		</body>
		</html>";
		$subject = "NEW PASSWORD";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <do_not_reply@readersverdict.com>';
		
		$k = mail($userEmail,$subject,$message,$headers);

		echo $k;
		if($k == true)
		{
			echo '<script language="javascript">alert("Ett nytt lösenord har skickats till '.$userEmail.'.")</script>';
			echo '<script language="javascript">window.location.href = "/index.php"</script>';
		}
		else
		{
			echo '<script language="javascript">alert("Något gick fel, försök igen om ett tag eller maila team@readersverdict.com för hjälp")</script>';
			echo '<script language="javascript">window.location.href = "/index.php"</script>';
		}

		
	}
	else
	{
		echo '<script language="javascript">alert("Något gick fel, försök igen om ett tag eller maila team@readersverdict.com för hjälp")</script>';
		echo '<script language="javascript">window.location.href = "/index.php"</script>';
	}
	$conn->close();
}
?>

</body>
</html>


