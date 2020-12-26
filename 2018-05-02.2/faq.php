 <!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="includes/index/topnav.css">
<link rel="stylesheet" type="text/css" href="includes/index/sidenavleft.css">
<link rel="stylesheet" type="text/css" href="includes/faq/faq.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel">
<!--https://material.io/icons/#ic_chat_bubble-->
<title>ReadersVerdict Sverige</title>
</head>

</script>

<body>
<?php
require('includes/db_connect.php');
session_start();
if ($_GET["logout"] == "0")
{
	$_SESSION["loggedin"] = FALSE;
	$_SESSION["userid"] = "";
	$_SESSION["username"] = "";
	session_destroy();
}
?>
<input type="checkbox" id="toggle">
<input type="checkbox" id="toggle2">
<div class="p-head">
<label for="toggle" class="toggle"> </label>
<label for="toggle2" class="toggle2"><i class="material-icons">person_outline</i></label>
<?php
echo "<nav>";
$categorys = $conn->query("SELECT * FROM categorytbl");
echo "<ul id=\"topnav\">";

echo '<li id="topnav"><a href="/index.php?c=0&v=0&co=0">Hem</a></li>';

if($categorys->num_rows > 0)
{
	while($cRow = $categorys->fetch_assoc())
	{
		if($c == $cRow["categoryID"])
		{
			echo '<li id="topnav" class="activetopnav"><a href="/index.php?c='.$cRow["categoryID"].'&v=0&co=0">'.$cRow["categoryName"].'</a></li>';
		}
		else
		{
			echo '<li id="topnav"><a href="/index.php?c='.$cRow["categoryID"].'&v=0&co=0">'.$cRow["categoryName"].'</a></li>';
		}
	}
}
echo '<li id="topnav" class="activetopnav"><a href="/faq.php">F.A.Q.</a></li>';
echo "</ul>";
echo "</nav></div>";
?>
<?php
echo '<div id="startinfo"><p id="startinfotitel">Vad är ReadersVerdict?</p>
<p id="startinfotext">ReadersVerdict.com är en plattform där läsare kan lägga upp, ta ställning till, bedömma och kommentera svenska nyhets och tidningsartiklars trovärdighet på ett demokratiskt sätt.</p></div>';
echo '</ul>';
echo "<div id=\"load\">";
echo "<nav2>";
if($_SESSION["loggedin"] == FALSE)
{
	echo '<ul id="sidenav">';
	echo '<li id="sidenav"><p id="plogo">ReadersVerdict</p></li>';
	echo '<li id="sidenav"><a id="localpages" href="/faq.php">Om oss</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/register_user.php">Skapa en användare</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/login.php">Logga in</a></li>';
	//echo '<li id="sidenavinfo"><iframe src="/login.php" height="140" width="150" style="border:none;"></iframe>';
	echo '<li id="sidenav"><a id="localpages" href="/newpass.php">Glömt lösenord</a></li>';
	echo '<li id"sidenavsoc"><p id="navinfo3">Följ oss på :</p><a href="https://twitter.com/ReadersVerdict" class="fa fa-twitter"></a><a href="https://www.instagram.com/readersverdict" class="fa fa-instagram"></a><a href="https://soundcloud.com/user-534172594" class="fa fa-soundcloud"></a></li>';
	echo '</ul>';
}
else
{
	echo '<ul id="sidenav">';
	echo '<li id="sidenav"><p id="plogo">ReadersVerdict</p></li>';
	echo '<li id="sidenav"><a id="localpages" href="/post_ad.php">Lägg upp en annons</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/index.php?logout=0">Logga ut</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/change_pw.php">Byt lösenord</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/faq.php">Om oss</a></li>';
	//echo '<li id="sidenavinfo"><p id="navinfo1">Kul att du är med!</p><p id="navinfo2">Tack för att du är en medlem i communityn! Gör skillnad genom att lägga upp ett eget inlägg eller rösta på andras inlägg.</p></li>';
	echo '<li id"sidenavsoc"><p id="navinfo3">Följ oss på :</p><a href="https://twitter.com/ReadersVerdict" class="fa fa-twitter"></a><a href="https://www.instagram.com/readersverdict" class="fa fa-instagram"></a><a href="https://soundcloud.com/user-534172594" class="fa fa-soundcloud"></a></li>';
	echo '</ul>';
}
echo "</nav2>";

echo "<h2>Om oss</h2>";
echo '<article class="ac-container">';
				echo '<div id="questions">';
					echo '<input id="ac-11" name="accordion-1" type="checkbox">';
					echo '<label for="ac-11">Vilka är vi?</label>';
					echo '<section class="ac-small">';
					echo '<p class="inside">Vi är en liten grupp teknikintresserade som kände att det saknades ett bra och neutralt verktyg på internet för att lättare kunna bedömma artiklar från svenska tidningar och nyhetsförmedlare. </p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-12" name="accordion-1" type="checkbox">';
					echo '<label for="ac-12">Varför skapade vi sidan?</label>';
					echo '<section class="ac-large">';
						echo '<p class="inside">Därför att det inte fanns någon oberoende liknande tjänst. Vi vill skapa en teknisk plattform med viktiga grundpelare som: tillhandahålla en tekniskt bra lösning, vara helt neutrala, samla så lite information om användare som möjligt, inte dela information om användare med andra, hög säkerhet. </p>';
					echo '</section>';
			echo '</article>';









echo "<h2>Frågor och svar</h2>";

