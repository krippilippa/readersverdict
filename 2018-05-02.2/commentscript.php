<?php
require('includes/db_connect.php');
session_start();
?>
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



$p = mysqli_real_escape_string($conn, $_GET["p"]);
if(strcmp($_GET["s"], session_id()) == 0 && $_SESSION["loggedin"] == TRUE)
{
	$c = mysqli_real_escape_string($conn, $_POST["c"]);
	$u = mysqli_real_escape_string($conn, $_GET["u"]);
	
	if($c != "")
	{
		$conn->query("INSERT INTO commenttbl(postID_fk, userID_fk, comment) VALUES ('$p', '$u', '$c');");
		$conn->query("UPDATE posttbl, usertbl SET posttbl.nrComments = posttbl.nrComments + 1, usertbl.userCredPoints = usertbl.userCredPoints + 2 WHERE posttbl.postID = $p AND usertbl.userID = $u;");
	}
	
	$temp = $conn->query("SELECT nrComments FROM posttbl WHERE postID = $p");
	$nr = $temp->fetch_assoc();
	$comnr = $nr["nrComments"];
	$comnr = $comnr - 10;
	if($comnr < 0)
	{
		$comnr = 0;
	}
	
	//$cn = mysqli_real_escape_string($conn, $_GET["cn"]);
	//$commentCounter = $cn
	$postCom = $conn->query("SELECT q.* FROM (SELECT commenttbl.*, usertbl.userNick FROM commenttbl, usertbl WHERE commenttbl.postID_fk = ".$p." AND commenttbl.userID_fk = usertbl.userID order by comID DESC limit 10) q ORDER BY q.comID ASC");
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
}
else
{
	echo "0";
}
$conn->close();
?>
