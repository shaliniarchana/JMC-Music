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
    unset($_SESSION["success"]); 
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>About - JMC Music Corner</title>
        <link rel="icon" href="images/favicon.png" type="image/png">

         <link rel="stylesheet" href="styles/tester.css">
        <link rel="stylesheet" href="styles/home.css">
		<link rel="stylesheet" href="styles/nav.css">
      
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <script defer src="script.js"></script>
    </head>
    
<body>
		<?php include("./views/includes/header.php") ?>
    

    <style>
     

        .container {
            width: 90%;
            margin: 0 auto;
            
        }

        header {
            text-align: center;
            padding: 20px;
        }

        header h1 {
            font-size: 2.5em;
            color: #333;
        }

     
        .story-section {
            background-color:rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .story-section img {
            width: 100%;
            max-width: 600px;
            border-radius: 10px;
        }

        .story-section p {
            display: none;
        }

        .story-section button {
            padding: 10px 20px;
            background-color: #4c56af;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .story-section button:hover {
            background-color: #4566a0;
        }

        /* Team Section */
        .team-section {
            margin: 30px 0;
            text-align: center;
        }

        .team-section h2 {
            margin-bottom: 20px;
        }

        .team-cards {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .team-card {
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            padding: 20px;
            width: 30%;
            margin-bottom: 20px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .team-card img {
            width: 100%;
            max-width: 150px;
            border-radius: 50%;
        }

        .team-card h3 {
            margin-top: 10px;
            font-size: 1.2em;
        }

        .team-card p {
            margin-top: 10px;
        }

        /* Certificate Section */
        .certificate-section {
    text-align: center;
    background-color: rgba(255, 255, 255, 0.5);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    width: 60%;
    margin: 20px auto;
    position: relative;
}

.certificate {
    position: relative;
    border: 2px solid #ccc;
    padding: 20px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.9);
}

    

        .certificate-icon {
    background-image: url('https://psb-planningcanada.ca/wp-content/uploads/2019/01/accredited_92386558-stock-illustration-accredited-gold-certificate.jpg'); 
    background-size: cover;
    background-repeat: no-repeat;
    width: 60px;
    height: 60px;
    margin: 0 auto 20px auto;
}

.signature {
    margin-top: 20px;
    font-family: 'Cursive', sans-serif;
}

.signature #signature-img {
    display: inline-block;
    width: 200px;
    height: 50px;
    background-image: url('https://t3.ftcdn.net/jpg/09/25/47/06/360_F_925470660_LzHJcqlf8CDG9v3ZUCbAj4WwjLETFq2L.jpg'); /* Example signature URL */
    background-size: contain;
    background-repeat: no-repeat;
    margin: 10px auto;
}

        /* Awards Section */
        .awards-section {
            text-align: center;
            margin: 30px 0;
        }

        .awards-section h2 {
            margin-bottom: 20px;
        }

        .award-cards {
          
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .award-card {
            width: 45%;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.5);
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }

        .award-card img {
            width: 100%;
            max-width: 200px;
            margin-bottom: 10px;
        }

        .award-card p {
            font-size: 0.9em;
        }

        .icons {
    display: flex;
    justify-content: space-around;
    margin-top: 40px;
}

.icon {
    text-align: center;
    font-size: 60px; 
    color: #ff5722; 
    transition: transform 0.3s, color 0.3s; 
}

.icon:hover {
    transform: scale(1.2); 
    color: #d32f2f; 
}


.icons .icon:not(:last-child) {
    margin-right: 20px;
}


.icon::after {
    content: '';
    display: block;
    margin-top: 8px;
    width: 48px;
    height: 4px;
    background: #ff5722; 
    opacity: 0;
    transition: opacity 0.3s;
}

.icon:hover::after {
    opacity: 1; 
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
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const readMoreButton = document.querySelector(".story-section button");
            const hiddenContent = document.querySelector(".story-section p");

            readMoreButton.addEventListener("click", () => {
                if (hiddenContent.style.display === "none") {
                    hiddenContent.style.display = "block";
                    readMoreButton.innerText = "Read Less";
                } else {
                    hiddenContent.style.display = "none";
                    readMoreButton.innerText = "Read Me";
                }
            });
        });
    </script>
