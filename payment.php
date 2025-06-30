<?php
session_start();
include 'config.php';

// Get total price from form
if (isset($_POST['checkout'])) {
    $_SESSION['total_price'] = $_POST['total_price'];
} else {
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - JMC Music Corner</title>
		<link rel="icon" href="images/favicon.png" type="image/png">

    <link rel="stylesheet" href="payment.css">
    <link rel="stylesheet" href="home.css">
   
    
</head>
<body>
    <style>

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


    <!-- Header Section -->
    <header>
        <div class="logo">
            <img src="images/JOY.gif" alt="Joy Music Corner Logo" class="img-responsive">
        </div>
        <nav class="navbar">
            <a href="homepage.html"><i class="fas fa-home"></i> Home</a>
            <a href="login.html"><i class="fas fa-user"></i> Login</a>
            <a href="products.html"><i class="fas fa-guitar"></i> Products</a>
            <a href="aboutus.html"><i class="fas fa-info-circle"></i> About Us</a>
            <a href="contactus.html"><i class="fas fa-phone-alt"></i> Contact Us</a>
            <a href="cart.php"><i class="fas fa-shopping-cart"></i></a>
        </nav>
    </header>


    <!-- Payment Section -->
    <main>
    <h2>Payment Method</h2>
    <p>Total Price: RS. <?php echo $_SESSION['total_price']; ?></p>

    <form method="post" action="process_payment.php">
        <label>Credit / Debit Card</label>
        <input type="text" name="card_number" placeholder="XXXX-XXXX-XXXX-XXXX" required>
        <label>Expiry Date</label>
        <input type="text" name="expiry_date" placeholder="MM/YY" required>
        <label>CVC/CVV</label>
        <input type="text" name="cvv" placeholder="123" required>
        <label>Name on Card</label>
        <input type="text" name="card_name" required>
        <button type="submit" name="submit_payment">Submit Payment</button>
    </form>
</main>



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