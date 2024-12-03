<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_POST['application_id']) && !isset($_GET['application_id'])) {
    echo "No application selected.";
    exit();
}

$applicationId = $_POST['application_id'] ?? $_GET['application_id'];
$previousPage = $_POST['previous_page'] ?? ($_SERVER['HTTP_REFERER'] ?? 'LT.php'); // Default to 'LT.php' if no referer
$applicationDetails = [];
$error = "";

try {
    $conn = db_connect();

    // Fetch details of the selected application
    $stmt = $conn->prepare("SELECT * FROM Application WHERE application_id = ?");
    $stmt->bindParam(1, $applicationId, PDO::PARAM_INT);
    $stmt->execute();
    $applicationDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$applicationDetails) {
        $error = "Application not found.";
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'], $_POST['text'], $_POST['pass'])) {
        $review = $_POST['review'];
        $documentPath = $_POST['text'];
        $valid = $_POST['pass'];

        // Call the stored procedure to add the review and document
        $stmt = $conn->prepare("{CALL AddReviewAndDocument(?, ?, ?, ?)}");
        $stmt->bindParam(1, $applicationId, PDO::PARAM_INT);
        $stmt->bindParam(2, $review, PDO::PARAM_STR);
        $stmt->bindParam(3, $documentPath, PDO::PARAM_STR);
        $stmt->bindParam(4, $valid, PDO::PARAM_INT);

        $stmt->execute();

        // Redirect to the previous page with a success message
        $_SESSION['message'] = "Document added successfully!";
        header("Location: " . htmlspecialchars($previousPage));
        exit();
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
    <title>Add Document | EV Manager</title>
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
            max-width: 800px;
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

        .details p {
            margin: 10px 0;
            font-size: 1rem;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        button {
            background: #004c91;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #003366;
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
        <h1>Add Document</h1>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($applicationDetails): ?>
            <div class="details">
                <p><strong>Application ID:</strong> <?= htmlspecialchars($applicationDetails['application_id']) ?></p>
                <p><strong>Submission Date:</strong> <?= htmlspecialchars($applicationDetails['submission_date']) ?></p>
                <p><strong>Status:</strong> <?= htmlspecialchars($applicationDetails['application_status']) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="application_id" value="<?= htmlspecialchars($applicationId) ?>">
            <input type="hidden" name="previous_page" value="<?= htmlspecialchars($previousPage) ?>">
            <label for="review">Give a review</label>
            <input type="text" id="review" name="review" required>
            <label for="text">Document Path</label>
            <input type="text" id="text" name="text" required>
            <label for="pass">Applicant is Valid</label>
            <select id="pass" name="pass" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
            <button type="submit">Submit</button>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>KSK_Team_Rocket &copy; <?= date("Y") ?>. All rights reserved.</p>
    </div>
</body>
</html>
