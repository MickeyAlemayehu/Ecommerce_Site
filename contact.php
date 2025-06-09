<?php
include 'includes/auth.php';
include 'includes/header.php';

require_login();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=login_required");
    exit;
}
?>
<section class="contact-page">
    <h1>📞 Contact Us</h1>
    <p>Have questions, feedback, or need help? We'd love to hear from you. Reach out to us through the form below or use the contact info provided.</p>

    <div class="contact-container">
      <form class="contact-form" id="form">
         <input type="hidden" name="access_key" value="c209db48-3502-488f-b160-79b5f78e8aff">
        <input type="text" name="name" placeholder="Your Name" placeholder="Your Email" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" rows="5" placeholder="Your Message" required></textarea>
        <button type="submit">Send Message</button>
        <div id="result"></div>
      </form>

      <div class="contact-info">
        <h3>📍 Our Location</h3>
        <p>Addis Ababa, Ethiopia</p>

        <h3>📧 Email</h3>
        <p>bogueofficialhub.com</p>

        <h3>📱 Phone</h3>
        <p>+251 912 345 678</p>

        <h3>🕒 Working Hours</h3>
        <p>Monday - Friday: 9:00 AM - 6:00 PM</p>

        <h3>🌐 Map</h3>
        <iframe src="https://www.google.com/maps?q=addis+ababa+ethiopia&output=embed" width="100%" height="200" style="border:0;" allowfullscreen loading="lazy"></iframe>
      </div>
    </div>
  </section>
  <script>
const form = document.getElementById('form');
const result = document.getElementById('result');

form.addEventListener('submit', function(e) {
    const formData = new FormData(form);
    e.preventDefault();

    const object = Object.fromEntries(formData);
    const json = JSON.stringify(object);

    result.innerHTML = "Please wait..."

    fetch('https://api.web3forms.com/submit', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: json
        })
        .then(async (response) => {
            let json = await response.json();
            if (response.status == 200) {
                result.innerHTML = json.message;
            } else {
                console.log(response);
                result.innerHTML = json.message;
            }
        })
        .catch(error => {
            console.log(error);
            result.innerHTML = "Something went wrong!";
        })
        .then(function() {
            form.reset();
            setTimeout(() => {
                result.style.display = "none";
            }, 3000);
        });
});
</script>
</body>
</html>