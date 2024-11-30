<?php
// Start session
session_start();
$_SESSION["serverName"]="mssql.cs.ucy.ac.cy";
$_SESSION["connectionOptions"]=array(
	"Database" => "kkypri06",
	"Uid" => "kkypri06",
	"PWD" =>"JcgSDR38"
);
header("Location: connect.php");
exit();
?>