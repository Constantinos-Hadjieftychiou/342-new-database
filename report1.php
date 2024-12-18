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

    // Fetch all distinct categories for checkboxes
    $stmt = $conn->prepare("SELECT DISTINCT [type] FROM CategoryOfApplication ORDER BY [type] ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $categoriesParam = isset($_POST['categories']) ? implode(',', $_POST['categories']) : null;
        $sortBy = 'amount';
        $order = $_POST['order'] ?? '';
        $amountType = $_POST['amount_type'] ?? '';

        if (!$order || !$amountType) {
            throw new Exception("Please provide all required fields: Order and Amount Type.");
        }

        // Call the GroupApplicationsByCategory stored procedure
        $stmt = $conn->prepare("EXEC GroupApplicationsByCategory @categories = ?, @sort_by = ?, @order = ?, @amount_type = ?");
        $stmt->bindParam(1, $categoriesParam, PDO::PARAM_STR);
        $stmt->bindParam(2, $sortBy, PDO::PARAM_STR);
        $stmt->bindParam(3, $order, PDO::PARAM_STR);
        $stmt->bindParam(4, $amountType, PDO::PARAM_STR);
        $stmt->execute();

        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    $error = "Database Error: " . $e->getMessage();
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report 1 | EV Manager</title>
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
        table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table th, table td {
        padding: 10px 15px; /* Add padding for better spacing */
        text-align: left;
        border: 1px solid #ddd; /* Add border for better column separation */
        font-size: 1rem;
    }
    table th {
        background: #004c91; /* Header background color */
        color: white; /* Header text color */
    }
    table tr:nth-child(even) {
        background-color: #f9f9f9; /* Alternate row colors for better readability */
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
        .container {
            max-width: 1200px;
            width: 90%;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-size: 2rem;
            text-align: center;
            margin-bottom: 20px;
            color: #004c91;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 30px;
        }
        select, button {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        select:hover {
            border-color: #004c91;
        }
        button {
            background-color: #004c91;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            background-color: #003366;
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
        <a href="reports.php">Back</a>
    </nav>
</div>

<div class="container">
    <h1>Report 1: Applications by Category</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Select Categories (Optional)</label>
        <div class="checkbox-group">
            <?php foreach ($categories as $category): ?>
                <div class="checkbox-item">
                    <input type="checkbox" name="categories[]" value="<?= htmlspecialchars($category['type']) ?>" id="category_<?= htmlspecialchars($category['type']) ?>">
                    <label for="category_<?= htmlspecialchars($category['type']) ?>"><?= htmlspecialchars($category['type']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>

        <label for="order">Order</label>
        <select name="order" id="order" required>
            <option value="ASC">Ascending</option>
            <option value="DESC">Descending</option>
        </select>

        <label for="amount_type">Amount Type</label>
        <select name="amount_type" id="amount_type" required>
            <option value="amount used">Amount Used</option>
            <option value="amount left">Amount Left</option>
        </select>

        <button type="submit">Generate Report</button>
    </form>

    <?php if (!empty($applications)): ?>
        <div class="scrollable-table-container">
            <table>
                <thead>
                    <tr>
                        <th>Category Type</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <td><?= htmlspecialchars($application['Category Type']) ?></td>
                            <td><?= htmlspecialchars($application['Amount Used'] ?? $application['Amount Left']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="footer">
    <p>KSK_Team_Rocket&copy; <?= date("Y") ?>. All rights reserved.</p>
</div>
</body>
</html>
