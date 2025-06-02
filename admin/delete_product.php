<?php
session_start();
include("../connection.php");

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if admin is logged in
if(!isset($_SESSION['email'])) {
    $_SESSION['error'] = "You must be logged in to delete products";
    header("Location: adminLogin.php");
    exit();
}

// Check if product ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "No product ID specified";
    header("Location: product.php");
    exit();
}

$product_id = intval($_GET['id']); // Sanitize input

try {
    // Begin transaction
    mysqli_begin_transaction($conn);

    // 1. First get the image path
    $query = "SELECT image_path FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) == 0) {
        throw new Exception("Product not found");
    }
    
    $product = mysqli_fetch_assoc($result);
    $image_path = $product['image_path'];
    mysqli_stmt_close($stmt);

    // 2. Delete the product from database
    $query = "DELETE FROM products WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    
    if(!mysqli_stmt_execute($stmt)) {
        throw new Exception("Database error: " . mysqli_error($conn));
    }
    
    $affected_rows = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);
    
    if($affected_rows == 0) {
        throw new Exception("No product was deleted");
    }

    // 3. Delete the image file
    if(!empty($image_path)) {
        $full_path = $_SERVER['DOCUMENT_ROOT'] . "/cambridge_website/" . $image_path;
        
        // Verify the path is within our directory for security
        $base_path = $_SERVER['DOCUMENT_ROOT'] . "/cambridge_website/images/products/";
        if(strpos(realpath($full_path), realpath($base_path)) === 0 && file_exists($full_path)) {
            if(!unlink($full_path)) {
                error_log("Failed to delete image file: " . $full_path);
                // Don't throw exception - deletion from DB already succeeded
            }
        }
    }

    // Commit transaction
    mysqli_commit($conn);
    
    $_SESSION['success'] = "Product deleted successfully";

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    $_SESSION['error'] = $e->getMessage();
    error_log("Delete Product Error: " . $e->getMessage());
}

// Redirect back to products page
header("Location: product.php");
exit();
?>