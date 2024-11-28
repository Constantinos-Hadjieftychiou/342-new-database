<?php
// Start session
session_start();

// Define database credentials
$host = 'mssql.cs.ucy.ac.cy'; // Replace with your database host
$user = 'chadji10'; // Replace with your MySQL username
$password = 'P5wHZj8v'; // Replace with your MySQL password
$dbname = 'chadji10'; // Replace with your database name

// Establish connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the database!";
}

// Store connection in session (optional, for reuse in other scripts)
$_SESSION['db_connection'] = $conn;
?>