</head>
<body>

<br>
<br>

<div class="container">
    
    <section class="story-section">
        <h2>Our Story</h2><br>
        <img src="images/mama.jpg" alt="Company Image" >
        <p style="display: none;">Starting from a small corner store in 1990, JMC Music Corner has grown into a global destination for music enthusiasts. Our journey reflects passion, determination, and dedication to delivering high-quality musical instruments and customer satisfaction.
            JMC Music Corner is your one-stop destination for all things music. We specialize in offering a wide range of high-quality musical instruments, accessories, and services tailored to meet the needs of musicians at all levels. From budding enthusiasts to seasoned professionals, our curated selection caters to a variety of tastes and genres. 
          
            Our mission is to inspire creativity and bring people closer to the universal language of music. Beyond selling instruments, we are deeply passionate about fostering a vibrant musical community. Whether you’re learning your first instrument, repairing a cherished one, or searching for the perfect gear, JMC Music Corner is here to provide expert guidance, reliable support, and a memorable experience that empowers you to achieve your musical dreams.
         <br>
         At JMC Music Corner, we believe in the power of music to bring people together and transform lives. Founded in [year], we started as a humble initiative to offer high-quality musical instruments to aspiring musicians in our community. Over the years, we’ve grown into a trusted name in the music industry, catering to professionals, hobbyists, and music enthusiasts worldwide. From classic instruments to the latest technological innovations, we’ve built a diverse catalog to meet every musical need.
        <br>
        At our core, JMC Music Corner is driven by passion, creativity, and a commitment to excellence. We pride ourselves on providing exceptional customer service, premium products, and a platform for individuals to discover the joy of music. Whether you're just starting your musical journey or looking to enhance your professional setup, our mission is to empower your creativity and inspire greatness.
        JMC Music Corner isn’t just a store—it’s a community. Our regular workshops, concerts, and collaborations bring musicians and enthusiasts together to learn, share, and celebrate the universal language of music. We actively support local talent and initiatives to promote music education, believing it to be a critical avenue for self-expression and emotional well-being.
    
    
    
    
    </p>
          <br>
        <br>
        
        <button>Read Me</button>
    </section>

  
    <section class="team-section">
        <h2>Meet Our Team</h2>
        <div class="team-cards">
            <div class="team-card">
                <img src="images/M.T.V (2).png" alt="CEO">
                <h3>MR.WARUNA</h3>
                <h4>CEO</h4>
                <p>Abilash has been the guiding force behind our vision, with expertise in both leadership and innovation.</p>
            </div>
            <div class="team-card">
                <img src="images/foun.png" alt="Founder">
                <h3>MR.ABHIMAN</h3>
                <h4>Founder</h4>
                <p>Krishna's relentless pursuit of excellence built Joy Music Corner into what it is today.</p>
            </div>
            <div class="team-card">
                <img src="images/M.T.V (3).png" alt="Executive Manager">
                <h3>MR.AKILA</h3>
                <h4>Executive Manager</h4>
                <p>Thiloshan ensures seamless operations with a keen focus on customer service and quality assurance.</p>
            </div>
        </div>
    </section>
<script>
    document.addEventListener("DOMContentLoaded", () => {
    
    const iconContainer = document.getElementById("certificate-icon");
    const approvedSymbol = document.createElement("span");
    approvedSymbol.innerHTML = "&#128275;"; 
    approvedSymbol.style.fontSize = "30px";
    approvedSymbol.style.color = "green";
    approvedSymbol.style.position = "absolute";
    approvedSymbol.style.top = "-20px";
    approvedSymbol.style.right = "20px";
    iconContainer.appendChild(approvedSymbol);

    
    const signatureContainer = document.getElementById("signature-img");
    signatureContainer.style.borderBottom = "2px solid black";
    signatureContainer.style.width = "150px";
    signatureContainer.style.margin = "10px auto";
});

</script>



