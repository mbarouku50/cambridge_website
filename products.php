<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include("temperate/header.php");
include("connection.php");
?>

<div class="products-container">
    <div class="products-header">
        <h1>Our Products</h1>
        <div class="category-filter">
            <button class="filter-btn active" data-category="all">All</button>
            <?php
            // Get unique categories for filter buttons
            $categoryQuery = "SELECT DISTINCT category FROM products";
            $categoryResult = mysqli_query($conn, $categoryQuery);
            
            while($category = mysqli_fetch_assoc($categoryResult)) {
                echo '<button class="filter-btn" data-category="'.str_replace(' ', '-', strtolower($category['category'])).'">'.$category['category'].'</button>';
            }
            ?>
        </div>
    </div>

    <div class="products-grid">
        <?php
        $query = "SELECT * FROM products ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                $sizes = explode(',', $row['sizes']);
                $colors = explode(',', $row['colors']);
                
                echo '
                    <div class="product-card" data-category="'.str_replace(' ', '-', strtolower($row['category'])).'">
                        <div class="product-image">
                            <img src="'.$row['image_path'].'" alt="'.$row['name'].'">
                            <div class="product-badge">New</div>
                        </div>
                        <div class="product-details">
                            <h3>'.$row['name'].'</h3>
                            <p class="product-description">'.substr($row['description'], 0, 50).'...</p>
                            <div class="product-price">'.number_format($row['price'], 2).' Tsh.</div>
                            
                            <div class="product-options">
                                <div class="size-options">
                                    <label>Sizes:</label>
                                    <select>
                                        <option value="">Select size</option>';
                                        foreach($sizes as $size) {
                                            echo '<option value="'.trim($size).'">'.trim($size).'</option>';
                                        }
                                    echo '</select>
                                </div>
                                
                                <div class="color-options">
                                    <label>Colors:</label>
                                    <div class="color-selector">';
                                        foreach($colors as $color) {
                                            $colorClass = strtolower(trim($color));
                                            echo '<span class="color-dot '.$colorClass.'" title="'.$color.'"></span>';
                                        }
                                    echo '</div>
                                </div>
                            </div>
                            
                            <div class="product-actions">
                                <a href="https://wa.me/+255748791897?text=I%20want%20to%20buy%20'.urlencode($row['name']).'%20(Product%20Name:%20'.$row['name'].')" class="add-to-cart">Order via WhatsApp</a>
                                <button class="wishlist-btn"><i class="far fa-heart"></i></button>
                            </div>
                        </div>
                    </div>';
            }
        } else {
            echo '<p class="no-products">No products found.</p>';
        }
        ?>
    </div>
</div>

<style>
    /* Base Styles */
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #34495e;
        --accent-color: #e74c3c;
        --light-color: #ecf0f1;
        --dark-color: #2c3e50;
        --text-color: #333;
        --text-light: #7f8c8d;
        --border-radius: 4px;
        --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }
    
    .products-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }
    
    .products-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .products-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: var(--dark-color);
        margin-bottom: 1rem;
    }
    
    .category-filter {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    
    .filter-btn {
        padding: 0.5rem 1rem;
        background: white;
        border: 1px solid var(--text-light);
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
    }
    
    .filter-btn:hover, .filter-btn.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }
    
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 2rem;
    }
    
    .product-card {
        background: white;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .product-image {
        position: relative;
        overflow: hidden;
        height: 200px;
    }
    
    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }
    
    .product-card:hover .product-image img {
        transform: scale(1.05);
    }
    
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--accent-color);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: var(--border-radius);
        font-size: 0.8rem;
        font-weight: bold;
    }
    
    .product-details {
        padding: 1rem;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }
    
    .product-details h3 {
        margin: 0 0 0.5rem;
        font-family: 'Poppins', sans-serif;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .product-description {
        color: var(--text-light);
        font-size: 0.9rem;
       
    }
    
    .product-price {
        font-size: 1.25rem;
        font-weight: bold;
        color: var(--primary-color);
       
    }

    
    .size-options, .color-options {
        margin-bottom: 1rem;
    }
    
    .size-options label, .color-options label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-color);
    }
    
    .size-options select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        font-family: 'Poppins', sans-serif;
    }
    
    .color-selector {
        display: flex;
        gap: 0.5rem;
    }
    
    .color-dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        cursor: pointer;
        border: 2px solid white;
        box-shadow: 0 0 0 1px #ddd;
    }
    
    /* Define color classes - you can add more as needed */
    .black { background-color: #000; }
    .white { background-color: #fff; }
    .blue { background-color: #3498db; }
    .red { background-color: #e74c3c; }
    .green { background-color: #2ecc71; }
    .gray { background-color: #95a5a6; }
    
    .product-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: auto;
    }
    
    .add-to-cart {
        flex-grow: 1;
        padding: 0.75rem;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        font-family: 'Poppins', sans-serif;
    }
    
    .add-to-cart:hover {
        background: var(--secondary-color);
    }
    
    .wishlist-btn {
        width: 40px;
        background: white;
        border: 1px solid #ddd;
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-light);
    }
    
    .wishlist-btn:hover {
        color: var(--accent-color);
        border-color: var(--accent-color);
    }
    
    .no-products {
        text-align: center;
        grid-column: 1 / -1;
        padding: 2rem;
        color: var(--text-light);
    }
    
    /* Responsive Styles */
    @media (max-width: 1024px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1.5rem;
        }
        
        .products-header h1 {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 576px) {
        .products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .products-header h1 {
            font-size: 1.75rem;
        }
        
        .category-filter {
            gap: 0.25rem;
        }
        
        .filter-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .product-details {
            padding: 1rem;
        }
        
        .product-price {
            font-size: 1.1rem;
        }
    }
</style>

<script>
    // Filter products by category
    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const productCards = document.querySelectorAll('.product-card');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                const category = this.dataset.category;
                
                // Filter products
                productCards.forEach(card => {
                    if(category === 'all' || card.dataset.category === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Color selection functionality
        document.querySelectorAll('.color-dot').forEach(dot => {
            dot.addEventListener('click', function() {
                // Remove active class from siblings
                this.parentNode.querySelectorAll('.color-dot').forEach(d => {
                    d.classList.remove('active');
                });
                
                // Add active class to clicked dot
                this.classList.add('active');
            });
        });
    });
</script>

<?php
include("temperate/footer.php");
?>