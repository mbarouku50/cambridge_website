<?php
session_start();
include("connection.php");

// Check if admin is logged in
if(!isset($_SESSION['email'])) {
    header("Location: adminLogin.php");
    exit();
}

// Get admin email from session
$email = $_SESSION['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Clothing Shop</title>
    <style>
        :root {
            --primary-color: #3498db;
            --dark-color: #000000;
            --light-color: #ffffff;
            --gray-color: #f5f5f5;
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
        
        .welcome-message {
            margin-bottom: 30px;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .card {
            background: var(--light-color);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .card-title {
            color: var(--dark-color);
            font-size: 16px;
            margin-bottom: 10px;
        }
        
        .card-value {
            color: var(--primary-color);
            font-size: 24px;
            font-weight: bold;
        }
        
        .recent-orders {
            background: var(--light-color);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        th {
            background-color: var(--primary-color);
            color: var(--light-color);
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-shipped {
            background-color: #cce5ff;
            color: #004085;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
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
            <span>Welcome, <?php echo $email; ?></span>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li class="active"><a href="#">Dashboard</a></li>
                <li><a href="product.php">Products</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="#">comment</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="welcome-message">
                <h1>Admin Dashboard</h1>
                <p>Manage your clothing shop efficiently</p>
            </div>
            
            <div class="stats-cards">
                <div class="card">
                    <div class="card-title">Products</div>
                    <div class="card-value">356</div>
                </div>
                <div class="card">
                    <div class="card-title">Customers</div>
                    <div class="card-value">1,248</div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>