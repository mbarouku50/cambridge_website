// Sample product data (expanded for new sections and descriptions)
const products = [
    {
        id: 1,
        name: "Classic Denim Jacket",
        price: 89.99,
        category: "men",
        description: "A timeless denim jacket, perfect for layering. Made from durable cotton for long-lasting wear. A wardrobe essential.",
        image: "images/1.jpg",
        thumbnails: ["images/1.jpg", "images/2.jpg", "images/3.jpg"],
        sizes: ["S", "M", "L", "XL"],
        colors: ["Blue Denim", "Black"]
    },
    {
        id: 2,
        name: "Elegant Floral Dress",
        price: 79.99,
        category: "women",
        description: "Flowy and vibrant, this floral dress is ideal for summer outings. Lightweight fabric ensures comfort all day long.",
        image: "images/2.jpg",
        thumbnails: ["images/2.jpg", "images/2.jpg", "images/3.jpg"],
        sizes: ["S", "M", "L"],
        colors: ["Red Floral", "Blue Floral", "Yellow Floral"]
    },
    {
        id: 3,
        name: "Premium Leather Wallet",
        price: 49.99,
        category: "accessories",
        description: "Crafted from genuine leather, this wallet offers ample space and durability. A sleek accessory for everyday use.",
        image: "images/3.jpg",
        thumbnails: ["images/3.jpg", "images/2.jpg"],
        sizes: ["One Size"],
        colors: ["Black", "Brown", "Tan"]
    },
    {
        id: 4,
        name: "Sporty Hoodie",
        price: 59.99,
        category: "men",
        description: "Comfortable and stylish, this hoodie is perfect for casual wear or workouts. Soft fleece lining for ultimate warmth.",
        image: "images/4.jpg",
        thumbnails: ["images/4.jpg", "images/2.jpg"],
        sizes: ["M", "L", "XL", "XXL"],
        colors: ["Grey", "Navy", "Black"]
    },
    {
        id: 5,
        name: "Striped Knit Sweater",
        price: 65.00,
        category: "women",
        description: "A cozy and chic sweater with a classic stripe pattern. Made from soft knit material, great for cooler evenings.",
        image: "images/11.jpg",
        thumbnails: ["images/11.jpg", "images/11.jpg"],
        sizes: ["S", "M", "L"],
        colors: ["Navy/White", "Black/Red"]
    },
    {
        id: 6,
        name: "Elegant Silk Scarf",
        price: 35.00,
        category: "accessories",
        description: "Add a touch of elegance with this pure silk scarf. Versatile design for various styling options.",
        image: "images/1.jpg",
        thumbnails: ["images/1.jpg", "images/1.jpg"],
        sizes: ["One Size"],
        colors: ["Blue Pattern", "Green Pattern"]
    },
    {
        id: 7,
        name: "Slim Fit Chinos",
        price: 75.00,
        category: "men",
        description: "Modern slim-fit chinos offering comfort and a sharp look. Perfect for smart-casual occasions.",
        image: "images/3.jpg",
        thumbnails: ["images/3.jpg", "images/3.jpg"],
        sizes: ["28", "30", "32", "34", "36"],
        colors: ["Khaki", "Navy", "Olive"]
    },
    {
        id: 8,
        name: "High-Waist Jeans",
        price: 70.00,
        category: "women",
        description: "Figure-flattering high-waist jeans with a comfortable stretch. A must-have for any denim lover.",
        image: "images/8.jpg",
        thumbnails: ["images/8.jpg", "images/8.jpg"],
        sizes: ["26", "28", "30", "32"],
        colors: ["Light Wash", "Dark Wash", "Black"]
    },
    {
        id: 9,
        name: "Stylish Sunglasses",
        price: 40.00,
        category: "accessories",
        description: "Protect your eyes with these fashionable sunglasses. UV protected lenses for ultimate eye safety.",
        image: "images/11.jpg",
        thumbnails: ["images/11.jpg", "images/11.jpg"],
        sizes: ["One Size"],
        colors: ["Black Frame", "Tortoise Shell"]
    },
    {
        id: 10,
        name: "Graphic T-Shirt",
        price: 25.00,
        category: "men",
        description: "Soft cotton t-shirt featuring a unique graphic print. Casual and comfortable for everyday wear.",
        image: "images/2.jpg",
        thumbnails: ["images/2.jpg", "images/2.jpg"],
        sizes: ["S", "M", "L", "XL"],
        colors: ["White", "Black", "Green"]
    },
    {
        id: 11,
        name: "Elegant Blouse",
        price: 55.00,
        category: "women",
        description: "A sophisticated blouse perfect for both office and evening wear. Lightweight and breathable fabric.",
        image: "images/4.jpg",
        thumbnails: ["images/4.jpg", "images/4.jpg"],
        sizes: ["S", "M", "L"],
        colors: ["Cream", "Navy", "Dusty Pink"]
    },
    {
        id: 12,
        name: "Knitted Beanie",
        price: 20.00,
        category: "accessories",
        description: "Keep warm and stylish with this soft knitted beanie. Perfect for chilly weather.",
        image: "images/11.jpg",
        thumbnails: ["images/11.jpg", "images/11.jpg"],
        sizes: ["One Size"],
        colors: ["Black", "Grey", "Burgundy"]
    }
];

