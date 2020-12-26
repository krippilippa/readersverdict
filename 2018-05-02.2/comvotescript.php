<?php
require('includes/db_connect.php');
session_start();
?>
<?php

if(strcmp($_GET["s"], session_id()) == 0 && $_SESSION["loggedin"] == TRUE)
{
	$v = mysqli_real_escape_string($conn, $_GET["v"]);
	$u = mysqli_real_escape_string($conn, $_GET["u"]);
	$c = mysqli_real_escape_string($conn, $_GET["c"]);
	
	$sql="SELECT * FROM comVotetbl WHERE comID_fk = $c AND userID_fk = $u;";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0)
	{
		echo "1";
	}
	else
	{
		//ny röst på en kommentar loggas och poäng delas ut.
		$conn->query("INSERT INTO comVotetbl VALUES ($c, $u, $v);");
		$conn->query("UPDATE commenttbl, usertbl SET commenttbl.nrComVotes = commenttbl.nrComVotes + 1, usertbl.userCredPoints = usertbl.userCredPoints + 1 WHERE commenttbl.comID = $c AND usertbl.userID = $u;");
		$conn->query("UPDATE usertbl INNER JOIN commenttbl ON usertbl.userID = commenttbl.userID_fk SET usertbl.userCredPoints = usertbl.userCredPoints + 1 WHERE commenttbl.comID = $c");
		

		$commentUpDown = $conn->query("SELECT COUNT(comVotetbl.upOrDown) AS UDnr FROM comVotetbl WHERE comVotetbl.upOrDown = 1 
		AND comVotetbl.comID_fk = $c;");
		$nrup = $commentUpDown->fetch_assoc();
		$noc = $conn->query("SELECT commenttbl.nrComVotes FROM commenttbl WHERE commenttbl.comID = $c;");
		$comRow = $noc->fetch_assoc();
		//echo $comRow["nrComVotes"] - $nrup["UDnr"];
		//echo $nrup["UDnr"];
		//echo "<p id=\"comupdown\">".$nrup["UDnr"]."</p><p id=\"comupdown\">".$comRow["nrComVotes"] - $nrup["UDnr"]."</p>";
		$temp = $comRow["nrComVotes"] - $nrup["UDnr"];
		echo '<p id="comupdown">'.$nrup["UDnr"].'</p><p id="comupdown">'.$temp.'</p>';
	}
}
else//om personen inte är inloggad returneras 0 så scriptet vet och kan ge alert
{
	echo "0";
}
$conn->close();
?>
