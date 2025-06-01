<footer>
        <div class="footer-content">
            <a href="index.html">
                <div class="logo">
                    <img src="images/12.png" alt="Chic Trends Logo" style="width: 150px; height: auto;">
                </div>
            </a>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                     <li><a href="./admin/adminLogin.php">Admin</a></li>
                </ul>
            </div>
            <div class="footer-social">
                <h3>Connect With Us</h3>
                <a href="https://www.instagram.com/p/DJvrM-fIlRi/?igsh=MXNycHE2YWMzZWRieA==" target="_blank" rel="noopener noreferrer" aria-label="Instagram">Instagram</a>
                <a href="https://wa.me/+255748791897" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp">WhatsApp</a>
            </div>
            <div class="footer-contact">
                <h3>Contact Info</h3>
                <p>Email: info@cambridge.com</p>
                <p>Phone: +255 123 456 789</p>
                <p>Dar es Salaam, Tanzania</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 Cambridge. All rights reserved.</p>
        </div>
    </footer>

    <a href="https://wa.me/+255748791897?text=Hello%20I%20have%20a%20question%20about%20your%20products" class="whatsapp-float" aria-label="Chat on WhatsApp">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#fff"><path d="M12.031 0C5.424 0 .048 5.376.048 12c0 2.176.576 4.224 1.584 6l-1.584 5.856 6-1.584c1.728.936 3.696 1.44 5.952 1.44 6.608 0 11.984-5.376 11.984-12S18.639 0 12.031 0zm0 21.984c-2.112 0-4.128-.576-5.856-1.584l-.432-.24-3.552.96.96-3.504-.24-.432c-1.008-1.728-1.584-3.744-1.584-5.904 0-5.856 4.752-10.608 10.608-10.608S22.639 6.144 22.639 12 17.887 22.608 12.031 22.608zm6.048-6.528c-.336-.168-1.968-.96-2.256-1.056-.336-.096-.576-.144-.816.168-.24.336-.96 1.056-1.176 1.272-.192.192-.384.216-.72.12-.336-.096-1.44-.528-2.736-1.632-1.008-.864-1.68-1.92-1.872-2.256-.192-.336-.024-.528.144-.696.168-.192.336-.432.504-.648.144-.192.192-.336.288-.528.096-.192.048-.336-.024-.528-.072-.192-.72-1.728-.96-2.352-.24-.624-.48-.528-.72-.528-.192 0-.432 0-.672 0-.24 0-.624.096-.96.48-.336.384-1.296 1.248-1.296 3.048 0 1.776 1.296 3.504 1.488 3.744.192.24 2.592 4.032 6.288 5.664.864.384 1.536.624 2.064.816.864.336 1.656.288 2.256.192.672-.12 1.968-.792 2.256-1.56.288-.768.288-1.44.192-1.584-.096-.144-.336-.216-.672-.384z"/></svg>
    </a>

    <script src="script.js"></script>
    <script>
        // Specific script for homepage functionality
        document.addEventListener("DOMContentLoaded", () => {
            // Initialize product displays for homepage sections
            populateProductGrid("latestProductsGrid", latestProducts);
            populateProductGrid("featuredProductsGrid", featuredProducts);

            // Initialize hero carousel
            showSlide(currentSlide);
            startCarousel();

            // Set active class for current page
            const currentPage = window.location.pathname.split("/").pop();
            document.querySelectorAll('.nav-links a').forEach(link => {
                if (link.getAttribute('href') === currentPage || (currentPage === '' && link.getAttribute('href') === 'index.html')) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>