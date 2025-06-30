<?php
session_start();
if (isset($_SESSION["success"])) {
    echo "<div id='successContainer'>
            <div class='successBox'>
                <dotlottie-player 
                    id='lottieAnimation'
                    src='https://lottie.host/39e2d42c-1cbc-4ab3-ac71-a28064ff260a/Ff2BIIUOlw.lottie'
                    background='transparent' 
                    speed='1' 
                    style='width: 80px; height: 80px; margin: 0 auto 20px;'
                    loop 
                    autoplay>
                </dotlottie-player>
                <p>".$_SESSION["success"]."</p>
            </div>
          </div>
          <script>
            setTimeout(() => {
                document.getElementById('lottieAnimation').stop(); // Stop Lottie after 1s
            }, 3000);

            setTimeout(() => {
                document.getElementById('successContainer').classList.add('fadeOut'); // Add fade-out effect
                setTimeout(() => {
                    document.getElementById('successContainer').style.display = 'none'; // Hide after fade-out
                }, 3000); // Extra 0.5s for smooth transition
            }, 3000);
          </script>";
    unset($_SESSION["success"]); // Clear session after displaying
}
?>




<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>JMC Music Corner</title>
		<link rel="icon" href="images/favicon.png" type="image/png">
 <link rel="stylesheet" href="styles/tester.css">
        <link rel="stylesheet" href="styles/home.css">
		<link rel="stylesheet" href="styles/nav.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">



        <script src="https://lottie.host/39e2d42c-1cbc-4ab3-ac71-a28064ff260a/Ff2BIIUOlw.lottie"></script>
        <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
        type="module"></script>

        <!-- Font Awesome CDN for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
		<!-- Font Awesome CDN for Icons -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

	



       
		<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="styles/tester.css">
      

        

    </head>
    
<body>
		<?php include("./views/includes/header.php"); ?>


<style>
	
	.favorite-icon {
  position: absolute;
  top: 15px;
  right: 15px;
  font-size: 22px;
  color: #fff;
  cursor: pointer;
  z-index: 10;
  background-color: rgba(0, 0, 0, 0.4);
  padding: 5px;
  border-radius: 50%;
  transition: color 0.3s ease;
}
.favorite-icon.favorited {
  color: red;
}

	.box.product-box {
  position: relative;
}

    .footer {
             background-color: #222;
    color: white;
    padding: 40px 20px;
        }
        .footer h4 {
    color: #2196F3; /* Blue */
    font-weight: 600;
}

/* Ensure rest of the text and links are white */
.footer a,
.footer ul li,
.footer-col li {
    color: white;
    text-decoration: none;
}

.footer a:hover {
    text-decoration: underline;
    color: #90caf9; /* Lighter blue on hover */
}

