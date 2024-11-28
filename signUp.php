<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up | Electric Future</title>
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
  <!-- Header -->
  <div class="header">
    <img src="static/eu.png" alt="European Union">
    <img src="static/dimokratia.png" alt="Cyprus Government">
    <img src="static/kypros.png" alt="Cyprus Tomorrow">
  </div>

  <!-- Signup Form -->
  <div class="container">
    <h1>Create Your Account</h1>
    <p class="subtitle">Join us on the journey to a sustainable future.</p>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
      <!-- Personal Information -->
      <div class="input-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" placeholder="you@example.com" required>
          </div>
      <div class="input-group">
        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" placeholder="Your First Name" required>
      </div>
      <div class="input-group">
        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" placeholder="Your Last Name" required>
      </div>
      <div class="input-group">
        <label for="birth_date">Birth Date</label>
        <input type="date" id="birth_date" name="birth_date" required>
      </div>
      <div class="input-group">
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
          <option value="M">Male</option>
          <option value="F">Female</option>
        </select>
      </div>

      <!-- Account Information -->
      <div class="input-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Your Username" required>
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Create a strong password" required>
      </div>
      <div class="input-group">
        <label for="identity_number">Identity Number</label>
        <input type="text" id="identity_number" name="identity_number" placeholder="Your ID Number" required>
      </div>
      <div class="input-group">
        <label for="user_type">User Type</label>
        <select id="user_type" name="user_type" required>
          <option value="LT">Λειτουργός Τμήματος Οδικών Μεταφορών</option>
          <option value="AA">Αντιπρόσωπος Αυτοκινήτων</option>
          <option value="AX-NP">Απλός Χρήστης-Νομικό Πρόσωπο</option>
          <option value="AX-FP">Απλός Χρήστης-Φυσικό Πρόσωπο</option>
        </select>
      </div>

      <!-- Legal Information -->
      <div class="input-group">
        <label for="valid_legalization">Valid Legalization</label>
        <select id="valid_legalization" name="valid_legalization">
          <option value="1">Yes</option>
          <option value="0">No</option>
        </select>
      </div>

      <!-- Hidden fields to set default values -->
      <input type="hidden" name="is_active" value="1">
      <input type="hidden" name="registration_date" value="<?php echo date('Y-m-d'); ?>">

      <!-- Submit -->
      <button type="submit" class="btn">Sign Up</button>
    </form>
    <p class="signin-link">Already have an account? <a href="index.php">Sign in here</a>.</p>
  </div>
</body>
</html>

<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
        $first_name = filter_input(INPUT_POST, "first_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $last_name = filter_input(INPUT_POST, "last_name", FILTER_SANITIZE_SPECIAL_CHARS);
        $birth_date = $_POST['birth_date']; // Date inputs don't need special sanitization
        $gender = $_POST['gender']; // Select values are predefined
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Securely hash passwords
        $identity_number = filter_input(INPUT_POST, "identity_number", FILTER_SANITIZE_SPECIAL_CHARS);
        $user_type = $_POST['user_type']; // Select values are predefined
        $valid_legalization = $_POST['valid_legalization']; // Select values are predefined
        $is_active = $_POST['is_active']; // Hidden field
        $registration_date = $_POST['registration_date']; // Hidden field
    
        // Database connection
        $mysqli = new mysqli("mssql.cs.ucy.ac.cy", "chadji10", "P5wHZj8v", "chadji10");
    
        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }
    
        // Call stored procedure
        $procedure = "CALL RegisterUser(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($procedure);
    
        if ($stmt) {
            // Bind parameters to the stored procedure
            $stmt->bind_param(
                "ssssssssssis",
                $email,
                $first_name,
                $last_name,
                $birth_date,
                $gender,
                $username,
                $password,
                $identity_number,
                $user_type,
                $valid_legalization,
                $is_active,
                $registration_date
            );
    
            // Execute the stored procedure
            if ($stmt->execute()) {
                echo "Registration successful!";
            } else {
                echo "Error executing stored procedure: " . $stmt->error;
            }
    
            // Close statement
            $stmt->close();
        } else {
            echo "Error preparing stored procedure: " . $mysqli->error;
        }
    
        // Close connection
        $mysqli->close();
        // if(empty($username)){
        //     echo"Please enter a username";
        // }
        // elseif(empty($password)){
        //     echo"Please enter a password";
        // }
        // else{
        //     $hash = password_hash($password, PASSWORD_DEFAULT); 
        //     $sql = "INSERT INTO users (user, password)
        //             VALUES ('$username', '$hash', '$first_name', '$last_name',')";
            
        //     try{
        //         mysqli_query($conn, $sql);
        //         echo"You are now registered!";
        //     }
        //     catch(mysqli_sql_exception){
        //         echo"That username is taken";
        //     }
        // }
    }

  
?>