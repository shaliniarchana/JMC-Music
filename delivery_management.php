<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'joy_music');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle deletion
if (isset($_POST['delete_order_id'])) {
    $delete_order_id = intval($_POST['delete_order_id']);
    $conn->query("DELETE FROM order_items WHERE order_id = $delete_order_id");
    $conn->query("DELETE FROM orders WHERE id = $delete_order_id");
    $_SESSION['success'] = "Order #$delete_order_id has been deleted.";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch all orders with items, ordered by order id DESC
$sql = "
    SELECT o.id AS order_id, o.order_number, o.user_id, o.name AS customer_name, o.email, o.phone1, o.phone2, o.address,
           o.payment_method, o.total_amount, o.delivery_charge, o.status, o.estimated_delivery_date,
           oi.product_name, oi.product_image, oi.price, oi.quantity, oi.subtotal
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    ORDER BY o.id DESC, oi.id ASC
";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching orders: " . $conn->error);
}

// Group data by order_id
$orders = [];
while ($row = $result->fetch_assoc()) {
    $order_id = $row['order_id'];
    if (!isset($orders[$order_id])) {
        $orders[$order_id] = [
            'order_number' => $row['order_number'] ?? '',
            'user_id' => $row['user_id'] ?? '',
            'customer_name' => $row['customer_name'] ?? '',
            'email' => $row['email'] ?? '',
            'phone1' => $row['phone1'] ?? '',
            'phone2' => $row['phone2'] ?? '',
            'address' => $row['address'] ?? '',
            'payment_method' => $row['payment_method'] ?? '',
            'total_amount' => $row['total_amount'] ?? 0,
            'delivery_charge' => $row['delivery_charge'] ?? 0,
            'status' => $row['status'] ?? '',
            'estimated_delivery_date' => $row['estimated_delivery_date'] ?? '',
            'items' => [],
        ];
    }
    $orders[$order_id]['items'][] = [
        'product_name' => $row['product_name'] ?? '',
        'product_image' => $row['product_image'] ?? '',
        'price' => $row['price'] ?? 0,
        'quantity' => $row['quantity'] ?? 0,
        'subtotal' => $row['subtotal'] ?? 0,
    ];
}

