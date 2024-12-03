<?php
session_start();
require_once "connection.php";

// Check if the user is logged in and is of type 'AX'
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$applications = [];
$error = "";

try {
    // Connect to the database
    $conn = db_connect();

    // Execute the stored procedure to retrieve applications for the logged-in user
    $stmt = $conn->prepare("{CALL ShowUserApplication(?)}");
    $stmt->bindParam(1, $_SESSION['username'], PDO::PARAM_STR);
    $stmt->execute();

    // Fetch the results
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
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
            background: linear-gradient(to bottom, #004c91, #87CEEB); /* Fade from dark blue to light blue */
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
        }

        .header .logo {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .header nav {
            display: flex;
            gap: 20px;
        }

        .header nav a {
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 15px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Logout Button */
        .btn-logout {
            background-color: #ff4d4d; /* Red */
            color: white;
        }

        .btn-logout:hover {
            background-color: #e63939; /* Darker Red */
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

        /* Status Colors */
        .status-denied {
            color: #ff4d4d; /* Red for Denied */
            font-weight: bold;
        }

        .status-approved {
            color: #00c7a3; /* Green for Approved */
            font-weight: bold;
        }

        .status-waiting {
            color: #FFC72C; /* Orange for Waiting */
            font-weight: bold;
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
    </style>
</head>
<body>
<div class="header">
    <div class="logo">EV Manager</div>
    <nav>
        <a href="index.php" class="btn-logout">Logout</a>
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
                    <th>Status</th>
                    <th>Type</th>
                    <th>File Path</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <?php
                    // Determine the status class based on the status value
                    $status = htmlspecialchars($app['application_status']);
                    $statusClass = '';
                    if ($status === 'Denied') {
                        $statusClass = 'status-denied';
                    } elseif ($status === 'Approved') {
                        $statusClass = 'status-approved';
                    } elseif ($status === 'Waiting') {
                        $statusClass = 'status-waiting';
                    }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($app['application_id']) ?></td>
                        <td><?= htmlspecialchars($app['submission_date']) ?></td>
                        <td><?= $app['is_active'] ? 'Yes' : 'No' ?></td>
                        <td class="<?= $statusClass ?>"><?= $status ?></td>
                        <td><?= htmlspecialchars($app['type']) ?></td>
                        <td><?= htmlspecialchars($app['file_path']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not created any applications yet.</p>
    <?php endif; ?>
</div>
<!-- Footer -->
<div class="footer">
    <p>KSK_Team_Rocket&copy; <?= date("Y") ?>. All rights reserved.</p>
</div>
</body>
</html>
