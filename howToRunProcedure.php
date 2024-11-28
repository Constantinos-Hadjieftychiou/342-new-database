<?php
// Create a new MySQLi connection
$mysqli = new mysqli("your_host", "your_username", "your_password", "your_database");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Stored procedure call
$procedure = "CALL YourStoredProcedure(?, ?)";
// Prepare the statement
$stmt = $mysqli->prepare($procedure);
// Bind parameters
$value1 = "example_string";
$value2 = 42;
$stmt->bind_param("si", $value1, $value2); // 's' for string, 'i' for integer

// Execute the statement
$stmt->execute();

// Fetch results (if the procedure returns any)
$result = $stmt->get_result();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
}

// Close the statement and connection
$stmt->close();
$mysqli->close();

//simpler method when there are not a lot of parameters
$result = $mysqli->query("CALL YourStoredProcedure('param1', 42)");


?>




