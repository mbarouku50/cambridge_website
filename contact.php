<?php
include("temperate/header.php");
include("connection.php");

// Initialize variables
$name = $email = $message = '';
$success = false;
$errors = [];

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate inputs
    $name = mysqli_real_escape_string($conn, trim($_POST['name'] ?? ''));
    $email = mysqli_real_escape_string($conn, trim($_POST['email'] ?? ''));
    $message = mysqli_real_escape_string($conn, trim($_POST['message'] ?? ''));
    
    // Validate inputs
    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }
    
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email';
    }
    
    if (empty($message)) {
        $errors['message'] = 'Message is required';
    }
    
    // If no errors, insert into database
    if (empty($errors)) {
        $query = "INSERT INTO contacts (name, email, message, created_at) 
                 VALUES ('$name', '$email', '$message', NOW())";
        
        if (mysqli_query($conn, $query)) {
            $success = true;
            // Clear form fields
            $name = $email = $message = '';
        } else {
            $errors['database'] = 'Error submitting your message. Please try again.';
        }
    }
}
?>

<section id="contact-page">
    <div class="section-header">
        <h2>Get In Touch</h2>
        <p>We'd love to hear from you! Whether you have a question, feedback, or need assistance, our team is ready to help.</p>
    </div>

    <?php if ($success): ?>
    <div class="alert alert-success">
        <p>Thank you for your message! We'll get back to you soon.</p>
    </div>
    <?php elseif (!empty($errors['database'])): ?>
    <div class="alert alert-error">
        <p><?= $errors['database'] ?></p>
    </div>
    <?php endif; ?>

    <div class="contact-info-map-container">
        <div class="contact-details">
            <h3>Our Contact Information</h3>
            <div class="info-item">
                <strong>Email:</strong> <a href="mailto:info@cambridge.com">info@cambridge.com</a>
            </div>
            <div class="info-item">
                <strong>Phone:</strong> <a href="tel:+255689118095">+255 689 118 095</a>
            </div>
            <div class="info-item">
                <strong>WhatsApp:</strong> <a href="https://wa.me/+255689118095?text=Hello%20I%20have%20a%20question" target="_blank" rel="noopener noreferrer">Chat with us on WhatsApp</a>
            </div>
            <div class="info-item">
                <strong>Address:</strong> <p>Upanga, Dar es Salaam, Tanzania</p>
            </div>
            <div class="info-item">
                <strong>Business Hours:</strong> 
                <p>Mon - Fri: 9:00 AM - 6:00 PM (EAT)</p>
                <p>Sat: 10:00 AM - 4:00 PM (EAT)</p>
                <p>Sun: Closed</p>
            </div>

            <h3>Connect Socially</h3>
            <div class="social-links">
                <a href="https://instagram.com/hassanayn_kashmal" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <i class="fab fa-instagram"></i> Instagram
                </a>
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i> Facebook
                </a>
            </div>
        </div>

        <div class="contact-map">
            <h3>Find Us On The Map</h3>
            <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15735.214880328896!2d39.26946898966506!3d-6.806232582598137!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4b75ffbed521%3A0xf20e02dfb45cae3d!2sUpanga%20Mashariki%2C%20Dar%20es%20Salaam!5e1!3m2!1sen!2stz!4v1747830042171!5m2!1sen!2stz" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" aria-label="Store location map"></iframe>
        </div>
    </div>

    <div class="contact-form-section">
        <h3>Send Us a Message</h3>
        <form class="contact-form" method="POST" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
            <div class="form-group <?= isset($errors['name']) ? 'has-error' : '' ?>">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required placeholder="Enter your name">
                <?php if (isset($errors['name'])): ?>
                    <span class="error-message"><?= $errors['name'] ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Enter your email">
                <?php if (isset($errors['email'])): ?>
                    <span class="error-message"><?= $errors['email'] ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group <?= isset($errors['message']) ? 'has-error' : '' ?>">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="6" required placeholder="Type your message here..."><?= htmlspecialchars($message) ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <span class="error-message"><?= $errors['message'] ?></span>
                <?php endif; ?>
            </div>
            <button type="submit" class="submit-btn">Send Message</button>
        </form>
    </div>
</section>

<style>
    /* Contact Page Specific Styles */
    #contact-page {
        max-width: 1400px;
        margin: 0 auto;
        padding: 4rem 2rem;
    }
    
    .contact-info-map-container {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 3rem;
        margin-bottom: 4rem;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
    }
    
    .contact-details {
        padding-right: 1rem;
    }
    
    .contact-details h3, .contact-map h3 {
        font-size: 1.8rem;
        margin-bottom: 1.5rem;
        color: #2b2b2b;
    }
    
    .info-item {
        margin-bottom: 1.5rem;
        font-size: 1.05rem;
        color: #333;
    }
    
    .info-item strong {
        color: #2A9D8F;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .info-item p {
        margin-bottom: 0.5rem;
    }
    
    .info-item a {
        color: #2A9D8F;
        font-weight: 500;
        transition: color 0.3s ease;
    }
    
    .info-item a:hover {
        color: #FF6F61;
        text-decoration: underline;
    }
    
    .social-links {
        display: flex;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .social-links a {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #2A9D8F;
        font-weight: 600;
        font-size: 1.1rem;
        transition: color 0.3s ease;
    }
    
    .social-links a:hover {
        color: #FF6F61;
        text-decoration: none;
    }
    
    .social-links i {
        font-size: 1.2rem;
    }
    
    .contact-map .map {
        width: 100%;
        height: 400px;
        border: 0;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    
    .contact-form-section {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        text-align: center;
    }
    
    .contact-form-section h3 {
        font-size: 2rem;
        margin-bottom: 2rem;
        color: #2b2b2b;
    }
    
    .contact-form .form-group {
        margin-bottom: 1.5rem;
        text-align: left;
    }
    
    .contact-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #444;
    }
    
    .contact-form input[type="text"],
    .contact-form input[type="email"],
    .contact-form textarea {
        width: 100%;
        padding: 1rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }
    
    .contact-form input:focus,
    .contact-form textarea:focus {
        border-color: #2A9D8F;
        outline: none;
        box-shadow: 0 0 0 3px rgba(42, 157, 143, 0.15);
    }
    
    .contact-form textarea {
        resize: vertical;
        min-height: 150px;
    }
    
    .submit-btn {
        display: inline-block;
        padding: 1rem 2.5rem;
        background: #FF6F61;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 10px rgba(255, 111, 97, 0.3);
        margin-top: 1rem;
    }
    
    .submit-btn:hover {
        background: #e65c50;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(255, 111, 97, 0.4);
    }
    
    /* Error Handling Styles */
    .form-group.has-error input,
    .form-group.has-error textarea {
        border-color: #e74c3c;
    }
    
    .error-message {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 0.3rem;
        display: block;
    }
    
    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    /* Responsive Styles */
    @media (max-width: 1024px) {
        .contact-info-map-container {
            grid-template-columns: 1fr;
        }
        
        .contact-details {
            padding-right: 0;
        }
    }
    
    @media (max-width: 768px) {
        #contact-page {
            padding: 3rem 1.5rem;
        }
        
        .contact-info-map-container,
        .contact-form-section {
            padding: 1.5rem;
        }
        
        .contact-details h3, 
        .contact-map h3,
        .contact-form-section h3 {
            font-size: 1.5rem;
        }
    }
    
    @media (max-width: 480px) {
        .social-links {
            flex-direction: column;
            gap: 1rem;
        }
        
        .submit-btn {
            width: 100%;
        }
    }
</style>

<?php
include("temperate/footer.php");
?>