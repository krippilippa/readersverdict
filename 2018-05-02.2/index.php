 <!DOCTYPE html>
<html>
<head>
<meta name="keywords" content="fakenews sverige, falska nyheter, tidning med mest fakenews, alternativmedia, mainstream media fake news" />
<meta name="description" content="ReadersVerdict" />
<meta name="DC.Title" content="ReadersVerdict" />
<meta name="DC.Description" content="Communityns medlemmar röstar." />
<meta name="DC.identifier" scheme="URL" content="http://www.readersverdict.com" />
<meta name="DC.Creator" content="ReadersVerdict" />


<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="includes/index/topnav.css">
<link rel="stylesheet" type="text/css" href="includes/index/sidenavleft.css">
<link rel="stylesheet" type="text/css" href="includes/index/srknav.css">
<link rel="stylesheet" type="text/css" href="includes/index/feed.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel">
<!--https://material.io/icons/#ic_chat_bubble-->
<title>ReadersVerdict Sverige</title>
</head>

<script>

var cn = 10;
var load = 1;

function loadPost(cat, vot, com)
{
	if (load == 1)
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
				temp = document.getElementById("load").innerHTML;
				temp2 = this.responseText;
				if (temp2 == 0)
				{
					load = 0;
					document.getElementById("load").innerHTML = temp + "Det fanns inga fler artiklar";
				}
				else
				{
					document.getElementById("load").innerHTML = temp + temp2;
				}
			}
		};
		//alert("längst ned");
		xmlhttp.open("GET","loadpagescript.php?c="+cat+"&v="+vot+"&co="+com+"&cn="+cn,true);
		xmlhttp.send();
		cn = cn +5;
	}
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
				document.getElementById("votedemo"+counter).innerHTML = temp;
			}
		}
	};
	xmlhttp.open("GET","votescript.php?v="+tf+"&u="+userID+"&p="+postID+"&s="+sessionID+"&c="+counter,true);
	xmlhttp.send();
}


function comvote(comID, userID, updown, sessionID, counter, commentCounter)
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
			document.getElementById("comvote"+counter+"and"+commentCounter).innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET","comvotescript.php?c="+comID+"&u="+userID+"&v="+updown+"&s="+sessionID,true);
	xmlhttp.send();
}

</script>
<?php
function getPostsBy($cat, $vot, $com)
{
	if ($cat == 0 && $vot == 0 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by postID desc limit 10;";
		return $s;
	}
	elseif($cat == 0 && $vot == 1 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrVotes desc limit 10;";
		return $s;
	}
	elseif($cat == 0 && $vot == 0 && $com == 1)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrComments desc limit 10;";
		return $s;
	}
	elseif($cat != 0 && $vot == 0 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE posttbl.categoryID_fk = $cat AND posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by postID desc limit 10;";
		return $s;
	}
	elseif($cat != 0 && $vot == 1 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.categoryID_fk = $cat 
		AND posttbl.userID_fk = usertbl.userID AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrVotes desc limit 10;";
		return $s;
	}
	elseif($cat != 0 && $vot == 0 && $com == 1)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.categoryID_fk = $cat 
		AND posttbl.userID_fk = usertbl.userID AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrComments desc limit 10;";
		return $s;
	}
}

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
if ($_GET["logout"] == "0")
{
	$_SESSION["loggedin"] = FALSE;
	$_SESSION["userid"] = "";
	$_SESSION["username"] = "";
	session_destroy();
}
?>

