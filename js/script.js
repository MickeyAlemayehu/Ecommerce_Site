document.addEventListener("DOMContentLoaded", () => {
const cartIcon = document.querySelector('.cart-icon');
const cartCountElement = document.querySelector('.cart-count');
const shopNowButtons = document.querySelectorAll(".product button");
let cartCount = parseInt(localStorage.getItem("cartCount")) || 0;
let cartItems = JSON.parse(localStorage.getItem("cartItems")) || [];
updateCartUI();
shopNowButtons.forEach(button => {
  button.addEventListener("click", () => {
    const productDiv = button.closest(".product");
    const name = productDiv.querySelector("h3").textContent;
    const description = productDiv.querySelector("p").textContent;
    const price = parseFloat(productDiv.dataset.price);
    const image = productDiv.querySelector("img").src;

    const productData = { name, description, price, image };
    cartItems.push(productData);

    cartCount++;
    localStorage.setItem("cartItems", JSON.stringify(cartItems));
    localStorage.setItem("cartCount", cartCount);
    updateCartUI();

    alert("🛒 Item added to cart!");
  });
});
function updateCartUI() {
    if (cartCountElement) {
      cartCountElement.textContent = cartCount;
    }
  }
});

// Validate email format
function isValidEmail(email) {
  const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return regex.test(email);
}

// Validate password strength (min 8 chars, at least one uppercase, one lowercase, one digit)
function isStrongPassword(password) {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
  return regex.test(password);
}

// Validate username (only letters, numbers, underscores, 3-15 chars)
function isValidUsername(username) {
  const regex = /^[a-zA-Z0-9_]{3,15}$/;
  return regex.test(username);
}

// Form validation example for registration form
document.addEventListener('DOMContentLoaded', () => {
  const regForm = document.getElementById('registerForm');
  if (regForm) {
    regForm.addEventListener('submit', (e) => {
      const email = regForm.email.value.trim();
      const password = regForm.password.value;
      const confirmPassword = regForm.confirm_password.value;
      const username = regForm.username.value.trim();

      let errors = [];

      if (!isValidEmail(email)) errors.push('Invalid email format.');
      if (!isValidUsername(username)) errors.push('Username must be 3-15 characters: letters, numbers, underscores only.');
      if (!isStrongPassword(password)) errors.push('Password must be at least 8 chars, with uppercase, lowercase, and a digit.');
      if (password !== confirmPassword) errors.push('Passwords do not match.');

      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault(); // prevent form submission
      }
    });
  }
});

// Dynamic UI example: toggle mobile nav menu
document.addEventListener('DOMContentLoaded', () => {
  const menuBtn = document.getElementById('menuToggle');
  const nav = document.querySelector('nav');

  if (menuBtn && nav) {
    menuBtn.addEventListener('click', () => {
      nav.classList.toggle('open');
    });
  }
});
document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
      const email = loginForm.email.value.trim();
      const password = loginForm.password.value;

      let errors = [];

      if (!isValidEmail(email)) errors.push('Please enter a valid email.');
      if (password.length === 0) errors.push('Please enter your password.');

      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault();
      }
    });
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const checkoutForm = document.getElementById('checkoutForm');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', (e) => {
      const name = checkoutForm.name.value.trim();
      const address = checkoutForm.address.value.trim();
      const city = checkoutForm.city.value.trim();
      const zip = checkoutForm.zip.value.trim();
      const phone = checkoutForm.phone.value.trim();

      let errors = [];

      if (name.length < 2) errors.push('Please enter a valid name.');
      if (address.length < 5) errors.push('Please enter a valid address.');
      if (city.length < 2) errors.push('Please enter a valid city.');
      if (!/^\d{5}(-\d{4})?$/.test(zip)) errors.push('Please enter a valid ZIP code.');
      if (!/^\+?\d{7,15}$/.test(phone)) errors.push('Please enter a valid phone number.');

      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault();
      }
    });
  }
});
document.addEventListener('DOMContentLoaded', () => {
  const productForm = document.getElementById('productForm');
  if (productForm) {
    productForm.addEventListener('submit', (e) => {
      const name = productForm.name.value.trim();
      const price = parseFloat(productForm.price.value);
      const image = productForm.querySelector('input[name="image"]');

      let errors = [];

      if (name.length < 3) errors.push('Product name must be at least 3 characters.');
      if (isNaN(price) || price <= 0) errors.push('Please enter a valid price.');

      // Check for image only if it is a new product or image input is not empty
      if (image && image.required && image.files.length === 0) {
        errors.push('Please upload a product image.');
      }

      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault();
      }
    });
  }
});

