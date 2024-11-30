<?php
session_start();
require_once "connection.php"; // Include the connection file for database connection

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'FY') {
  header("Location: index.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>FY Dashboard | Electric Future</title>
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

    .header nav {
      display: flex;
      gap: 20px;
    }

    .header nav a {
      color: white;
      text-decoration: none;
      font-size: 1rem;
      transition: color 0.3s;
    }

    .header nav a:hover {
      color: #00c7a3;
    }

    /* Main Content */
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
    <h1>Welcome, FY User</h1>

    <!-- Section: Verify Users -->
    <div class="section">
      <h2>Verify Users (LT / AA)</h2>
      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>User Type</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Sample Data -->
          <tr>
            <td>john_doe</td>
            <td>John Doe</td>
            <td>john@example.com</td>
            <td>LT</td>
            <td>
              <button class="btn">Verify</button>
            </td>
          </tr>
          <tr>
            <td>jane_smith</td>
            <td>Jane Smith</td>
            <td>jane@example.com</td>
            <td>AA</td>
            <td>
              <button class="btn">Verify</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Section: View Applications -->
    <div class="section">
      <h2>View Applications (AX-NP / AX-FP)</h2>
      <table>
        <thead>
          <tr>
            <th>Application ID</th>
            <th>Applicant Name</th>
            <th>Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Sample Data -->
          <tr>
            <td>1001</td>
            <td>Michael Brown</td>
            <td>AX-NP</td>
            <td>Pending</td>
            <td>
              <button class="btn">Review</button>
            </td>
          </tr>
          <tr>
            <td>1002</td>
            <td>Susan Lee</td>
            <td>AX-FP</td>
            <td>Pending</td>
            <td>
              <button class="btn">Review</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Section: Verify Applications -->
    <div class="section">
      <h2>Verify Applications</h2>
      <p>Select an application to approve or reject.</p>
      <table>
        <thead>
          <tr>
            <th>Application ID</th>
            <th>Details</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- Sample Data -->
          <tr>
            <td>1001</td>
            <td>Electric Vehicle Purchase by Michael Brown</td>
            <td>
              <button class="btn">Approve</button>
              <button class="btn">Reject</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    <p>Electric Future &copy; <?php echo date("Y"); ?>. All rights reserved.</p>
  </div>
</body>
</html>