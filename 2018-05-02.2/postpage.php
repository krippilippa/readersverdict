 <!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="includes/postpage/topnav.css">
<link rel="stylesheet" type="text/css" href="includes/postpage/sidenavleft.css">
<link rel="stylesheet" type="text/css" href="includes/postpage/postpage.css">
<link rel="stylesheet" type="text/css" href="includes/postpage/feed.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel">
<title>ReadersVerdict Sverige</title>
</head>

<script>

var cn = 10;

function loadcom(postID, comnr)
{
	if(window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp = new ActiveXObjext("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			temp = document.getElementById("comdemo").innerHTML;
			document.getElementById("comdemo").innerHTML = this.responseText + temp;
		}
	};
	xmlhttp.open("GET","loadcommentscript.php?p="+postID+"&cn="+cn+"&comnr="+comnr,true);
	xmlhttp.send();
	cn = cn + 10;
}



function comvote(comID, userID, updown, sessionID, commentCounter)
{
	if(window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp = new ActiveXObjext("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			temp = this.responseText;
			if (temp == 0)
			{
				alert("Logga in för att rösta");
			}
			else if(temp == 1)
			{
				alert("Du har redan röstat på denna kommentar");
			}
			else
			{
				document.getElementById("comvote"+commentCounter).innerHTML = this.responseText;
			}
		}
	};
	xmlhttp.open("GET","comvotescript.php?c="+comID+"&u="+userID+"&v="+updown+"&s="+sessionID,true);
	xmlhttp.send();
}

