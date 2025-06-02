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

// Display upload status if available
if(isset($_SESSION['upload_status'])) {
    $upload_status = $_SESSION['upload_status'];
    unset($_SESSION['upload_status']);
    
    $alert_class = $upload_status['success'] ? 'alert-success' : 'alert-danger';
    
    echo '<div class="alert ' . $alert_class . '" style="margin: 20px; padding: 15px; border-radius: 4px;">';
    echo '<h4>' . ($upload_status['success'] ? 'Success!' : 'Error!') . '</h4>';
    echo '<p>' . htmlspecialchars($upload_status['message']) . '</p>';
    
    if(!empty($upload_status['upload_status'])) {
        echo '<p>Upload details: ' . htmlspecialchars($upload_status['upload_status']) . '</p>';
    }
    
    if(!empty($upload_status['image_path']) && $upload_status['success']) {
        echo '<p>Image path: ' . htmlspecialchars($upload_status['image_path']) . '</p>';
        echo '<img src="../' . htmlspecialchars($upload_status['image_path']) . '" style="max-width: 200px; margin-top: 10px;">';
    }
    
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products | Admin Dashboard</title>
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
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            height: 200px;
            overflow: hidden;
        }
        
        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .product-info {
            padding: 15px;
        }
        
        .product-name {
            font-size: 18px;
            margin-bottom: 10px;
            color: var(--dark-color);
        }
        
        .product-price {
            font-weight: bold;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .product-category {
            display: inline-block;
            background-color: var(--gray-color);
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            margin-bottom: 10px;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .add-product-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
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
        
        textarea.form-control {
            min-height: 100px;
        }
        
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
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
            
            .product-grid {
                grid-template-columns: 1fr;
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
                <li><a href="adminDashboard.php">Dashboard</a></li>
                <li class="active"><a href="product.php">Products</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="#">Comments</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Manage Products</h1>
                <button class="btn btn-primary" onclick="document.getElementById('addProductForm').style.display='block'">
                    Add New Product
                </button>
            </div>
            
            <!-- Add Product Form -->
            <div id="addProductForm" class="add-product-form" style="display: none;">
                <h2>Add New Product</h2>
                <form action="add_product.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price (Tsh)</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="Formal Trousers">Formal Trousers</option>
                            <option value="T-Shirts">T-Shirts</option>
                            <option value="Formal Shirts">Formal Shirts</option>
                            <option value="Casual Shirts">Casual Shirts</option>
                            <option value="Jacket">Jacket</option>
                            <option value="Accessories">Accessories</option>
                            <option value="others">others</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Available Sizes</label>
                        <div class="checkbox-group">
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="S"> S</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="M"> M</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="L"> L</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="XL"> XL</label>
                            <label class="checkbox-item"><input type="checkbox" name="sizes[]" value="XXL"> XXL</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Available Colors</label>
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
                        <label for="image">Product Image</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" required>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Product</button>
                        <button type="button" class="btn btn-danger" onclick="document.getElementById('addProductForm').style.display='none'">Cancel</button>
                    </div>
                </form>
            </div>
            
            <!-- Products Grid -->
            <div class="product-grid">
                <?php foreach($products as $product): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="../<?php echo $product['image_path']; ?>" alt="<?php echo $product['name']; ?>">
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo $product['name']; ?></h3>
                            <div class="product-price">Tsh.<?php echo number_format($product['price'], 2); ?></div>
                            <span class="product-category"><?php echo $product['category']; ?></span>
                            <p><?php echo substr($product['description'], 0, 100) . '...'; ?></p>
                            <div class="product-actions">
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-primary">Edit</a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>