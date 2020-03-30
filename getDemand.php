<?php

session_start();

if (!isset($_SESSION["newSim"])){
	echo '<script>window.location.href="welcome.php"; window.alert("Don\'t do that!");</script>';
}

$mysql_link=new mysqli('localhost','root','','map');

if(mysqli_connect_error())
{
	die('Connect Error ('.mysqli_connect_errno().')'.mysqli_connect_error());
}

mysqli_set_charset($mysql_link, "utf8");

$sql="SELECT hour, kind_id, demand FROM `hourly_parking_demand`";
$res=array();
$res1=array();
$i=0;

if($result=mysqli_query($mysql_link,$sql)){
	while($row = mysqli_fetch_row($result)) {
		$res[]=array($row[0],$row[1],$row[2]);

		$hour = $res[$i][0];	//hora
		$kind_id = $res[$i][1];		//tipo
		$demand = $res[$i][2];	//demanda

		$res1[]=array('hour'=>$hour,'kind_id'=>$kind_id,'demand'=>$demand);
		$i++;
	}

	mysqli_free_result($result);
}

$myJSON = json_encode($res1);
echo $myJSON;
$mysql_link->close();
?>
