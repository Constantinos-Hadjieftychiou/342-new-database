<?php
// Start session
session_start();

$_SESSION["serverName"]="mssql.cs.ucy.ac.cy";
$_SESSION["connectionOptions"]=array(
	"Database" => "chadji10",
	"Uid" => "chadji10",
	"PWD" =>"P5wHZj8v"
);

    // Redirect to signIn.html
    header("Location: connect.php");
    exit();

?>