/* Optional: adjust spacing */
.footer-col {
    margin-bottom: 30px;
}

        .footer-container {
            width: 100%;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

       

        .footer-row:last-child {
            margin-top: 20px; 
        }

       .footer h5 {
    font-size: 14px;
    color: #aaa; /* Optional: a soft gray for footer note */
    font-weight: normal;
    margin-top: 30px;
}

	
	</style>

    
   

    <section class="home section" id="home">
        <div class="container">
            <div class="content">
                <h2>Welcome! to JMC Music Corner</h2>
                <p>Discover the rhythm of life with our premium music collection!</p>
            </div>
        </div>
    </section>

    <section class="slider-container">
        <div class="slider">
            <img class="slide" src="images/sl1.webp" alt="Music Slider 1">
            
            <img class="slide" src="images/sl2.png" alt="Music Slider 2">
            
            <img class="slide" src="images/sl6.png" alt="Music Slider 3">

            <img class="slide" src="images/sl4.jpg" alt="Music Slider 4">
            <img class="slide" src="images/22.png" alt="Music Slider 1">
            <img class="slide" src="images/sl3.png" alt="Music Slider 4">
            
            <img class="slide" src="images/sl7.jpg" alt="Music Slider 4">
            <img class="slide" src="images/25.png" alt="Music Slider 1">
        </div>
        <div class="slider-buttons">
            <button class="circle-btn" onclick="prevSlide()">❮</button>
            <button class="circle-btn" onclick="nextSlide()">❯</button>
        </div>
    </section>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');

        function showSlide(n) {
            slides.forEach(slide => slide.style.display = 'none');
            slides[currentSlide].style.display = 'block';
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        }

        setInterval(nextSlide, 5000); // Change slide every 2 seconds
    </script>

    <section class="products section" id="products">
        <h2 class="heading">New Arrivals</h2>
        <div class="box-container">
           <div class="box product-box" data-product-id="guitar001">
                <img src="images/g4.jpg" alt="Guitar">
				<i class="fas fa-heart favorite-icon" onclick="toggleFavorite(this)"></i>

                <h3>Acoustic Guitar</h3>
                <p><b>High-quality acoustic guitar for music enthusiasts!</b></p>
                <p class="price">250,000 LKR</p>
               <button class="btn btn-primary" onclick="openCartModal('123', 'Acoustic Guitar', '250 000 LKR', 'images/g4.jpg')">
  Add to Cart
</button>

            </div>
           <div class="box product-box" data-product-id="keyboard001">
                <img src="images/k3.png" alt="Keyboard">
				<i class="fas fa-heart favorite-icon" onclick="toggleFavorite(this)"></i>

                <h3>Electronic Keyboard</h3>
                <p><b>Compact and versatile keyboard with amazing sound!</b></p>
                <p class="price">180,000 LKR</p>
                 <button class="btn btn-primary" onclick="openCartModal('123', 'Electronic Keyboard', '180 000 LKR', 'images/k3.png')">
  Add to Cart
</button>

            </div>
            <div class="box product-box" data-product-id="headphone001">
                <img src="images/w1.avif" alt="Headphones">
			<i class="fas fa-heart favorite-icon" onclick="toggleFavorite(this)"></i>

                <h3>Wireless Headphones</h3>
                <p><b>Experience immersive sound with our latest headphones!</b></p>
                <p class="price">8,000 LKR</p>
                <button class="btn btn-primary" onclick="openCartModal('123', 'Wireless Headphones', '8000 LKR', 'images/w1.avif')">
  Add to Cart
</button>

            </div>
        </div>
    </section>




    <section class="services-section">
        <h2 class="heading">Services We Offer</h2>
        
       
      
        <div class="services-container">
      
          
          <!-- Example service boxes -->
          <div class="service-box" style="background-image: url('https://kaizenaire.com/wp-content/uploads/2024/01/Speaker-Repair-Service-Singapore-Get-Your-Speakers-Fixed-Today.jpg');">
            <div class="service-overlay">
              <h3>Instrument Repairing</h3>
              <p>We provide top-notch repairing services for all your musical instruments.</p>
            </div>
          </div>
          <div class="service-box" style="background-image: url('https://www.careersinmusic.com/wp-content/uploads/2014/11/music-store-salesperson.jpg');">
            <div class="service-overlay">
              <h3>Customer Services</h3>
              <p>Friendly and efficient customer service for all your needs.</p>
            </div>
          </div>
          <div class="service-box" style="background-image: url('images/door.jpg');">
            <div class="service-overlay">
              <h3>Door Delivery</h3>
              <p>Fast and reliable delivery service to your doorstep.</p>
            </div>
          </div>
          <div class="service-box" style="background-image: url('https://www.namm.org/sites/default/files/2024-10/introductiontothemusicproductsindustry_namm.jpg');">
            <div class="service-overlay">
              <h3>Import from Foreign</h3>
              <p>High-quality imported instruments delivered to your hands.</p>
            </div>
          </div>
      
        
      </section>


    <section class="about section" id="about">
        <div class="container">
            <h2 class="heading">About Us</h2>
            <div class="content">
                <p><b>JMC Music Corner brings you a curated selection of musical instruments and accessories.</b></p>
                <p><b>Our mission is to inspire creativity and enrich lives through the power of music.</b></p>

                <p>
                    JMC Music Corner is your one-stop destination for all things music. We specialize in offering a wide range of high-quality musical instruments, accessories, and services tailored to meet the needs of musicians at all levels. From budding enthusiasts to seasoned professionals, our curated selection caters to a variety of tastes and genres. 
                  </p>
                  <p>
                    Our mission is to inspire creativity and bring people closer to the universal language of music. Beyond selling instruments, we are deeply passionate about fostering a vibrant musical community. Whether you’re learning your first instrument, repairing a cherished one, or searching for the perfect gear, JMC Music Corner is here to provide expert guidance, reliable support, and a memorable experience that empowers you to achieve your musical dreams.
                  </p>
                  



            </div>
        </div>
    </section>
    <h2 class="heading" >Our Partners</h2>
    <section class="curved-logo-slider">
       
        <!-- Scrolling track for logos -->
        <div class="logo-slider-track">
            <img src="images/l1.webp" alt="Brand 1">
            <img src="images/l2.png" alt="Brand 2">
            <img src="images/l3.webp" alt="Brand 3">
            <img src="images/daraz.png" alt="Brand 3">
            <img src="images/l4.webp" alt="Brand 4">
            <img src="images/l5.webp" alt="Brand 5">
            <img src="images/l6.webp" alt="Brand 6">
            <img src="images/l7.png" alt="Brand 7">
            <img src="images/l8.webp" alt="Brand 8">
            <!-- Clone logos for seamless effect -->
            <img src="images/l9.png" alt="Brand 1 Clone">
            <img src="images/l10.webp" alt="Brand 2 Clone">
            <img src="images/l11.png" alt="Brand 3 Clone">
            <img src="images/l12.png" alt="Brand 4 Clone">
            <img src="images/l13.png" alt="Brand 4 Clone">
            <img src="images/l15.png" alt="Brand 4 Clone">
            <img src="images/l16.png" alt="Brand 4 Clone">
            <img src="images/l17.png" alt="Brand 4 Clone">
            <img src="images/l19.png" alt="Brand 4 Clone">
            <img src="images/l20.png" alt="Brand 4 Clone">


                  
            
        </div>
    </section>
		
		<!-- Floating Chatbot Button -->
<div id="chatToggle" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
  <button class="btn btn-primary">💬 FAQ</button>
</div>

<!-- Chatbot Window -->
<div id="chatWindow" style="display: none; position: fixed; bottom: 80px; right: 20px; width: 300px; height: 400px; background: #fff; border: 1px solid #ccc; border-radius: 10px; z-index: 9999; box-shadow: 0 0 10px rgba(0,0,0,0.2);">
  <div id="chatLog" style="padding: 10px; height: 320px; overflow-y: auto;"></div>
  <div style="padding: 10px;">
    <input type="text" id="chatInput" class="form-control" placeholder="Ask something...">
    <button class="btn btn-success mt-2" onclick="sendChat()">Send</button>
  </div>
</div>

<script>
document.getElementById("chatToggle").onclick = () => {
  const chat = document.getElementById("chatWindow");
  chat.style.display = (chat.style.display === "none") ? "block" : "none";
};

// SEND MESSAGE FUNCTION
function sendChat() {
  const input = document.getElementById("chatInput");
  const message = input.value.trim();
  if (!message) return;

  const chatLog = document.getElementById("chatLog");

  // User bubble
  chatLog.innerHTML += `
    <div style="text-align: right; margin-bottom: 10px;">
      <div style="
        display: inline-block;
        background-color: #d4edda;
        color: #155724;
        padding: 8px 12px;
        border-radius: 15px 15px 0 15px;
        max-width: 80%;
      ">${message}</div>
    </div>
  `;

  fetch("chatbot.php", {
    method: "POST",
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: "message=" + encodeURIComponent(message)
  })
  .then(res => res.text())
  .then(reply => {
    if (reply.startsWith("REDIRECT:")) {
      const url = reply.replace("REDIRECT:", "").trim();
      window.location.href = url;
    } else {
      // Bot bubble
      chatLog.innerHTML += `
        <div style="text-align: left; margin-bottom: 10px;">
          <div style="
            display: inline-block;
            background-color: #e7f3fe;
            color: #0c5460;
            padding: 8px 12px;
            border-radius: 15px 15px 15px 0;
            max-width: 80%;
          ">${reply}</div>
        </div>
      `;
      chatLog.scrollTop = chatLog.scrollHeight;
    }
    input.value = "";
  });
}

// 🔹 Enter key listener
document.getElementById("chatInput").addEventListener("keydown", function(e) {
  if (e.key === "Enter") {
    e.preventDefault();
    sendChat();
  }
});

// 🔹 Click outside to close chatbot
document.addEventListener("click", function(event) {
  const chat = document.getElementById("chatWindow");
  const toggle = document.getElementById("chatToggle");
  if (
    chat.style.display === "block" &&
    !chat.contains(event.target) &&
    !toggle.contains(event.target)
  ) {
    chat.style.display = "none";
  }
});
</script>



    <script>
        
 // Highlight middle logo effect
        function highlightMiddleLogo() {
            const middleLogoIndex = Math.floor(logos.length / 2);
            logos.forEach((logo, index) => {
                logo.classList.toggle('highlight-middle', index === middleLogoIndex);
            });
        }

        // Infinite looping animation
        function autoScroll() {
            if (offset <= -trackWidth / 2) {
                offset = 0; // Reset to start when end is reached
            }
            offset -= 2; // Gradual movement for smooth scrolling
            track.style.transform = `translateX(${offset}px)`;

            // Periodically check for middle logo to highlight
            if (Math.abs(offset) % 200 === 0) highlightMiddleLogo();

            requestAnimationFrame(autoScroll); // Continuously call autoScroll
        }

        autoScroll(); // Start auto-scrolling
    </script>
   

    <section class="contact section" id="contact">
        <div class="container">
            <h2 class="heading">Get in Touch</h2>
            <div class="t-container">
                <div class="t">
                    <h3>Contact Info</h3>
                    <p><i class="fas fa-phone"></i> +94 (72) 2559277</p>
                    <p><i class="fas fa-envelope"></i> info@jmcmusiccorner.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> 456 Harmony Road, Batticaloa, Sri Lanka</p>
                </div>
                <div class="t">
                    <h3>Opening Hours</h3>
                    <p><span>Monday - Friday:</span> 9:00 AM - 8:00 PM</p>
                    <p><span>Saturday - Sunday:</span> 10:00 AM - 9:00 PM</p>
                </div>
              <!-- Newsletter Section -->
<div class="t">
  <h3>Newsletter</h3>
  <p>Subscribe for the latest updates</p>

  <form id="newsletterForm">
    <input type="email" name="email" placeholder="Your email" class="email" required>
    <button type="submit">Subscribe</button>
  </form>
</div>

<!-- Thank You Popup -->
<div id="thankYouPopup" style="
  display: none;
  position: fixed;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  background: white;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0,0,0,0.3);
  z-index: 9999;
  text-align: center;
