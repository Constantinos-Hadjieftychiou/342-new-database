<?php
session_start();
require_once "connection.php";

// // Check if the user is logged in and is of type 'AA'
// if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'AA') {
//     header("Location: index.php");
//     exit();
// }

// try {
//     $conn = db_connect();

//     // Retrieve applications related to the logged-in AA user
//     $sql = "EXEC GetApplicationsForAA"; // Replace with the actual stored procedure or query
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
// } catch (PDOException $e) {
//     die("Error: " . $e->getMessage());
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AA Dashboard | Electric Future</title>
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

    .header nav a {
      color: white;
      text-decoration: none;
      font-size: 1rem;
    }

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

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
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

    .btn {
      background: #00c7a3;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      font-size: 0.9rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #009b85;
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
  <!-- Header -->
  <div class="header">
    <div class="logo">Electric Future</div>
    <nav>
      <a href="index.php">Logout</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="container">
    <h1>Welcome, AA User</h1>

    <!-- Section: View Applications -->
    <div class="section">
      <h2>View and Edit Applications</h2>
      <table>
        <thead>
          <tr>
            <th>Application ID</th>
            <th>Applicant Name</th>
            <th>Category</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($applications)): ?>
            <?php foreach ($applications as $app): ?>
              <tr>
                <td><?= htmlspecialchars($app['application_id']) ?></td>
                <td><?= htmlspecialchars($app['applicant_name']) ?></td>
                <td><?= htmlspecialchars($app['category']) ?></td>
                <td><?= htmlspecialchars($app['status']) ?></td>
                <td>
                  <form action="EditApplication.php" method="GET" style="display: inline;">
                    <input type="hidden" name="application_id" value="<?= htmlspecialchars($app['application_id']) ?>">
                    <button class="btn">Edit</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">No applications found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>Electric Future &copy; <?= date("Y") ?>. All rights reserved.</p>
  </div>
</body>
</html>