echo '<article class="ac-container">';
				echo '<div id="questions">';
					echo '<input id="ac-211" name="accordion-1" type="checkbox">';
					echo '<label for="ac-211">Vem har ansvar för innehållet?</label>';
					echo '<section class="ac-small">';
					echo '<p class="inside">ReadersVerdict.com tar inget ansvar för det innehåll som dess användare publicerar. Var och en som lägger upp artiklar och/eller kommentar på ReadersVerdict.com är själva helt och hållet ansvariga för det de själva skriver och publicerar. </p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-21" name="accordion-1" type="checkbox">';
					echo '<label for="ac-21">Hur lägger man upp en artikel?</label>';
					echo '<section class="ac-small">';
					echo '<p class="inside">Man hittar en artikel från en svensk nyhetssida och kopierar url:en till artikeln från webbläsaren. Sedan loggar man in på readersverdict.com och trycker på "lägg upp en artikel" i den vänstra menyn. </p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-22" name="accordion-1" type="checkbox">';
					echo '<label for="ac-22">Vad är cred?</label>';
					echo '<section class="ac-large">';
						echo '<p class="inside">Cred-poäng är ett mått på hur aktiv en användare är i communityn. Man får cred-poäng när man lägger upp, röstar eller kommenterar på en artikel. Man får poäng om någon röstar eller kommenterar på en artikel man postat och när någon annan röstar upp en kommentar man skrivit. Vi jobbar även på att antalet sanna/falska artiklar (röstade av communityn) som en användare har lagt upp ska visas brevid namnet för att på så sätt kunna avgöra en användares trovärdighet. </p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-23" name="accordion-1" type="checkbox">';
					echo '<label for="ac-23">Hur röstar/kommenterar man?</label>';
					echo '<section class="ac-small">';
						echo '<p class="inside">För att kunna rösta/kommentera måste man vara inloggad, om du ej har en användare så måste du skapa det annars är det bara att logga in.</p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-24" name="accordion-1" type="checkbox">';
					echo '<label for="ac-24">Vad är mest röstad/kommenterad?</label>';
					echo '<section class="ac-medium">';
						echo '<p class="inside">Under varje kategori-flik finns senast upplagt, flest röster och flest kommentarer. Senast upplagt är alla artiklar under fliken i kronologisk ordning efter uppläggningsdatum, flest röster/kommentar är sorterat efter inlägg med flest röster/kommentarer de senaste 24 timmarna.</p>';
					echo '</section>';
				echo '</div>';
			echo '<div id="questions">';
					echo '<input id="ac-25" name="accordion-1" type="checkbox">';
					echo '<label for="ac-25">Min artikel lades inte upp</label>';
					echo '<section class="ac-large">';
						echo '<p class="inside">Detta kan bero på flera saker. Antingen att tidningen inte fanns med i databasen och måste godkännas av oss som en svensk nyhetssajt. Att du inte har klickat på verifieringslänken i det mail som skickas till den e-mailadressen du angav när du gjorde din användare, kolla även skräppost inboxen på din e-mail. Till sist kan det vara att vi på ReadersVerdict beslutat att den sida din länk är ifrån inte räknas som en svensk nyhetssajt, har du motsättningar till detta beslut är du varmt välkommen att kontakta oss på team@readersverdict.com </p>';
					echo '</section>';
				echo '</div>';
			echo '</article>';
			
			
			
			
			
			
			
			
echo "<h2>Kontakt</h2>";

echo '<article class="ac-container">';
				echo '<div id="questions">';
					echo '<input id="ac-31" name="accordion-1" type="checkbox">';
					echo '<label for="ac-31">Jag kan inte logga in</label>';
					echo '<section class="ac-large">';
					echo '<p class="inside">Om du inte kommer ihåg ditt lösenord så tryck på "Glömt lösenord" i vänstra menyn. Har du nyss skapat din användare men kan inte logga in kan det bero på att du måste klicka på verifieringslänken som skickades till e-mailadressen du angav när du skapade din användare, kolla även skräppost! om du inte fått något mail, setill att du angav rätt emailadress när du skapade användare annars kontakta oss på team@readersverdict.com</p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-32" name="accordion-1" type="checkbox">';
					echo '<label for="ac-32">Jag har förslag på funktionalitet</label>';
					echo '<section class="ac-small">';
						echo '<p class="inside">Har du förslag på vad som kan förbättras med sidan, eller bara åsikter om något? Ta gärna kontakt med oss på team@readersverdict.com</p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-33" name="accordion-1" type="checkbox">';
					echo '<label for="ac-33">Varför togs min kommentar/artikel bort?</label>';
					echo '<section class="ac-small">';
						echo '<p class="inside">Vi på readersverdict är för ytraandefrihet och tar aldrig bort en kommentar/artikel pga av någons åsikt, men bryter inlägget mot sveriges lag, är helt off-topic eller uppenbar spam så behåller vi oss rätten att ta bort kommentaren/inlägget. </p>';
					echo '</section>';
				echo '</div>';
				echo '<div id="questions">';
					echo '<input id="ac-34" name="accordion-1" type="checkbox">';
					echo '<label for="ac-34">Donationer</label>';
					echo '<section class="ac-small">';
						echo '<p class="inside">Vi kommer snart att lägga upp upgifter för dig som tycker att vi gör någonting bra och vill stötta oss.</p>';
					echo '</section>';
				echo '</div>';
			echo '</article>';


echo "</div>";


?>

</body>
</html>
