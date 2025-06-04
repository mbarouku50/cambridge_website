<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambridge - Curated Fashion</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        nav {
            background-color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
            list-style: none;
        }

        .nav-links li a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links li a:hover,
        .nav-links li a.active {
            color: #2A9D8F;
        }

        .hamburger {
            display: none;
            font-size: 1.8rem;
            cursor: pointer;
        }

        .social-icons a {
            margin-right: 0.5rem;
            color: #333;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #2A9D8F;
        }

        @media (max-width: 768px) {
            .nav-links {
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                flex-direction: column;
                background-color: white;
                overflow: hidden;
                max-height: 0;
                transition: max-height 0.4s ease;
                box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            }

            .nav-links.open {
                max-height: 400px;
            }

            .hamburger {
                display: block;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-container">
            <a href="index.php">
                <div class="logo">
                    <img src="images/12.png" alt="Cambridge Logo" style="width: 150px; height: auto;">
                </div>
            </a>

            <div class="hamburger" id="hamburger">☰</div>

            <ul class="nav-links" id="navLinks">
                <li><a href="index.php" class="active">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li class="social-icons">
                    <a href="https://facebook.com" target="_blank" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/p/DJvrM-fIlRi/" target="_blank" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://twitter.com" target="_blank" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="https://wa.me/+255748791897" target="_blank" aria-label="whatsapp" ><i class="fab fa-whatsapp"></i></a>
                </li>
            </ul>
        </div>
    </nav>

    <script>
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.getElementById('navLinks');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('open');
        });
    </script>
