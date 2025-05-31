<?php
session_start();
include("../connection.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if(!isset($_SESSION['email'])) {
    header("Location: adminLogin.php");
    exit();
}

// Initialize variables
$admin_email = $_SESSION['email'];
$message = '';
$success = false;
$admin_data = [];

// Fetch current admin data
$query = "SELECT * FROM admin_users WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $admin_email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0) {
    $admin_data = mysqli_fetch_assoc($result);
}
mysqli_stmt_close($stmt);

// Handle form submissions
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Update profile information
        if(isset($_POST['update_profile'])) {
            
            $query = "UPDATE admin_users SET email = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "sss", $fullname, $phone, $admin_email);
            
            if(!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to update profile: " . mysqli_error($conn));
            }
            
            mysqli_stmt_close($stmt);
            
            // Update session data
            $_SESSION['fullname'] = $fullname;
            
            $success = true;
            $message = "Profile updated successfully!";
        }
        
        // Change password
        if(isset($_POST['change_password'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Verify current password
            if(!password_verify($current_password, $admin_data['password'])) {
                throw new Exception("Current password is incorrect");
            }
            
            // Validate new password
            if(empty($new_password) || strlen($new_password) < 8) {
                throw new Exception("New password must be at least 8 characters");
            }
            
            if($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match");
            }
            
            // Hash new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $query = "UPDATE admin_users SET password = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $hashed_password, $admin_email);
            
            if(!mysqli_stmt_execute($stmt)) {
                throw new Exception("Failed to update password: " . mysqli_error($conn));
            }
            
            mysqli_stmt_close($stmt);
            
            $success = true;
            $message = "Password changed successfully!";
        }
        
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
    
    $_SESSION['message'] = $message;
    $_SESSION['success'] = $success;
    header("Location: settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Settings | Clothing Shop</title>
    <style>
        :root {
            --primary-color: #3498db;
            --dark-color: #000000;
            --light-color: #ffffff;
            --gray-color: #f5f5f5;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--gray-color);
            min-height: 100vh;
        }
        
        .dashboard-header {
            background-color: var(--dark-color);
            color: var(--light-color);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        
        .logo span {
            color: var(--primary-color);
        }
        
        .nav-menu {
            display: flex;
            gap: 20px;
        }
        
        .nav-menu a {
            color: var(--light-color);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .nav-menu a:hover {
            color: var(--primary-color);
        }
        
        .dashboard-container {
            display: flex;
        }
        
        .sidebar {
            width: 250px;
            background-color: var(--dark-color);
            color: var(--light-color);
            height: calc(100vh - 60px);
            padding: 20px 0;
        }
        
        .sidebar-menu {
            list-style: none;
        }
        
        .sidebar-menu li {
            padding: 15px 25px;
            transition: all 0.3s;
        }
        
        .sidebar-menu li:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu a {
            color: var(--light-color);
            text-decoration: none;
            display: block;
        }
        
        .sidebar-menu li.active {
            background-color: var(--primary-color);
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .page-title {
            color: var(--dark-color);
            font-size: 24px;
        }
        
        .settings-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            max-width: 600px;
        }
        
        .section-title {
            color: var(--dark-color);
            font-size: 20px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c0392b;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo">Clothing<span>Shop</span></div>
        <nav class="nav-menu">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['email']); ?></span>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="adminDashboard.php">Dashboard</a></li>
                <li><a href="product.php">Products</a></li>
                <li class="active"><a href="settings.php">Settings</a></li>
                <li><a href="#">Comments</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Admin Settings</h1>
            </div>
            
            <?php if(isset($_SESSION['message'])): ?>
                <div class="alert <?php echo $_SESSION['success'] ? 'alert-success' : 'alert-danger'; ?>">
                    <?php 
                    echo htmlspecialchars($_SESSION['message']); 
                    unset($_SESSION['message']);
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="settings-section">
                <h2 class="section-title">Profile Information</h2>
                <form action="settings.php" method="POST">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" class="form-control" 
                               value="<?php echo htmlspecialchars($admin_data['email'] ?? ''); ?>" >
                    </div>
                    <div class="form-group">
                        <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
                    </div>
                </form>
            </div>
            
            <div class="settings-section">
                <h2 class="section-title">Change Password</h2>
                <form action="settings.php" method="POST">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="form-control" required>
                        <small>Password must be at least 8 characters</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="change_password" class="btn btn-primary">Change Password</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>