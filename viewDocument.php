<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_POST['application_id'])) {
    echo "No application selected.";
    exit();
}

$applicationId = $_POST['application_id'];
$previousPage = $_SERVER['HTTP_REFERER'] ?? 'LT.php'; // Default to 'LT.php' if no referer
$documents = [];
$error = "";

try {
    $conn = db_connect();

    // Call the stored procedure to get documents for the selected application
    $stmt = $conn->prepare("{CALL ShowDocumentsApplication(?)}");
    $stmt->bindParam(1, $applicationId, PDO::PARAM_INT);
    $stmt->execute();
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$documents) {
        $error = "No documents found for this application.";
    }
} catch (PDOException $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Documents | EV Manager</title>
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
            background: linear-gradient(to bottom, #004c91, #87CEEB);
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

        .header nav a {
            text-decoration: none;
            font-size: 1rem;
            font-weight: bold;
            border-radius: 5px;
            padding: 10px 15px;
            background: white;
            color: #004c91;
            border: 2px solid #004c91;
            transition: all 0.3s ease;
        }

        .header nav a:hover {
            background: #f0f0f0;
        }

        /* Main Content */
        .container {
            max-width: 1100px;
            margin: 20px auto;
            padding: 20px;
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

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: #004c91;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Footer */
        .footer {
            margin-top: auto;
            background: #004c91;
            color: white;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            font-size: 0.9rem;
        }

        .btn-back {
            background-color: #004c91;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="logo">EV Manager</div>
        <nav>
            <a href="<?= htmlspecialchars($previousPage) ?>">Back</a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="container">
        <h1>Documents for Application ID: <?= htmlspecialchars($applicationId) ?></h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>Document ID</th>
                        <th>Review</th>
                        <th>Upload Date</th>
                        <th>Document Path</th>
                        <th>Passed the Check</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $document): ?>
                        <tr>
                            <td><?= htmlspecialchars($document['Application ID']) ?></td>
                            <td><?= htmlspecialchars($document['Document ID']) ?></td>
                            <td><?= htmlspecialchars($document['Review']) ?></td>
                            <td><?= htmlspecialchars($document['Upload Date']) ?></td>
                            <td><a href="<?= htmlspecialchars($document['Document Path']) ?>" target="_blank">View Document</a></td>
                            <td><?= htmlspecialchars($document['pass']) == '1' ? 'Yes' : 'No' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>KSK_Team_Rocket &copy; <?= date("Y") ?>. All rights reserved.</p>
    </div>
</body>
</html>
