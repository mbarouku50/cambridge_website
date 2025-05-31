<?php
include("temperate/header.php");
?>
    <section id="contact-page">
        <div class="section-header">
            <h2>Get In Touch</h2>
            <p>We'd love to hear from you! Whether you have a question, feedback, or need assistance, our team is ready to help.</p>
        </div>

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
                    <strong>Business Hours:</strong> <p>Mon - Fri: 9:00 AM - 6:00 PM (EAT)</p>
                    <p>Sat: 10:00 AM - 4:00 PM (EAT)</p>
                    <p>Sun: Closed</p>
                </div>

                <h3>Connect Socially</h3>
                <div class="social-links">
                    <a href="https://instagram.com/hassanayn_kashmal" target="_blank" rel="noopener noreferrer" aria-label="Instagram">Instagram</a>
                    </div>
            </div>

            <div class="contact-map">
                <h3>Find Us On The Map</h3>
                <iframe class="map" src=https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15735.214880328896!2d39.26946898966506!3d-6.806232582598137!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4b75ffbed521%3A0xf20e02dfb45cae3d!2sUpanga%20Mashariki%2C%20Dar%20es%20Salaam!5e1!3m2!1sen!2stz!4v1747830042171!5m2!1sen!2stz" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" aria-label="Store location map"></iframe>
                
            </div>
        </div>

        <div class="contact-form-section">
            <h3>Send Us a Message</h3>
            <form class="contact-form">
                <div class="form-group">
                    <label for="name">Your Name:</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required placeholder="Subject of your message">
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="6" required placeholder="Type your message here..."></textarea>
                </div>
                <button type="submit" class="submit-btn">Send Message</button>
            </form>
            <p class="form-note">Please note: This is a frontend demo. The contact form does not send actual emails.</p>
        </div>
    </section>

<?php
include("temperate/footer.php");
?>