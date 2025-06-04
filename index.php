<?php
include("temperate/header.php");
include("connection.php");

// Fetch latest products (6 most recent)
$latestQuery = "SELECT * FROM products ORDER BY created_at DESC LIMIT 6";
$latestResult = mysqli_query($conn, $latestQuery);
$latestProducts = mysqli_fetch_all($latestResult, MYSQLI_ASSOC);

// Fetch featured products (you could add a 'featured' column to your database)
$featuredQuery = "SELECT * FROM products ORDER BY RAND() LIMIT 6"; // Random for now
$featuredResult = mysqli_query($conn, $featuredQuery);
$featuredProducts = mysqli_fetch_all($featuredResult, MYSQLI_ASSOC);

// Fetch best sellers (you could add a 'sales_count' column to your database)
$bestsellersQuery = "SELECT * FROM products ORDER BY RAND() LIMIT 4"; // Random for now
$bestsellersResult = mysqli_query($conn, $bestsellersQuery);
$bestsellers = mysqli_fetch_all($bestsellersResult, MYSQLI_ASSOC);

// Get unique categories
$categoriesQuery = "SELECT DISTINCT category FROM products LIMIT 6";
$categoriesResult = mysqli_query($conn, $categoriesQuery);
$categories = mysqli_fetch_all($categoriesResult, MYSQLI_ASSOC);
?>
    <div class="hero-carousel-container">
        <div class="hero-carousel" id="heroCarousel">
            <div class="carousel-slide">
                <img src="images/JPG_7.jpg" alt="Fashion Collection 1" loading="lazy">
                <div class="hero-content">
                    <h1>Discover Cambridge Trends</h1>
                    <p>Elevate your wardrobe and leave a Lasting impression wherever you go.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="images/JPG_71.jpg" alt="Fashion Collection 2" loading="lazy">
                <div class="hero-content">
                    <h1>Style Meets Sustainability</h1>
                    <p>Embrace fashion that feels good and does good for the planet.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="images/JPG_57.jpg" alt="Fashion Collection 3" loading="lazy">
                <div class="hero-content">
                    <h1>Your Perfect Outfit Awaits</h1>
                    <p>Explore our diverse range of clothing for every occasion.</p>
                </div>
            </div>
        </div>
        <button class="carousel-nav prev" aria-label="Previous slide">❮</button>
        <button class="carousel-nav next" aria-label="Next slide">❯</button>
        <div class="carousel-dots" id="carouselDots"></div>
    </div>

    <section id="about-brief">
        <div class="section-header">
            <h2>About Our Brand</h2>
            <p>We blend style and sustainability to create clothing that inspires confidence and individuality. Since 2025, Cambridge has been crafting sustainable fashion that celebrates quality.</p>
            <a href="about.html" class="learn-more-btn">Learn More About Us</a>
        </div>
    </section>
    <section id="latest-products-section">
        <div class="section-header">
            <h2>Latest Arrivals</h2>
            <p>Discover our newest collections, fresh off the design board.</p>
        </div>
        <div class="product-grid">
            <?php foreach($latestProducts as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= $product['image_path'] ?>" alt="<?= $product['name'] ?>" loading="lazy">
                    <div class="product-badge">New</div>
                </div>
                <div class="product-content">
                    <h3><?= $product['name'] ?></h3>
                    <div class="product-meta">
                        <span class="product-category"><?= $product['category'] ?></span>
                        <span class="product-price">Tsh.<?= number_format($product['price'], 2) ?></span>
                    </div>
                    <p class="product-description"><?= substr($product['description'], 0, 60) ?>...</p>
                    <a href="https://wa.me/+255748791897?text=I%20want%20to%20buy%20<?= urlencode($product['name']) ?>%20(Product ID: <?= $product['name'] ?>)" class="card-whatsapp-btn">
                        <i class="fab fa-whatsapp"></i> Order Now
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="featured-products-section">
        <div class="section-header">
            <h2>Featured Picks</h2>
            <p>Handpicked styles that everyone loves.</p>
        </div>
        <div class="product-grid">
            <?php foreach($featuredProducts as $product): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?= $product['image_path'] ?>" alt="<?= $product['name'] ?>" loading="lazy">
                    <div class="product-badge">Featured</div>
                </div>
                <div class="product-content">
                    <h3><?= $product['name'] ?></h3>
                    <div class="product-meta">
                        <span class="product-category"><?= $product['category'] ?></span>
                        <span class="product-price">Tsh.<?= number_format($product['price'], 2) ?> </span>
                    </div>
                    <p class="product-description"><?= substr($product['description'], 0, 60) ?>...</p>
                    <a href="https://wa.me/+255748791897?text=I%20want%20to%20buy%20<?= urlencode($product['name']) ?>%20(Product ID: <?= $product['name'] ?>)" class="card-whatsapp-btn">
                        <i class="fab fa-whatsapp"></i> Order Now
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="best-sellers-section">
        <div class="section-header">
            <h2>Our Best Sellers</h2>
            <p>These are what everyone is loving right now!</p>
        </div>
        <div class="gallery">
            <?php foreach($bestsellers as $product): ?>
            <div class="gallery-item">
                <img src="<?= $product['image_path'] ?>" alt="<?= $product['name'] ?>" loading="lazy">
                <div class="gallery-overlay">
                    <h3><?= $product['name'] ?></h3>
                    <p>Tsh.<?= number_format($product['price'], 2) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

   <!-- In your testimonials section, replace the empty testimonials-grid with this: -->
<section id="testimonials-section">
    <div class="section-header">
        <h2>What Our Customers Say</h2>
        <p>Hear from those who love Cambridge.</p>
    </div>
    <div class="testimonials-grid">
        <?php
        // Fetch testimonials from database
        $testimonialsQuery = "SELECT * FROM feedbck ORDER BY created_at DESC LIMIT 3";
        $testimonialsResult = mysqli_query($conn, $testimonialsQuery);
        
        if(mysqli_num_rows($testimonialsResult) > 0) {
            while($testimonial = mysqli_fetch_assoc($testimonialsResult)) {
                echo '<div class="testimonial-card">';
                echo '<div class="testimonial-content">';
                echo '<p>"' . htmlspecialchars($testimonial['feedback']) . '"</p>';
                echo '</div>';
                echo '<div class="testimonial-author">';
                echo '<span class="author-name">' . htmlspecialchars($testimonial['name']) . '</span>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p class="no-testimonials">No testimonials yet. Be the first to review!</p>';
        }
        ?>
    </div>
</section>

<style>
.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.testimonial-card {
    background: #fff;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
}

.testimonial-content p {
    font-style: italic;
    color: #555;
    line-height: 1.6;
    margin-bottom: 1rem;
    position: relative;
}

.testimonial-content p::before,
.testimonial-content p::after {
    color: #2A9D8F;
    font-size: 1.5rem;
    line-height: 1;
}

.testimonial-content p::before {
    content: '"';
    position: absolute;
    left: -0.5rem;
    top: -0.5rem;
}

.testimonial-content p::after {
    content: '"';
}

.testimonial-author {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.author-name {
    font-weight: 600;
    color: #333;
}

.author-date {
    font-size: 0.8rem;
    color: #888;
}

.no-testimonials {
    text-align: center;
    grid-column: 1/-1;
    padding: 2rem;
    color: #666;
    font-style: italic;
}

@media (max-width: 768px) {
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
}
</style>

    <section class="whatsapp-cta-section">
        <div class="cta-content">
            <h2>Need Assistance? Chat with Us!</h2>
            <p>Have questions about sizing, materials, or anything else? Our team is ready to help you find your perfect fit.</p>
            <a href="https://wa.me/+255748791897?text=Hello%20I%20have%20a%20question%20about%20your%20products" class="whatsapp-btn-large" aria-label="Chat on WhatsApp">
                Start a WhatsApp Chat
            </a>
        </div>
    </section>

<style>
    /* Additional styles for the gallery overlay */
    .gallery {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }
    
    .gallery-item {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    }
    
    .gallery-item img {
        width: 100%;
        height: 280px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-item:hover img {
        transform: scale(1.05);
    }
    
    .gallery-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: white;
        padding: 1.5rem 1rem 1rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    .gallery-overlay h3 {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }
    
    .gallery-overlay p {
        font-size: 1rem;
        font-weight: 600;
    }
</style>

<script>
    // Carousel functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize carousel
        const carousel = document.getElementById('heroCarousel');
        const slides = document.querySelectorAll('.carousel-slide');
        const dotsContainer = document.getElementById('carouselDots');
        let currentIndex = 0;
        
        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('dot');
            if(index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });
        
        // Navigation buttons
        document.querySelector('.carousel-nav.prev').addEventListener('click', () => {
            goToSlide(currentIndex > 0 ? currentIndex - 1 : slides.length - 1);
        });
        
        document.querySelector('.carousel-nav.next').addEventListener('click', () => {
            goToSlide(currentIndex < slides.length - 1 ? currentIndex + 1 : 0);
        });
        
        function goToSlide(index) {
            currentIndex = index;
            carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
            
            // Update dots
            document.querySelectorAll('.dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === currentIndex);
            });
        }
        
        // Auto-rotate carousel
        setInterval(() => {
            goToSlide(currentIndex < slides.length - 1 ? currentIndex + 1 : 0);
        }, 5000);
        
        // Animate sections as they come into view
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, { threshold: 0.1 });
        
        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });
    });
</script>

<?php
include("temperate/footer.php");
?>