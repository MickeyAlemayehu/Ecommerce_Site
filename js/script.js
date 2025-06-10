document.addEventListener("DOMContentLoaded", () => {
  const cartIcon = document.querySelector('.cart-icon');
  const cartCountElement = document.querySelector('.cart-count');
  const shopNowButtons = document.querySelectorAll(".product button");

  shopNowButtons.forEach(button => {
    button.addEventListener("click", async () => {
      const productDiv = button.closest(".product");
      const name = productDiv.querySelector("h3").textContent;
      const price = parseFloat(productDiv.dataset.price);
      const image = productDiv.querySelector("img").src;

      try {
        const response = await fetch('add_to_cart.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `name=${encodeURIComponent(name)}&price=${price}&image=${encodeURIComponent(image)}`
        });

        const data = await response.json();
        if (data.success) {
          if (cartCountElement) {
            cartCountElement.textContent = data.cart_count;
          }
          alert("🛒 Item added to cart!");
        } else {
          alert("Failed to add item to cart");
        }
      } catch (error) {
        console.error('Error:', error);
        alert("Error adding item to cart");
      }
    });
  });

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function isStrongPassword(password) {
    return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/.test(password);
  }

  function isValidUsername(username) {
    return /^[a-zA-Z0-9_]{3,15}$/.test(username);
  }

  const regForm = document.getElementById('registerForm');
  if (regForm) {
    regForm.addEventListener('submit', (e) => {
      const email = regForm.email.value.trim();
      const password = regForm.password.value;
      const confirmPassword = regForm.confirm_password.value;
      const username = regForm.username.value.trim();
      const errors = [];

      if (!isValidEmail(email)) errors.push('Invalid email format.');
      if (!isValidUsername(username)) errors.push('Username must be 3-15 characters: letters, numbers, underscores only.');
      if (!isStrongPassword(password)) errors.push('Password must be at least 8 chars, with uppercase, lowercase, and a digit.');
      if (password !== confirmPassword) errors.push('Passwords do not match.');

      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault();
      }
    });
  }

  const loginForm = document.getElementById('loginForm');
  if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
      const email = loginForm.email.value.trim();
      const password = loginForm.password.value;
      const errors = [];

      if (!isValidEmail(email)) errors.push('Please enter a valid email.');
      if (password.length === 0) errors.push('Please enter your password.');

      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault();
      }
    });
  }

  const checkoutForm = document.getElementById('checkoutForm');
  if (checkoutForm) {
    checkoutForm.addEventListener('submit', (e) => {
      const name = checkoutForm.name.value.trim();
      const email = checkoutForm.email.value.trim();
      const address = checkoutForm.address.value.trim();
      const cardNumber = checkoutForm.card_number.value.trim();
      const cvv = checkoutForm.cvv.value.trim();
      const expiry = checkoutForm.expiry.value.trim();

      const nameRegex = /^[a-zA-Z\s]{2,50}$/;
      const cardRegex = /^\d{16}$/;
      const cvvRegex = /^\d{3}$/;
      const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;

      const errors = [];

      if (!nameRegex.test(name)) errors.push("Name must be 2–50 letters and spaces only.");
      if (!isValidEmail(email)) errors.push('Please enter a valid email.');
      if (address.length < 5) errors.push("Address must be at least 5 characters.");
      if (!cardRegex.test(cardNumber)) errors.push("Card number must be 16 digits.");
      if (!cvvRegex.test(cvv)) errors.push("CVV must be 3 digits.");
      if (!expiryRegex.test(expiry)) {
        errors.push("Expiry must be in MM/YY format.");
      } else {
        const [month, year] = expiry.split('/');
        const expiryDate = new Date(`20${year}`, month);
        const now = new Date();
        now.setDate(1);
        if (expiryDate < now) errors.push("Card expiry must be in the future.");
      }

      if (errors.length > 0) {
        alert(errors.join('\n'));
        e.preventDefault();
      }
    });
  }
});