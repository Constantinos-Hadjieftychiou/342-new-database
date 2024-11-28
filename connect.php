<?php
// Start session
session_start();

// Validate session variables (Optional)
if (!isset($_SESSION["serverName"]) || !isset($_SESSION["connectionOptions"])) {
    die("Session not initialized properly. Please return to the start page.");
}

// Check database connection (Optional)
$connection = new mysqli(
    $_SESSION["serverName"],
    $_SESSION["connectionOptions"]["Uid"],
    $_SESSION["connectionOptions"]["PWD"],
    $_SESSION["connectionOptions"]["Database"]
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// If the connection is successful, display the HTML code for the sign-in page.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign In | Electric Future</title>
  <link rel="stylesheet" href="static/styles.css"> <!-- Adjust the path if needed -->
</head>
<body>
  <div class="background">
    <div class="container">
      <div class="logo">
        <img src="https://via.placeholder.com/100" alt="Electric Future Logo">
      </div>
      <div class="signin-box">
        <h1>Sign In</h1>
        <p class="subtitle">Drive the future of sustainability.</p>
        <form action="/signIn" method="POST">
          <div class="input-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required>
          </div>
          <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
          </div>
          <button type="submit" class="btn">Sign In</button>
        </form>
        <p class="signup-link">Don't have an account? <a href="/signUp">Create one here</a>.</p>
      </div>
    </div>
  </div>
</body>
</html>
