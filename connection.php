<?php
function db_connect() {
    $dsn = "sqlsrv:Server=mssql.cs.ucy.ac.cy;Database=kkypri06";
    $username = "kkypri06";
    $password = "JcgSDR38";

    try {
        $conn = new PDO($dsn, $username, $password);
        // Set error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Test the connection
$conn = db_connect();
if ($conn) {
    echo "Connection successful!";
    $conn = null; // Close the connection
}
?>
