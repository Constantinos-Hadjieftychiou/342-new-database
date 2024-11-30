<?php
session_start();
require_once "connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password'];

    try {
        $conn = db_connect();

        // Prepare the SQL to call the stored procedure
        $sql = "EXEC SignIn ?, ?";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $username);
        $stmt->bindParam(2, $password);

        // Execute the procedure
        $stmt->execute();

        // Fetch the single result directly (user_type)
        $user_type = $stmt->fetchColumn();
      
        if ($user_type) {
            $_SESSION['user_type'] = $user_type;
            $_SESSION['username'] = $username;

            // Redirection based on user_type
            if ($_SESSION['user_type'] === 'FY') { 
              header("Location: FY.php");
              exit();
            } else if($_SESSION['user_type'] === 'LT'){
                header("Location: LT.php");
                exit();
            }
            else if($_SESSION['user_type'] === 'AA'){
              header("Location: AA.php");
              exit();
          }else if($_SESSION['user_type'] === 'AX-NP' || $_SESSION['user_type'] === 'AX-FP' ){
            header("Location: AX.php");
            exit();
        }
        } else {
            // Handle case where user_type is not found
            $_SESSION['error'] = "Invalid username or password.";
            header("Location: index.php");
            exit();
        }
    } catch (PDOException $e) {
        // Handle exceptions
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: index.php");
        exit();
    }
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In | Electric Future</title>
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Roboto', sans-serif;
      background: #f4f4f4; /* Light neutral background color */
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
      color: #333;
    }

    /* Header */
    .header {
      width: 100%; /* Full width */
      height: 150px; /* Increased height */
      background: rgba(255, 255, 255, 0.95);
      padding: 30px 20px; /* Increased vertical padding */
      display: flex;
      justify-content: space-between; /* Align images across the width */
      align-items: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      left: 0;
      z-index: 1000;
    }

    .header img {
      height: 120px; /* Increased image height */
      flex: 1; /* Ensure images are evenly spaced */
      object-fit: contain;
      max-width: 300px;
    }

    .header img:nth-child(2) {
      margin: 0 50px; /* Add extra spacing between the center image */
    }

    /* Container */
    .container {
      background: rgba(255, 255, 255, 0.85); /* Semi-transparent white */
      max-width: 400px;
      width: 90%;
      padding: 20px 30px;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
      text-align: center;
      margin-top: 200px; /* Add space for the header */
    }

    h1 {
      font-size: 2rem;
      color: #004c91;
      margin-bottom: 10px;
    }

    .subtitle {
      font-size: 1rem;
      margin-bottom: 20px;
      color: #666;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .input-group {
      text-align: left;
    }

    .input-group label {
      font-weight: 500;
      margin-bottom: 5px;
      display: block;
      color: #333;
    }

    .input-group input,
    .input-group select {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      border: 1px solid #ddd;
      border-radius: 5px;
      transition: all 0.3s ease;
    }

    .input-group input:focus,
    .input-group select:focus {
      border-color: #0077cc;
      outline: none;
      box-shadow: 0 0 5px rgba(0, 119, 204, 0.5);
    }

    .btn {
      background: #00c7a3;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #009b85;
    }

    .signin-link {
      margin-top: 10px;
      font-size: 0.9rem;
    }

    .signin-link a {
      color: #0077cc;
      text-decoration: none;
    }

    .signin-link a:hover {
      text-decoration: underline;
    }

    @media (max-width: 768px) {
      .container {
        padding: 15px 20px;
      }

      .header {
        height: 100px; /* Reduced header size for smaller screens */
        padding: 20px 10px;
      }

      .header img {
        height: 80px;
      }

      .header img:nth-child(2) {
        margin: 0 20px; /* Adjust spacing between images */
      }
    }
  </style>
</head>
<body>
<div class="header">
    <img src="static/eu.png" alt="European Union">
    <img src="static/dimokratia.png" alt="Cyprus Government">
    <img src="static/kypros.png" alt="Cyprus Tomorrow">
</div>
<div class="background">
    <div class="container">
      <div class="signin-box">
        <h1>Sign In</h1>
        <p class="subtitle">Drive the future of sustainability.</p>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Your Username" required>
      </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="btn">Sign In</button>
        </form>
        <p class="signup-link">Don't have an account? <a href="signUp.php">Create one here</a>.</p>
      </div>
    </div>
</div>
</body>
</html>
