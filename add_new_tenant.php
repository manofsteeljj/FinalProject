<?php
require_once('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'];
    $gender = $_POST['gender'];
    $mobileNumber = $_POST['mobile_number'];

    // Insert the new tenant into the database
    $query = "INSERT INTO tenants (full_name, gender, mobile_number) VALUES (:full_name, :gender, :mobile_number)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'full_name' => $fullName,
        'gender' => $gender,
        'mobile_number' => $mobileNumber
    ]);

    // Redirect to the manage new tenant page after adding the tenant
    header("Location: manage_new_tenant.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Tenant</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
<div class="sidebar">
        <div class="logo-container">
            <img src="images/Dorm.png" alt="Dormitory Logo" class="logo">
            <h1>Dorm Management</h1>
        </div>
        <ul class="sidebar-menu">
            <li><a href="room_manage.php">Manage Rooms</a></li>
            <li><a href="manage_new_tenant.php">Manage New Tenant</a></li>
            <li><a href="manage_tenants.php">Manage Tenants</a></li>
            <li><a href="manage_facilities.php">Manage Facilities</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="content">
        <h2>Add New Tenant</h2>

        <form method="POST" action="add_new_tenant.php">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" required>

            <button type="submit">Add Tenant</button>
        </form>
    </div>
</body>
</html>
