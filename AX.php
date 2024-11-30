<?php
session_start();
require_once "connection.php";

// // Check if the user is logged in and is of type 'AX'
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'AX-NP'|| $_SESSION['user_type'] !== 'AX-FP') {
//     header("Location: index.php");
//     exit();
// }

// Initialize error message
// $error = "";

// try {
//     $conn = db_connect();

//     // Retrieve applications created by the logged-in user
//     $sql = "SELECT application_id, submission_date, is_active FROM Application WHERE user_id = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->execute([$_SESSION['user_id']]);
//     $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// } catch (PDOException $e) {
//     $error = "Error: " . $e->getMessage();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AX Application Interface</title>
    
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
    <nav>
          <a href="index.php">Logout</a>
        </nav>
  </div>
  
<div class="container">
    <h1>Welcome! Here you can create an Application and view your existing Applications</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Button to Create Application -->
    <form action="CreateApplication.php" method="GET">
        <button type="submit">Create Application</button>
    </form>

    <!-- Display Created Applications -->
    <h2>Your Applications</h2>
    <?php if (!empty($applications)): ?>
        <table>
            <thead>
                <tr>
                    <th>Application ID</th>
                    <th>Submission Date</th>
                    <th>Is Active</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?= htmlspecialchars($app['application_id']) ?></td>
                        <td><?= htmlspecialchars($app['submission_date']) ?></td>
                        <td><?= $app['is_active'] ? 'Yes' : 'No' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not created any applications yet.</p>
    <?php endif; ?>
</div>
</body>
</html>
