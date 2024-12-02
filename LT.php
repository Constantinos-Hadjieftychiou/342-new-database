<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

$applicationsToReview = [];
$error = "";
$message = "";

try {
    $conn = db_connect();

    // Handle approval or rejection of applications
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['action'])) {
        $applicationId = $_POST['application_id'];
        $action = $_POST['action'];

        // Call the procedure to approve or reject
        $stmt = $conn->prepare("{CALL ApproveOrDeclineApplicationsForReview(?, ?)}");
        $stmt->bindParam(1, $applicationId, PDO::PARAM_INT);
        $stmt->bindParam(2, $action, PDO::PARAM_STR);
        $stmt->execute();
    }

    // Fetch applications for review
    $stmt = $conn->prepare("{CALL ShowWaitingApplications()}");
    $stmt->execute();
    $applicationsToReview = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LT Dashboard | Electric Future</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
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
      color: white;
      text-decoration: none;
      font-size: 1rem;
      transition: color 0.3s;
    }

    .header nav a:hover {
      color: #00c7a3;
    }

    /* Main Content */
    .container {
      margin: 20px auto;
      padding: 20px;
      max-width: 1100px;
      width: 90%;
      background: white;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }

    h1 {
      text-align: center;
      font-size: 2rem;
      color: #004c91;
      margin-bottom: 20px;
    }

    .section {
      margin-bottom: 30px;
    }

    .section h2 {
      font-size: 1.5rem;
      color: #333;
      margin-bottom: 15px;
      border-bottom: 2px solid #004c91;
      padding-bottom: 5px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table th, table td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }

    table th {
      background: #004c91;
      color: white;
    }

    table tr:nth-child(even) {
      background: #f9f9f9;
    }

   /* Action Buttons */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 10px; /* Spacing between buttons */
}

.btn {
    padding: 8px 15px;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
}
 /* Logout Button */
 .btn-logout {
        background: #dc3545; /* Red background */
        color: white; /* White text */
        font-weight: bold;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        border: 2px solid #dc3545; /* Red border */
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-logout:hover {
        background: #a71d2a; /* Darker red on hover */
        border-color: #a71d2a; /* Darker red border on hover */
    }
/* Add Button */
.btn-add {
    background: #00c7a3;
    color: white;
}

.btn-add:hover {
    background: #009b85;
}

/* View Button */
.btn-view {
    background: #FFC72C;
    color: white;
}

.btn-view:hover {
    background: #E0A806;
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

    .message {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }

    .error {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">Electric Future</div>
        <nav>
    <a href="index.php" class="btn-logout">Logout</a>
</nav>

    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>Welcome, LT User</h1>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Section: Review Applications -->
        <div class="section">
            <h2>Review Applications</h2>
            <?php if (!empty($applicationsToReview)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <!-- <th>Details</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicationsToReview as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['Application ID']) ?></td>
                                <td><?= htmlspecialchars($app['Submission Date']) ?></td>
                                <td><?= htmlspecialchars($app['Status']) ?></td>
                                <!-- <td><?= htmlspecialchars($app['Details']) ?></td> -->
                                <td>
    <div class="action-buttons">
        <form method="POST" action="addDocument.php">
            <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['Application ID']) ?>">
            <button class="btn btn-add" name="action" value="Add">Add</button>
        </form>
        <form method="POST" action="viewDocument.php">
            <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['Application ID']) ?>">
            <button class="btn btn-view" name="action" value="View">View</button>
        </form>
    </div>
</td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No applications available for review.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>KSK_Team_Rocket &copy; <?= date("Y") ?>. All rights reserved.</p>
    </div>
</body>
</html>