<?php
$stopload = 0;
$c = 0;
$v = 0;
$co = 0;
if(isset($_GET["c"]));
{
	$c = mysqli_real_escape_string($conn, $_GET["c"]);
}
if(isset($_GET["v"]));
{
	$v = mysqli_real_escape_string($conn, $_GET["v"]);
}
if(isset($_GET["co"]));
{
	$co = mysqli_real_escape_string($conn, $_GET["co"]);
}
?>
<script type="text/javascript">var c = <?php echo json_encode($c); ?>;</script>
<script type="text/javascript">var v = <?php echo json_encode($v); ?>;</script>
<script type="text/javascript">var co = <?php echo json_encode($co); ?>;</script>
<input type="checkbox" id="toggle">
<input type="checkbox" id="toggle2">
<div class="p-head">
<label for="toggle" class="toggle"> </label>
<label for="toggle2" class="toggle2"><i class="material-icons">person_outline</i></label>
<?php
echo "<nav>";
$categorys = $conn->query("SELECT * FROM categorytbl");
echo "<ul id=\"topnav\">";
if($c == 0)
{
	echo '<li id="topnav" class="activetopnav"><a href="/index.php?c=0&v=0&co=0">Hem</a></li>';
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
			echo '<li id="topnav" class="activetopnav"><a href="/index.php?c='.$cRow["categoryID"].'&v=0&co=0">'.$cRow["categoryName"].'</a></li>';
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
?>

<?php
if ($_SESSION["loggedin"] == false && $c == 0 && $v == 0 && $co == 0)
{
	echo '<div id="startinfo"><p id="startinfotitel">Vad är ReadersVerdict?</p>
	<p id="startinfotext">ReadersVerdict.com är en plattform där läsare kan lägga upp, ta ställning till, bedömma och kommentera svenska nyhets och tidningsartiklars trovärdighet på ett demokratiskt sätt.</p></div>';
}
$sql = getPostsBy($c,$v,$co);
$result = $conn->query($sql);
echo '<ul id="srk">';
if($v == 0 && $co == 0)
{
	echo '<li id="srk"><a href="/index.php?c='.$c.'&v=0&co=0" class="activesrk">Senast upplagt</a></li>';
}
else
{
	echo '<li id="srk"><a href="/index.php?c='.$c.'&v=0&co=0">Senast upplagt</a></li>';
}

if($v == 1)
{
	echo '<li id="srk"><a href="/index.php?c='.$c.'&v=1&co=0" class="activesrk">Flest röster</a></li>';
}
else
{
	echo '<li id="srk"><a href="/index.php?c='.$c.'&v=1&co=0">Flest röster</a></li>';
}


if($co == 1)
{
	echo '<li id="srk"><a href="/index.php?c='.$c.'&v=0&co=1" class="activesrk">Flest kommentarer</a></li>';
}
else
{
	echo '<li id="srk"><a href="/index.php?c='.$c.'&v=0&co=1">Flest kommentarer</a></li>';
}

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
	echo '<li id="sidenav"><a id="localpages" href="/post_ad.php">Lägg upp en artikel</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/index.php?logout=0">Logga ut</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/change_pw.php">Byt lösenord</a></li>';
	echo '<li id="sidenav"><a id="localpages" href="/faq.php">Om oss</a></li>';
	//echo '<li id="sidenavinfo"><p id="navinfo1">Kul att du är med!</p><p id="navinfo2">Tack för att du är en medlem i communityn! Gör skillnad genom att lägga upp ett eget inlägg eller rösta på andras inlägg.</p></li>';
	echo '<li id"sidenavsoc"><p id="navinfo3">Följ oss på :</p><a href="https://twitter.com/ReadersVerdict" class="fa fa-twitter"></a><a href="https://www.instagram.com/readersverdict" class="fa fa-instagram"></a><a href="https://soundcloud.com/user-534172594" class="fa fa-soundcloud"></a></li>';
	echo '</ul>';
}
echo "</nav2>";
if ($result->num_rows > 0)
{
	while($row = $result->fetch_assoc())
	{
		$counter++;
		if($row["postVer"] == 1)
		{
			echo "<div id=\"article\">";

			$postID = $row["postID"];
			$userID = $row["userID_fk"];
			$nrVotes = $row["nrVotes"];
						
			echo "<div id=\"posttop\"><p id=\"name1\">".$row["sourceName"]."</p><p id=\"name2\">".$row["categoryName"]."</p><i class=\"material-icons\">chat_bubble</i><p id=\"name3\"> ".$row["nrComments"]."</p><p id=\"name4\"> ".timeSince($row["postDate"])." ago</p></div>";
			
			echo "<h2><a href=\"/postpage.php?p=".$postID."&c=".$c."\">".$row["postTitle"]."</a></h2>";
			
			echo "<a id=\"articlelink\" href=".$row["postLink"]." target=\"_blank\">Läs artikeln från ".$row["sourceName"]." här</a>";
			
			$postdescwithrb = str_replace(array("\r\n", "\r", "\n"), "<br>", $row["postDescription"]);
			if (strlen($postdescwithrb)>1000)
			{
				$postdescwithrb = substr($postdescwithrb, 0, 1000);
				$postdescwithrb = $postdescwithrb." .....";
			}
			
			
			echo "<div class=\"userbox\"><p id=\"postuser\">".$row["userNick"]."</p><p id=\"creduser\"> cred:".$row["userCredPoints"]."</div></p><p id=\"postdesc\">";
			
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
			
			echo "<div id=\"demo".$counter."\"></div>";
			echo "<br>";
		}
	}
}

echo "</div>";
if ($stopload == 0)
{
	echo '<script>
	console.log("Doc Height = " + document.body.offsetHeight);
	console.log("win Height = " + document.documentElement.clientHeight);
	window.onscroll = function (ev) {
	    var docHeight = document.body.offsetHeight;
	    docHeight = docHeight == undefined ? window.document.documentElement.scrollHeight : docHeight;
	
	    var winheight = window.innerHeight;
	    winheight = winheight == undefined ? document.documentElement.clientHeight : winheight;
	
	    var scrollpoint = window.scrollY;
	    scrollpoint = scrollpoint == undefined ? window.document.documentElement.scrollTop : scrollpoint;
	
	    if ((scrollpoint + winheight) >= docHeight) {
	        loadPost(c,v,co);
	    }
	};
	</script>';
}
echo "<button id=\"loadpost\" onclick=\"loadPost('".$c."','".$v."','".$co."')\">ladda mer</button>";
?>

</body>
</html>
