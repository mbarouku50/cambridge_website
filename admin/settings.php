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
            
            // Verify current password - matches your login method
            $current_password_hashed = sha1($admin_email."_".sha1($current_password));
            if($current_password_hashed !== $admin_data['password']) {
                throw new Exception("Current password is incorrect");
            }
            
            // Validate new password
            if(empty($new_password) || strlen($new_password) < 8) {
                throw new Exception("New password must be at least 8 characters");
            }
            
            if($new_password !== $confirm_password) {
                throw new Exception("New passwords do not match");
            }
            
            // Hash the new password the same way as login
            $new_password_hashed = sha1($admin_email."_".sha1($new_password));
            
            $query = "UPDATE admin_users SET password = ? WHERE email = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "ss", $new_password_hashed, $admin_email);
            
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
            --primary-color: #6C63FF;
            --primary-light: #a29bfe;
            --dark-color: #2D3436;
            --dark-light: #636e72;
            --light-color: #ffffff;
            --gray-color: #f8f9fa;
            --gray-dark: #dfe6e9;
            --danger-color: #d63031;
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --sidebar-width: 280px;
            --header-height: 70px;
            --transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--gray-color);
            color: var(--dark-color);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .dashboard-header {
            background-color: var(--light-color);
            color: var(--dark-color);
            padding: 0 30px;
            height: var(--header-height);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: fixed;
            width: 100%;
            z-index: 100;
        }

        .logo {
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            color: var(--primary-color);
            font-size: 24px;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--light-color);
            font-weight: bold;
            text-transform: uppercase;
        }

        .logout-btn {
            background-color: var(--danger-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        .dashboard-container {
            display: flex;
            padding-top: var(--header-height);
        }

        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--light-color);
            height: calc(100vh - var(--header-height));
            padding: 20px 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            position: fixed;
            transition: var(--transition);
            z-index: 90;
        }

        .sidebar-menu {
            list-style: none;
            margin-top: 20px;
        }

        .sidebar-menu li {
            margin: 5px 15px;
            border-radius: 8px;
            overflow: hidden;
            transition: var(--transition);
        }

        .sidebar-menu li:hover {
            background-color: rgba(108, 99, 255, 0.1);
        }

        .sidebar-menu a {
            color: var(--dark-light);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            font-weight: 500;
        }

        .sidebar-menu i {
            width: 24px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar-menu li.active {
            background-color: var(--primary-color);
        }

        .sidebar-menu li.active a {
            color: var(--light-color);
        }

        .main-content {
            flex: 1;
            padding: 30px;
            margin-left: var(--sidebar-width);
            transition: var(--transition);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: #5a52e0;
        }

        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn-success {
            background-color: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background-color: #00a884;
        }

        .edit-product-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--dark-color);
            font-size: 15px;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--gray-dark);
            border-radius: 6px;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
            outline: none;
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
            background-color: var(--gray-color);
            padding: 8px 12px;
            border-radius: 6px;
            transition: var(--transition);
        }

        .checkbox-item:hover {
            background-color: rgba(108, 99, 255, 0.1);
        }

        .checkbox-item input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
        }

        .current-image-container {
            margin-bottom: 20px;
            position: relative;
            max-width: 300px;
        }

        .current-image {
            max-width: 100%;
            border-radius: 8px;
            border: 1px solid var(--gray-dark);
            transition: var(--transition);
        }

        .current-image:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .image-actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .file-upload {
            position: relative;
            margin-top: 15px;
        }

        .file-upload-label {
            display: block;
            padding: 12px;
            border: 2px dashed var(--gray-dark);
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .file-upload-label:hover {
            border-color: var(--primary-color);
            background-color: rgba(108, 99, 255, 0.05);
        }

        .file-upload-input {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--gray-dark);
        }

        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            animation: fadeIn 0.5s ease-out;
        }

        .alert-success {
            background-color: rgba(0, 184, 148, 0.1);
            border-left: 4px solid var(--success-color);
            color: var(--success-color);
        }

        .alert-danger {
            background-color: rgba(214, 48, 49, 0.1);
            border-left: 4px solid var(--danger-color);
            color: var(--danger-color);
        }

        .alert-icon {
            font-size: 24px;
        }

        .alert-content h4 {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .alert-content p {
            font-size: 14px;
            margin-bottom: 5px;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .menu-toggle {
                display: block !important;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                padding: 0 15px;
            }
            
            .main-content {
                padding: 20px 15px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .form-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: var(--dark-color);
        }

        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 80;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition);
        }

        .overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Preview for uploaded image */
        .image-preview {
            display: none;
            margin-top: 15px;
            max-width: 300px;
            border-radius: 8px;
            border: 1px solid var(--gray-dark);
        }
    </style>
</head>
<body>
    <header class="dashboard-header">
        <div class="logo">
        <button class="menu-toggle"><i class="fas fa-bars"></i></button>
        <span class="logo-icon"><i class="fas fa-tshirt"></i></span>
        Admin</span></span>
    </div>
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
                <li><a href="comment.php">Comments</a></li>
                <li class="active"><a href="settings.php">Settings</a></li>
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