<?php
    include("index.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In </title>
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
    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
        <h2>Welcome to Fakebook!</h2>
        username:<br>
        <input type="text" name="username"><br>
        password:<br>
        <input type="password" name="password"><br>
        <input type="submit" name="submit" value="register">
    </form>
</body>
</html>
<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    
        if(empty($username)){
            echo"Please enter a username";
        }
        elseif(empty($password)){
            echo"Please enter a password";
        }
        else{
            $hash = password_hash($password, PASSWORD_DEFAULT); 
            $sql = "INSERT INTO users (user, password)
                    VALUES ('$username', '$hash')";
            
            try{
                mysqli_query($conn, $sql);
                echo"You are now registered!";
            }
            catch(mysqli_sql_exception){
                echo"That username is taken";
            }
        }
    }

    mysqli_close($conn);
?>

<?php
    // $db_server = "localhost";
    // $db_user = "root";
    // $db_pass = "";
    // $db_name = "businessdb";
    // $conn = "";

    // try{
    //     $conn = mysqli_connect($db_server, 
    //                                                 $db_user, 
    //                                                 $db_pass, 
    //                                                 $db_name);
    // }
    // catch(mysqli_sql_exception){
    //     echo"Could not connect! <br>";
    // }
?>