<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'FY') {
    header("Location: index.php");
    exit();
}

try {
    $conn = db_connect();

    // Fetch all distinct categories for checkboxes
    $stmt = $conn->prepare("SELECT DISTINCT [type] FROM CategoryOfApplication ORDER BY [type] ASC");
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch the categories
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
    <title>Reports | EV Manager</title>
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
        <a href="FY.php">Back</a>
    </nav>
</div>

<div class="container">
    <h1>Applications Reports</h1>

    <form method="GET" action="">
        <!-- Report Selector -->
        <div>
            <label for="report_type">Select Report</label>
            <select id="report_type" name="report_type" required>
                <option value="" disabled selected>Select a Report</option>
                <option value="report1">Αναφορά Επιχορηγήσεων</option>
                <option value="report2">Αναφορές Στατιστικών αιτήσεων</option>
                <option value="report3">Αναφορές Ύψους επιχορηγήσεων</option>
                <option value="report4">Αναφορές Απόδοσης</option>

            </select>
        </div>

        <button type="submit" id="continue_button">Continue</button>
    </form>
</div>

<script>
    const reportType = document.getElementById('report_type');
    const continueButton = document.getElementById('continue_button');

    // Redirect to the appropriate report page
    continueButton.addEventListener('click', function (event) {
        const selectedReport = reportType.value;
        if (!selectedReport) {
            alert('Please select a report.');
            event.preventDefault();
            return;
        }
        const redirectUrl = `${selectedReport}.php`;
        this.form.action = redirectUrl;
    });
</script>

<div class="footer">
    <p>KSK_Team_Rocket&copy; <?= date("Y") ?>. All rights reserved.</p>
</div>
</body>
</html>
