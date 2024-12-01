<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_POST['application_id'])) {
    echo "No application selected.";
    exit();
}

$applicationId = $_POST['application_id'];
$applicationDetails = [];
$error = "";
$message = "";

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
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review'], $_POST['text'])) {
        $review = $_POST['review'];
        $documentPath = $_POST['text'];
        $valid =$_POST['pass'];

        // Call the stored procedure to add the review and document
        $stmt = $conn->prepare("{CALL AddReviewAndDocument(?, ?, ?,?)}");
        $stmt->bindParam(1, $applicationId, PDO::PARAM_INT);
        $stmt->bindParam(2, $review, PDO::PARAM_STR);
        $stmt->bindParam(3, $documentPath, PDO::PARAM_STR);
        $stmt->bindParam(4, $valid, PDO::PARAM_INT);

        $stmt->execute();

        // Redirect to AA.php with a success message
        $_SESSION['message'] = "Document added successfully!";
        header("Location: AA.php");
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
    <title>Add Document | Electric Future</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
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
        .details {
            margin-bottom: 20px;
        }
        .details label {
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background: #004c91;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #003366;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add Document</h1>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php elseif ($applicationDetails): ?>
            <div class="details">
                <p><label>Application ID:</label> <?= htmlspecialchars($applicationDetails['application_id']) ?></p>
                <p><label>Submission Date:</label> <?= htmlspecialchars($applicationDetails['submission_date']) ?></p>
                <p><label>Status:</label> <?= htmlspecialchars($applicationDetails['application_status']) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="application_id" value="<?= htmlspecialchars($applicationId) ?>">
            <div class="form-group">
                <label for="review">Give a review</label>
                <input type="text" id="review" name="review" required>
            </div>
            <div class="form-group">
                <label for="text">Document Path</label>
                <input type="text" id="text" name="text" required>
            </div>
            <div class="input-group">
        <label for="pass">Applicant is Valid</label>
        <select id="pass" name="pass" required>
          <option value=1>Yes</option>
          <option value=0>No</option>
        </select>
      </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
