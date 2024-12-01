<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_POST['application_id'])) {
    echo "No application selected.";
    exit();
}

$applicationId = $_POST['application_id'];
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
    <title>View Documents | Electric Future</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #004c91;
        }
        .error {
            color: red;
            margin-bottom: 10px;
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
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            background-color: #004c91;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #003366;
        }
    </style>
</head>
<body>
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
                        <th>Passed the check</th>
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

<button onclick="history.back()" class="btn-back">Back to Previous Page</button>
    </div>
</body>
</html>
