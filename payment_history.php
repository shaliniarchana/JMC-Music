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
                <p>" . htmlspecialchars($_SESSION["success"]) . "</p>
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
                }, 1000); // 1s for smooth transition
            }, 3000);
          </script>";
    unset($_SESSION["success"]); // Clear session after displaying
}

$conn = new mysqli('localhost', 'root', '', 'joy_music');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in.");
}

// Fetch orders with their items by status
function fetchOrdersByStatus($conn, $user_id, $status) {
    $stmt = $conn->prepare("
        SELECT o.id, o.order_number, o.payment_method, o.status, o.estimated_delivery_date,
               o.name, o.email, o.phone1, o.phone2, o.address,
               oi.product_name, oi.product_image, oi.price, oi.quantity, oi.subtotal
        FROM orders o 
        JOIN order_items oi ON o.id = oi.order_id
        WHERE o.user_id = ? AND o.status = ?
        ORDER BY o.id DESC
    ");
    $stmt->bind_param("is", $user_id, $status);
    $stmt->execute();
    return $stmt->get_result();
}


$processing_orders = fetchOrdersByStatus($conn, $user_id, 'processing');
$on_the_way_orders = fetchOrdersByStatus($conn, $user_id, 'on_the_way');
$delivered_orders = fetchOrdersByStatus($conn, $user_id, 'delivered');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment History - JMC Music Corner</title>
		<link rel="icon" href="images/favicon.png" type="image/png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles/tester.css" />
  <link rel="stylesheet" href="styles/home.css" />
  <link rel="stylesheet" href="styles/nav.css" />
  <!-- Font Awesome CDN for Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <style>
    #successContainer {
      transition: opacity 1s ease;
    }
    #successContainer.fadeOut {
      opacity: 0;
    }
    .order-status-bar {
      height: 10px;
      border-radius: 5px;
      margin-bottom: 10px;
      background: #e0e0e0;
      overflow: hidden;
    }
    .order-status-progress {
      height: 100%;
      transition: width 0.5s ease;
    }
    .card-title {
      font-weight: bold;
      color: #032F40;
    }
    .track-btn {
      float: right;
    }
	  .payment-text
	  {
		  color: #000000;
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
</head>
<body>
  <?php include("./views/includes/header.php") ?>

  <div class="container py-5">
    <h2 class="text-center payment-text"><b>📦 My Payment History</b></h2>

    <ul class="nav nav-tabs" id="orderStatusTabs" role="tablist">
      <li class="nav-item">
        <button
          class="nav-link active"
          id="processing-tab"
          data-bs-toggle="tab"
          data-bs-target="#processing"
          type="button"
          role="tab"
          aria-controls="processing"
          aria-selected="true" style="color:#000000"
        >
          Processing
        </button>
      </li>
      <li class="nav-item">
        <button
          class="nav-link"
          id="on-the-way-tab"
          data-bs-toggle="tab"
          data-bs-target="#on-the-way"
          type="button"
          role="tab"
          aria-controls="on-the-way"
          aria-selected="false" style="color:#000000"
        >
          On The Way
        </button>
      </li>
      <li class="nav-item">
        <button
          class="nav-link"
          id="delivered-tab"
          data-bs-toggle="tab"
          data-bs-target="#delivered"
          type="button"
          role="tab"
          aria-controls="delivered"
          aria-selected="false" style="color:#000000"
        >
          Delivered
        </button>
      </li>
    </ul>

    <div class="tab-content mt-3" id="orderStatusTabsContent">
      <div class="tab-pane fade show active" id="processing" role="tabpanel" aria-labelledby="processing-tab">
        <?php displayOrders($processing_orders, 'processing'); ?>
      </div>

      <div class="tab-pane fade" id="on-the-way" role="tabpanel" aria-labelledby="on-the-way-tab">
        <?php displayOrders($on_the_way_orders, 'on_the_way'); ?>
      </div>

      <div class="tab-pane fade" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
        <?php displayOrders($delivered_orders, 'delivered'); ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.track-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const orderNumber = btn.dataset.ordernumber;
        // Redirect to a real tracking page - customize URL as needed
        window.location.href = 'track_order.php?order=' + encodeURIComponent(orderNumber);
      });
    });
  </script>

  <script>
    const toggleBtn = document.getElementById("modeToggle");
    const body = document.body;

    toggleBtn?.addEventListener("click", () => {
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
      toggleBtn?.querySelector("i").classList.replace("fa-sun", "fa-moon");
    }
  </script>

<?php
function displayOrders($result, $status) {
    if ($result->num_rows === 0) {
        echo "<p class='text-center text-muted mt-4'>No " . htmlspecialchars(ucfirst(str_replace('_', ' ', $status))) . " orders found.</p>";
        return;
    }

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orderNum = $row['order_number'];
     if (!isset($orders[$orderNum])) {
    $orders[$orderNum] = [
        'name' => $row['name'],
        'email' => $row['email'],
        'phone1' => $row['phone1'],
        'phone2' => $row['phone2'],
        'address' => $row['address'],
        'payment_method' => $row['payment_method'],
        'status' => $row['status'],
        'estimated_delivery_date' => $row['estimated_delivery_date'],
        'items' => [],
    ];
}
$orders[$orderNum]['items'][] = $row;

    }

    foreach ($orders as $order_number => $details) {
        $total = 0;
        echo "<div class='card mb-4 shadow-sm'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>Order #: " . htmlspecialchars($order_number) . "</h5>";

        // Progress Bar
        $progressPercent = 0;
        $barColor = '';
        switch ($details['status']) {
            case 'processing': $progressPercent = 33; $barColor = '#ffc107'; break;
            case 'on_the_way': $progressPercent = 66; $barColor = '#0d6efd'; break;
            case 'delivered': $progressPercent = 100; $barColor = '#198754'; break;
        }
        echo '<div class="order-status-bar mb-2">';
        echo "<div class='order-status-progress' style='width: {$progressPercent}%; background-color: {$barColor};'></div>";
        echo '</div>';
      echo "<p><strong>Name:</strong> " . htmlspecialchars($details['name'] ?? '') . "</p>";
echo "<p><strong>Email:</strong> " . htmlspecialchars($details['email'] ?? '') . "</p>";
echo "<p><strong>Phone 1:</strong> " . htmlspecialchars($details['phone1'] ?? '') . "</p>";
echo "<p><strong>Phone 2:</strong> " . htmlspecialchars($details['phone2'] ?? '') . "</p>";
echo "<p><strong>Address:</strong> " . htmlspecialchars($details['address'] ?? '') . "</p>";


        echo "<p><strong>Payment Method:</strong> " . htmlspecialchars(ucfirst($details['payment_method'])) . "</p>";
        echo "<p><strong>Estimated Delivery:</strong> " . htmlspecialchars($details['estimated_delivery_date']) . "</p>";

        echo "<div class='table-responsive'>";
        echo "<table class='table table-bordered mt-3'>";
        echo "<thead class='table-light'><tr><th>Image</th><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead><tbody>";
        foreach ($details['items'] as $item) {
            $total += $item['subtotal'];
            echo "<tr>";
            echo "<td><img src='" . htmlspecialchars($item['product_image']) . "' style='height:50px;' alt='" . htmlspecialchars($item['product_name']) . "'></td>";
            echo "<td>" . htmlspecialchars($item['product_name']) . "</td>";
            echo "<td>" . number_format($item['price']) . " LKR</td>";
            echo "<td>" . (int)$item['quantity'] . "</td>";
            echo "<td>" . number_format($item['subtotal']) . " LKR</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "</div>";

        echo "<h6 class='text-end mt-3'>Total: <strong>" . number_format($total) . " LKR</strong></h6>";

      
        echo "</div></div>";
    }
}
?>

	
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
