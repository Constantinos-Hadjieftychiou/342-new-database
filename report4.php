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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $reportType = $_POST['report_type'] ?? null;
        $timePeriod = $_POST['time_period'] ?? null;
        $startDate = $_POST['start_date'] ?? null;
        $endDate = $_POST['end_date'] ?? null;
        $minApplications = $_POST['min_applications'] ?? null;

        if (!$reportType) {
            throw new Exception("Please select a report type.");
        }

        // Call the GenerateAdvancedReports stored procedure with the selected report type
        $stmt = $conn->prepare("EXEC GenerateAdvancedReports 
            @report_type = :report_type, 
            @time_period = :time_period, 
            @start_date = :start_date, 
            @end_date = :end_date, 
            @min_applications = :min_applications
        ");
        $stmt->bindParam(':report_type', $reportType, PDO::PARAM_INT);
        $stmt->bindParam(':time_period', $timePeriod, PDO::PARAM_STR);
        $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->bindParam(':min_applications', $minApplications, PDO::PARAM_INT);
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
    <title>Report 4 | EV Manager</title>
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
        #time_period {
    display: none; /* Completely hides the select box */
}

    </style>
   <script>
    function toggleFields() {
        const reportType = document.getElementById("report_type").value;
        const timePeriodField = document.getElementById("time_period");
        const timePeriodSection = document.getElementById("time_period_section");
        const minApplicationsSection = document.getElementById("min_applications_section");

        if (reportType === "1") {
            // Fix time period to "Custom" and hide the field
            timePeriodField.value = "custom";
            timePeriodSection.classList.remove("hidden");
        } else if (reportType === "3") {
            // Show only the Minimum Applications field for Report Type 3
            timePeriodSection.classList.add("hidden");
            minApplicationsSection.classList.remove("hidden");
        } else {
            // Show time period and hide Minimum Applications for other report types
            timePeriodSection.classList.add("hidden");
            minApplicationsSection.classList.add("hidden");
        }
    }

    // Initialize the visibility on page load
    document.addEventListener("DOMContentLoaded", () => {
        toggleFields(); // Ensure the correct fields are shown/hidden based on the initial state
    });
</script>

</head>
<body>
<div class="header">
    <div class="logo">EV Manager</div>
    <nav>
        <a href="reports.php">Back</a>
    </nav>
</div>

<div class="container">
    <h1>Generate Report 4</h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" onchange="toggleFields()">
        <label for="report_type">Select Report Type</label>
        <select name="report_type" id="report_type" required>
            <option value="" disabled selected>Select a Report</option>
            <option value="1" <?= $reportType == 1 ? 'selected' : '' ?>>Report 1: Legal Entities Applications</option>
            <option value="2" <?= $reportType == 2 ? 'selected' : '' ?>>Report 2: Categories with Monthly Applications</option>
            <option value="3" <?= $reportType == 3 ? 'selected' : '' ?>>Report 3: Categories with Minimum Applications</option>
        </select>

        <div id="time_period_section" class="hidden">
    <select name="time_period" id="time_period">
        <option value="custom" selected>Custom</option>
    </select>

    <label for="start_date">Start Date (for custom period)</label>
    <input type="date" name="start_date" id="start_date">

    <label for="end_date">End Date (for custom period)</label>
    <input type="date" name="end_date" id="end_date">
</div>




        <div id="min_applications_section" class="hidden">
            <label for="min_applications">Minimum Applications (for Report 3 only)</label>
            <input type="number" name="min_applications" id="min_applications" placeholder="Enter minimum applications">
        </div>

        <button type="submit">Generate Report</button>
    </form>

    <?php if (!empty($applications)): ?>
        <div class="scrollable-table-container">
            <table>
                <thead>
                    <tr>
                        <?php if ($reportType == 1): ?>
                            <th>Legal Entity</th>
                            <th>Category Type</th>
                            <th>Application Status</th>
                            <th>Submission Date</th>
                        <?php elseif ($reportType == 2): ?>
                            <th>Category Type</th>
                            <th>Total Applications</th>
                        <?php elseif ($reportType == 3): ?>
                            <th>Category Type</th>
                            <th>Year</th>
                            <th>Total Applications</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($applications as $application): ?>
                        <tr>
                            <?php if ($reportType == 1): ?>
                                <td><?= htmlspecialchars($application['Legal Entity']) ?></td>
                                <td><?= htmlspecialchars($application['Category Type']) ?></td>
                                <td><?= htmlspecialchars($application['Application Status']) ?></td>
                                <td><?= htmlspecialchars($application['Submission Date']) ?></td>
                            <?php elseif ($reportType == 2): ?>
                                <td><?= htmlspecialchars($application['Category Type']) ?></td>
                                <td><?= htmlspecialchars($application['Total Applications']) ?></td>
                            <?php elseif ($reportType == 3): ?>
                                <td><?= htmlspecialchars($application['Category Type']) ?></td>
                                <td><?= htmlspecialchars($application['Year']) ?></td>
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
