// Sample product data
const products = [
    { id: 1, name: "Men's Casual Shirt", price: 49.99, category: "men", image: "images/1.jpg", sizes: ["S", "M", "L", "XL"], colors: ["Black", "White", "Blue"] },
    { id: 2, name: "Women's Summer Dress", price: 79.99, category: "women", image: "images/11.jpg", sizes: ["S", "M", "L"], colors: ["Red", "White", "Blue"] },
    { id: 3, name: "Leather Belt", price: 29.99, category: "accessories", image: "images/11.jpg", sizes: ["One Size"], colors: ["Black", "Brown"] },
    { id: 4, name: "Men's Jacket", price: 99.99, category: "men", image: "images/11.jpg", sizes: ["M", "L", "XL"], colors: ["Navy", "Grey"] },
];

// Populate product grid
function displayProducts(category = "all") {
    const productGrid = document.getElementById("productGrid");
    productGrid.innerHTML = "";
    const filteredProducts = category === "all" ? products : products.filter(p => p.category === category);
    filteredProducts.forEach((product, index) => {
        const card = document.createElement("div");
        card.className = "product-card";
        card.setAttribute("role", "button");
        card.setAttribute("tabindex", "0");
        card.style.animationDelay = `${index * 0.1}s`;
        card.innerHTML = `
            <img src="${product.image}" alt="${product.name}" loading="lazy">
            <h3>${product.name}</h3>
            <p>$${product.price.toFixed(2)}</p>
        `;
        card.onclick = () => showProductDetail(product);
        card.onkeydown = (e) => { if (e.key === "Enter") showProductDetail(product); };
        productGrid.appendChild(card);
    });
}

// Show product detail
function showProductDetail(product) {
    document.getElementById("home").style.display = "none";
    document.getElementById("products").style.display = "none";
    document.getElementById("gallery").style.display = "none";
    document.getElementById("about").style.display = "none";
    document.getElementById("contact").style.display = "none";
    document.getElementById("product-detail").style.display = "block";
    document.getElementById("mainImage").src = product.image;
    document.getElementById("productName").textContent = product.name;
    document.getElementById("productPrice").textContent = product.price.toFixed(2);
    // Populate size and color dropdowns
    const sizeSelect = document.getElementById("sizeSelect");
    const colorSelect = document.getElementById("colorSelect");
    sizeSelect.innerHTML = product.sizes.map(size => `<option value="${size}">${size}</option>`).join("");
    colorSelect.innerHTML = product.colors.map(color => `<option value="${color}">${color}</option>`).join("");
    // Set WhatsApp order button
    document.getElementById("whatsappOrder").onclick = () => {
        const size = sizeSelect.value;
        const color = colorSelect.value;
        showOrderModal(product, size, color);
    };
    window.scrollTo({ top: 0, behavior: "smooth" });
}

// Show order confirmation modal
function showOrderModal(product, size, color) {
    const modal = document.getElementById("orderModal");
    const modalMessage = document.getElementById("modalMessage");
    const modalConfirm = document.getElementById("modalConfirm");
    modalMessage.textContent = `You are about to order: ${product.name} (Size: ${size}, Color: ${color})`;
    modalConfirm.href = `https://wa.me/1234567890?text=I%20want%20to%20order%20${encodeURIComponent(product.name)}%20(Size:%20${encodeURIComponent(size)},%20Color:%20${encodeURIComponent(color)})`;
    modal.style.display = "flex";
}

// Change main image in product detail
function changeImage(src) {
    document.getElementById("mainImage").src = src;
}

// Filter products
document.getElementById("categoryFilter").addEventListener("change", (e) => {
    displayProducts(e.target.value);
});

// Hamburger menu toggle
document.getElementById("hamburger").addEventListener("click", () => {
    const navLinks = document.getElementById("navLinks");
    navLinks.classList.toggle("active");
});

// Active link highlighting
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener("click", function() {
        document.querySelectorAll('.nav-links a').forEach(l => l.classList.remove("active"));
        this.classList.add("active");
        document.getElementById("navLinks").classList.remove("active");
        document.getElementById("product-detail").style.display = "none";
        document.getElementById("home").style.display = "block";
        document.getElementById("products").style.display = "block";
        document.getElementById("gallery").style.display = "block";
        document.getElementById("about").style.display = "block";
        document.getElementById("contact").style.display = "block";
    });
});

// Smooth scroll for navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function(e) {
        e.preventDefault();
        document.querySelector(this.getAttribute("href")).scrollIntoView({
            behavior: "smooth"
        });
    });
});

// Modal close
document.querySelector(".close").addEventListener("click", () => {
    document.getElementById("orderModal").style.display = "none";
});

// Close modal when clicking outside
window.addEventListener("click", (e) => {
    const modal = document.getElementById("orderModal");
    if (e.target === modal) {
        modal.style.display = "none";
    }
});

// Initial product display
displayProducts();