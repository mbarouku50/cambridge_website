<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../connection.php");

// Check if admin is logged in
if(!isset($_SESSION['email'])) {
    header("Location: adminLogin.php");
    exit();
}

$email = $_SESSION['email'];

// Count total products
$product_count_query = "SELECT COUNT(*) AS total_products FROM products";
$product_count_result = mysqli_query($conn, $product_count_query);
$product_count_data = mysqli_fetch_assoc($product_count_result);
$total_products = $product_count_data['total_products'];

// Count total comments (feedback)
$feedback_count_query = "SELECT COUNT(*) AS total_feedback FROM feedbck";
$feedback_count_result = mysqli_query($conn, $feedback_count_query);
$feedback_count_data = mysqli_fetch_assoc($feedback_count_result);
$total_feedback = $feedback_count_data['total_feedback'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Clothing Boutique</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
:root {
    --primary-color: #6C63FF;
    --primary-light: #a29bfe;
    --dark-color: #2D3436;
    --dark-light: #636e72;
    --light-color: #ffffff;
    --gray-color: #f8f9fa;
    --gray-dark: #dfe6e9;
    --success-color: #00b894;
    --danger-color: #d63031;
    --warning-color: #fdcb6e;
    --info-color: #0984e3;
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

.logo span {
    color: var(--primary-color);
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

.welcome-message {
    margin-bottom: 30px;
}

.welcome-message h1 {
    font-size: 28px;
    margin-bottom: 10px;
    color: var(--dark-color);
}

.welcome-message p {
    color: var(--dark-light);
    font-size: 16px;
}

.stats-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.card {
    background: var(--light-color);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: var(--transition);
    cursor: pointer;
    border-left: 4px solid var(--primary-color);
    position: relative;
    overflow: hidden;
}

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(108, 99, 255, 0.1) 0%, rgba(108, 99, 255, 0) 100%);
    opacity: 0;
    transition: var(--transition);
}

.card:hover::before {
    opacity: 1;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.card-title {
    color: var(--dark-light);
    font-size: 16px;
    margin-bottom: 10px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.card-value {
    color: var(--dark-color);
    font-size: 36px;
    font-weight: 700;
    margin-bottom: 15px;
}

.card-icon {
    font-size: 24px;
    color: var(--primary-color);
}

.card-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: var(--dark-light);
    font-size: 14px;
}

.trend-up {
    color: var(--success-color);
}

.trend-down {
    color: var(--danger-color);
}

.section-title {
    font-size: 20px;
    margin: 30px 0 20px;
    color: var(--dark-color);
    display: flex;
    align-items: center;
    gap: 10px;
}

.recent-activities {
    background: var(--light-color);
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
}

.activity-list {
    list-style: none;
}

.activity-item {
    padding: 15px 0;
    border-bottom: 1px solid var(--gray-dark);
    display: flex;
    align-items: center;
    gap: 15px;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(108, 99, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 18px;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    margin-bottom: 5px;
}

.activity-time {
    color: var(--dark-light);
    font-size: 13px;
}

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
    .stats-cards {
        grid-template-columns: 1fr;
    }
    
    .dashboard-header {
        padding: 0 15px;
    }
    
    .main-content {
        padding: 20px 15px;
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

.card {
    animation: fadeIn 0.5s ease-out forwards;
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }

/* Badge for notifications */
.badge {
    background-color: var(--danger-color);
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 12px;
    margin-left: auto;
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
    <div class="user-menu">
        <div class="user-profile">
            <div class="avatar"><?php echo strtoupper(substr($email, 0, 1)); ?></div>
            <span><?php echo htmlspecialchars($email); ?></span>
        </div>
        <button class="logout-btn" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</header>

<div class="overlay"></div>

<div class="dashboard-container">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li class="active"><a href="adminDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="product.php"><i class="fas fa-tshirt"></i> Products</a></li>
            <li><a href="comment.php"><i class="fas fa-comments"></i> Comments <span class="badge"><?php echo $total_feedback; ?></span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
        </ul>
    </aside>

    <main class="main-content">
        <div class="welcome-message">
            <h1>Welcome back, <?php echo explode('@', $email)[0]; ?>!</h1>
            <p>Here's what's happening with your store today.</p>
        </div>

        <div class="stats-cards">
            <div class="card">
                <div class="card-title"><i class="fas fa-tshirt card-icon"></i> Total Products</div>
                <div class="card-value"><?php echo $total_products; ?></div>
                <div class="card-footer">
                    <span>Last 7 days</span>
                    <span class="trend-up"><i class="fas fa-arrow-up"></i> 12%</span>
                </div>
            </div>
            <div class="card">
                <div class="card-title"><i class="fas fa-comments card-icon"></i> Customer Feedback</div>
                <div class="card-value"><?php echo $total_feedback; ?></div>
                <div class="card-footer">
                    <span>Last 7 days</span>
                    <span class="trend-up"><i class="fas fa-arrow-up"></i> 8%</span>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
// Toggle sidebar on mobile
document.querySelector('.menu-toggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
    document.querySelector('.overlay').classList.toggle('active');
});

// Close sidebar when clicking on overlay
document.querySelector('.overlay').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.remove('active');
    this.classList.remove('active');
});

// Add animation to cards when they come into view
const cards = document.querySelectorAll('.card');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = 1;
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.1 });

cards.forEach(card => {
    card.style.opacity = 0;
    card.style.transform = 'translateY(20px)';
    card.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
    observer.observe(card);
});
</script>
</body>
</html>