// Define products for specific sections (IDs should correspond to `products` array)
const latestProducts = [products[10], products[9], products[8], products[7]]; // Example: Blouse, T-Shirt, Jeans, Sunglasses
const featuredProducts = [products[0], products[1], products[3], products[6]]; // Example: Denim Jacket, Floral Dress, Hoodie, Chinos

const WHATSAPP_PHONE_NUMBER = "+255689118095"; // Your WhatsApp number

// --- Product Display and Filtering ---

function createProductCard(product) {
    const card = document.createElement("div");
    card.className = "product-card";
    card.setAttribute("role", "button");
    card.setAttribute("tabindex", "0");
    card.innerHTML = `
        <img src="${product.image}" alt="${product.name}" loading="lazy">
        <div class="product-card-content">
            <h3>${product.name}</h3>
            <p class="price">$${product.price.toFixed(2)}</p>
            <p class="short-description">${product.description}</p>
            <a href="#" class="card-whatsapp-btn" data-product-id="${product.id}" aria-label="Order ${product.name} via WhatsApp">Order via WhatsApp</a>
        </div>
    `;
    card.querySelector('.card-whatsapp-btn').addEventListener('click', (e) => {
        e.preventDefault(); // Prevent card click event from firing if button is clicked
        e.stopPropagation(); // Stop propagation to prevent showing product detail
        const selectedProduct = products.find(p => p.id === product.id);
        const prefilledMessage = `I'm interested in ${encodeURIComponent(selectedProduct.name)}.`;
        const whatsappLink = `https://wa.me/${WHATSAPP_PHONE_NUMBER}?text=${prefilledMessage}`;
        window.open(whatsappLink, '_blank');
    });

    // Handle clicks on the card itself (excluding the button) to show detail.
    // This will now only work on products.html
    card.addEventListener('click', () => {
        // Only show product detail if on the products.html page
        if (window.location.pathname.includes('products.html')) {
            showProductDetail(product);
        } else {
            // If on homepage, navigate to products.html and then show detail
            window.location.href = `products.html?product_id=${product.id}`;
        }
    });
    card.addEventListener('keydown', (e) => {
        if (e.key === "Enter") {
            if (window.location.pathname.includes('products.html')) {
                showProductDetail(product);
            } else {
                window.location.href = `products.html?product_id=${product.id}`;
            }
        }
    });

    return card;
}

// Populates a specific grid (e.g., latest, featured, or all products)
function populateProductGrid(gridId, productList) {
    const productGrid = document.getElementById(gridId);
    if (!productGrid) return; // Exit if grid doesn't exist on this page
    productGrid.innerHTML = ""; // Clear existing cards
    if (productList.length === 0) {
        productGrid.innerHTML = '<p class="no-results">No products found matching your criteria.</p>';
        return;
    }
    productList.forEach((product, index) => {
        const card = createProductCard(product);
        card.style.animationDelay = `${index * 0.1}s`; // Stagger animation
        productGrid.appendChild(card);
    });
}

