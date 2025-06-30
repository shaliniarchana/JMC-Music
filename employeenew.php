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
    <title>Employee Management - JMC Music Corner</title>
    <link rel="icon" href="images/favicon.png" type="image/png">
    
        <link rel="stylesheet" href="styles/tester.css">
        <link rel="stylesheet" href="styles/home.css">
		<link rel="stylesheet" href="styles/nav.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script defer src="script.js"></script>
</head>
    
<body>
	<?php include("./views/includes/header.php") ?>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
			 height: auto;
            overflow-x: hidden;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85);
            color: #333;
            width: 90%;
            max-width: 900px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
            margin: 40px auto;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease;
			 overflow-x: auto; 
             word-wrap: break-word;
             overflow-wrap: break-word;
        }

        .container:hover {
            transform: scale(1.03);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #0F0584;
        }

        .form-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
    justify-content: center; 
    align-items: center; 
}

        input, select, button {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

       button {
    margin-left: 20px;
    background-color: #0F0584;
    color: white;
    cursor: pointer;
    border: none;
    transition: 0.3s;
    text-align: center; 
}
button:hover {
    background-color: #158BCC;
}
		
		
    .edit-btn {
        background-color: #0DB7E3;
        color: white;
        cursor: pointer;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .edit-btn:hover {
        background-color: #0F0584;
    }


    .delete-btn {
        background-color: #D82E31;
        color: white;
        cursor: pointer;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .delete-btn:hover {
        background-color: #8A0507;
    }

    
   .delete-all-btn {
    background-color: #dc3545;
    color: white;
    cursor: pointer;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    transition: 0.3s;
    width: 180px; 
}

.delete-all-btn:hover {
    background-color: #8A0507;
}


    
    .download-btn {
        background-color:#28a745;
        color: white;
        cursor: pointer;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        transition: 0.3s;
    }

    .download-btn:hover {
        background-color: #0D6607;
    }
      
		
     .edit-btn, .delete-btn {
    width: 80px; 
}


      .delete-all-btn, .download-btn {
    width: 180px; 
}
		
   #sortBtn {
  padding: 10px 20px;
  background: linear-gradient(to right, #3b82f6, #6366f1);
  color: white;
  border: none;
  border-radius: 12px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: background 0.3s ease, transform 0.2s ease;
}

#sortBtn:hover {
  background: linear-gradient(to right, #2563eb, #4f46e5);
  transform: scale(1.03);
}

      

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
			 word-break: break-word; 
              white-space: normal;    
            max-width: 150px;     
        }

        th {
            background: #6B3BE3;
            color: white;
        }

        .table-actions {
            margin-top: 20px;
            text-align: center;
        }

        .table-actions button {
            width: auto;
            margin: 0 10px;
            padding: 10px 15px;
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

    <div class="container">
		<div style="display: flex; justify-content: flex-end; margin-bottom: 10px;">
    <button id="sortBtn" onclick="toggleSort()">Sort: Newest First</button>
	
        </div>

        <h1>Employee Management</h1>
        <br>

        <div class="form-container">
            <input type="text" id="empName" placeholder="Employee Name">
            <input type="text" id="empContact" placeholder="Contact Number">
            <input type="text" id="empAddress" placeholder="Address">
			<input type="text" id="empEmail" placeholder="Email">
            <select id="empRole">
                <option value="">Select Role</option>
                <option value="Cashier">Cashier</option>
                <option value="Sales Associate">Sales Associate</option>
                <option value="Inventory Manager">Inventory Manager</option>
                <option value="Music Instructor">Music Instructor</option>
                <option value="Sound Technician">Sound Technician</option>
                <option value="Store Manager">Store Manager</option>
            </select>
            <button onclick="addEmployee()">Add Employee</button>
        </div>
        <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Address</th>
					<th>Email</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="employeeTable">
                
            </tbody>
        </table>
		</div>
        
        <div class="table-actions">
            <button class="delete-all-btn" onclick="deleteAllEmployees()">Delete All</button>
            <button class="download-btn" onclick="downloadReport()">Download Employee Report</button>
        </div>

    </div>

    <script>
    let editingEmployeeId = null;
    let sortOrder = 'desc'; 

    
    window.onload = function() {
        
        sortOrder = localStorage.getItem('sortOrder') || 'desc';
        document.getElementById('sortBtn').innerText = 
            sortOrder === 'desc' ? 'Sort: Newest First' : 'Sort: Oldest First';

        fetchEmployees(); 
    };

  
    function toggleSort() {
        sortOrder = sortOrder === 'desc' ? 'asc' : 'desc';  
        localStorage.setItem('sortOrder', sortOrder);  
        document.getElementById('sortBtn').innerText = 
            sortOrder === 'desc' ? 'Sort: Newest First' : 'Sort: Oldest First';

        fetchEmployees();  
    }

   
    function fetchEmployees() {
        fetch(`employee_management.php?fetch=true&sort=${sortOrder}`)
            .then(response => response.json())
            .then(data => {
                let table = document.getElementById('employeeTable');
                table.innerHTML = "";  
                data.forEach(emp => {
                    let row = `
                        <tr>
                            <td>${emp.id}</td>
                            <td>${emp.name}</td>
                            <td>${emp.contact}</td>
                            <td>${emp.address}</td>
                            <td>${emp.email}</td>
                            <td>${emp.role}</td>
                            <td>
                                <button class="edit-btn" onclick="startEditEmployee(${emp.id}, '${emp.name}', '${emp.contact}', '${emp.address}', '${emp.email}', '${emp.role}')">Edit</button>
                                <button class="delete-btn" onclick="deleteEmployee(${emp.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                    table.innerHTML += row;
                });
            });
    }

    // Add a new employee
    function addEmployee() {
        let name = document.getElementById('empName').value;
        let contact = document.getElementById('empContact').value;
        let address = document.getElementById('empAddress').value;
        let email = document.getElementById('empEmail').value;
        let role = document.getElementById('empRole').value;

        // Validation
        let nameRegex = /^[A-Za-z\s]+$/;
        if (!nameRegex.test(name) || name.length < 3) {
            alert("Employee name must be at least 3 characters long and cannot contain numbers.");
            return;
        }

        let contactRegex = /^[0-9]{10,}$/;
        if (!contactRegex.test(contact)) {
            alert("Contact number must be at least 10 digits and contain only numbers.");
            return;
        }

        if (address.length < 3) {
            alert("Address must be at least 3 characters long.");
            return;
        }

        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert("Please enter a valid email address.");
            return;
        }

        if (role === "") {
            alert("Please select a role.");
            return;
        }

        let formData = new FormData();
        if (editingEmployeeId !== null) {
            // Update employee
            formData.append('action', 'update');
            formData.append('id', editingEmployeeId);
        } else {
            // Add new employee
            formData.append('action', 'add');
        }

        formData.append('name', name);
        formData.append('contact', contact);
        formData.append('address', address);
        formData.append('email', email);
        formData.append('role', role);

        fetch('employee_management.php', { method: 'POST', body: formData })
            .then(() => {
                fetchEmployees();
                resetForm();
            });
    }

    // Start editing an employee's details
    function startEditEmployee(id, name, contact, address, email, role) {
        editingEmployeeId = id;

        document.getElementById('empName').value = name;
        document.getElementById('empContact').value = contact;
        document.getElementById('empAddress').value = address;
        document.getElementById('empEmail').value = email;
        document.getElementById('empRole').value = role;

        const button = document.querySelector('button[onclick="addEmployee()"]');
        button.innerText = "Update Employee";
        button.style.backgroundColor = "#ffc107";
    }

    // Reset the form after add/edit
    function resetForm() {
        editingEmployeeId = null;
        document.getElementById('empName').value = "";
        document.getElementById('empContact').value = "";
        document.getElementById('empAddress').value = "";
        document.getElementById('empEmail').value = "";
        document.getElementById('empRole').value = "";

        const button = document.querySelector('button[onclick="addEmployee()"]');
        button.innerText = "Add Employee";
        button.style.backgroundColor = "#0F0584";
    }

    // Delete an employee
    function deleteEmployee(id) {
        if (confirm("Are you sure you want to delete this employee?")) {
            let formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            fetch('employee_management.php', { method: 'POST', body: formData })
                .then(() => fetchEmployees());
        }
    }

    // Delete all employees
    function deleteAllEmployees() {
        if (confirm("Are you sure you want to delete all employees? This action cannot be undone.")) {
            let formData = new FormData();
            formData.append('action', 'deleteAll');

            fetch('employee_management.php', { method: 'POST', body: formData })
                .then(() => {
                    fetchEmployees();
                    resetForm();
                });
        }
    }

    // Download the employee report
    function downloadReport() {
        const sortBy = 'id';  
        const order = sortOrder;  

        const link = document.createElement('a');
        link.href = `employee_report.php?sort_by=${sortBy}&order=${order}`;
        link.download = '';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>
	
	<script>
  const toggleBtn = document.getElementById("modeToggle");
  const body = document.body;

  if (toggleBtn) {
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

    // Apply saved theme on page load
    if (localStorage.getItem("theme") === "dark") {
      body.classList.add("dark-mode");
      toggleBtn.querySelector("i").classList.replace("fa-sun", "fa-moon");
    }
  }
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