">
  <h4>🎉 Thank you for subscribing!</h4>
</div>

            </div>
        </div>
    </section>

   

<!-- Cart Modal -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content p-3">
      <div class="modal-header">
        <h5 class="modal-title" id="cartModalLabel">Add to Cart</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <img id="modalProductImage" src="" class="img-fluid" style="max-height: 150px;" />
        <h5 id="modalProductName" class="mt-3"></h5>
        <p>Price: <span id="modalProductPrice"></span> LKR</p>
        <form id="addToCartForm">
          <input type="hidden" name="product_id" id="modalProductId">
			<input type="hidden" name="image" id="modalImage">

          <input type="hidden" name="name" id="modalName">
          <input type="hidden" name="price" id="modalPrice">
          <label>Quantity:</label>
          <input type="number" name="quantity" id="modalQuantity" value="1" min="1" class="form-control w-50 mx-auto mb-3" />
          <button type="submit" class="btn btn-success">Confirm Add to Cart</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById("newsletterForm").addEventListener("submit", function(e) {
  e.preventDefault(); // Prevent page reload

  const form = e.target;
  const formData = new FormData(form);

  fetch("https://formspree.io/f/meokrlqv", {
    method: "POST",
    body: formData,
    headers: { 'Accept': 'application/json' }
  })
  .then(response => {
    if (response.ok) {
      // Show thank you popup
      document.getElementById("thankYouPopup").style.display = "block";
      form.reset(); // Clear email input

      // Auto-close popup after 3 seconds
      setTimeout(() => {
        document.getElementById("thankYouPopup").style.display = "none";
      }, 3000);
    } else {
      alert("Something went wrong. Please try again.");
    }
  });
});
</script>

    
    <script>
    const toggleBtn = document.getElementById("modeToggle");
    const body = document.body;

    toggleBtn.addEventListener("click", () => {
        body.classList.toggle("dark-mode");

        // Toggle icon between moon/sun
        const icon = toggleBtn.querySelector("i");
        if (body.classList.contains("dark-mode")) {
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");
        } else {
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
        }

        // Optional: save preference
        localStorage.setItem("theme", body.classList.contains("dark-mode") ? "dark" : "light");
    });

    // Load saved preference
    if (localStorage.getItem("theme") === "dark") {
        body.classList.add("dark-mode");
        toggleBtn.querySelector("i").classList.replace("fa-sun", "fa-moon");
    }
