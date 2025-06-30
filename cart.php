<?php
session_start();
$form_error = ''; // global error message
$show_payment_modal = false; // control modal display
$input = [
    'name' => '',
    'email' => '',
    'phone1' => '',
    'phone2' => '',
    'address' => '',
    'payment_method' => '',
    'card_number' => '',
    'card_expiry' => '',
    'card_cvv' => '',
    'card_pin' => ''
];
$invalid_fields = []; // for tracking invalid input fields


// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or show message
    die("Please login to view your cart.");
}

$user_id = $_SESSION['user_id'] ?? null;

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
                }, 3000); // Extra 0.5s for smooth transition
            }, 3000);
          </script>";
    unset($_SESSION["success"]); // Clear session after displaying
}
?>

<?php
$conn = new mysqli('localhost', 'root', '', 'joy_music');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert item (from modal form directly)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $price = floatval(preg_replace("/[^\d.]/", "", $_POST['price']));
    $quantity = (int)$_POST['quantity'];
    $total = $price * $quantity;
    $image = $_POST['image'];

    // Insert with user_id
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, name, price, quantity, total, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdiis", $user_id, $product_id, $name, $price, $quantity, $total, $image);
    $stmt->execute();
    $stmt->close();
}

// Update quantity
if (isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $quantity = (int)$_POST['edit_quantity'];

    // Get price, ensure user owns the cart item
    $stmt = $conn->prepare("SELECT price FROM cart WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->bind_result($price);
    if ($stmt->fetch()) {
        $total = $price * $quantity;
        $stmt->close();

        $stmt = $conn->prepare("UPDATE cart SET quantity=?, total=? WHERE id=? AND user_id=?");
        $stmt->bind_param("iiii", $quantity, $total, $id, $user_id);
        $stmt->execute();
        $stmt->close();
    } else {
        $stmt->close();
        // Optionally: handle unauthorized edit attempt
    }
}

// Delete item
if (isset($_POST['delete_confirm_id'])) {
    $id = $_POST['delete_confirm_id'];

    // Delete only if user owns the item
    $stmt = $conn->prepare("DELETE FROM cart WHERE id=? AND user_id=?");
    $stmt->bind_param("ii", $id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Select only current user's cart items
$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_items = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    // Keep user input
    foreach ($input as $key => $_) {
        $input[$key] = trim($_POST[$key] ?? '');
    }

    $payment_method = $input['payment_method'];
    $final_total = $_POST['final_total'];
    $delivery_charge = ($payment_method === 'cash') ? 250 : 0;

    // Basic validation
    if (empty($input['name'])) $invalid_fields['name'] = "Full name is required.";
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) $invalid_fields['email'] = "Valid email is required.";
    if (!preg_match('/^\d{10}$/', $input['phone1'])) $invalid_fields['phone1'] = "Enter valid 10-digit primary phone.";
    if (!preg_match('/^\d{10}$/', $input['phone2'])) $invalid_fields['phone2'] = "Enter valid 10-digit alternative phone.";
    if (empty($input['address'])) $invalid_fields['address'] = "Delivery address is required.";
    if (empty($payment_method)) $invalid_fields['payment_method'] = "Please select a payment method.";

    // Card details
    if ($payment_method === 'card') {
        if (!preg_match('/^\d{16}$/', $input['card_number'])) $invalid_fields['card_number'] = "16-digit card number required.";
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $input['card_expiry'])) $invalid_fields['card_expiry'] = "Use format MM/YY.";
        if (!preg_match('/^\d{3}$/', $input['card_cvv'])) $invalid_fields['card_cvv'] = "3-digit CVV required.";
        if (!preg_match('/^\d{4}$/', $input['card_pin'])) $invalid_fields['card_pin'] = "4-digit PIN required.";
    }

    if (!empty($invalid_fields)) {
        $form_error = "Please correct the highlighted fields.";
        $show_payment_modal = true;
    } 

    // ✅ Proceed only if no errors
    if (!$form_error) {
		
		$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone1 = $_POST['phone1'] ?? '';
$phone2 = $_POST['phone2'] ?? '';
$address = $_POST['address'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';

        $order_number = 'ORD' . time();
        $estimated_delivery = date('Y-m-d', strtotime('+2 days'));

        // Save order
        $stmt = $conn->prepare("INSERT INTO orders (user_id, order_number, name, email, phone1, phone2, address, payment_method, total_amount, delivery_charge, status, estimated_delivery_date)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'processing', ?)");
        $stmt->bind_param("isssssssiis", $user_id, $order_number, $name, $email, $phone1, $phone2, $address, $payment_method, $final_total, $delivery_charge, $estimated_delivery);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Insert order items
        $stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $cart_items = $stmt->get_result();

        $insert_item = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal, product_image)
                                       VALUES (?, ?, ?, ?, ?, ?, ?)");

        while ($item = $cart_items->fetch_assoc()) {
            $insert_item->bind_param("iisdiis",
                $order_id,
                $item['product_id'],
                $item['name'],
                $item['price'],
                $item['quantity'],
                $item['total'],
                $item['image']
            );
            $insert_item->execute();
        }
        $insert_item->close();

        // Clear cart
        $conn->query("DELETE FROM cart WHERE user_id = $user_id");

        // Redirect
        $_SESSION['success'] = "🎉 Payment successful! Order #$order_number placed.";
        header("Location: payment_history.php");
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cart - JMC Music Corner</title>
		<link rel="icon" href="images/favicon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <link rel="stylesheet" href="styles/tester.css">
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/nav.css">


    <style>
        body {
            background-color: #f9fafb;
        }

        .cart-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgb(149 157 165 / 20%);
        }

        .cart-item {
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .cart-item-info {
            flex-grow: 1;
            margin-right: 20px;
        }

        .cart-item h5 {
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 0.3rem;
        }

        .cart-item p {
            margin-bottom: 0.2rem;
            color: #495057;
            font-size: 0.95rem;
        }

        .cart-actions button {
            margin-left: 10px;
        }

        .empty-cart {
            text-align: center;
            padding: 50px;
            color: #6c757d;
            font-size: 1.2rem;
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
    <?php include("./views/includes/header.php"); ?>

    <div class="cart-container">
        <h2 class="mb-4 text-primary text-center">🛒 Your Shopping Cart</h2>

        <?php if ($cart_items->num_rows === 0): ?>
            <div class="empty-cart">Your cart is empty.</div>
        <?php endif; ?>

        <?php while ($row = $cart_items->fetch_assoc()): ?>
            <div class="cart-item">
                <div style="display: flex; align-items: center;">
                    <img src="<?= htmlspecialchars($row['image'] ?? '') ?>" alt="<?= htmlspecialchars($row['name'] ?? 'Product') ?>"
                        style="width: 80px; height: 80px; object-fit: cover; margin-right: 20px; border-radius: 8px;">

                    <div class="cart-item-info">
                        <h5><?= htmlspecialchars($row['name']) ?></h5>
                        <p>Price: <strong><?= number_format($row['price']) ?> LKR</strong></p>
                        <p>Quantity: <?= $row['quantity'] ?> | Total: <strong><?= number_format($row['total']) ?> LKR</strong></p>
                    </div>
                </div>

                <div class="cart-actions">
                    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>">Edit</button>
                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $row['id'] ?>">Delete</button>
                </div>
            </div>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger text-center">
        <?= htmlspecialchars($_SESSION['error']) ?>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header">
                                <h5 class="modal-title">Update Quantity</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                                <div class="mb-3">
                                    <label for="edit_quantity_<?= $row['id'] ?>" class="form-label">New Quantity:</label>
                                    <input id="edit_quantity_<?= $row['id'] ?>" type="number" name="edit_quantity" class="form-control" value="<?= $row['quantity'] ?>" min="1" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal<?= $row['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="post">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Are you sure you want to remove <strong><?= htmlspecialchars($row['name']) ?></strong> from your cart?
                                <input type="hidden" name="delete_confirm_id" value="<?= $row['id'] ?>">
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endwhile; ?>
		
		<?php if ($cart_items->num_rows > 0): ?>
    <div class="text-end mt-4">
        <button class="btn btn-success btn-lg" data-bs-toggle="modal" data-bs-target="#paymentModal">
            <i class="fas fa-credit-card"></i> Pay Now
        </button>
    </div>
<?php endif; ?>
		
		
	<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
		
		
    <form id="paymentForm" method="post">


        <div class="modal-header bg-success text-white">
          <h5 class="modal-title" id="paymentModalLabel">Checkout & Payment</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
			<?php if (!empty($form_error)): ?>
    <div class="alert alert-danger text-center">
        <?= htmlspecialchars($form_error) ?>
    </div>
<?php endif; ?>

          <!-- Lottie Animation -->
          <div class="text-center mb-3">
            <dotlottie-player
              src="https://lottie.host/080eb70e-ff5f-402d-8e2c-2ec92c86f56b/zdixXigp1e.json"
              background="transparent"
              speed="1"
              style="width: 120px; height: 120px;"
              loop
              autoplay>
            </dotlottie-player>
          </div>

          <!-- Product Summary -->
          <div class="border rounded p-3 mb-4 bg-light">
            <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-box-open me-1"></i> Order Summary</h6>
            <?php
              $stmt = $conn->prepare("SELECT name, quantity, total FROM cart WHERE user_id = ?");
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $items = $stmt->get_result();

              $grandTotal = 0;
              while ($item = $items->fetch_assoc()) {
                $grandTotal += $item['total'];
                echo "<p class='mb-1'><strong>{$item['name']}</strong> x {$item['quantity']} — " . number_format($item['total']) . " LKR</p>";
              }
              $stmt->close();
            ?>
            <p class="mt-2"><i class="fas fa-shipping-fast"></i> Delivery: <span id="deliveryCharge">0</span> LKR</p>
            <h5 class="text-end mt-2">Subtotal: <span id="subtotal"><?= number_format($grandTotal) ?> LKR</span></h5>
            <input type="hidden" name="raw_total" id="rawTotal" value="<?= $grandTotal ?>">
            <input type="hidden" name="final_total" id="finalTotal" value="<?= $grandTotal ?>">
          </div>

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
            <input type="text" class="form-control <?= isset($invalid_fields['name']) ? 'is-invalid' : '' ?>" 
       name="name" value="<?= htmlspecialchars($input['name']) ?>" required>
<?php if (isset($invalid_fields['name'])): ?>
    <div class="invalid-feedback"><?= $invalid_fields['name'] ?></div>
<?php endif; ?>

            </div>
            <div class="col-md-6">
              <label class="form-label">Email Address</label>
             <input type="email" class="form-control <?= isset($invalid_fields['email']) ? 'is-invalid' : '' ?>"
       name="email" value="<?= htmlspecialchars($input['email']) ?>" required>
<?php if (isset($invalid_fields['email'])): ?>
    <div class="invalid-feedback"><?= $invalid_fields['email'] ?></div>
<?php endif; ?>

            </div>

            <div class="col-md-6">
              <label class="form-label">Primary Phone</label>
              <input type="text" class="form-control <?= isset($invalid_fields['phone1']) ? 'is-invalid' : '' ?>"
       name="phone1" value="<?= htmlspecialchars($input['phone1']) ?>" required>
<?php if (isset($invalid_fields['phone1'])): ?>
    <div class="invalid-feedback"><?= $invalid_fields['phone1'] ?></div>
<?php endif; ?>

            </div>
            <div class="col-md-6">
              <label class="form-label">Alternative Phone</label>
             <input type="text" class="form-control <?= isset($invalid_fields['phone2']) ? 'is-invalid' : '' ?>"
       name="phone2" value="<?= htmlspecialchars($input['phone2']) ?>" required>
<?php if (isset($invalid_fields['phone2'])): ?>
    <div class="invalid-feedback"><?= $invalid_fields['phone2'] ?></div>
<?php endif; ?>

            </div>

            <div class="col-md-12">
              <label class="form-label">Delivery Address</label>
            <textarea class="form-control <?= isset($invalid_fields['address']) ? 'is-invalid' : '' ?>"
          name="address" rows="2" required><?= htmlspecialchars($input['address']) ?></textarea>
<?php if (isset($invalid_fields['address'])): ?>
    <div class="invalid-feedback"><?= $invalid_fields['address'] ?></div>
<?php endif; ?>

            </div>

            <div class="col-md-6">
              <label class="form-label">Payment Method</label>
            <select class="form-select <?= isset($invalid_fields['payment_method']) ? 'is-invalid' : '' ?>" 
        name="payment_method" id="paymentMethod" required>
    <option value="">Select...</option>
    <option value="card" <?= $input['payment_method'] === 'card' ? 'selected' : '' ?>>Credit / Debit Card</option>
    <option value="cash" <?= $input['payment_method'] === 'cash' ? 'selected' : '' ?>>Cash on Delivery</option>
</select>
<?php if (isset($invalid_fields['payment_method'])): ?>
    <div class="invalid-feedback"><?= $invalid_fields['payment_method'] ?></div>
<?php endif; ?>

                
            </div>
          </div>

        <div class="row g-3 card-fields <?= ($input['payment_method'] === 'card') ? '' : 'd-none' ?> mt-2">
  <div class="col-md-6">
    <label class="form-label">Card Number</label>
    <input type="text" class="form-control <?= isset($invalid_fields['card_number']) ? 'is-invalid' : '' ?>" name="card_number" value="<?= htmlspecialchars($input['card_number']) ?>" />
    <?php if (isset($invalid_fields['card_number'])): ?>
      <div class="invalid-feedback"><?= $invalid_fields['card_number'] ?></div>
    <?php endif; ?>
  </div>
  <div class="col-md-3">
    <label class="form-label">Expiry</label>
    <input type="text" class="form-control <?= isset($invalid_fields['card_expiry']) ? 'is-invalid' : '' ?>" name="card_expiry" placeholder="MM/YY" value="<?= htmlspecialchars($input['card_expiry']) ?>" />
    <?php if (isset($invalid_fields['card_expiry'])): ?>
      <div class="invalid-feedback"><?= $invalid_fields['card_expiry'] ?></div>
    <?php endif; ?>
  </div>
  <div class="col-md-3">
    <label class="form-label">CVV</label>
    <input type="text" class="form-control <?= isset($invalid_fields['card_cvv']) ? 'is-invalid' : '' ?>" name="card_cvv" value="<?= htmlspecialchars($input['card_cvv']) ?>" />
    <?php if (isset($invalid_fields['card_cvv'])): ?>
      <div class="invalid-feedback"><?= $invalid_fields['card_cvv'] ?></div>
    <?php endif; ?>
  </div>
  <div class="col-md-4">
    <label class="form-label">PIN</label>
    <input type="password" class="form-control <?= isset($invalid_fields['card_pin']) ? 'is-invalid' : '' ?>" name="card_pin" value="<?= htmlspecialchars($input['card_pin']) ?>" />
    <?php if (isset($invalid_fields['card_pin'])): ?>
      <div class="invalid-feedback"><?= $invalid_fields['card_pin'] ?></div>
    <?php endif; ?>
  </div>
</div>


        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Confirm Payment</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
		</div>
	
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


    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
	<script>
  const form = document.getElementById("paymentForm");
  const paymentMethod = document.getElementById("paymentMethod");
  const deliveryCharge = document.getElementById("deliveryCharge");
  const subtotal = document.getElementById("subtotal");
  const rawTotal = parseFloat(document.getElementById("rawTotal").value);
  const finalTotal = document.getElementById("finalTotal");

  // Handle payment method change
  paymentMethod.addEventListener("change", () => {
    const isCard = paymentMethod.value === "card";
    const isCash = paymentMethod.value === "cash";

    document.querySelectorAll(".card-fields").forEach(field => {
      field.classList.toggle("d-none", !isCard);
    });

    if (isCash) {
      deliveryCharge.innerText = "250";
      const updatedTotal = rawTotal + 250;
      subtotal.innerText = `${updatedTotal.toLocaleString()} LKR`;
      finalTotal.value = updatedTotal;
    } else {
      deliveryCharge.innerText = "0";
      subtotal.innerText = `${rawTotal.toLocaleString()} LKR`;
      finalTotal.value = rawTotal;
    }
  });

  // Validation
  form.addEventListener("submit", function (e) {
  

    const name = form.name.value.trim();
    const email = form.email.value.trim();
    const phone1 = form.phone1.value.trim();
    const phone2 = form.phone2.value.trim();
    const address = form.address.value.trim();
    const method = paymentMethod.value;

    // Basic field checks
    if (!name || !email || !phone1 || !phone2 || !address || !method) {
      alert("Please fill all required fields.");
		     e.preventDefault(); 
      return;
    }

    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      alert("Enter a valid email address.");
      return;
    }

    // Phone number validation (10 digits)
    const phoneRegex = /^\d{10}$/;
    if (!phoneRegex.test(phone1) || !phoneRegex.test(phone2)) {
      alert("Phone numbers must be exactly 10 digits.");
      return;
    }

    // Card details validation
    if (method === "card") {
      const cardNumber = form.card_number.value.trim();
      const cardExpiry = form.card_expiry.value.trim();
      const cardCVV = form.card_cvv.value.trim();
      const cardPIN = form.card_pin.value.trim();

      if (!cardNumber || !cardExpiry || !cardCVV || !cardPIN) {
        alert("Please fill all card details.");
        return;
      }

      if (!/^\d{16}$/.test(cardNumber)) {
        alert("Card number must be 16 digits.");
        return;
      }

      if (!/^(0[1-9]|1[0-2])\/\d{2}$/.test(cardExpiry)) {
        alert("Expiry must be in MM/YY format.");
        return;
      }

      if (!/^\d{3}$/.test(cardCVV)) {
        alert("CVV must be 3 digits.");
        return;
      }

      if (!/^\d{4}$/.test(cardPIN)) {
        alert("PIN must be 4 digits.");
        return;
      }
    }

    // All passed
    const btn = form.querySelector("button[type='submit']");
    btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Processing...`;
    btn.disabled = true;

    setTimeout(() => {
      btn.innerHTML = `<i class="fas fa-check-circle"></i> Paid Successfully!`;
      btn.classList.replace("btn-success", "btn-primary");
      setTimeout(() => location.href = "payment_history.php", 2000);
    }, 2000);
  });
</script>
	
	<script>
  document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('paymentMethod');
    const cardFields = document.querySelector('.card-fields');

    function toggleCardFields() {
      if (paymentMethodSelect.value === 'card') {
        cardFields.classList.remove('d-none');
        // Optionally, make card inputs required
        cardFields.querySelectorAll('input').forEach(input => {
          input.required = true;
        });
      } else {
        cardFields.classList.add('d-none');
        // Remove required attribute when not card
        cardFields.querySelectorAll('input').forEach(input => {
          input.required = false;
          input.value = ''; // Optional: clear card inputs when hidden
        });
      }
    }

    paymentMethodSelect.addEventListener('change', toggleCardFields);
    toggleCardFields(); // call once on page load
  });
</script>




<?php if ($show_payment_modal): ?>
<script>
    const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
    paymentModal.show();
</script>
<?php endif; ?>
	</div>
		
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
