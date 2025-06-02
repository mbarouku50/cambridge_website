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
$categories = ['Formal Trousers', 'T-Shirts', 'Formal Shirts', 'Casual Shirts', 'Jacket', 'Accessories', 'Others'];
$size_options = ['S', 'M', 'L', 'XL', 'XXL'];
$color_options = ['Black', 'White', 'Blue', 'Red', 'Green', 'Gray', 'Beige', 'Navy', 'Brown'];
$message = '';
$success = false;

// Check if product ID is provided
if(isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    
    // Fetch product data using prepared statement
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
        $required_fields = ['name', 'description', 'price', 'category'];
        foreach($required_fields as $field) {
            if(empty($_POST[$field])) {
                throw new Exception("Please fill all required fields!");
            }
        }

        // Sanitize and validate input data
        $product_id = intval($_POST['product_id']);
        $name = mysqli_real_escape_string($conn, trim($_POST['name']));
        $description = mysqli_real_escape_string($conn, trim($_POST['description']));
        $price = floatval($_POST['price']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        
        // Process sizes and colors
        $sizes = isset($_POST['sizes']) ? implode(',', array_map('trim', $_POST['sizes'])) : '';
        $colors = isset($_POST['colors']) ? implode(',', array_map('trim', $_POST['colors'])) : '';
        
        // Handle file upload if a new image was provided
        $image_path = $_POST['existing_image'];
        if(isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/cambridge_website/images/products/";
            
            // Verify directory exists and is writable
            if(!file_exists($target_dir)) {
                if(!mkdir($target_dir, 0755, true)) {
                    throw new Exception("Failed to create upload directory");
                }
            }
            
            if(!is_writable($target_dir)) {
                throw new Exception("Upload directory is not writable");
            }
            
            // Get file info
            $file_name = basename($_FILES["image"]["name"]);
            $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $file_size = $_FILES["image"]["size"];
            $tmp_name = $_FILES["image"]["tmp_name"];
            
            // Validate image
            $check = getimagesize($tmp_name);
            if($check === false) throw new Exception("File is not a valid image");
            if($file_size > 8000000) throw new Exception("File is too large (max 8MB)");
            
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if(!in_array($file_type, $allowed_types)) {
                throw new Exception("Only JPG, JPEG, PNG, GIF, and WEBP files allowed");
            }
            
            // Generate unique filename
            $new_filename = 'product_' . uniqid() . '.' . $file_type;
            $target_file = $target_dir . $new_filename;
            
            // Upload new file
            if(!move_uploaded_file($tmp_name, $target_file)) {
                throw new Exception("Failed to upload image: " . error_get_last()['message']);
            }
            
            // Delete old image if it exists and is not the default placeholder
            if(!empty($image_path) && strpos($image_path, 'placeholder') === false) {
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
    <title>Edit Product | Boutique Admin</title>
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
            <span>Boutique<span>Admin</span></span>
        </div>
        <div class="user-menu">
            <div class="user-profile">
                <div class="avatar"><?php echo strtoupper(substr($_SESSION['email'], 0, 1)); ?></div>
                <span><?php echo htmlspecialchars($_SESSION['email']); ?></span>
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
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Error!</h4>
                        <p><?php echo htmlspecialchars($_SESSION['error']); ?></p>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Success!</h4>
                        <p><?php echo htmlspecialchars($_SESSION['success']); ?></p>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <div class="page-header">
                <h1 class="page-title"><i class="fas fa-edit"></i> Edit Product</h1>
                <a href="product.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>
            
            <?php if(!empty($product)): ?>
                <div class="edit-product-form">
                    <form action="edit_product.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product['image_path']); ?>">
                        
                        <div class="form-group">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" id="name" name="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description" name="description" class="form-control" 
                                      required><?php echo htmlspecialchars($product['description']); ?></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="price" class="form-label">Price (Tsh)</label>
                            <input type="number" id="price" name="price" class="form-control" 
                                   step="0.01" min="0" value="<?php echo htmlspecialchars($product['price']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="category" class="form-label">Category</label>
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
                            <label class="form-label">Available Sizes</label>
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
                            <label class="form-label">Available Colors</label>
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
                            <label class="form-label">Product Image</label>
                            
                            <?php if(!empty($product['image_path'])): ?>
                                <div class="current-image-container">
                                    <img src="../<?php echo htmlspecialchars($product['image_path']); ?>" 
                                         alt="Current Product Image" class="current-image">
                                </div>
                            <?php else: ?>
                                <p>No image currently set</p>
                            <?php endif; ?>
                            
                            <div class="file-upload">
                                <label for="image" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt" style="font-size: 24px; margin-bottom: 10px;"></i>
                                    <p>Click to upload new image or drag and drop</p>
                                    <p class="text-muted">PNG, JPG, GIF up to 8MB</p>
                                </label>
                                <input type="file" id="image" name="image" class="file-upload-input" accept="image/*">
                            </div>
                            <img id="imagePreview" class="image-preview" alt="Image preview">
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" name="update_product" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                            <a href="delete_product.php?id=<?php echo $product['id']; ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                <i class="fas fa-trash"></i> Delete Product
                            </a>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Product Not Found</h4>
                        <p>The requested product could not be found or no product ID was specified.</p>
                    </div>
                </div>
            <?php endif; ?>
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

        // Image preview functionality
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    imagePreview.style.display = 'block';
                    imagePreview.setAttribute('src', this.result);
                });
                
                reader.readAsDataURL(file);
            }
        });

        // Show confirmation before leaving page if form has changes
        const form = document.querySelector('form');
        let formChanged = false;
        
        form.querySelectorAll('input, textarea, select').forEach(element => {
            element.addEventListener('change', () => {
                formChanged = true;
            });
        });
        
        window.addEventListener('beforeunload', (e) => {
            if (formChanged) {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
        
        form.addEventListener('submit', () => {
            formChanged = false;
        });
    </script>
</body>
</html>