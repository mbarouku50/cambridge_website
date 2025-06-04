<?php


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

        .page-title {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
        }

       

        .action-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: #fff;
        }

        .alert {
            margin-bottom: 1rem;
            padding: 1rem;
            border-radius: 5px;
        }

        .alert-success {
            background: #2ecc71;
            color: white;
        }

        .alert-danger {
            background: #e74c3c;
            color: white;
        }
        /* Modern Responsive Table Styles */
.feedback-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
    margin: 20px 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.feedback-table {
    width: 100%;
    min-width: 600px; /* Minimum width before scrolling kicks in */
    border-collapse: separate;
    border-spacing: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.feedback-table thead {
    background: linear-gradient(135deg, #6C63FF 0%, #4a42d6 100%);
    color: white;
}

.feedback-table th {
    padding: 15px 12px;
    text-align: left;
    font-weight: 600;
    font-size: 0.85rem;
    position: sticky;
    top: 0;
    z-index: 10;
}

.feedback-table tbody tr {
    transition: all 0.2s ease;
    border-bottom: 1px solid rgba(0, 0, 0, 0.03);
}

.feedback-table tbody tr:last-child {
    border-bottom: none;
}

.feedback-table tbody tr:hover {
    background-color: rgba(108, 99, 255, 0.05);
}

.feedback-table td {
    padding: 12px;
    color: #555;
    vertical-align: middle;
    position: relative;
}

/* Mobile-specific styles */
@media (max-width: 768px) {
    .feedback-table {
        min-width: 100%;
    }
    
    .feedback-table th,
    .feedback-table td {
        padding: 10px 8px;
        font-size: 0.85rem;
    }
    
    /* Make the first column sticky on mobile */
    .feedback-table td:first-child {
        position: sticky;
        left: 0;
        background: white;
        z-index: 5;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }
    
    /* Hide less important columns on small screens */
    .feedback-table th:nth-child(4),
    .feedback-table td:nth-child(4) {
        display: none;
    }
}

/* Feedback message with expandable functionality */
.feedback-message {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
}

.feedback-message.expanded {
    white-space: normal;
    max-width: none;
    overflow: visible;
}

/* Rating Stars */
.rating-stars {
    color: #FFC107;
    font-size: 0.9rem;
    white-space: nowrap;
}

/* Action Buttons */
.action-btns {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

.btn-table {
    padding: 6px 10px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border: none;
    min-width: 60px;
}

.btn-view {
    background: rgba(108, 99, 255, 0.1);
    color: #6C63FF;
}

.btn-delete {
    background: rgba(231, 76, 60, 0.1);
    color: #E74C3C;
}

/* Empty State */
.empty-table {
    text-align: center;
    padding: 40px;
    color: #888;
    font-style: italic;
    min-width: 100%;
}

/* Mobile action menu alternative */
.mobile-actions {
    display: none;
}

@media (max-width: 480px) {
    .action-btns {
        display: none;
    }
    
    .mobile-actions {
        display: block;
    }
    
    .mobile-action-btn {
        background: none;
        border: none;
        color: #6C63FF;
        font-size: 1.2rem;
        cursor: pointer;
    }
    
    .mobile-action-menu {
        position: absolute;
        right: 5px;
        top: 100%;
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border-radius: 4px;
        z-index: 20;
        display: none;
    }
    
    .mobile-action-menu.show {
        display: block;
    }
    
    .mobile-action-item {
        padding: 8px 15px;
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
    }
    
    .mobile-action-item:hover {
        background: #f5f5f5;
    }
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
        <button class="logout-btn" onclick="window.location.href='logout.php'">
            <i class="fas fa-sign-out-alt"></i> Logout
        </button>
    </div>
</header>

<div class="dashboard-container">
    <aside class="sidebar">
        <ul class="sidebar-menu">
            <li ><a href="adminDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="product.php"><i class="fas fa-tshirt"></i> Products</a></li>
            <li class="active"><a href="comment.php"><i class="fas fa-comments"></i> Comments <span class="badge"><?php echo $total_feedback; ?></span></a></li>
            <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
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

        <div class="feedback-container">
    <table class="feedback-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Feedback</th>
                <th>Rating</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($all_feedback) > 0): ?>
                <?php foreach ($all_feedback as $feedback): ?>
                    <tr>
                        <td>#<?php echo htmlspecialchars($feedback['feedback_id']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                        <td class="feedback-message" onclick="this.classList.toggle('expanded')">
                            <?php echo htmlspecialchars($feedback['feedback']); ?>
                        </td>
                        <td>
                            <?php if (!empty($feedback['rating'])): ?>
                                <div class="rating-stars">
                                    <?php 
                                    $rating = (int)$feedback['rating'];
                                    for ($i = 1; $i <= 5; $i++) {
                                        echo $i <= $rating ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('M j, Y', strtotime($feedback['created_at'])); ?></td>
                        <td>
                            <div class="action-btns">
                                <button class="btn-table btn-view" onclick="viewFeedback(<?php echo $feedback['feedback_id']; ?>)">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn-table btn-delete" onclick="confirmDelete(<?php echo $feedback['feedback_id']; ?>)">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                            <div class="mobile-actions">
                                <button class="mobile-action-btn" onclick="toggleMobileMenu(this)">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="mobile-action-menu">
                                    <div class="mobile-action-item" onclick="viewFeedback(<?php echo $feedback['feedback_id']; ?>)">
                                        <i class="fas fa-eye"></i> View
                                    </div>
                                    <div class="mobile-action-item" onclick="confirmDelete(<?php echo $feedback['feedback_id']; ?>)">
                                        <i class="fas fa-trash"></i> Delete
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="empty-table">
                        <i class="far fa-comment-dots" style="font-size: 2rem; margin-bottom: 10px;"></i>
                        <p>No feedback found</p>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
// Mobile menu toggle
function toggleMobileMenu(button) {
    const menu = button.nextElementSibling;
    menu.classList.toggle('show');
    
    // Close when clicking elsewhere
    document.addEventListener('click', function closeMenu(e) {
        if (!button.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
            document.removeEventListener('click', closeMenu);
        }
    });
}

// Feedback functions
function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this feedback?")) {
        window.location.href = "comment.php?delete_id=" + id;
    }
}

function viewFeedback(id) {
    // Implement your view functionality here
    alert("Viewing feedback ID: " + id);
}
</script>
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
