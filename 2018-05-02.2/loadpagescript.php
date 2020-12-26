<?php
require('includes/db_connect.php');
session_start();
?>
<?php
function getPostsBy($cat, $vot, $com, $cn)
{
	if ($cat == 0 && $vot == 0 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by postID desc limit 5 OFFSET $cn;";
		return $s;
	}
	elseif($cat == 0 && $vot == 1 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrVotes desc limit 5 OFFSET $cn;";
		return $s;
	}
	elseif($cat == 0 && $vot == 0 && $com == 1)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrComments desc limit 5 OFFSET $cn;";
		return $s;
	}
	elseif($cat != 0 && $vot == 0 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE posttbl.categoryID_fk = $cat AND posttbl.userID_fk = usertbl.userID 
		AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by postID desc limit 5 OFFSET $cn;";
		return $s;
	}
	elseif($cat != 0 && $vot == 1 && $com == 0)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.categoryID_fk = $cat AND 
		posttbl.userID_fk = usertbl.userID AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrVotes desc limit 5 OFFSET $cn;";
		return $s;
	}
	elseif($cat != 0 && $vot == 0 && $com == 1)
	{
		$s = "SELECT posttbl.*, usertbl.userNick, usertbl.userCredPoints, sourcetbl.sourceName, categorytbl.categoryName 
		FROM posttbl, usertbl, sourcetbl, categorytbl WHERE postDate >= NOW() - INTERVAL 1 DAY AND posttbl.categoryID_fk = $cat AND 
		posttbl.userID_fk = usertbl.userID AND posttbl.sourceID_fk = sourcetbl.sourceID AND posttbl.categoryID_fk = categorytbl.categoryID 
		order by nrComments desc limit 5 OFFSET $cn;";
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
<?php

$c = mysqli_real_escape_string($conn, $_GET["c"]);
$v = mysqli_real_escape_string($conn, $_GET["v"]);
$co = mysqli_real_escape_string($conn, $_GET["co"]);
$cn = mysqli_real_escape_string($conn, $_GET["cn"]);

$sql = getPostsBy($c,$v,$co,$cn);
$result = $conn->query($sql);
$counter = $cn;
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
			echo "<h2><a href=\"/postpage.php?p=".$postID."\">".$row["postTitle"]."</a></h2>";
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

			echo "<div id=\"demo".$counter."\"></div><br>";
		}	
	}
	echo "</div>";
}
else
{
	echo "0";
}


?>