</script>
		
		   <!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	  
	  <script>
  function openCartModal(id, name, price, image) {
  document.getElementById('modalProductImage').src = image;
  document.getElementById('modalProductName').innerText = name;
  document.getElementById('modalProductPrice').innerText = price;
  document.getElementById('modalProductId').value = id;
  document.getElementById('modalName').value = name;
  document.getElementById('modalPrice').value = price;
  document.getElementById('modalImage').value = image; // ✅ ADD THIS

  new bootstrap.Modal(document.getElementById('cartModal')).show();
}


  document.getElementById('addToCartForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('cart.php', {
  method: 'POST',
  body: formData
})

      .then(res => res.text())
      .then(data => {
        alert("Item added to cart!");
        location.reload(); // Optional: Refresh to show updates
      })
      .catch(err => {
        console.error(err);
        alert("Failed to add item.");
      });
  });
</script>
		
		<script>
		
			function toggleFavorite(icon) {
  const productBox = icon.closest('.product-box');
  const productId = productBox.getAttribute('data-product-id');

  icon.classList.toggle('favorited');

  // Replace the icon toggle to only switch between solid and regular heart
  if (icon.classList.contains('favorited')) {
    icon.classList.remove('fa-regular');
    icon.classList.add('fa-solid');
  } else {
    icon.classList.remove('fa-solid');
    icon.classList.add('fa-regular');
  }

  let favorites = JSON.parse(localStorage.getItem('favorites') || '{}');
  favorites[productId] = icon.classList.contains('favorited');
  localStorage.setItem('favorites', JSON.stringify(favorites));
}

		</script>
		
		<script>
  // On page load: restore favorite states from localStorage
  document.addEventListener("DOMContentLoaded", function () {
    let favorites = JSON.parse(localStorage.getItem('favorites') || '{}');

    document.querySelectorAll('.product-box').forEach(box => {
      const productId = box.getAttribute('data-product-id');
      const icon = box.querySelector('.favorite-icon');

      if (favorites[productId]) {
        icon.classList.add('favorited');
        icon.classList.remove('fa-regular');
        icon.classList.add('fa-solid');
      } else {
        icon.classList.remove('favorited');
        icon.classList.remove('fa-solid');
        icon.classList.add('fa-regular');
      }
    });
  });
