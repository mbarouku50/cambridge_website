<style>
/* Ultra Compact Footer */
footer {
    background: #2b2b2b;
    color: #f8f9fa;
    padding: 1rem 0 0;
    font-family: 'Poppins', sans-serif;
    font-size: 0.75rem;
    line-height: 1.4;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.footer-main {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 1rem;
}

.footer-section h3 {
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    color: #fff;
    font-weight: 600;
}

.footer-links ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 0.3rem;
}

.footer-links a {
    color: #adb5bd;
    text-decoration: none;
    transition: color 0.2s ease;
    display: block;
}

.footer-links a:hover {
    color: #fff;
}

.social-icons {
    display: flex;
    gap: 0.6rem;
    margin-top: 0.3rem;
}

.social-icons a {
    color: #adb5bd;
    font-size: 0.9rem;
    transition: color 0.2s ease;
}

.social-icons a:hover {
    color: #fff;
}

.footer-contact p {
    margin-bottom: 0.4rem;
    color: #adb5bd;
}

.feedback-form {
    display: flex;
    flex-direction: column;
    gap: 0.4rem;
}

.feedback-form input,
.feedback-form textarea {
    width: 100%;
    padding: 0.3rem;
    border: 1px solid #495057;
    border-radius: 2px;
    background: #343a40;
    color: #fff;
    font-size: 0.7rem;
}

.feedback-form textarea {
    resize: vertical;
    min-height: 40px;
}

.submit-btn {
    background: #2A9D8F;
    color: white;
    border: none;
    padding: 0.3rem 0.6rem;
    border-radius: 2px;
    cursor: pointer;
    font-size: 0.7rem;
    width: fit-content;
}

.footer-bottom {
    text-align: center;
    padding: 0.5rem 0;
    border-top: 1px solid #495057;
    color: #adb5bd;
    font-size: 0.65rem;
}

/* WhatsApp float */
.whatsapp-float {
    position: fixed;
    bottom: 10px;
    right: 10px;
    background: #25D366;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    z-index: 100;
}

.whatsapp-float i {
    font-size: 0.9rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .footer-main {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .footer-main {
        grid-template-columns: 1fr;
        gap: 0.8rem;
    }
    
    .footer-section h3 {
        margin-bottom: 0.3rem;
    }
}
</style>

<footer>
    <div class="footer-content">
        <div class="footer-main">
            <div class="footer-section footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="./admin/adminLogin.php">Admin</a></li>
                </ul>
            </div>
            
            <div class="footer-section footer-contact">
                <h3>Contact Info</h3>
                <p>info@cambridge.com</p>
                <p>+255 123 456 789</p>
                <p>Dar es Salaam, Tanzania</p>
            </div>
            
            <div class="footer-section footer-social">
                <h3>Connect With Us</h3>
                <div class="social-icons">
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
            
            <div class="footer-section">
                <h3>Your Feedback</h3>
                <form class="feedback-form">
                    <input type="text" placeholder="Your name">
                    <textarea placeholder="Your message..."></textarea>
                    <button type="submit" class="submit-btn">Send</button>
                </form>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; 2025 Cambridge. All rights reserved.</p>
        </div>
    </div>

    <a href="https://wa.me/+255748791897" class="whatsapp-float" aria-label="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</footer>