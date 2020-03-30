<?php
session_start();

if (!isset($_SESSION["newDel"])){
	echo '<script>window.location.href="welcome.php"; window.alert("Don\'t do that!");</script>';
}

if (!isset($_SESSION["username"]) && !isset($_SESSION["password"])){
	exit("You have to login first!");
}

$mysql_link=new mysqli('localhost','root','','map');

if(mysqli_connect_error())
{
	die('Connect Error ('.mysqli_connect_errno().')'.mysqli_connect_error());
}

mysqli_set_charset($mysql_link, "utf8");

//eliminar datos de los poligonos
$my_query="DELETE FROM polygon";
$result=$mysql_link->query($my_query);

//eliminar datos de los coordinaciones
$my_query="DELETE FROM coordinates";
$result1=$mysql_link->query($my_query);

// auto_increment en 1
$auto_incr1 = "ALTER TABLE `polygon` AUTO_INCREMENT = 1";
$result1 = $mysql_link->query($auto_incr1);
 
$auto_incr2 = "ALTER TABLE `coordinates` AUTO_INCREMENT = 1";
$result2 = $mysql_link->query($auto_incr2);
 
header('Location: /options.php');


$mysql_link->close();
$_SESSION["newAccess"] = ['yes'];
echo '<script>window.location.href="options.php"; window.alert("Successful deletion!");</script>';
unset($_SESSION["newDel"]);
?>