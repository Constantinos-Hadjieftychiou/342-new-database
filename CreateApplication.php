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
            background: linear-gradient(to bottom, #004c91, #87CEEB); /* Fade from dark blue to light blue */
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .footer {
            margin-top: auto;
            background: #004c91;
            color: white;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
        }
        /* Header */
        .header {
            width: 100%;
            height: 80px;
            background: #004c91;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .header .logo {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .btn-back {
            position: absolute;
            right: 20px;
            top: 20px;
            background-color: white; /* White background */
            color: #004c91; /* Text color matching the header for consistency */
            border: 2px solid #004c91; /* Add a border for visibility */
            font-weight: bold;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #f0f0f0; /* Light gray on hover */
        }

        /* Container */
        .container {
            max-width: 800px;
            margin: 150px auto 50px; /* Add space for the header */
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

        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .input-group select,
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
<div class="header">
    <div class="logo">Electric Future</div>
    <a href="AX.php" class="btn-back">Back</a>
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
        </div>
        <div class="input-group">
            <label for="file_path">Document Path</label>
            <input type="text" id="file_path" name="file_path" placeholder="Enter the document path" required>
        </div>
        <button type="submit">Submit Application</button>
    </form>
</div>

<!-- Footer -->
<div class="footer">
    <p>KSK_Team_Rocket&copy; <?= date("Y") ?>. All rights reserved.</p>
</div>
</body>
</html>
