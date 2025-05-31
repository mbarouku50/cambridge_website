<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../connection.php");
session_start(); 

if(isset($_POST["submit"])){
    $email = $_POST['email'];
    $password = $_POST['Password'];

    if($email == ''){
        echo 'please fill email';
    }else if($password == ''){
        echo 'please fill password';
    } else{
        $new_password = sha1($password);
        $encrypted_password=sha1($email."_".$new_password);

        $query=mysqli_query($conn,"SELECT * FROM admin_users WHERE email='$email' AND password='$encrypted_password'");
        $num_of_row = mysqli_num_rows($query);

        if($num_of_row == 1){
            $_SESSION["email"] = $email;
            header("location:adminDashboard.php");
            exit();
        } else{
            echo"wrong details";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .contact-form-section {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 450px;
            transition: all 0.3s ease;
        }
        
        .contact-form-section:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        h3 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 24px;
            font-weight: 600;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 500;
        }
        
        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        input[type="email"]:focus,
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }
        
        input[type="submit"] {
            background-color:rgb(123, 200, 252);
            color: white;
            border: none;
            padding: 12px 20px;
            width: 100%;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: background-color 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        input[type="submit"]:hover {
            background-color:rgb(215, 225, 231);
        }
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .contact-form-section {
                padding: 20px;
            }
            
            h3 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="contact-form-section">
        <h3>Admin Login</h3>
        <form class="contact-form" action="#" method="POST">
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email"  name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password"  name="Password" required placeholder="Enter your password">
            </div>
            <input type="submit" name="submit" value="Login">
        </form>
    </div>
</body>
</html>