<script>
    const toggleBtn = document.getElementById("modeToggle");
    const body = document.body;

    toggleBtn.addEventListener("click", () => {
        body.classList.toggle("dark-mode");

        
        const icon = toggleBtn.querySelector("i");
        if (body.classList.contains("dark-mode")) {
            icon.classList.remove("fa-sun");
            icon.classList.add("fa-moon");
        } else {
            icon.classList.remove("fa-moon");
            icon.classList.add("fa-sun");
        }

       
        localStorage.setItem("theme", body.classList.contains("dark-mode") ? "dark" : "light");
    });

   
    if (localStorage.getItem("theme") === "dark") {
        body.classList.add("dark-mode");
        toggleBtn.querySelector("i").classList.replace("fa-sun", "fa-moon");
    }
</script>
		
<section class="certificate-section">
        <h2>Our Certification</h2><br>
    
            <div class="certificate">
                <div id="certificate-icon" class="certificate-icon"></div>
                <h2>Company Registration Certificate</h2>
                <p>Registration Number: 200330912421</p>
                <p>Registered Office: JOY MUSIC CORNER , BATTICALOA</p>
                <p>Date of Registration: Jan 15, 1990</p>
                <p><b>License Approved by Global Music Authority</b></p>
                <div id="signature" class="signature">
                    <p>Approved by:</p>
                    <span id="signature-img"></span>
                </div>
            </div>
        </section>
        
    </section>

    
    <section class="awards-section">
        <h2>Awards & Achievements</h2>
        <div class="award-cards">
            <div class="award-card">
                <img src="images/mm.png" alt="Award 2021">
                <h3>Best Music Store 2021</h3>
                <p>Awarded for outstanding customer satisfaction and innovative products.</p>
            </div>
            <div class="award-card">
                <img src="images/yy.png" alt="Award 2022">
                <h3>Excellence in Quality 2022</h3>
                <p>Honored for exceptional commitment to quality in musical instruments.</p>
            </div>
            <div class="award-card">
              <img src="images/uu.png" alt="Award 2023">
              <h3>Innovation in Music Retail 2023</h3>
            <p>Praised for creative store design and interactive shopping experiences.</p>
          </div>
          <div class="award-card">
            <img src="images/M.T.V (4).png" alt="Award 2024">
            <h3>Global Excellence in Music Industry 2024</h3>
            <p>Awarded for exemplary contributions to global music markets and emerging talent support.</p>
        </div>
        </div>
        <section>
            <h2>Enhance Your Experience</h2>
            <div class="icons">
                <div class="icon">&#127911;</div>
                <div class="icon">&#127926;</div>
                <div class="icon">&#128187;</div>
            </div>
        </section>
    </section>
</div>
	
<div id="chatToggle" style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
  <button class="btn btn-primary">💬 FAQ</button>
</div>


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


function sendChat() {
  const input = document.getElementById("chatInput");
  const message = input.value.trim();
  if (!message) return;

  const chatLog = document.getElementById("chatLog");

  
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


document.getElementById("chatInput").addEventListener("keydown", function(e) {
  if (e.key === "Enter") {
    e.preventDefault();
    sendChat();
  }
});


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




  <footer class="footer">
        <div class="footer-container">
            <div class="footer-row">
                <div class="footer-col">
                    <h4><i class="fas fa-user-circle"></i> My Account</h4>
                    <ul>
                        <li><a href="login.php"><i class="fas fa-cogs"></i> Edit Account</a></li>
                        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> View Cart</a></li>
                        <li><a href="cart.php"><i class="fas fa-map-marker-alt"></i> Edit Address</a></li>
                        <li><a href="payment_history.php"><i class="fas fa-box"></i> Track Order</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4><i class="fas fa-link"></i> Quick Links</h4>
                    <ul>
                        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="Aboutus.php"><i class="fas fa-shield-alt"></i> Privacy Policy</a></li>
                        <li><a href="products.php"><i class="fas fa-guitar"></i> Products</a></li>
                        <li><a href="ContactUs.php"><i class="fas fa-phone-alt"></i> Contact</a></li>
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
                        <li><a href="Aboutus.php"><i class="fas fa-building"></i> About Us</a></li>
                        <li><a href="ContactUs.php"><i class="fas fa-briefcase"></i> Careers</a></li>
                        <li><a href="Aboutus.php"><i class="fas fa-file-alt"></i> Terms of Service</a></li>
         
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