// Reusable order card rendering function
function renderOrderCard($order_id, $order) {
    ob_start(); ?>
    <div class="order-card"
     data-order-number="<?= htmlspecialchars(strtolower($order['order_number'])) ?>"
     data-customer-name="<?= htmlspecialchars(strtolower($order['customer_name'])) ?>"
     data-status="<?= htmlspecialchars(strtolower($order['status'])) ?>"
     data-email="<?= htmlspecialchars(strtolower($order['email'])) ?>"
     data-phone1="<?= htmlspecialchars(strtolower($order['phone1'])) ?>"
     data-phone2="<?= htmlspecialchars(strtolower($order['phone2'])) ?>"
     data-address="<?= htmlspecialchars(strtolower($order['address'])) ?>">

        <div class="order-header">
            <div>
                <strong>Order #<?= htmlspecialchars($order['order_number']) ?></strong> &nbsp;&mdash;&nbsp;
                Customer: <?= htmlspecialchars($order['customer_name']) ?>
            </div>
            <div>
				  <form method="POST" action="" onsubmit="return confirmDelete(event, <?= $order_id ?>)" class="d-inline">
                    <input type="hidden" name="delete_order_id" value="<?= $order_id ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i> Delete</button>
                </form>
                <form method="POST" action="update_order.php" class="d-flex align-items-center gap-2">
                    <input type="hidden" name="order_id" value="<?= $order_id ?>">
                    <select name="status" class="form-select form-select-sm">
                        <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                        <option value="on_the_way" <?= $order['status'] === 'on_the_way' ? 'selected' : '' ?>>On the Way</option>
                        <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                    </select>
                    <input type="date" name="estimated_delivery_date" class="form-control form-control-sm"
                           value="<?= htmlspecialchars($order['estimated_delivery_date']) ?>" min="<?= date('Y-m-d') ?>" required>
                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-save"></i> Update</button>
                </form>
            </div>
        </div>
        <div class="p-4 bg-white rounded-bottom">
            <div class="row mb-3">
                <div class="col-md-6 customer-info">
                    <p><strong>Email:</strong> <?= htmlspecialchars($order['email']) ?></p>
                    <p><strong>Phone 1:</strong> <?= htmlspecialchars($order['phone1']) ?></p>
                    <p><strong>Phone 2:</strong> <?= htmlspecialchars($order['phone2']) ?></p>
                    <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
                </div>
                <div class="col-md-6 order-info">
                    <p><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']) ?></p>
                    <p><strong>Estimated Delivery:</strong> <?= htmlspecialchars($order['estimated_delivery_date']) ?></p>
                    <p><strong>Delivery Charge:</strong> <?= number_format($order['delivery_charge']) ?> LKR</p>
                    <p><strong>Total Amount:</strong> <?= number_format($order['total_amount']) ?> LKR</p>
                </div>
            </div>
            <h5 class="mb-3">Products</h5>
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price (LKR)</th>
                        <th>Quantity</th>
                        <th>Subtotal (LKR)</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($item['product_image']) ?>" class="product-img" alt=""></td>
                        <td><?= htmlspecialchars($item['product_name']) ?></td>
                        <td><?= number_format($item['price']) ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td><?= number_format($item['subtotal']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php return ob_get_clean();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Delivery Management - JMC Music Corner</title>
		<link rel="icon" href="images/favicon.png" type="image/png">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="styles/tester.css" />
  <link rel="stylesheet" href="styles/home.css" />
  <link rel="stylesheet" href="styles/nav.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


  <style>
    body {  background-image: url('images/bg5.png'); }
    .order-card { margin-bottom: 2rem; box-shadow: 0 4px 12px rgb(0 0 0 / 0.05); border-radius: 10px; }
    .order-header { background: #032F40; color: white; padding: 1rem 1.5rem; border-radius: 10px 10px 0 0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem; }
    .table thead th { background-color: #0d6073; color: white; }
    .product-img { height: 50px; width: 50px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd; }
    .customer-info p, .order-info p { margin-bottom: 0.25rem; font-size: 0.9rem; }
    .search-input { max-width: 300px; margin-bottom: 1.5rem; }
	    .delivery-text
	  {
		  color: #000000;
	  }

   html, body {
  height: 100%;
  margin: 0;
  padding: 0;
}

body {
  display: flex;
  flex-direction: column;
  background: url('images/bg5.png') no-repeat center center fixed;
  background-size: cover;
}

.main-wrapper {
  flex: 1 0 auto;
  display: flex;
  flex-direction: column;
  padding: 0;
  margin: 0;
}

.container {
  flex: 1;
  padding: 0 !important;
  margin: 0 auto;
  width: 100%;
}

footer.footer {
  flex-shrink: 0;
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

<?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050">
    <div id="toastMsg" class="toast align-items-center text-white <?= isset($_SESSION['success']) ? 'bg-success' : 'bg-danger' ?>" role="alert">
      <div class="d-flex">
        <div class="toast-body"><?= $_SESSION['success'] ?? $_SESSION['error']; ?></div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
<?php unset($_SESSION['success'], $_SESSION['error']); endif; ?>
<div class="main-wrapper">
<div class="container py-0">
  <h2 class="delivery-text text-center"><b>📦 Delivery Management</b></h2>
  <input type="text" id="searchOrders" class="form-control search-input" placeholder="Search orders by Order Number, Customer Name or Status...">

  <?php if (empty($orders)): ?>
    <p class="text-center text-muted mt-5">No orders found.</p>
  <?php else: ?>
    <ul class="nav nav-tabs mb-3" id="orderTabs" role="tablist">
      <li class="nav-item"><button class="nav-link active" id="processing-tab" data-bs-toggle="tab" data-bs-target="#processing" type="button" style="color:#000000">Processing</button></li>
      <li class="nav-item"><button class="nav-link" id="ontheway-tab" data-bs-toggle="tab" data-bs-target="#ontheway" type="button" style="color:#000000">On the Way</button></li>
      <li class="nav-item"><button class="nav-link" id="delivered-tab" data-bs-toggle="tab" data-bs-target="#delivered" type="button" style="color:#000000">Delivered</button></li>
    </ul>

    <div class="tab-content">
      <div class="tab-pane fade show active" id="processing">
        <?php foreach ($orders as $id => $o) if ($o['status'] === 'processing') echo renderOrderCard($id, $o); ?>
      </div>
      <div class="tab-pane fade" id="ontheway">
        <?php foreach ($orders as $id => $o) if ($o['status'] === 'on_the_way') echo renderOrderCard($id, $o); ?>
      </div>
      <div class="tab-pane fade" id="delivered">
        <?php foreach ($orders as $id => $o) if ($o['status'] === 'delivered') echo renderOrderCard($id, $o); ?>
      </div>
    </div>
  <?php endif; ?>
</div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const searchInput = document.getElementById('searchOrders');
searchInput.addEventListener('input', () => {
  const query = searchInput.value.toLowerCase();
  document.querySelectorAll('.order-card').forEach(order => {
    const match = ['orderNumber', 'customerName', 'status', 'email', 'phone1', 'phone2', 'address'].some(attr =>
      (order.dataset[attr] || '').includes(query)
    );
    order.style.display = match ? '' : 'none';
  });
});



  window.addEventListener('DOMContentLoaded', () => {
    const toastEl = document.getElementById('toastMsg');
    if (toastEl) new bootstrap.Toast(toastEl).show();
  });
</script>
	
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmDelete(event, orderId) {
  event.preventDefault();
  Swal.fire({
    title: 'Are you sure?',
    text: "This order will be permanently deleted!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      event.target.submit();
    }
  });
  return false;
}
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
