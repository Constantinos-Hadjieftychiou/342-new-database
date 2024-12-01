<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'FY') {
    header("Location: index.php");
    exit();
}

$applications = [];
$error = "";

try {
    $conn = db_connect();

    // Fetch all distinct categories for filtering
    $stmt = $conn->prepare("SELECT DISTINCT [type] FROM CategoryOfApplication");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch applications filtered by the selected category
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category'])) {
        $selectedCategory = $_POST['category'];

        $stmt = $conn->prepare("SELECT * FROM Application WHERE [type] = ?");
        $stmt->bindParam(1, $selectedCategory, PDO::PARAM_STR);
        $stmt->execute();
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Reports | Electric Future</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
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
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        select, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        button {
            background: #004c91;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #003366;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Applications Reports</h1>

        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Category Filter Form -->
        <form method="POST" class="form-group">
            <label for="category">Select a Category:</label>
            <select name="category" id="category" required>
                <option value="" disabled selected>Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['type']) ?>">
                        <?= htmlspecialchars($category['type']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filter Applications</button>
        </form>

        <!-- Applications Table -->
        <?php if (!empty($applications)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Application ID</th>
                        <th>User ID</th>
                        <th>Submission Date</th>
                        <th>Is Active</th>
                        <th>Status</th>
                        <th>File Path</th>
                        <th>Category Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?= htmlspecialchars($application['application_id']) ?></td>
                            <td><?= htmlspecialchars($application['user_id']) ?></td>
                            <td><?= htmlspecialchars($application['submission_date']) ?></td>
                            <td><?= $application['is_active'] ? 'Yes' : 'No' ?></td>
                            <td><?= htmlspecialchars($application['application_status']) ?></td>
                            <td><a href="<?= htmlspecialchars($application['file_path']) ?>" target="_blank">View File</a></td>
                            <td><?= htmlspecialchars($application['type']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <p>No applications found for the selected category.</p>
        <?php endif; ?>
    </div>
</body>
</html>
