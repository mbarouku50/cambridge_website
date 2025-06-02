<?php
session_start();
include("../connection.php");

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if(!isset($_SESSION['email'])) {
    header("Location: adminLogin.php");
    exit();
}

// Initialize variables with detailed status
$message = '';
$success = false;
$upload_status = '';
$image_path = '';

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    try {
        // Validate required fields
        if(empty($_POST['name']) || empty($_POST['description']) || empty($_POST['price'])) {
            throw new Exception("Please fill all required fields!");
        }

        // Sanitize and validate input data
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $price = floatval($_POST['price']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        
        // Process sizes and colors
        $sizes = isset($_POST['sizes']) ? implode(',', $_POST['sizes']) : '';
        $colors = isset($_POST['colors']) ? implode(',', $_POST['colors']) : '';
        
        // Handle file upload
        if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $upload_errors = [
                UPLOAD_ERR_INI_SIZE => "File exceeds upload_max_filesize in php.ini",
                UPLOAD_ERR_FORM_SIZE => "File exceeds MAX_FILE_SIZE in form",
                UPLOAD_ERR_PARTIAL => "File only partially uploaded",
                UPLOAD_ERR_NO_FILE => "No file was uploaded",
                UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder",
                UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk",
                UPLOAD_ERR_EXTENSION => "File upload stopped by extension"
            ];
            
            $error_code = $_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE;
            throw new Exception("File upload error: " . ($upload_errors[$error_code] ?? "Unknown error"));
        }

        // Configure upload directory
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/cambridge_website/images/products/";
        
        // Create directory if it doesn't exist
        if(!file_exists($target_dir)) {
            if(!mkdir($target_dir, 0755, true)) {
                throw new Exception("Failed to create upload directory: " . $target_dir);
            }
        }

        // Verify directory is writable
        if(!is_writable($target_dir)) {
            throw new Exception("Upload directory is not writable: " . $target_dir);
        }

        // Get file info
        $file_name = basename($_FILES["image"]["name"]);
        $file_type = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $file_size = $_FILES["image"]["size"];
        $tmp_name = $_FILES["image"]["tmp_name"];

        // Check if image file is a actual image
        $check = getimagesize($tmp_name);
        if($check === false) {
            throw new Exception("File is not a valid image.");
        }

        // Check file size (max 2MB)
        if($file_size > 8000000) {
            throw new Exception("File is too large (max 8MB allowed).");
        }

        // Allow certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if(!in_array($file_type, $allowed_types)) {
            throw new Exception("Only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Generate unique filename
        $new_filename = uniqid() . '.' . $file_type;
        $target_file = $target_dir . $new_filename;

        // Upload file
        if(!move_uploaded_file($tmp_name, $target_file)) {
            $error = error_get_last();
            throw new Exception("Failed to move uploaded file. Error: " . ($error['message'] ?? "Unknown error"));
        }

        $image_path = "images/products/" . $new_filename;
        $upload_status = "File uploaded successfully.";

        // Insert into database
        $query = "INSERT INTO products (name, description, price, category, sizes, colors, image_path, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = mysqli_prepare($conn, $query);
        if(!$stmt) {
            throw new Exception("Database preparation error: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssdssss", $name, $description, $price, $category, $sizes, $colors, $image_path);
        
        if(!mysqli_stmt_execute($stmt)) {
            throw new Exception("Database error: " . mysqli_error($conn));
        }

        mysqli_stmt_close($stmt);
        
        $success = true;
        $message = "Product added successfully!";

    } catch (Exception $e) {
        $message = $e->getMessage();
        error_log("Product Upload Error: " . $message);
        
        // Clean up if file was uploaded but DB failed
        if(!empty($target_file) && file_exists($target_file)) {
            unlink($target_file);
        }
    }
}

// Store all status information in session
$_SESSION['upload_status'] = [
    'success' => $success,
    'message' => $message,
    'upload_status' => $upload_status ?? null,
    'image_path' => $image_path ?? null
];

// Redirect back
header("Location: product.php");
exit();
?>