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
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 15px;
            transition: background 0.3s ease, color 0.3s ease;
        }

        /* Reports Button */
        .btn-reports {
            background-color: #808080; /* Gray */
            color: white;
        }

        .btn-reports:hover {
            background-color: #696969; /* Darker Gray */
        }

        /* Logout Button */
        .btn-logout {
            background-color: #ff4d4d; /* Red */
            color: white;
        }

        .btn-logout:hover {
            background-color: #e63939; /* Darker Red */
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

        /* Scrollable Table */
        .scrollable-table {
            max-height: 300px; /* Limit the height of the table container */
            overflow-y: auto; /* Enable vertical scrolling */
            overflow-x: hidden; /* Disable horizontal scrolling */
            border: 1px solid #ddd; /* Optional: Add a border for clarity */
            border-radius: 5px; /* Optional: Add rounded corners */
        }

        table {
            width: 100%;
            border-collapse: collapse;
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

        /* Buttons */
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

        .btn-approve {
            background: #00c7a3;
            color: white;
        }

        .btn-approve:hover {
            background: #009b85;
        }

        .btn-reject {
            background: #ff4d4d;
            color: white;
        }

        .btn-reject:hover {
            background: #e63939;
        }

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
/* Status Colors */
.status-denied {
    color: #ff4d4d; /* Red for Denied */
    font-weight: bold;
}
/* User Type Colors */
.type-lt {
    color: #9400D3; /* Purple for LT */
    font-weight: bold;
}

.type-aa {
    color: #4169E1; /* Pink for AA */
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
<div class="header">
    <div class="logo">Electric Future</div>
    <nav>
        <a href="reports.php" class="btn-reports">Reports</a>
        <a href="index.php" class="btn-logout">Logout</a>
    </nav>
</div>

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
            <div class="scrollable-table">
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
                                <td>
                                <?php
                                $userType = htmlspecialchars($user['user_type']);
                                $userTypeClass = '';
                                if ($userType === 'LT') {
                                    $userTypeClass = 'type-lt';
                                } elseif ($userType === 'AA') {
                                    $userTypeClass = 'type-aa';
                                }
                                ?>
                                <span class="<?= $userTypeClass ?>"><?= $userType ?></span>
                            </td>                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                                        <button class="btn btn-approve" name="user_action" value="Approve">Verify</button>
                                        <button class="btn btn-reject" name="user_action" value="Reject">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No users pending verification.</p>
        <?php endif; ?>
    </div>

    <!-- Section: View Applications -->
<!-- Section: View Applications -->
<div class="section">
    <h2>View Applications</h2>
    <?php if (!empty($applicationsToView)): ?>
        <div class="scrollable-table">
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
                            <td>
                                <?php
                                $status = htmlspecialchars($app['Status']);
                                $statusClass = '';
                                if ($status === 'Denied') {
                                    $statusClass = 'status-denied';
                                } elseif ($status === 'Approved') {
                                    $statusClass = 'status-approved';
                                } elseif ($status === 'Waiting') {
                                    $statusClass = 'status-waiting';
                                }
                                ?>
                                <span class="<?= $statusClass ?>"><?= $status ?></span>
                            </td>
                            <td><?= htmlspecialchars($app['File Path']) ?></td>
                            <td><?= htmlspecialchars($app['Category Type']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No applications available.</p>
    <?php endif; ?>
</div>


    <!-- Section: Verify Applications -->
    <div class="section">
        <h2>Verify Applications</h2>
        <?php if (!empty($verifyApplications)): ?>
            <div class="scrollable-table">
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
                                <td>
                                <?php
                                $status = htmlspecialchars($app['Status']);
                                $statusClass = '';
                                if ($status === 'Denied') {
                                    $statusClass = 'status-denied';
                                } elseif ($status === 'Approved') {
                                    $statusClass = 'status-approved';
                                } elseif ($status === 'Waiting') {
                                    $statusClass = 'status-waiting';
                                }
                                ?>
                                <span class="<?= $statusClass ?>"><?= $status ?></span>
                            </td>
                                <td><?= htmlspecialchars($app['File Path']) ?></td>
                                <td><?= htmlspecialchars($app['Category Type']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST">
                                            <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['Application ID']) ?>">
                                            <button class="btn btn-approve" name="action" value="Approve">Approve</button>
                                        </form>
                                        <form method="POST">
                                            <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['Application ID']) ?>">
                                            <button class="btn btn-reject" name="action" value="Decline">Reject</button>
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
            </div>
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

