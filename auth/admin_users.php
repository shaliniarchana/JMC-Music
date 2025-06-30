<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "joy_music";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT id, username, email FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Users List - JMC Music Corner</title>
		<link rel="icon" href="images/favicon.png" type="image/png">

  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	 

	
  <style>
    body {
     background-image: url('../images/bg5.png');
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
    }

    h2 {
      text-align: center;
      margin-top: 30px;
      color: #333;
    }

    .search-box {
      display: flex;
      justify-content: center;
      margin: 20px auto;
      width: 90%;
      max-width: 500px;
    }

    .search-box input {
      width: 100%;
      padding: 12px 16px;
      border-radius: 30px;
      border: 1px solid #ccc;
      outline: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      font-size: 16px;
    }

    .table-container {
      width: 95%;
      max-width: 1100px;
      margin: 30px auto;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }

    th, td {
      text-align: center;
      padding: 14px 12px;
      font-size: 15px;
      color: #333;
    }

    th {
      background: #6366f1;
      color: white;
    }

    tr:nth-child(even) {
      background: #f9f9f9;
    }

    .edit-btn, .delete-btn, .report-btn {
      padding: 6px 12px;
      margin: 2px;
      border: none;
      border-radius: 6px;
      font-size: 14px;
      cursor: pointer;
      transition: 0.2s ease-in-out;
    }

    .edit-btn {
      background: #0d6efd;
      color: white;
    }

    .edit-btn:hover {
      background: #0a58ca;
    }

    .delete-btn {
      background: #dc3545;
      color: white;
    }

    .delete-btn:hover {
      background: #bb2d3b;
    }

    .report-btn {
      background: #198754;
      color: white;
    }

    .report-btn:hover {
      background: #157347;
    }

    .thilo {
      display: block;
      margin: 30px auto;
      padding: 10px 25px;
      font-size: 16px;
      background-color: #6c757d;
      color: white;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      transition: background 0.2s;
    }

    .thilo:hover {
      background-color: #5a6268;
    }
  </style>

  <script>
    function filterUsers() {
      let input = document.getElementById("searchInput").value.toLowerCase();
      let rows = document.querySelectorAll(".table-container table tr:not(:first-child)");
      rows.forEach(row => {
        let username = row.cells[1].textContent.toLowerCase();
        row.style.display = username.includes(input) ? "" : "none";
      });
    }
  </script>
</head>
<body>
	

  <h2>👤 All Registered Users</h2>

  <!-- Search Bar -->
  <div class="search-box">
    <input type="text" id="searchInput" placeholder="🔍 Search by username..." onkeyup="filterUsers()" />
  </div>

  <!-- Users Table -->
  <div class="table-container">
	  <div class="text-center mt-3">
  <a href="generate_report.php" class="btn btn-success" style="padding: 10px 20px; border-radius: 8px;">
    📥 Download All Users Report (CSV)
  </a>
</div>
	  
	  <br>
	  <br>
	  

    <table>
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row["id"]) ?></td>
          <td><?= htmlspecialchars($row["username"]) ?></td>
          <td><?= htmlspecialchars($row["email"]) ?></td>
          <td>
            <form action="edit_user.php" method="GET" style="display:inline;">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" class="edit-btn">Edit</button>
            </form>

            <form action="delete_user.php" method="POST" style="display:inline;">
              <input type="hidden" name="user_id" value="<?= $row["id"] ?>">
              <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</button>
            </form>

           
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>

  <a href="../admin_all.html"><button class="thilo">⬅ Back to Home</button></a>

</body>
</html>
