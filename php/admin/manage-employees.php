<?php
require_once 'admin_functions.php';
validateAdminAccess();

$conn = connect_db();
$query = "SELECT u.id, u.name, u.email, s.dutyFrom, s.dutyTo 
          FROM users u 
          JOIN staff s ON u.id = s.userId 
          WHERE u.role = 'staff'";
$result = $conn->query($query);
$employees = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees - Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/admin/admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <nav class="navbar">
            <ul class="nav-links">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage-employees.php" class="active">Manage Employees</a></li>
                <li><a href="adminprofile.php">Profile</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="manage-users.php">Manage Users</a></li>
                <li><a href="manage-coupons.php">Manage Coupons</a></li>
                <li><a href="logout.php" class="logout-btn">Logout</a></li>
            </ul>
        </nav>
        
        <div class="welcome-box">
            <div class="hero-section">
                <img src="../../resources/Brown Modern Circle Coffee Shop Logo.png" alt="Cafe Logo" class="logo">
                <h1>Manage Employees</h1>
                <p>Manage staff schedules and information</p>
            </div>
            
            <section class="management-section">
                <div class="section-header">
                    <h2>Employee List</h2>
                    <button class="btn" onclick="showAddEmployeeModal()">Add New Employee</button>
                </div>
                
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Duty From</th>
                                <th>Duty To</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($employees as $employee): ?>
                            <tr>
                                <td><?php echo $employee['id']; ?></td>
                                <td><?php echo htmlspecialchars($employee['name']); ?></td>
                                <td><?php echo htmlspecialchars($employee['email']); ?></td>
                                <td><?php echo $employee['dutyFrom']; ?></td>
                                <td><?php echo $employee['dutyTo']; ?></td>
                                <td class="action-buttons">
                                    <button class="btn-small btn-edit" onclick="editEmployee(<?php echo $employee['id']; ?>)">Edit</button>
                                    <button class="btn-small btn-delete" onclick="deleteEmployee(<?php echo $employee['id']; ?>)">Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <div id="employeeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3 id="modalTitle">Add New Employee</h3>
            <form id="employeeForm">
                <div class="form-group">
                    <label for="employeeName">Name</label>
                    <input type="text" id="employeeName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="employeeEmail">Email</label>
                    <input type="email" id="employeeEmail" name="email" required>
                </div>
                <div class="form-group password-group">
                    <label for="employeePassword">Password</label>
                    <input type="password" id="employeePassword" name="password" required>
                </div>
                <div class="form-group">
                    <label for="dutyFrom">Duty From</label>
                    <input type="time" id="dutyFrom" name="dutyFrom">
                </div>
                <div class="form-group">
                    <label for="dutyTo">Duty To</label>
                    <input type="time" id="dutyTo" name="dutyTo">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn">Save Employee</button>
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../../js/manageemployees.js"></script>
</body>
</html>
