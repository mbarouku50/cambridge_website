<?php
session_start();
include("../connection.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if(!isset($_SESSION['email'])) {
    $_SESSION['error'] = "You must be logged in to edit products";
    header("Location: adminLogin.php");
    exit();
}

// Initialize variables
$product = [];
$categories = ['Formal Trousers', 'T-Shirts', 'Formal Shirts', 'Casual Shirts', 'Jacket', 'Accessories', 'others'];
$size_options = ['S', 'M', 'L', 'XL', 'XXL'];
$color_options = ['Black', 'White', 'Blue', 'Red', 'Green', 'Gray'];
$message = '';
$success = false;

// Check if product ID is provided
if(isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Fetch product data
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        // Convert comma-separated sizes/colors to arrays
        $product['sizes_array'] = !empty($product['sizes']) ? explode(',', $product['sizes']) : [];
        $product['colors_array'] = !empty($product['colors']) ? explode(',', $product['colors']) : [];
    } else {
        $_SESSION['error'] = "Product not found";
        header("Location: product.php");
        exit();
    }
    mysqli_stmt_close($stmt);
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    try {
        // Validate required fields
        if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price'])) {
            throw new Exception("Please fill all required fields!");
        }

        // Sanitize and validate input data
        $product_id = intval($_POST['product_id']);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        
        // Process sizes and colors
        $sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
        $colors = isset($_POST['colors']) ? implode(',', $_POST['colors']) : '';
        
        // Handle file upload if a new image was provided
        $image_path = $_POST['existing_image'];
        if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/cambridge_website/images/products/";
            
            // Verify directory exists and is writable
            if(!file_exists($target_dir) || !is_writable($target_dir)) {
                throw new Exception("Upload directory is not accessible");
            }
            
            // Get file info
            $file_type = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $file_size = $_FILES["image"]["size"];
            $tmp_name = $_FILES["image"]["tmp_name"];
            
            // Validate image
            $check = getimagesize($tmp_name);
            if($check === false) throw new Exception("File is not a valid image");
            if($file_size > 8000000) throw new Exception("File is too large (max 8MB)");
            
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if(!in_array($file_type, $allowed_types)) throw new Exception("Only JPG, JPEG, PNG & GIF files allowed");
            
            // Generate unique filename
            $new_filename = uniqid() . '.' . $file_type;
            $target_file = $target_dir . $new_filename;
            
            // Upload new file
            if(!move_uploaded_file($tmp_name, $target_file)) {
                throw new Exception("Failed to upload image: " . error_get_last()['message']);
            }
            
            // Delete old image if it exists
            if(!empty($image_path)) {
                $old_image_path = $_SERVER['DOCUMENT_ROOT'] . "/cambridge_website/" . $image_path;
                if(file_exists($old_image_path)) {
                    unlink($old_image_path);
                }
            }
            
            $image_path = "images/products/" . $new_filename;
        }
        
        // Update product in database
        $query = "UPDATE products SET 
                  name = ?, 
                  description = ?, 
                  price = ?, 
                  category = ?, 
                  sizes = ?, 
                  colors = ?, 
                  image_path = ? 
                  WHERE id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        if(!$stmt) throw new Exception("Database error: " . mysqli_error($conn));
        
        mysqli_stmt_bind_param($stmt, "ssdssssi", $name, $description, $price, $category, $sizes, $colors, $image_path, $product_id);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to update product: " . mysqli_error($conn));
        }
        
        mysqli_stmt_close($stmt);
        
        $success = true;
        $message = "Product updated successfully!";
        $_SESSION['success'] = $message;
        
        // Refresh product data
        header("Location: edit_product.php?id=" . $product_id);
        exit();
        
    } catch (Exception $e) {
        $message = $e->getMessage();
        $_SESSION['error'] = $message;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product | Admin Dashboard</title>
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
        
        .edit-product-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            max-width: 800px;
            margin: 0 auto;
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
            margin-top: 10px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
        }
        
        .checkbox-item input {
            margin-right: 5px;
        }
        
        .current-image {
            max-width: 200px;
            display: block;
            margin-bottom: 15px;
            border-radius: 4px;
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
                <li class="active"><a href="product.php">Products</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Comments</a></li>
            </ul>
        </aside>
        
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Edit Product</h1>
                <a href="product.php" class="btn btn-primary">Back to Products</a>
            </div>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?php 
                    echo htmlspecialchars($_SESSION['success']); 
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if(!empty($product)): ?>
                <div class="edit-product-form">
                    <form action="edit_product.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product['image_path']); ?>">
                        
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" class="form-control" 
                                      required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="price">Price (Tsh)</label>
                            <input type="number" id="price" name="price" class="form-control" 
                                   step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" class="form-control" required>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat); ?>" 
                                        <?php if($cat == $product['category']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($cat); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Available Sizes</label>
                            <div class="checkbox-group">
                                <?php foreach($size_options as $size): ?>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="sizes[]" value="<?php echo htmlspecialchars($size); ?>"
                                            <?php if(in_array($size, $product['sizes_array'])) echo 'checked'; ?>>
                                        <?php echo htmlspecialchars($size); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Available Colors</label>
                            <div class="checkbox-group">
                                <?php foreach($color_options as $color): ?>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="colors[]" value="<?php echo htmlspecialchars($color); ?>"
                                            <?php if(in_array($color, $product['colors_array'])) echo 'checked'; ?>>
                                        <?php echo htmlspecialchars($color); ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Current Image</label>
                            <?php if(!empty($product['image_path'])): ?>
                                <img src="../<?php echo htmlspecialchars($product['image_path']); ?>" 
                                     alt="Current Product Image" class="current-image">
                            <?php else: ?>
                                <p>No image currently set</p>
                            <?php endif; ?>
                            
                            <label for="image">Update Image (leave blank to keep current)</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" name="update_product" class="btn btn-primary">Update Product</button>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this product?')">
                                Delete Product
                            </a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    Product not found or no product ID specified.
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>