// Function to display products in the main product grid on products.html
function displayAllProducts(category = "all", sortBy = "default", searchTerm = "") {
    let filteredProducts = [...products];

    // 1. Filter by Search Term
    if (searchTerm) {
        const lowerCaseSearchTerm = searchTerm.toLowerCase();
        filteredProducts = filteredProducts.filter(p =>
            p.name.toLowerCase().includes(lowerCaseSearchTerm) ||
            p.description.toLowerCase().includes(lowerCaseSearchTerm)
        );
    }

    // 2. Filter by Category
    if (category !== "all") {
        filteredProducts = filteredProducts.filter(p => p.category === category);
    }

    // 3. Sort
    switch (sortBy) {
        case "price-asc":
            filteredProducts.sort((a, b) => a.price - b.price);
            break;
        case "price-desc":
            filteredProducts.sort((a, b) => b.price - a.price);
            break;
        case "name-asc":
            filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case "name-desc":
            filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
            break;
        default:
            // No specific sorting, maintain original order or a default (e.g., by ID)
            filteredProducts.sort((a,b) => a.id - b.id);
            break;
    }

    populateProductGrid("allProductGrid", filteredProducts);
}

// --- Product Detail View (Only applicable on products.html) ---

function showProductDetail(product) {
    const productsPageSection = document.getElementById("products-page");
    const productDetailSection = document.getElementById("product-detail");

    if (productsPageSection) productsPageSection.classList.add('hidden');
    if (productDetailSection) {
        productDetailSection.classList.remove('hidden');
        productDetailSection.classList.add('visible');
    } else {
        console.error("Product detail section not found. Ensure this script is on products.html");
        return;
    }


    document.getElementById("mainImage").src = product.image;
    document.getElementById("productName").textContent = product.name;
    document.getElementById("productPrice").textContent = product.price.toFixed(2);
    document.querySelector("#product-detail .product-description").textContent = product.description;

    // Populate size dropdown
    const sizeSelect = document.getElementById("sizeSelect");
    sizeSelect.innerHTML = product.sizes.map(size => `<option value="${size}">${size}</option>`).join("");
    // Populate color dropdown
    const colorSelect = document.getElementById("colorSelect");
    colorSelect.innerHTML = product.colors.map(color => `<option value="${color}">${color}</option>`).join("");

    // Populate thumbnail images
    const imagesContainer = document.querySelector("#product-detail .images");
    imagesContainer.innerHTML = "";
    product.thumbnails.forEach(thumbnailSrc => {
        const img = document.createElement("img");
        img.src = thumbnailSrc;
        img.alt = product.name;
        img.loading = "lazy";
        img.onclick = () => changeImage(img.src);
        imagesContainer.appendChild(img);
    });

    // Set WhatsApp order button for product detail
    document.getElementById("whatsappOrder").onclick = () => {
        const size = sizeSelect.value;
        const color = colorSelect.value;
        showOrderModal(product, size, color);
    };
    window.scrollTo({ top: 0, behavior: "smooth" }); // Scroll to top
}

function hideProductDetail() {
    const productsPageSection = document.getElementById("products-page");
    const productDetailSection = document.getElementById("product-detail");

    if (productDetailSection) {
        productDetailSection.classList.add('hidden');
        productDetailSection.classList.remove('visible');
    }
    if (productsPageSection) productsPageSection.classList.remove('hidden');
    window.scrollTo({ top: 0, behavior: "smooth" }); // Scroll to top
}

function changeImage(src) {
    document.getElementById("mainImage").src = src;
}

// --- Order Confirmation Modal ---

function showOrderModal(product, size, color) {
    const modal = document.getElementById("orderModal");
    const modalMessage = document.getElementById("modalMessage");
    const modalConfirm = document.getElementById("modalConfirm");

    modalMessage.textContent = `You are about to order: ${product.name} (Size: ${size}, Color: ${color}). Click 'Proceed to WhatsApp' to confirm your order.`;
    const prefilledMessage = `I'm interested in ordering ${encodeURIComponent(product.name)} (Size: ${encodeURIComponent(size)}, Color: ${encodeURIComponent(color)}).`;
    modalConfirm.href = `https://wa.me/${WHATSAPP_PHONE_NUMBER}?text=${prefilledMessage}`;
    modal.style.display = "flex"; // Use flex to center
    document.body.style.overflow = "hidden"; // Prevent scrolling behind modal
}