function comment(userID, postID, sessionID)
{
	//alert('funktionen har startat');
	if(window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp = new ActiveXObjext("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			temp = this.responseText;
			if (temp == 0)
			{
				alert("Logga in för att kommentera");
			}
			else
			{
				document.getElementById("comdemo").innerHTML = this.responseText;
			}
		}
	};
	xmlhttp.open("POST","commentscript.php?u="+userID+"&p="+postID+"&s="+sessionID, true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("c="+document.getElementById("com").value);
}

function vote(tf, userID, postID, sessionID, counter)
{
	if(window.XMLHttpRequest)
	{
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else
	{
		// code for IE6, IE5
		xmlhttp = new ActiveXObjext("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			temp = this.responseText;
			if (temp == 0)
			{
				alert("Logga in för att rösta");
			}
			else
			{
				document.getElementById("votedemo"+counter).innerHTML = this.responseText;
			}
		}
	};
	xmlhttp.open("GET","votescript.php?v="+tf+"&u="+userID+"&p="+postID+"&s="+sessionID+"&c="+counter,true);
	xmlhttp.send();
}


</script>
<?php
function timeSince($time)
{
	$time = strtotime($time);
	$time = time() - $time; // to get the time since that moment
	
	$tokens = array (
	31536000 => 'year',
	2592000 => 'month',
	604800 => 'week',
	86400 => 'day',
	3600 => 'hour',
	60 => 'minute',
	1 => 'second'
	);
	
	foreach ($tokens as $unit => $text)
	{
		if ($time < $unit) continue;
		$numberOfUnits = floor($time / $unit);
		return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	}
}

?>
<body>
<?php
require('includes/db_connect.php');
session_start();

$c = 0;
if(isset($_GET["c"]));
{
	$c = mysqli_real_escape_string($conn, $_GET["c"]);
}
?>
<input type="checkbox" id="toggle">
<input type="checkbox" id="toggle2">
<div class="p-head">
<label for="toggle" class="toggle"> </label>
<label for="toggle2" class="toggle2"><i id="personlogo" class="material-icons">person_outline</i></label>
<?php
echo "<nav>";

$categorys = $conn->query("SELECT * FROM categorytbl");
echo "<ul id=\"topnav\">";
if($c == 0)
{
	echo '<li id="topnav"><a href="/index.php?c=0&v=0&co=0" class="activetopnav">Hem</a></li>';
}
else
{
	echo '<li id="topnav"><a href="/index.php?c=0&v=0&co=0">Hem</a></li>';
}

if($categorys->num_rows > 0)
{
	while($cRow = $categorys->fetch_assoc())
	{
		if($c == $cRow["categoryID"])
		{
			echo '<li id="topnav"><a href="/index.php?c='.$cRow["categoryID"].'&v=0&co=0" class="activetopnav">'.$cRow["categoryName"].'</a></li>';
		}
		else
		{
			echo '<li id="topnav"><a href="/index.php?c='.$cRow["categoryID"].'&v=0&co=0">'.$cRow["categoryName"].'</a></li>';
		}
	}
}
echo '<li id="topnav"><a href="/faq.php">F.A.Q.</a></li>';
echo "</ul>";
echo "</nav></div>";




if ($_GET["logout"] == "0")
{
	$_SESSION["loggedin"] = FALSE;
	$_SESSION["userid"] = "";
	$_SESSION["username"] = "";
	session_destroy();
}
?>

<?php

echo '<div id="feed">';
echo "<nav2>";
if($_SESSION["loggedin"] == FALSE)
{
	echo '<ul id="sidenav">';
	echo '<li id="sidenav"><p id="plogo">ReadersVerdict</p></li>';
	echo '<li id="sidenav"><a id="localpages" href="/faq.php">Om oss</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/register_user.php">Skapa en användare</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/login.php">Logga in</a></li>';
	//echo '<li id="sidenavinfo"><iframe src="/login.php" height="140" width="150" style="border:none;"></iframe>';
	echo '<li id="sidenav"><a id="localpages" href="/newpass.php">Glömt lösenord</a></li>';	echo '<li id"sidenavsoc"><p id="navinfo3">Följ oss på :</p><a href="https://twitter.com/ReadersVerdict" class="fa fa-twitter"></a><a href="https://www.instagram.com/readersverdict" class="fa fa-instagram"></a><a href="https://soundcloud.com/user-534172594" class="fa fa-soundcloud"></a></li>';
	echo '</ul>';
}
else
{
	echo '<ul id="sidenav">';
	echo '<li id="sidenav"><p id="plogo">ReadersVerdict</p></li>';
	echo '<li id="sidenav"><a id="localpages" href="/post_ad.php">Lägg upp en artikel</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/index.php?logout=0">Logga ut</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/change_pw.php">Byt lösenord</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/faq.php">Om oss</a></li>';
	//echo '<li id="sidenavinfo"><p id="navinfo1">Vad gör vi?</p><p id="navinfo2">Träutensilierna i ett tryckeri äro ingalunda en faktor där trevnadens ordningens och ekonomiens upprätthållande, och dock är det icke sällan som sorgliga erfarenheter göras ordningens och ekon och miens därmed upprätthållande. Träutensilierna i ett tryckeri äro ingalunda en oviktig faktor.</p></li>';
	echo '<li id"sidenavsoc"><p id="navinfo3">Följ oss på :</p><a href="https://twitter.com/ReadersVerdict" class="fa fa-twitter"></a><a href="https://www.instagram.com/readersverdict" class="fa fa-instagram"></a><a href="https://soundcloud.com/user-534172594" class="fa fa-soundcloud"></a></li>';
	echo '</ul>';
}
echo "</nav2>";
$p = mysqli_real_escape_string($conn, $_GET["p"]);

$result =	$conn->query("SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName FROM 
			posttbl, usertbl, sourcetbl, categorytbl WHERE posttbl.postID = $p AND posttbl.userID_fk = usertbl.userID 
			AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID");
			
if ($result->num_rows > 0)
{
	$row = $result->fetch_assoc();
	echo "<div id=\"article\">";
			//echo "<hr>";
			//echo $counter;
			$postID = $row["postID"];
			$userID = $row["userID_fk"];
			$nrVotes = $row["nrVotes"];
			/*$sourceID = $row["sourceID_fk"];
			$categoryID = $row["categoryID_fk"];
			$writerID = $row["writerID_fk"];
			$postLink = $row["postLink"];
			$postTitle = $row["postTitle"];
			$postDescription = $row["postDescription"];
			$postDate = $row["postDate"];
			$nrChange = $row["nrChange"];
			$changeDate = $row["changeDate"];
			$nrComments = $row["nrComments"];*/
	$comnr = $row["nrComments"] - 10;
	echo "<div id=\"posttop\"><p id=\"name1\">".$row["sourceName"]."</p><p id=\"name2\">".$row["categoryName"]."</p><i class=\"material-icons\">chat_bubble</i><p id=\"name3\"> ".$row["nrComments"]."</p><p id=\"name4\"> ".timeSince($row["postDate"])." ago</p></div>";
	echo "<h2><p id=\"posttitle\">".$row["postTitle"]."</p></h2>";
	echo "<a id=\"articlelink\" href=".$row["postLink"]." target=\"_blank\">Läs ursprungsartikeln från ".$row["sourceName"]." här</a>";
	echo "<div class=\"userbox\"><p id=\"postuser\">".$row["userNick"]."</p><p id=\"creduser\"> cred:".$row["userCredPoints"]."</div></p><p id=\"postdesc\">";
	$postdescwithrb = str_replace(array("\r\n", "\r", "\n"), "<br>", $row["postDescription"]);
	echo $postdescwithrb."</p>";
	
	
	$true = $conn->query("SELECT COUNT(votetbl.voteTF) AS tnr FROM votetbl WHERE votetbl.voteTF = 1 
	AND votetbl.postID_fk = $postID");
	$nrvotetrue = $true->fetch_assoc();
	$nrvotefalse = $nrVotes - $nrvotetrue["tnr"];
	
	echo "<p id=\"artfake\">Är artikeln från ".$row["sourceName"]." fake?</p>";
	echo "<div id=\"votedemo".$counter."\">";
	
	echo "<button id = \"votetrue\" onclick = \"vote('1','".$_SESSION["userid"]."','$postID','".session_id()."','$counter')\">JA - ".$nrvotetrue["tnr"]."</button>";
	$vtp = (($nrvotetrue["tnr"] / $nrVotes)*100)-1;
	$vfp = (($nrvotefalse / $nrVotes)*100)-1;
	if($vtp < 0)
	{
		$vfp = $vfp-1;
	}
	if($vfp < 0)
	{
		$vtp = $vtp-1;
	}
	
	
	echo "<button id = \"votefalse\" onclick = \"vote('0','".$_SESSION["userid"]."','$postID','".session_id()."','$counter')\"> ".$nrvotefalse." - NEJ</button>";
	
	echo '<div id="votebar" style="background-color: #ddd;">
	<div style="float:left; width:'.$vtp.'%; height:100%; background-color: #ff4145;"></div>
	<div style="float:left; width:2%; height:100%; background-color: yellow;"></div>
	<div style="float:right; width:'.$vfp.'%; height:100%; background-color: #3fa43f;"></div>
	</div>';

	echo "</div></div>";

	echo '<button id="loadcomments" onclick="loadcom('.$postID.','.$comnr.')">Ladda fler kommentarer</button>';
	echo "<div id=\"comdemo\">";
	$postCom = $conn->query("SELECT q.* FROM (SELECT commenttbl.*, usertbl.userNick FROM commenttbl, usertbl WHERE commenttbl.postID_fk = ".$postID." AND commenttbl.userID_fk = usertbl.userID order by comID DESC limit 10) q ORDER BY q.comID ASC");
	if($comnr < 0)
	{
		$comnr = 0;
	}
	$commentCounter = 0;
	if ($postCom->num_rows > 0)
	{
		while($comRow = $postCom->fetch_assoc())
		{
			echo '<div id="comment">';
			$a = $conn->query("SELECT votetbl.voteTF FROM votetbl WHERE postID_fk = ".$p." AND userID_fk = ".$comRow["userID_fk"].";");
			if($a->num_rows > 0)
			{
				$usercolor = $a->fetch_assoc();
				$color = $usercolor["voteTF"];
			}
			else
			{
				$color = 3;
			}
			$commentCounter++;
			$comnr++;
			//echo "<br> cC = ".$commentCounter;
			echo '<div id="user">';
			if($color == 3)
			{
				echo '<div id="ball" style="background-color: #ffff00;"></div>';
			}
			else
			{
				if($color == 1)
				{
					echo '<div id="ball" style="background-color: #ff4145;"></div>';
				}
				else
				{
					echo '<div id="ball" style="background-color: #3fa43f;"></div>';
				}
			}
			
			echo "<p id=\"username\">".$comRow["userNick"]."</p> <p id=\"comtime\">".timeSince($comRow["comDate"])." ago</p></div>";
			
			$commentUpDown = $conn->query("SELECT COUNT(comVotetbl.upOrDown) AS UDnr FROM comVotetbl WHERE comVotetbl.upOrDown = 1 
			AND comVotetbl.comID_fk = '".$comRow["comID"]."';");
			$nrup = $commentUpDown->fetch_assoc();
			$nrdown = $comRow["nrComVotes"] - $nrup["UDnr"];
			
			echo "<button id = \"upp\" onclick = \"comvote('".$comRow["comID"]."','".$_SESSION["userid"]."','1','".session_id()."', '$commentCounter')\">";
			echo '<p id="pilupp"><i class="material-icons">keyboard_arrow_up</i></p>';
			echo '</button>';
			
			
			echo '<div id="comvote'.$commentCounter.'"><p id="comupdown">'.$nrup["UDnr"].'</p><p id="comupdown">'.$nrdown.'</p></div>';
			
			
			echo "<button id = \"ner\" onclick = \"comvote('".$comRow["comID"]."','".$_SESSION["userid"]."',
			'0','".session_id()."', '$commentCounter')\"><p id=\"pilner\"><i class=\"material-icons\">keyboard_arrow_down</i></p></button>";
			

			echo "<p id=\"comment\">".$comRow["comment"]."</p>";
			echo '<p id="comnr">'.$comnr.'</p>';
			echo "</div>";
		}
	}
	echo "</div>";
	echo "<input type=\"text\" id=\"com\" name=\"com\" placeholder=\"Kommentera...\"></input><br>";
	echo "<button id =\"go\" onclick=\"comment('".$_SESSION["userid"]."','$postID','".session_id()."')\">kommentera</button>";
}
echo "</div>";
?>


</body>
</html>
