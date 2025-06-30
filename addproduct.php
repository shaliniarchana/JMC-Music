<?php
session_start();
$conn = new mysqli("localhost", "root", "", "joy_music");

$errors = [];
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // server-side validation
    foreach (['name','image_url','price','description','category'] as $f) {
        if (empty($_POST[$f])) {
            $errors[] = ucfirst(str_replace('_',' ',$f))." is required.";
        }
    }
   if (empty($errors)) {
    // 1) Check for existing product with same name & category
    $chk = $conn->prepare("SELECT COUNT(*) FROM addproducts WHERE name = ? AND category = ?");
    $chk->bind_param("ss", $_POST['name'], $_POST['category']);
    $chk->execute();
    $chk->bind_result($count);
    $chk->fetch();
    $chk->close();

    if ($count > 0) {
        $errors[] = "A product named '{$_POST['name']}' already exists in the '{$_POST['category']}' category.";
    } else {
        // 2) Only insert if no duplicate
        $stmt = $conn->prepare(
            "INSERT INTO addproducts (name, image_url, price, description, category)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssdss",
            $_POST['name'],
            $_POST['image_url'],
            $_POST['price'],
            $_POST['description'],
            $_POST['category']
        );
        if ($stmt->execute()) {
            $_SESSION["success"] = "Product added successfully!";
            header("Location: addproduct.php");
            exit;
        } else {
            $errors[] = "Database error: " . $stmt->error;
        }
    }
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Product - JMC Music Corner</title>
  <link rel="icon" href="images/favicon.png" type="image/png">
		<link rel="icon" href="images/favicon.png" type="image/png">

  <!-- Bootstrap 5 CSS + FontAwesome -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="styles/tester.css">
        <link rel="stylesheet" href="styles/home.css">
		<link rel="stylesheet" href="styles/nav.css">
        <!-- Font Awesome CDN for Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    
    .card {
      max-width: 700px;
      margin: auto;
      border-radius: 1rem;
      box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .card-header {
      background: #0D6073;
      color: #fff;
      font-size: 1.3rem;
      font-weight: 600;
      text-align: center;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
      padding: 1.25rem;
    }
    .form-floating > .form-control,
    .form-floating > .form-select {
      height: 3.75rem;
      padding-top: 1.1rem;
    }
    .preview-img {
      width: 100%;
      max-height: 200px;
      object-fit: contain;
      border: 1px solid #ddd;
      border-radius: .5rem;
    }
    .toast-container {
      position: fixed;
      top: 1rem;
      right: 1rem;
      z-index: 1055;
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
	
	<br>

  <div class="toast-container"></div>

  <div class="card">
    <div class="card-header">
      <i class="fas fa-plus-circle me-2"></i> Add New Product
    </div>
    <div class="card-body p-5">
      <form method="POST" novalidate>
        <div class="row g-4">
          <div class="col-12">
            <div class="form-floating">
              <input type="text" name="name" class="form-control" id="productName"
                     placeholder="Product Name" required>
              <label for="productName">Product Name</label>
            </div>
          </div>
          <div class="col-md-8">
            <div class="form-floating">
              <input type="url" name="image_url" class="form-control" id="imageUrl"
                     placeholder="Image URL" required>
              <label for="imageUrl">Image URL</label>
            </div>
          </div>
          <div class="col-md-4 d-flex align-items-center">
            <img src="" id="imagePreview" class="preview-img" alt="Image preview" hidden>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="number" name="price" class="form-control" id="priceLKR"
                     placeholder="12000" min="0" step="0.01" required>
              <label for="priceLKR">Price (LKR)</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <select name="category" class="form-select" id="categorySelect" required>
                <option value="" disabled selected>Choose category...</option>
                <option>Headphones</option>
                <option>Speakers</option>
                <option>Pianos</option>
                <option>Guitars</option>
                <option>Microphones</option>
                <option>Electronic Keyboards</option>
                <option>Drums</option>
                <option>Sound Mixers</option>
                <option>Trumpets</option>
                <option>Violins</option>
                <option>Traditional Instruments</option>
              </select>
              <label for="categorySelect">Category</label>
            </div>
          </div>
          <div class="col-12">
            <div class="form-floating">
              <textarea name="description" class="form-control" id="description"
                        placeholder="Description" style="height:120px" required></textarea>
              <label for="description">Description</label>
            </div>
          </div>
        </div>

        <div class="mt-4 d-flex justify-content-between">
          <a href="products.php" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Products
          </a>
          <button type="submit" class="btn btn-success">
            <i class="fas fa-save me-1"></i> Add Product
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Bootstrap & dependencies -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Show toast
    function showToast(message, isError = false) {
      const toastEl = document.createElement('div');
      toastEl.className = 'toast align-items-center text-white ' +
        (isError ? 'bg-danger' : 'bg-success') + ' border-0';
      toastEl.role = 'alert';
      toastEl.innerHTML = `
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>`;
      document.querySelector('.toast-container').append(toastEl);
      new bootstrap.Toast(toastEl, { delay: 3000 }).show();
    }

    // On page load, display server messages
    <?php if (!empty($errors)): ?>
      <?php foreach ($errors as $err): ?>
        showToast(<?= json_encode($err) ?>, true);
      <?php endforeach; ?>
    <?php elseif (isset($_SESSION['success'])): ?>
      showToast(<?= json_encode($_SESSION['success']) ?>);
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    // Image preview
    document.getElementById('imageUrl').addEventListener('input', function() {
      const url = this.value.trim();
      const img = document.getElementById('imagePreview');
      if (url) {
        img.src = url;
        img.hidden = false;
      } else {
        img.hidden = true;
      }
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
	
	<br>
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
