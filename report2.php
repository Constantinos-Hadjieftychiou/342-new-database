<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'FY') {
    header("Location: index.php");
    exit();
}

$applications = [];
$error = "";
$reportType = null;

try {
    $conn = db_connect();

    // Fetch all distinct categories for checkboxes
    $stmt = $conn->prepare("SELECT DISTINCT [type] FROM CategoryOfApplication ORDER BY [type] ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reportType = $_POST['report_type'] ?? null;
        $timePeriod = $_POST['time_period'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $categoriesParam = isset($_POST['categories']) ? implode(',', $_POST['categories']) : null;
        $applicantType = $_POST['applicant_type'] ?? null;

        if (!$reportType) {
            throw new Exception("Please select a report type.");
        }

        // Call the GenerateReport stored procedure with the selected report type
        $stmt = $conn->prepare("EXEC GenerateReport 
            @report_type = :report_type, 
            @time_period = :time_period, 
            @start_date = :start_date, 
            @end_date = :end_date, 
            @categories = :categories, 
            @applicant_type = :applicant_type
        ");
        $stmt->bindParam(':report_type', $reportType, PDO::PARAM_INT);
        $stmt->bindParam(':time_period', $timePeriod, PDO::PARAM_STR);
        $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->bindParam(':categories', $categoriesParam, PDO::PARAM_STR);
        $stmt->bindParam(':applicant_type', $applicantType, PDO::PARAM_STR);
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
    <title>Report 2 | Electric Future</title>
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
        select, button, input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        select:hover, input:hover {
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
        .scrollable-table-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px 15px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background: #004c91;
            color: white;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
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
        /* Hide dynamically */
        .hidden {
            display: none;
        }
    </style>
    <script>
        // JavaScript to toggle the visibility of fields
        function toggleFields() {
            const reportType = document.getElementById("report_type").value;
            const categorySection = document.getElementById("category_section");
            const applicantSection = document.getElementById("applicant_section");

            // Hide or show fields based on report_type
            if (reportType === "2") {
                categorySection.classList.add("hidden");
                applicantSection.classList.remove("hidden");
            } else if (reportType === "4") {
                categorySection.classList.add("hidden");
                applicantSection.classList.add("hidden");
            } else {
                categorySection.classList.remove("hidden");
                applicantSection.classList.remove("hidden");
            }
        }

        // Initialize the visibility on page load
        document.addEventListener("DOMContentLoaded", toggleFields);
    </script>
</head>
<body>
<div class="header">
    <div class="logo">Electric Future</div>
    <nav>
        <a href="reports.php">Back</a>
    </nav>
</div>
<div class="container">
    <h1>Generate Report</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" onchange="toggleFields()">
        <label for="report_type">Select Report Type</label>
        <select name="report_type" id="report_type" required>
            <option value="" disabled selected>Select a Report</option>
            <option value="1" <?= $reportType == 1 ? 'selected' : '' ?>>Report 1: Total Applications</option>
            <option value="2" <?= $reportType == 2 ? 'selected' : '' ?>>Report 2: Trends by Category</option>
            <option value="3" <?= $reportType == 3 ? 'selected' : '' ?>>Report 3: Success Percentage</option>
            <option value="4" <?= $reportType == 4 ? 'selected' : '' ?>>Report 4: Applications Over Time</option>
        </select>

        <label for="time_period">Time Period</label>
        <select name="time_period" id="time_period">
            <option value="" selected>All Time</option>
            <option value="daily">Daily</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="custom">Custom</option>
        </select>

        <label for="start_date">Start Date (for custom period)</label>
        <input type="date" name="start_date" id="start_date">

        <label for="end_date">End Date (for custom period)</label>
        <input type="date" name="end_date" id="end_date">

        <div id="category_section">
            <label>Select Categories (Optional)</label>
            <div class="checkbox-group">
                <?php foreach ($categories as $category): ?>
                    <div class="checkbox-item">
                        <input type="checkbox" name="categories[]" value="<?= htmlspecialchars($category['type']) ?>" id="category_<?= htmlspecialchars($category['type']) ?>">
                        <label for="category_<?= htmlspecialchars($category['type']) ?>"><?= htmlspecialchars($category['type']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div id="applicant_section">
            <label for="applicant_type">Applicant Type</label>
            <select name="applicant_type" id="applicant_type">
                <option value="" selected>All</option>
                <option value="AX-FP">AX-FP</option>
                <option value="AX-NP">AX-NP</option>
            </select>
        </div>

        <button type="submit">Generate Report</button>
    </form>

    <?php if (!empty($applications)): ?>
        <div class="scrollable-table-container">
            <table>
                <thead>
                    <tr>
                        <?php if ($reportType == 1): ?>
                            <th>Total Applications</th>
                        <?php elseif ($reportType == 2): ?>
                            <th>Category Type</th>
                            <th>Total Applications</th>
                            <th>Percentage of Total</th>
                        <?php elseif ($reportType == 3): ?>
                            <th>Success Percentage</th>
                        <?php elseif ($reportType == 4): ?>
                            <th>Submission Date</th>
                            <th>Total Applications</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <?php if ($reportType == 1): ?>
                                <td><?= htmlspecialchars($application['Total Applications']) ?></td>
                            <?php elseif ($reportType == 2): ?>
                                <td><?= htmlspecialchars($application['Category Type']) ?></td>
                                <td><?= htmlspecialchars($application['Total Applications']) ?></td>
                                <td><?= htmlspecialchars($application['Percentage of Total']) ?>%</td>
                            <?php elseif ($reportType == 3): ?>
                                <td><?= htmlspecialchars($application['Success Percentage']) ?>%</td>
                            <?php elseif ($reportType == 4): ?>
                                <td><?= htmlspecialchars($application['Submission Date']) ?></td>
                                <td><?= htmlspecialchars($application['Total Applications']) ?></td>
                            <?php endif; ?>
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
