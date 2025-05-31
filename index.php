<?php
include("temperate/header.php");
?>
    <div class="hero-carousel-container">
        <div class="hero-carousel" id="heroCarousel">
            <div class="carousel-slide">
                <img src="images/1.jpg" alt="Fashion Collection 1" loading="lazy">
                <div class="hero-content">
                    <h1>Discover Cambridge Trends</h1>
                    <!-- <p>Elevate your wardrobe with our curated, sustainable fashion collections.</p> -->
                     <p>Elevate your wardrobe and leave a Lasting impression wherever you go.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="images/2.jpg" alt="Fashion Collection 2" loading="lazy">
                <div class="hero-content">
                    <h1>Style Meets Sustainability</h1>
                    <p>Embrace fashion that feels good and does good for the planet.</p>
                </div>
            </div>
            <div class="carousel-slide">
                <img src="images/3.jpg" alt="Fashion Collection 3" loading="lazy">
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

    <section id="categories-section">
        <div class="section-header">
            <h2>Shop by Category</h2>
            <p>Find exactly what you're looking for.</p>
        </div>
        <div class="categories">
            <div class="category-card" onclick="location.href='products.html?category=men'">
                <img src="images/1.jpg" alt="Men's Clothing" loading="lazy">
                <h3>Formal Trousers</h3>
            </div>
            <div class="category-card" onclick="location.href='products.html?category=women'">
                <img src="images/2.jpg" alt="Women's Clothing" loading="lazy">
                <h3>T-Shirts</h3>
            </div>
            <div class="category-card" onclick="location.href='products.html?category=accessories'">
                <img src="images/3.jpg" alt="Accessories" loading="lazy">
                <h3>Jackets</h3>
            </div>
            <div class="category-card" onclick="location.href='products.html?category=accessories'">
                <img src="images/3.jpg" alt="Accessories" loading="lazy">
                <h3>Formal Shirt</h3>
            </div>
            <div class="category-card" onclick="location.href='products.html?category=accessories'">
                <img src="images/3.jpg" alt="Accessories" loading="lazy">
                <h3>Casual Shirts</h3>
            </div>
            <div class="category-card" onclick="location.href='products.html?category=accessories'">
                <img src="images/3.jpg" alt="Accessories" loading="lazy">
                <h3>Accessories</h3>
            </div>
            
        </div>
    </section>

    <section id="latest-products-section">
        <div class="section-header">
            <h2>Latest Arrivals</h2>
            <p>Discover our newest collections, fresh off the design board.</p>
             <!-- <p>Discover the perfect balance of Traditions and Premium Quality.</p> -->
        </div>
        <div class="product-grid" id="latestProductsGrid">
            </div>
    </section>

    <section id="featured-products-section">
        <div class="section-header">
            <h2>Featured Picks</h2>
            <p>Handpicked styles that everyone loves.</p>
        </div>
        <div class="product-grid" id="featuredProductsGrid">
            </div>
    </section>

    <section id="best-sellers-section">
        <div class="section-header">
            <h2>Our Best Sellers</h2>
            <p>These are what everyone is loving right now!</p>
        </div>
        <div class="gallery">
            <img src="images/4.jpg" alt="Best Seller 1" loading="lazy">
            <img src="images/1.jpg" alt="Best Seller 2" loading="lazy">
            <img src="images/4.jpg" alt="Best Seller 3" loading="lazy">
            <img src="images/8.jpg" alt="Best Seller 4" loading="lazy">
        </div>
    </section>

    <section id="testimonials-section">
        <div class="section-header">
            <h2>What Our Customers Say</h2>
            <p>Hear from those who love Cambridge.</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <p class="quote">"Absolutely love the quality and style! Cambridge has truly elevated my wardrobe. Highly recommend!"</p>
                <p class="author">- Juma.</p>
            </div>
            <div class="testimonial-card">
                <p class="quote">"Sustainable fashion that doesn't compromise on design. Their pieces are timeless and comfortable."</p>
                <p class="author">- Alex </p>
            </div>
            <div class="testimonial-card">
                <p class="quote">"The customer service is fantastic, and the clothes are even better in person. My new favorite brand!"</p>
                <p class="author">- Mwajuma.</p>
            </div>
        </div>
    </section>

    <section class="whatsapp-cta-section">
        <div class="cta-content">
            <h2>Need Assistance? Chat with Us!</h2>
            <p>Have questions about sizing, materials, or anything else? Our team is ready to help you find your perfect fit.</p>
            <a href="https://wa.me/+255689118095?text=Hello%20I%20have%20a%20question%20about%20your%20products" class="whatsapp-btn-large" aria-label="Chat on WhatsApp">
                Start a WhatsApp Chat
            </a>
        </div>
    </section>
<?php
include("temperate/footer.php");
?>