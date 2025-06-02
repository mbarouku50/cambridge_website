<?php
// Error reporting at the VERY TOP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include("../connection.php");

// Check if admin is logged in
if (!isset($_SESSION['email'])) {
    header("Location: adminLogin.php");
    exit();
}

// Get admin email from session
$email = $_SESSION['email'];

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM feedbck WHERE feedback_id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $message = "Feedback deleted successfully!";
        $message_class = "alert-success";
    } else {
        $message = "Error deleting feedback!";
        $message_class = "alert-danger";
    }
    mysqli_stmt_close($stmt);
}

// Fetch all feedback from database
$feedback_query = "SELECT feedback_id, name, feedback, created_at FROM feedbck ORDER BY created_at DESC";
$feedback_result = mysqli_query($conn, $feedback_query);

if (!$feedback_result) {
    die("Error fetching feedback: " . mysqli_error($conn));
}

$all_feedback = mysqli_fetch_all($feedback_result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Feedback | Clothing Shop</title>
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
.feedback-table {
    width: 100%;
    border-collapse: collapse;
    background-color: var(--light-color);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    border-radius: 12px;
    overflow: hidden;
    animation: fadeIn 0.5s ease-out forwards;
}

.feedback-table thead {
    background-color: var(--primary-color);
    color: var(--light-color);
}

.feedback-table th, .feedback-table td {
    padding: 12px 15px;
    text-align: left;
}

.feedback-table tbody tr:nth-child(even) {
    background-color: var(--gray-color);
}

.feedback-table tbody tr:hover {
    background-color: rgba(108, 99, 255, 0.1);
    transition: background-color 0.3s;
}

.feedback-table th:first-child,
.feedback-table td:first-child {
    border-top-left-radius: 12px;
}

.feedback-table th:last-child,
.feedback-table td:last-child {
    border-top-right-radius: 12px;
}

.feedback-table .action-btn {
    background-color: var(--danger-color);
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: var(--transition);
}

.feedback-table .action-btn:hover {
    background-color: #c0392b;
}

.alert {
    padding: 12px 20px;
    margin-bottom: 20px;
    border-radius: 6px;
    font-size: 14px;
}

.alert-success {
    background-color: #dff9fb;
    color: var(--success-color);
}

.alert-danger {
    background-color: #fab1a0;
    color: var(--danger-color);
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

<div class="dashboard-container">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li><a href="adminDashboard.php">Dashboard</a></li>
            <li><a href="product.php">Products</a></li>
            <li class="active"><a href="comment.php">Comments</a></li>
            <li><a href="settings.php">Settings</a></li>
            
        </ul>
    </aside>

    <main class="main-content">
        <div class="page-header">
            <h1 class="page-title">Customer Feedback</h1>
        </div>

        <?php if (isset($message)): ?>
            <div class="alert <?php echo $message_class; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <table class="feedback-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($all_feedback) > 0): ?>
                    <?php foreach ($all_feedback as $feedback): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($feedback['feedback_id'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($feedback['name'] ?? ''); ?></td>
                            <td class="feedback-message" title="<?php echo htmlspecialchars($feedback['feedback'] ?? ''); ?>">
                                <?php echo htmlspecialchars($feedback['feedback'] ?? ''); ?>
                            </td>
                            <td><?php echo isset($feedback['created_at']) ? date('M j, Y', strtotime($feedback['created_at'])) : ''; ?></td>
                            <td>
                                <button class="action-btn delete-btn"
                                        onclick="confirmDelete(<?php echo (int)$feedback['feedback_id']; ?>)">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No feedback found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>

<script>
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this feedback?")) {
        window.location.href = "comment.php?delete_id=" + id;
    }
}
</script>
</body>
</html>
