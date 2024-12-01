<?php
session_start();
require_once "connection.php";

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Check if the session variables are set
        if (!isset($_SESSION['username']) || !isset($_SESSION['password'])) {
            throw new Exception("Session variables not set. Please log in again.");
        }

        // Retrieve input values
        $category_type = $_POST['category_type'];
        $file_path = $_POST['file_path'];
        $username = $_SESSION['username'];



        // Connect to the database
        $conn = db_connect();
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the stored procedure
        $stmt = $conn->prepare("{CALL SubmitApplication(@username = ?, @category_type = ?, @file_path = ?)}");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $category_type, PDO::PARAM_STR);
        $stmt->bindParam(3, $file_path, PDO::PARAM_STR);
        $stmt->execute();

  
        header("Location: AX.php");
        exit();

    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
        echo $error;
    } catch (Exception $e) {
        $error = $e->getMessage();
        echo $error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Application</title>
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4; /* Light neutral background color */
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            color: #333;
        }

        /* Header */
        .header {
            width: 100%; /* Full width */
            height: 150px; /* Increased height */
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 20px; /* Increased vertical padding */
            display: flex;
            justify-content: space-between; /* Align images across the width */
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
        }

        .header img {
            height: 120px; /* Increased image height */
            flex: 1; /* Ensure images are evenly spaced */
            object-fit: contain;
            max-width: 300px;
        }

        .header img:nth-child(2) {
            margin: 0 50px; /* Add extra spacing between the center image */
        }

        /* Container */
        .container {
            max-width: 800px;
            margin: 200px auto 50px; /* Add space for the header */
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #004c91;
            margin-bottom: 20px;
        }

        button {
            background-color: #00c7a3;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #009b85;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
<div class="header">
    <img src="static/eu.png" alt="European Union">
    <img src="static/dimokratia.png" alt="Cyprus Government">
    <img src="static/kypros.png" alt="Cyprus Tomorrow">
  </div>
<div class="container">
    <h1>Create a New Application</h1>


    <form method="POST">
    <div class="input-group">
        <label for="category_type">Select the category of the sponsorship</label>
        <select id="category_type" name="category_type" required>
          <option value="G1">Γ1</option>
          <option value="G2">Γ2</option>
          <option value="G3">Γ3</option>
          <option value="G4">Γ4</option>
          <option value="G5">Γ5</option>
          <option value="G6">Γ6</option>
          <option value="G7">Γ7</option>
          <option value="G8">Γ8</option>
          <option value="G10">Γ10</option>
          <option value="G11">Γ11</option>
          <option value="G12">Γ12</option>
          <option value="G13">Γ13</option>
          <option value="G14">Γ14</option>
        </select>
        <div class="input-group">
        <label for="file_path">Document Path</label>
        <input type="text" id="file_path" name="file_path" placeholder="Enter the document path" required>
      </div>
      </div><button type="submit">Submit Application</button>
    </form>
</div>
</body>
</html>
