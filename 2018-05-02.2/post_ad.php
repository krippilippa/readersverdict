<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="includes/uploadpost/uploadpost.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel">

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
if($_SESSION["loggedin"] == FALSE)
{
	echo '<script language="javascript">window.location.href = "/login.php"</script>';
}
?>

<?php
$title_err = $desc_err = $url_err = /*$writer_err = */$category_err = "";
$title = $desc = $url = /*$writer = */$category = "";

$form_error = FALSE;

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	
	if(empty($_POST["title"]))
	{
		$title_err = "Titel nödvändig";
		$form_error = TRUE;
	}
	else
	{
		$title = test_input($_POST["title"]);
		if((strlen($title)) > 100)
		{
			$title_err = "Titel för lång, max antal bokstäver är 100.";
			$form_error = TRUE;
		}
	}
	
	if(empty($_POST["desc"]))
	{
		$desc_err = "Din post behöver en förklaring";
		$form_error = TRUE;
	}
	else
	{
		//$desc = str_replace(array("\r\n", "\r", "\n"), "<br>", $_POST["desc"]);
		$desc = test_input($_POST["desc"]);
	}
	
	if(empty($_POST["url"]))
	{
		$url_err = "Länk till artikel krävs";
		$form_error = TRUE;
	}
	else
	{
		$url = test_input($_POST["url"]);
		if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url))
		{
			$url_err = "Ogiltig URL";
			$form_error = TRUE;
		}
	}
	
	/*if (empty($_POST["writer"]))	
	{
		$writer_err = "Ange artikelskribent";
		$form_error = TRUE;
	}
	else
	{
		$writer = test_input($_POST["writer"]);
	}*/
	
	if (empty($_POST["category"]))	
	{
		$category_err = "En kategori måste väljas";
		$form_error = TRUE;
	}
	else
	{
		$category = test_input($_POST["category"]);
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
echo "<h2>Hej ".$_SESSION["username"]."! Kul att du har hittat något du vill lägga upp!</h2>";
?>
<div id="all">
<div id="left">
<br>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype='multipart/form-data'>

	<label for="title">Din rubrik</label>
	<span class="error">* <?php echo $title_err;?></span><br>
	<input type="text" id="title" name="title" value="<?php echo $title;?>" placeholder="Rubrik.."></input>
	<br>
	
	<label for="desc">Varför du anser att artikeln är fake.</label>
	<span class="error">* <?php echo $desc_err;?></span><br>
	<textarea id="desc" name="desc" placeholder="Beskrivning.."><?php echo $desc;?></textarea>
	<br>
	
	<label for="url">Länken till artikeln</label>
	<span class="error">* <?php echo $url_err;?></span><br>
	<input type="text" id="url" name="url" value="<?php echo $url;?>" placeholder="Länk.."></input>
	<br>
	
	<!--Artikelskribent : <input type="text" name="writer" value="<?php echo $writer;?>">
	<span class="error">* <?php echo $writer_err;?></span>
	<br>-->
	<label for="cat">Kategori för artikeln</label>
	<span class="error">* <?php echo $category_err;?></span><br>
	
	<?php
	$sql = "SELECT * FROM categorytbl ORDER BY categoryName;";
	$result = $conn->query($sql);
	
	echo '<select id="cat" name="category">';
	echo '<option></option>';
	echo header('Content-Type: text/html; charset=UTF-8');
	
	
	if ($result->num_rows > 0)
	{
		
		while($row = $result->fetch_assoc())
		{
			
			if($row["categoryID"] == $_POST["category"])
			{
				
				echo "<option value=".$row["categoryID"]." selected>".$row["categoryName"]."</option>";
				
			}
			else
			{
				
				echo "<option value=".$row["categoryID"].">".$row["categoryName"]."</option>";
				
			}
		}
	}
	echo '</select>';
	?>
	<br>
	<input type="submit" id="button"name="submit" value="Lägg upp och låt läsarna bedömma">

</form>
</div>
<?php

if($form_error == FALSE)
{
	$userID = $_SESSION['userid'];
	$domain = parse_url($url, PHP_URL_HOST);
	$domain = preg_replace ("~^www\.~", "", $domain);
	$sourceresult = $conn->query("SELECT sourceID, sourceVer FROM sourcetbl WHERE sourcetbl.sourceName LIKE '$domain'");
	if($sourceresult->num_rows > 0)
	{
		//kollar att sourcen är godkänd.
		$source = $sourceresult->fetch_assoc();
		if($source["sourceVer"] == 1)
		{
			$sql = "INSERT INTO posttbl (userID_fk, sourceID_fk, categoryID_fk, postLink, postTitle, postDescription) VALUES ('".$userID."', '".$source["sourceID"]."', '".$category."', '".$url."', '".$title."', '".$desc."');";
			$conn->query($sql);
			//hämtar id på senaste inserted och packeterar det med massa skit så mailet skickas o kan veriferieras på nästa sida.
			$postID = $conn->insert_id;
			$c = $postID;
			$postID .= "kallejohanson";
			$postID .= $postID;
			//echo $postID;
			$e = $conn->query("SELECT usertbl.userEmail FROM usertbl WHERE usertbl.userID = ".$userID."");
			$email = $e->fetch_assoc();
			$userEmail = $email["userEmail"];
			$hash = password_hash($postID, PASSWORD_DEFAULT);
			//echo $hash;
			$url = "http://www.readersverdict.com/post_verify.php?a=$userID&b=$hash&c=$c";
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
			<p>Klicka på länken för att aktivera din post:</p>
			
			<br><br>
			<a href=\"".$url."\">Aktivera post</a>
			<br><br><br>
			
			<p>Team ReadersVerdict <br>- <i>Let the community decide</i></p>
			</body>
			</html>";
			$subject = "ACTIVATE POST";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <do_not_reply@readersverdict.com>';
			
			$k = mail($userEmail,$subject,$message,$headers);
	
			if($k == true)
			{
				echo '<script language="javascript">alert("Ett email med en verifieringslänk har skickats till '.$userEmail.'.")</script>';
				echo '<script language="javascript">window.location.href = "/index.php"</script>';
			}
			else
			{
				echo '<div id = "right">';
				echo "<br>Något gick fel, försök igen om ett tag eller maila team@readersverdict.com för hjälp";
				echo '</div>';
			}
		}
		else
		{
			echo '<div id = "right">';
			echo $domain." är en hemsida som vi på RedersVerdict inte klassar som en tidning därför kommer denna artikel ej att läggas upp. Anser du endå att denna hemsida bör klassas som en tidning är du varmt välkommen att maila oss på team@redersverdict.com och lägga fram dina argument till varför detta ska klassas som en tidning.";
			echo '</div>';
		}
		
	}
	else
	{
		$conn->query("INSERT INTO sourcetbl (sourceName) VALUES ('$domain');");
		$sourceresult = $conn->query("SELECT sourceID FROM sourcetbl WHERE sourcetbl.sourceName LIKE '$domain'");
		if($sourceresult->num_rows > 0)
		{
			$source = $sourceresult->fetch_assoc();
			$sql1 = "INSERT INTO posttbl (userID_fk, sourceID_fk, categoryID_fk, postLink, postTitle, postDescription, postVer) 
			VALUES ('".$userID."', '".$source["sourceID"]."', '".$category."', '".$url."', '".$title."', '".$desc."', '1');";
			$sql2 = "UPDATE usertbl SET userNrPost = userNrPost + 1, userCredPoints = userCredPoints + 50 WHERE userID = ".$userID.";";
			$sql3 = "UPDATE sourcetbl SET sourceVer = 1 WHERE sourceID = ".$source["sourceID"].";";
			
			$message = "LÄS ALLT I DETTA MAIL!!! <br><br>Ny tidning: ".$domain." med ID ".$userID." <br>länken från användaren är: ".$url."<br><br> om det är en tidning posta detta kommandon i databasen <br><br>".$sql1." ".$sql2." ".$sql3."<br><br> Om det inte är en tidning maila: ";
			
			$e = $conn->query("SELECT usertbl.userEmail FROM usertbl WHERE usertbl.userID = ".$userID."");
			$email = $e->fetch_assoc();
			$message .= $email["userEmail"]."<br> om varför detta inte räknas som en tidning enligt oss! Denna del är väldigt viktig att vi ger ett personligt och proffsigt svar för att värna om våra användare och inte visar oss dumma eller nådlåtande, detta är en person som tagit sig tid att leta på en artikel, tänkt, funderat och formulerat en lång och dryg motiviering. OM den inte postas måste det vara på bra grunder annars kommer den sprida dåligheter om oss och bli bitter!!!! SVARA PROFFSIGT OCH SÅ SNABBT SOM MÖJLIGT!!!!!";
		
			$subject = "NY TIDNING";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			$headers .= 'From: <new_source@readersverdict.com>';
			$email = "team@readersverdict.com";
			$k = mail($email,$subject,$message,$headers);
	
			if($k == true)
			{
				echo '<div id = "right">';
				echo "<br>Tyvärr hade vi inte denna hemsida i våran databas, vi kommer nu att verifiera att detta är en svnesk tidnings-sajt. Om det visar sig att vi inte klassar detta som en svensk tidnings-sajt kommer vi meddela dig så fort som möjligt annars laddas posten upp så fort vi har godkännt hemsidan i våran databas. Vi ber om ursäkt för detta och hoppas du kan ha överseende med att vi vill hålla communityn fri från trolls och spam. Med Vänliga Hälsningar ReadersVerdict.";
				echo '</div>';
			}
			else
			{
				echo '<div id = "right">';
				echo "<br>Något gick fel, försök igen om ett tag eller maila team@readersverdict.com för hjälp";
				echo '</div>';
			}

			
			//echo '<script language="javascript">window.location.href = "/index.php"</script>';
		}
		else
		{
			echo '<div id = "right">';
			echo "Det blev något fel... försök igen senare.";
			echo '</div>';
		}

	}
$conn->close();
}
else
{
	echo '<div id = "right">
	<p id="c">ReadersVerdict.com tar inget ansvar för det innehåll som dess användare publicerar. Var och en som lägger upp artiklar och/eller kommentar på ReadersVerdict.com är själva helt och hållet ansvariga för det de själva skriver och publicerar.</p>
	<p id="a">Här är lite tips för hur ett inlägg kan skrivas.</p>
	<p id="b">Rubrik</p>
	<p id="c">Skriv en titel som förklarar vad artikeln du hittat handlar om.</p>
	<p id="b">Beskrivning</p>
	<p id="c">Beskriv vad du anser är ifrågasättbart i artikeln och lägg fram argument till varför. Det är sällan en hel artikel är falsk därför måste du beskriva vad i arikeln du tycker är falskt så att de andra användarna lätt kan förstå vad du syftar på.</p> 
	<p id="b">Länken till artikeln</p>
	<p id="c">Kopiera hela länken till arikeln och klistra in den i länk-fältet. Se till att inte vara inloggad med en användare på tidningen när du gör detta då detta resulterar i att andra ej kan se den.</p> 
	<p id="b">Kategori</p>
	<p id="c">Välj en liknande kategori som artikeln är upplagd i från tidningens hemsida, är artikeln från "Nöje" på tidningen så välj nöje här med. Om samma kategori inte finns försök att välja en så lik kategori som möjligt</p>
	</div></div>'; 
}
?>
</body>
</html>

