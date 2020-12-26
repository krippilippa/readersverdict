<?php
require('includes/db_connect.php');
session_start();
?>
<?php
$p = mysqli_real_escape_string($conn, $_GET["p"]);
$c = mysqli_real_escape_string($conn, $_GET["c"]);

if(strcmp($_GET["s"], session_id()) == 0 && $_SESSION["loggedin"] == TRUE)
{
	$v = mysqli_real_escape_string($conn, $_GET["v"]);
	$u = mysqli_real_escape_string($conn, $_GET["u"]);
	
	$sql="SELECT * FROM votetbl WHERE postID_fk = $p AND userID_fk = $u;";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		$k = $row["voteTF"];
		if ($k != $v)
		{
			//här ändras rösten om personen redan röstat.
			$n = $row["voteFirst"];
			$n = $n + 1;
			$conn->query("UPDATE votetbl SET voteTF = $v, voteFirst = ".$n." WHERE postID_fk = $p AND userID_fk = $u;");
		}
	}
	else
	{
		//ny röst registreras och alla poäng ges ut.
		$conn->query("INSERT INTO votetbl VALUES ($p, $u, $v, '0');");
		$conn->query("UPDATE posttbl, usertbl SET posttbl.nrVotes = posttbl.nrVotes + 1, usertbl.userCredPoints = usertbl.userCredPoints + 5 WHERE posttbl.postID = $p AND usertbl.userID = $u;");
		//om det är en röst på "fake" får artikelägaren poäng, annars inte. Möjligtvis att den ska få minus?!
		if($v == 1)
		{
			$conn->query("UPDATE usertbl INNER JOIN posttbl ON usertbl.userID = posttbl.userID_fk SET usertbl.userCredPoints = usertbl.userCredPoints + 1 WHERE posttbl.postID = $p");
		}
	}

	//ny sträng returneras
	$true = $conn->query("SELECT COUNT(votetbl.voteTF) AS tnr FROM votetbl WHERE votetbl.voteTF = 1 
	AND votetbl.postID_fk = $p");
	$f = $conn->query("SELECT posttbl.nrVotes FROM posttbl WHERE posttbl.postID = $p");
	$nv = $f->fetch_assoc();
	$nrvotetrue = $true->fetch_assoc();
	$nrvotefalse = $nv["nrVotes"] - $nrvotetrue["tnr"];
	echo "<button id = \"votetrue\" onclick = \"vote('1','".$_SESSION["userid"]."','$p','".session_id()."','$c')\">JA - ".$nrvotetrue["tnr"]."</button>";
	$vtp = (($nrvotetrue["tnr"] / $nv["nrVotes"])*100)-1;
	$vfp = (($nrvotefalse / $nv["nrVotes"])*100)-1;
	
	if($vtp < 0)
	{
		$vfp = $vfp-1;
	}
	if($vfp < 0)
	{
		$vtp = $vtp-1;
	}
	
	echo "<button id = \"votefalse\" onclick = \"vote('0','".$_SESSION["userid"]."','$p','".session_id()."','$c')\"> ".$nrvotefalse." - NEJ</button>";
	
	echo '<div id="votebar" style="background-color: #ddd;">
	<div style="float:left; width:'.$vtp.'%; height:100%; background-color: #ff4145;"></div>
	<div style="float:left; width:2%; height:100%; background-color: yellow;"></div>
	<div style="float:right; width:'.$vfp.'%; height:100%; background-color: #3fa43f;"></div>
	</div>';
}
else
{
	echo "0";
}

$conn->close();
?>
