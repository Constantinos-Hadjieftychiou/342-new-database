<?php
session_start();
require_once "connection.php";

// // Check if the user is logged in and is of type 'AX'
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'AX') {
//     header("Location: index.php");
//     exit();
// }

$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // try {
    //     $conn = db_connect();

    //     $submission_date = date('Y-m-d');
    //     $is_active = isset($_POST['is_active']) ? 1 : 0;
    //     $description = $_POST['description'];
    //     $user_id = $_SESSION['user_id'];

    //     // Insert application into the database
    //     $sql = "INSERT INTO Application (submission_date, is_active, user_id, description) VALUES (?, ?, ?, ?)";
    //     $stmt = $conn->prepare($sql);
    //     $stmt->execute([$submission_date, $is_active, $user_id, $description]);

    //     $message = "Application created successfully!";
    //     header("Location: AX.php"); // Redirect back to AX.php
    //     exit();

    // } catch (PDOException $e) {
    //     $error = "Error: " . $e->getMessage();
    // }
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

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
    <div class="input-group">
        <label for="category">Select the category of the sponsorship</label>
        <select id="category" name="category" required>
          <option value="Γ1">Γ1</option>
          <option value="Γ2">Γ2</option>
          <option value="Γ3">Γ3</option>
          <option value="Γ4">Γ4</option>
          <option value="Γ5">Γ5</option>
          <option value="Γ6">Γ6</option>
          <option value="Γ7">Γ7</option>
          <option value="Γ8">Γ8</option>
          <option value="Γ10">Γ10</option>
          <option value="Γ11">Γ11</option>
          <option value="Γ12">Γ12</option>
          <option value="Γ13">Γ13</option>
          <option value="Γ14">Γ14</option>
        </select>
        <div class="input-group">
        <label for="document_path">Document Path</label>
        <input type="text" id="document_path" name="document_path" placeholder="Enter the document path" required>
      </div>
      </div><button type="submit">Submit Application</button>
    </form>
</div>
</body>
</html>
