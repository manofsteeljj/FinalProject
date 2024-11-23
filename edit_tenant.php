<?php
require_once('db.php');

// Get tenant ID from URL
$tenantId = $_GET['id'];

// Fetch tenant details
$query = "SELECT * FROM tenants WHERE id = :tenant_id";
$stmt = $pdo->prepare($query);
$stmt->execute(['tenant_id' => $tenantId]);
$tenant = $stmt->fetch();

// Process the form data when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = $_POST['full_name'];
    $gender = $_POST['gender'];
    $mobileNumber = $_POST['mobile_number'];

    // Update the tenant details
    $query = "UPDATE tenants SET full_name = :full_name, gender = :gender, mobile_number = :mobile_number WHERE id = :tenant_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'full_name' => $fullName,
        'gender' => $gender,
        'mobile_number' => $mobileNumber,
        'tenant_id' => $tenantId
    ]);

    // Redirect to the manage new tenant page after editing the tenant
    header("Location: manage_new_tenant.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tenant</title>
    <link rel="stylesheet" href="styles.css">
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
        <h2>Edit Tenant</h2>

        <form method="POST" action="edit_tenant.php?id=<?php echo $tenant['id']; ?>">
            <label for="full_name">Full Name:</label>
            <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($tenant['full_name']); ?>" required>

            <label for="gender">Gender:</label>
            <select id="gender" name="gender" required>
                <option value="Male" <?php echo $tenant['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo $tenant['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
            </select>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($tenant['mobile_number']); ?>" required>

            <button type="submit">Save Changes</button>
        </form>
    </div>
</body>
</html>
