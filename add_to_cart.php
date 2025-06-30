<?php
session_start();
if (isset($_SESSION['cart_message'])) {
    echo "<script>alert('{$_SESSION['cart_message']}');</script>";
    unset($_SESSION['cart_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Products - JMC Music Corner</title>
  <link rel="icon" href="images/favicon.png" type="image/png">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">


  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to right, #8e4cd5, #4868a0);
      color: #fff;
      min-height: 100vh;
      padding: 40px 20px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
    }

    .card {
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .form-control, .form-control:focus {
      border-radius: 8px;
      box-shadow: none;
    }

    .btn-primary, .btn-warning {
      border-radius: 8px;
      font-weight: 600;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .top-right {
      position: absolute;
      top: 20px;
      right: 20px;
    }

    .back-btn {
      margin-top: 20px;
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

<div class="top-right">
  <a href="export_excel.php" class="btn btn-success">Download Excel Report</a>
</div>

<div class="container">
  <h2 class="text-center mb-4">Add a New Product</h2>

  <div class="card p-4 mb-5 bg-light text-dark">
    <form action="create.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Enter the product name" required>
      </div>
      <div class="mb-3">
        <input type="number" name="price" class="form-control" placeholder="Enter the product price" required>
      </div>
      <div class="mb-3">
        <input type="file" name="image" class="form-control">
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary">Add Product</button>
      </div>
    </form>
  </div>

  <div class="text-center back-btn">
    <a href="products.php" class="btn btn-warning">← Back to Products</a>
  </div>

  <h2 class="text-center mt-5">Product List</h2>

  <div class="table-responsive mt-3">
    <table class="table table-striped table-bordered bg-white text-dark">
      <thead class="table-dark">
        <tr>
          <th>Product Image</th>
          <th>Product Name</th>
          <th>Product Price</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php include 'read.php'; ?>
      </tbody>
    </table>
  </div>
</div>

</body>
</html>
