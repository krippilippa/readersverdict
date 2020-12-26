<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="includes/registeruser/registeruser.css">
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
?>
<?php
//Define variables and set to empty values.
$nick_name_err = $email_err = $password_err = $sex_err = "";
$nick_name = $email = $password1 = $password2 = $sex = "";

$form_error = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	//Checks that input is filled from user.
	if (empty($_POST["nick_name"]))
	{
		$nick_name_err = "Användarnamn är obligatoriskt";
		$form_error = TRUE;
	}
	else
	{
		$nick_name = test_input($_POST["nick_name"]);
		//Checks that its only letter not #€%/(= etc.
		if (!preg_match("/^[a-zA-Z_0-9]*$/", $nick_name))
		{
			$nick_name_err = "Bara a-z, 0-9 och \"_\" tillåtna";
			$form_error = TRUE;
		}
		else
		{
			$sql = "SELECT usertbl.userNick FROM usertbl WHERE usertbl.userNick = '".$nick_name."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0)
			{
				$nick_name_err = "Användarnamnet är upptaget"; 
  				$form_error = TRUE;
			}
		}
	}
	
	if (empty($_POST["email"]))
	{
		$email_err = "E-mail är ett krav";
		$form_error = TRUE;
	}
	else
	{
		$email = test_input($_POST["email"]);
		//Checks that its a valid email.
		if (!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
  			$email_err = "Inte en e-mailadress."; 
  			$form_error = TRUE;
		}
		else
		{
			$sql = "SELECT usertbl.userEmail FROM usertbl WHERE usertbl.userEmail = '".$email."'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0)
			{
				$email_err = "Det finns redan en e-mailadress som denna."; 
  				$form_error = TRUE;
			}
		}
	}
	
	if (empty($_POST["sex"]))
	{
		$sex_err = "Obligatoriskt";
		$form_error = TRUE;
	}
	else
	{
		$sex = test_input($_POST["sex"]);
	}
		
	if (empty($_POST["password1"]) || empty($_POST["password2"]))
	{
		$password_err = "Lösenorden stämmer inte överrens";
		$form_error = TRUE;
	}
	elseif (($_POST["password1"]) !== ($_POST["password2"]))
	{
		$password_err = "Lösenorden stämmer inte överrens";
		$form_error = TRUE;
	}
	else
	{
		$password1 = test_input($_POST["password1"]);
		$password2 = test_input($_POST["password2"]);
		if (strlen($password1) < 8 || strlen($password1) > 72)
		{
			$password_err = "Lösenordet måste vara mellan 8 och 72 tecken långt";
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
<div id="all">
<div id="left">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	
	<label for="nick_name" id="nick">Användarnamn</label> <span class="error">* <?php echo $nick_name_err;?></span>
	<input type="text" name="nick_name" id="nick" value="<?php echo $nick_name;?>" placeholder="Användarnamn..">
	<br>
	
	<label for="email">Email</label> <span class="error">* <?php echo $email_err;?></span><br>
	<input type="text" name="email" id="email"  value="<?php echo $email;?>" placeholder="Email..">
	<br>
	<span class="error">* <?php echo $sex_err;?></span><br>
	<input type="radio" name="sex" id="sex"  <?php if (isset($sex) && $sex=="2") echo "checked";?> value="2"><label id="sex" for="sex">Man</label><br>

	<input type="radio" name="sex" id="sex"  <?php if (isset($sex) && $sex=="1") echo "checked";?> value="1"><label id="sex" for="sex">Kvinna</label><br>
	<br>
		
	<label for="password">Lösenord</label> <span class="error">* <?php echo $password_err;?></span>
	<input type ="password" name="password1" id="pass"  value="" placeholder="lösenord..">
	<br>
	<label for="password">Lösenord</label> <span class="error">* <?php echo $password_err;?></span>
	<input type ="password" name="password2" id="pass"  value="" placeholder="lösenord..">
	<br>
	<input type="submit" id="button" name="submit" value="Bli medlem">
	<br>
</form>
</div>
</div>
<?php
/*echo "<h2>Your Input:</h2>";
echo $nick_name;
echo "<br>";
echo $email;
echo "<br>";
echo $password1;
echo "<br>";
echo $sex;
echo "<br>";
echo $form_error;*/
?>

<?php
if($form_error == FALSE)
{
	$token = bin2hex(openssl_random_pseudo_bytes(8));
	
	$sql="INSERT INTO usertbl (userEmail, userNick, userSex, userPW, activeToken) VALUES ('".$email."','".$nick_name."','".$sex."','".$password1."','".$token."');";
	$conn->query($sql);
	
	$result = $conn->query("SELECT usertbl.userID FROM usertbl WHERE userEmail LIKE '$email'");
	if($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		$userID = $row["userID"];
		$url = "http://www.readersverdict.com/verify.php?t=$token&u=$userID";
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
		<p>Lägg upp din första artikel, rösta på din första artikel, kommentera på din första artikel.<br> Tryck på länken för att verifiera ditt konto.</p>
		
		<br><br>
		<a href=\"".$url."\">Klicka här för att verifiera<a>
		<br><br><br>
		
		<p>Team ReadersVerdict är glada att du vill bli med i communityn<br>- <i>Let the community decide</i></p>
		</body>
		</html>";
		$subject = "VERIFIERINGS-LÄNK";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$headers .= 'From: <do_not_reply@readersverdict.com>';
		
		$k = mail($email,$subject,$message,$headers);

		if($k == true)
		{
			echo '<script language="javascript">alert("en verifieringslänk har skickats till den angivna e-mailadressen, klicka på den för att verifiera ditt konto.(kolla skräpmail))")</script>';
			echo '<script language="javascript">window.location.href = "/index.php"</script>';
		}
		else
		{
			echo '<script language="javascript">alert("Något gick fel, försök igen om ett tag eller maila team@readersverdict.com")</script>';
		}
	}
	else
	{
		echo '<script language="javascript">alert("Något gick fel, försök igen om ett tag eller maila team@readersverdict.com")</script>';
	}
	$conn->close();
}
?>

</body>
</html>
