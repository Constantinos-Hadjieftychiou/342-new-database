<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'FY') {
    header("Location: index.php");
    exit();
}
$verifyUsers = [];
$applicationsToView = [];
$verifyApplications = [];
$error = "";
$message = "";

try {
    $conn = db_connect();

    // Handle approval or rejection of users
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['user_action'])) {
        $username = $_POST['username'];
        $userAction = $_POST['user_action'];

        // Call the procedure to approve or reject the user
        $stmt = $conn->prepare("{CALL ApproveUserRegistration(?, ?)}");
        $stmt->bindParam(1, $username, PDO::PARAM_STR);
        $stmt->bindParam(2, $userAction, PDO::PARAM_STR);
        $stmt->execute();

        $message = "User '$username' has been " . ($userAction === 'Approve' ? 'approved' : 'rejected') . ".";
    }

    // Handle approval or rejection of applications
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['action'])) {
        $applicationId = $_POST['application_id'];
        $action = $_POST['action'];

        // Call the procedure to approve or reject the application
        $stmt = $conn->prepare("{CALL ApproveOrDeclineApplicationsForVerify(?, ?)}");
        $stmt->bindParam(1, $applicationId, PDO::PARAM_INT);
        $stmt->bindParam(2, $action, PDO::PARAM_STR);
        $stmt->execute();

        $message = "Application ID $applicationId has been " . ($action === 'Approve' ? 'approved' : 'rejected') . ".";
    }

    // Fetch users who need verification (e.g., LT / AA)
    $stmt = $conn->prepare("{CALL Pending_for_approve_list()}");
    $stmt->execute();
    $verifyUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch all applications
    $stmt = $conn->prepare("{CALL ShowApplications()}");
    $stmt->execute();
    $applicationsToView = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch applications to verify
    $stmt = $conn->prepare("{CALL ShowApplicationsForVerify()}");
    $stmt->execute();
    $verifyApplications = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FY Dashboard | Electric Future</title>
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
            background: #f4f4f4;
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
/* Reports Button */
.btn-reports {
    background-color: #808080; /* Gray */
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1rem;
    transition: background 0.3s ease;
}

.btn-reports:hover {
    background-color: #696969; /* Darker Gray */
}

/* Logout Button */
.btn-logout {
    background-color: #ff4d4d; /* Red */
    color: white;
    padding: 10px 15px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 1rem;
    transition: background 0.3s ease;
}

.btn-logout:hover {
    background-color: #e63939; /* Darker Red */
}


        table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .btn {
            background: #00c7a3;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }



        /* Red Button for Reject */
        .btn-reject {
            background: #ff4d4d;
        }

        .btn-reject:hover {
            background: #e63939;
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
/* Yellow Button for View */
.btn-view {
    background: #FFC72C;
    color: white;
}

.btn-view:hover {
    background: #E0A806;
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
<div class="header">
    <div class="logo">Electric Future</div>
    <nav>
        <a href="reports.php" class="btn-reports">Reports</a>
        <a href="index.php" class="btn-logout">Logout</a>
    </nav>
</div>



    <!-- Main Content -->
    <div class="container">
        <h1>Welcome, FY User</h1>

        <?php if ($message): ?>
            <div class="message"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Section: Verify Users -->
        <div class="section">
            <h2>Verify Users (LT / AA)</h2>
            <?php if (!empty($verifyUsers)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($verifyUsers as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['user_type']) ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                                        <button class="btn" name="user_action" value="Approve">Verify</button>
                                        <button class="btn btn-reject" name="user_action" value="Reject">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No users pending verification.</p>
            <?php endif; ?>
        </div>

        <!-- Section: View Applications -->
        <div class="section">
            <h2>View Applications</h2>
            <?php if (!empty($applicationsToView)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Submission Date</th>
                            <th>Is Active</th>
                            <th>Status</th>
                            <th>File Path</th>
                            <th>Category Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($applicationsToView as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['Application ID']) ?></td>
                                <td><?= htmlspecialchars($app['Submission Date']) ?></td>
                                <td><?= $app['Is Active'] ? 'Yes' : 'No' ?></td>
                                <td><?= htmlspecialchars($app['Status']) ?></td>
                                <td><?= htmlspecialchars($app['File Path']) ?></td>
                                <td><?= htmlspecialchars($app['Category Type']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No applications available.</p>
            <?php endif; ?>
        </div>

        <!-- Section: Verify Applications -->
        <div class="section">
            <h2>Verify Applications</h2>
            <?php if (!empty($verifyApplications)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Application ID</th>
                            <th>Submission Date</th>
                            <th>Is Active</th>
                            <th>Status</th>
                            <th>File Path</th>
                            <th>Category Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($verifyApplications as $app): ?>
                            <tr>
                                <td><?= htmlspecialchars($app['Application ID']) ?></td>
                                <td><?= htmlspecialchars($app['Submission Date']) ?></td>
                                <td><?= $app['Is Active'] ? 'Yes' : 'No' ?></td>
                                <td><?= htmlspecialchars($app['Status']) ?></td>
                                <td><?= htmlspecialchars($app['File Path']) ?></td>
                                <td><?= htmlspecialchars($app['Category Type']) ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['Application ID']) ?>">
                                        <button class="btn" name="action" value="Approve">Approve</button>
                                        <button class="btn btn-reject" name="action" value="Decline">Reject</button>
                                    </form>
                                    <form method="POST" action="viewDocument.php">
                                        <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['Application ID']) ?>">
                                        <button class="btn btn-view" name="action" value="View">View</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No applications pending approval or rejection.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>KSK_Team_Rocket&copy; <?= date("Y") ?>. All rights reserved.</p>
    </div>
</body>
</html>