function closeModal() {
    document.getElementById("orderModal").style.display = "none";
    document.body.style.overflow = "auto"; // Restore scrolling
}

// Close modal when clicking outside
window.addEventListener("click", (e) => {
    const modal = document.getElementById("orderModal");
    if (e.target === modal) {
        closeModal();
    }
});

// --- Event Listeners ---

// Filter, Sort, and Search products (only on products.html)
if (document.getElementById("categoryFilter")) {
    document.getElementById("categoryFilter").addEventListener("change", updateProductDisplay);
}
if (document.getElementById("sortProducts")) {
    document.getElementById("sortProducts").addEventListener("change", updateProductDisplay);
}
if (document.getElementById("productSearch")) {
    document.getElementById("productSearch").addEventListener("input", updateProductDisplay); // Use 'input' for real-time search
}


function updateProductDisplay() {
    const category = document.getElementById("categoryFilter") ? document.getElementById("categoryFilter").value : "all";
    const sortBy = document.getElementById("sortProducts") ? document.getElementById("sortProducts").value : "default";
    const searchTerm = document.getElementById("productSearch") ? document.getElementById("productSearch").value : "";
    displayAllProducts(category, sortBy, searchTerm);
}

// Hamburger menu toggle
document.getElementById("hamburger").addEventListener("click", () => {
    const navLinks = document.getElementById("navLinks");
    navLinks.classList.toggle("active");
});


// --- Hero Carousel Logic (Only applicable on index.html) ---
let currentSlide = 0;
let slides;
let totalSlides;
let carousel;
let dotsContainer;
let carouselInterval;

if (document.getElementById('heroCarousel')) {
    slides = document.querySelectorAll('.carousel-slide');
    totalSlides = slides.length;
    carousel = document.getElementById('heroCarousel');
    dotsContainer = document.getElementById('carouselDots');

    function showSlide(index) {
        if (index >= totalSlides) {
            currentSlide = 0;
        } else if (index < 0) {
            currentSlide = totalSlides - 1;
        } else {
            currentSlide = index;
        }
        carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
        updateDots();
    }

    function nextSlide() {
        showSlide(currentSlide + 1);
    }

    function prevSlide() {
        showSlide(currentSlide - 1);
    }

    function startCarousel() {
        carouselInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
    }

    function stopCarousel() {
        clearInterval(carouselInterval);
    }

    function updateDots() {
        if (!dotsContainer) return; // Ensure dots container exists
        dotsContainer.innerHTML = ''; // Clear existing dots
        slides.forEach((_, index) => {
            const dot = document.createElement('span');
            dot.classList.add('dot');
            if (index === currentSlide) {
                dot.classList.add('active');
            }
            dot.addEventListener('click', () => {
                stopCarousel(); // Stop carousel on manual navigation
                showSlide(index);
                startCarousel(); // Restart carousel after a delay
            });
            dotsContainer.appendChild(dot);
        });
    }

    document.querySelector('.carousel-nav.next').addEventListener('click', () => {
        stopCarousel();
        nextSlide();
        startCarousel();
    });
    document.querySelector('.carousel-nav.prev').addEventListener('click', () => {
        stopCarousel();
        prevSlide();
        startCarousel();
    });
}


// --- Intersection Observer for Animations (applies to all pages) ---
const sections = document.querySelectorAll('section');

const observerOptions = {
    root: null,
    rootMargin: '0px',
    threshold: 0.2 // Trigger when 20% of the section is visible
};

const sectionObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        // Only animate sections on the homepage (or if it's the product detail being shown)
        // This prevents sections on other pages from having an initial fade-in delay if they are not dynamically loaded
        if (window.location.pathname.includes('index.html') || window.location.pathname === '/') {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        } else {
            // For other pages, just make sections visible immediately
            entry.target.classList.add('animate-in'); // Or just remove initial hidden state
        }
    });
}, observerOptions);

sections.forEach(section => {
    sectionObserver.observe(section);
});