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

// Get admin email from session
$email = $_SESSION['email'];

// Fetch all products
$products = [];
$query = "SELECT * FROM products ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
if($result) {
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | Boutique Admin</title>
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

        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: var(--transition);
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .product-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: var(--success-color);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            z-index: 1;
        }

        .product-image {
            height: 220px;
            overflow: hidden;
            position: relative;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 20px;
        }

        .product-name {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--dark-color);
        }

        .product-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .product-meta {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .product-category, .product-stock {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 13px;
            color: var(--dark-light);
        }

        .product-category i, .product-stock i {
            color: var(--primary-color);
            font-size: 14px;
        }

        .product-description {
            color: var(--dark-light);
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn-sm {
            padding: 8px 15px;
            font-size: 14px;
        }

        .btn-icon {
            padding: 8px;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            justify-content: center;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background-color: white;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--gray-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--dark-light);
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--danger-color);
            transform: rotate(90deg);
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-color);
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
        }

        .checkbox-item input {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
        }

        .modal-footer {
            padding: 20px;
            border-top: 1px solid var(--gray-dark);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
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

        .alert-content img {
            max-width: 200px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid var(--gray-dark);
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
            .product-grid {
                grid-template-columns: 1fr;
            }
            
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

        .product-card {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }

        /* Create staggered animation delays */
        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
        .product-card:nth-child(5) { animation-delay: 0.5s; }
        .product-card:nth-child(6) { animation-delay: 0.6s; }
        .product-card:nth-child(7) { animation-delay: 0.7s; }
        .product-card:nth-child(8) { animation-delay: 0.8s; }

        /* Badge for stock status */
        .out-of-stock {
            background-color: var(--danger-color);
        }

        .low-stock {
            background-color: var(--warning-color);
            color: var(--dark-color);
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
                <li><a href="adminDashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li class="active"><a href="product.php"><i class="fas fa-tshirt"></i> Products</a></li>
                <li><a href="comment.php"><i class="fas fa-comments"></i> Comments</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <?php if(isset($_SESSION['upload_status'])): ?>
                <?php 
                $upload_status = $_SESSION['upload_status'];
                unset($_SESSION['upload_status']);
                $alert_class = $upload_status['success'] ? 'alert-success' : 'alert-danger';
                ?>
                <div class="alert <?php echo $alert_class; ?>">
                    <div class="alert-icon">
                        <i class="fas <?php echo $upload_status['success'] ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
                    </div>
                    <div class="alert-content">
                        <h4><?php echo $upload_status['success'] ? 'Success!' : 'Error!'; ?></h4>
                        <p><?php echo htmlspecialchars($upload_status['message']); ?></p>
                        
                        <?php if(!empty($upload_status['upload_status'])): ?>
                            <p><?php echo htmlspecialchars($upload_status['upload_status']); ?></p>
                        <?php endif; ?>
                        
                        <?php if(!empty($upload_status['image_path']) && $upload_status['success']): ?>
                            <p>Image path: <?php echo htmlspecialchars($upload_status['image_path']); ?></p>
                            <img src="../<?php echo htmlspecialchars($upload_status['image_path']); ?>" alt="Uploaded product">
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-tshirt"></i> Product Management</h1>
                <button class="btn btn-primary" onclick="openModal()">
                    <i class="fas fa-plus"></i> Add New Product
                </button>
            </div>
            
            <div class="product-grid">
                <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <div class="product-badge">In Stock</div>
                        <div class="product-image">
                            <img src="../<?php echo htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                            <div class="product-price">Tsh <?php echo number_format($product['price'], 2); ?></div>
                            <div class="product-meta">
                                <span class="product-category"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($product['category']); ?></span>
                                <span class="product-stock"><i class="fas fa-box"></i> 25 available</span>
                            </div>
                            <p class="product-description"><?php echo htmlspecialchars(substr($product['description'], 0, 100) . '...'); ?></p>
                            <div class="product-actions">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                                <button class="btn btn-success btn-icon">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>

    <!-- Add Product Modal -->
    <div id="productModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Product</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="form-label">Price (Tsh)</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category" class="form-label">Category</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="Formal Trousers">Formal Trousers</option>
                            <option value="T-Shirts">T-Shirts</option>
                            <option value="Formal Shirts">Formal Shirts</option>
                            <option value="Casual Shirts">Casual Shirts</option>
                            <option value="Jacket">Jacket</option>
                            <option value="Accessories">Accessories</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Available Sizes</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="S"> S</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="M"> M</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="L"> L</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="XL"> XL</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="XXL"> XXL</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Available Colors</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item"><input type="checkbox" name="colors[]" value="Black"> Black</label>
                            <label class="checkbox-item"><input type="checkbox" name="colors[]" value="White"> White</label>
                            <label class="checkbox-item"><input type="checkbox" name="colors[]" value="Blue"> Blue</label>
                            <label class="checkbox-item"><input type="checkbox" name="colors[]" value="Red"> Red</label>
                            <label class="checkbox-item"><input type="checkbox" name="colors[]" value="Green"> Green</label>
                            <label class="checkbox-item"><input type="checkbox" name="colors[]" value="Gray"> Gray</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="image" class="form-label">Product Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
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

        // Modal functions
        function openModal() {
            document.getElementById('productModal').classList.add('active');
            document.querySelector('.overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('productModal').classList.remove('active');
            document.querySelector('.overlay').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.querySelector('.overlay').addEventListener('click', function() {
            closeModal();
        });

        // Add animation to cards when they come into view
        const cards = document.querySelectorAll('.product-card');
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