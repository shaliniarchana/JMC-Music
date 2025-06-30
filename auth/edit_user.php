<?php
session_start();
include "../config/database.php";

if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
    $sql = "SELECT username, email FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
} else {
    header("Location: admin_users.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit User - JMC Muisc Corner</title>
		<link rel="icon" href="images/favicon.png" type="image/png">

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Segoe UI', sans-serif;
    }

    .container {
      max-width: 600px;
      background: white;
      padding: 30px;
      margin: 50px auto;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
      border-radius: 10px;
    }

    h2 {
      text-align: center;
      color: #343a40;
      margin-bottom: 30px;
    }

    .form-label {
      font-weight: 600;
    }

    .form-control {
      border-radius: 8px;
    }

    .btn-primary {
      background-color: #6366f1;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
    }

    .btn-primary:hover {
      background-color: #4f46e5;
    }

    .btn-secondary {
      margin-top: 10px;
      border-radius: 8px;
    }

    .message.success {
      background-color: #d4edda;
      color: #155724;
      padding: 12px;
      border-radius: 6px;
      margin: 15px auto;
      text-align: center;
      width: 90%;
      max-width: 500px;
      border: 1px solid #c3e6cb;
    }

    .message.error {
      background-color: #f8d7da;
      color: #721c24;
      padding: 12px;
      border-radius: 6px;
      margin: 15px auto;
      text-align: center;
      width: 90%;
      max-width: 500px;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>

  <?php if (isset($_SESSION["success_message"])): ?>
    <div class="message success"><?= $_SESSION["success_message"]; ?></div>
    <?php unset($_SESSION["success_message"]); ?>
  <?php elseif (isset($_SESSION["error_message"])): ?>
    <div class="message error"><?= $_SESSION["error_message"]; ?></div>
    <?php unset($_SESSION["error_message"]); ?>
  <?php endif; ?>

  <div class="container">
    <h2>✏️ Edit User</h2>
    <form action="update_user.php" method="POST">
      <input type="hidden" name="user_id" value="<?= $user_id ?>">

      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($result["username"]) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($result["email"]) ?>" required>
      </div>

      <div class="mb-3">
        <label class="form-label">New Password <small class="text-muted">(Optional)</small></label>
        <input type="password" class="form-control" name="password" placeholder="Leave blank to keep existing password">
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="admin_users.php" class="btn btn-secondary">⬅ Back to User List</a>
      </div>
    </form>
  </div>

</body>
</html>
