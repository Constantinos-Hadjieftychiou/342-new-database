<?php
// Start session
session_start();

// Debug: Check session variables
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
// print_r($_SESSION);

// Validate session variables (if necessary)
// if (!isset($_SESSION["serverName"]) || !isset($_SESSION["connectionOptions"])) {
//     die("Session not initialized properly. Please return to the start page.");
// }

// Debug: Connect to database (Optional, if needed)
// $connection = new mysqli(
//     $_SESSION["serverName"],
//     $_SESSION["connectionOptions"]["Uid"],
//     $_SESSION["connectionOptions"]["PWD"],
//     $_SESSION["connectionOptions"]["Database"]
// );
// if ($connection->connect_error) {
//     die("Connection failed: " . $connection->connect_error);
// }
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
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
      color: #333;
      background-color: #f9f9f9;
    }

    a {
      text-decoration: none;
      color: #0077cc;
    }

    a:hover {
      text-decoration: underline;
    }

    /* Background */
    .background {
      background: linear-gradient(to bottom right, #004c91, #00c7a3);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #fff;
    }

    /* Container */
    .container {
      background: #fff;
      max-width: 500px;
      width: 100%;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      text-align: center;
    }

    /* Logo */
    .logo img {
      width: 80px;
      margin-bottom: 20px;
    }

    /* Titles and Subtitles */
    h1 {
      font-size: 1.8em;
      margin-bottom: 10px;
      color: #004c91;
    }

    .subtitle {
      font-size: 1em;
      margin-bottom: 20px;
      color: #666;
    }

    /* Form Styles */
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .input-group {
      text-align: left;
    }

    .input-group label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      color: #333;
    }

    .input-group input,
    .input-group select {
      width: 100%;
      padding: 10px;
      font-size: 1em;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .input-group input:focus,
    .input-group select:focus {
      border-color: #0077cc;
      outline: none;
    }

    /* Buttons */
    .btn {
      background: #00c7a3;
      color: #fff;
      border: none;
      padding: 10px 15px;
      font-size: 1em;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background: #009b85;
    }

    /* Links */
    .signup-link,
    .signin-link {
      margin-top: 10px;
      font-size: 0.9em;
    }

    .signup-link a,
    .signin-link a {
      color: #0077cc;
    }

    .signup-link a:hover,
    .signin-link a:hover {
      text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .container {
        padding: 15px;
      }

      h1 {
        font-size: 1.5em;
      }
    }
  </style>
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