</script>
 
       <footer class="footer">
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-col">
                    <h4><i class="fas fa-user-circle"></i> My Account</h4>
                    <ul>
                        <li><a href="account.php?type=acc"><i class="fas fa-cogs"></i> Edit Account</a></li>
                        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> View Cart</a></li>
                        <li><a href="account.php?type=addr"><i class="fas fa-map-marker-alt"></i> Edit Address</a></li>
                        <li><a href="account.php"><i class="fas fa-box"></i> Track Order</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><i class="fas fa-link"></i> Quick Links</h4>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="private_policy.php"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                        <li><a href="products.php"><i class="fas fa-guitar"></i> Products</a></li>
                        <li><a href="index.php#contact"><i class="fas fa-phone-alt"></i> Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><i class="fas fa-share-alt"></i> Follow Us</h4>
                    <div class="social-links">
                        <li><a href="https://github.com/shaliniarchana"><i class="fab fa-github"></i> GitHub</a></li>
                        <li><a href="https://telegram.org/"><i class="fab fa-telegram"></i> Telegram</a></li>
                        <li><a href="https://facebook.com"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="https://instagram.com"><i class="fab fa-instagram"></i> Instagram</a></li>
                    </div>
                </div>
				
				 <div class="footer-col">
                    <h4><i class="fas fa-info-circle"></i> Company Info</h4>
                    <ul>
                        <li><a href="aboutus.html"><i class="fas fa-building"></i> About Us</a></li>
                        <li><a href="careers.html"><i class="fas fa-briefcase"></i> Careers</a></li>
                        <li><a href="terms_of_service.html"><i class="fas fa-file-alt"></i> Terms of Service</a></li>
                        <li><a href="faq.html"><i class="fas fa-question-circle"></i> FAQ</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><i class="fas fa-clock"></i> Opening Hours</h4>
                    <ul>
                        <li><span>Mon - Fri:</span> 9:00 AM - 8:00 PM</li>
                        <li><span>Sat - Sun:</span> 10:00 AM - 9:00 PM</li>
                    </ul>
                </div>
            </div>
            
            
            <h5 align="center">
                Copyright © 2025 JMC Music Corner. All Rights Reserved.
            </h5>
        </div>
    </footer>
    


</body>
</html>
