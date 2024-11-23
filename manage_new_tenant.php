<?php
// Include database connection
require_once('db.php');

// Fetch tenants who are not assigned to any room
$query = "SELECT * FROM tenants WHERE room_id IS NULL";
$stmt = $pdo->prepare($query);
$stmt->execute();
$tenants = $stmt->fetchAll();

// Search functionality (if any search query is provided)
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $query = "SELECT * FROM tenants WHERE room_id IS NULL AND (full_name LIKE :search OR mobile_number LIKE :search)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['search' => "%$searchTerm%"]);
    $tenants = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage New Tenant</title>
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
        <h2>Manage New Tenant</h2>

        <!-- Add New Tenant Button -->
        <a href="add_new_tenant.php" class="add-new-btn">Add New Tenant</a>

        <!-- Search Bar -->
        <form method="POST" action="manage_new_tenant.php">
            <input type="text" name="search" placeholder="Search by Name or Mobile Number" value="<?php echo htmlspecialchars($searchTerm); ?>" />
            <button type="submit">Search</button>
        </form>

        <!-- Tenant List -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Mobile Number</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($tenants): ?>
                    <?php foreach ($tenants as $tenant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tenant['id']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['gender']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['mobile_number']); ?></td>
                            <td>
                                <a href="assign_tenant.php?id=<?php echo $tenant['id']; ?>" class="assign-btn">Assign</a>
                                <a href="edit_tenant.php?id=<?php echo $tenant['id']; ?>" class="edit-btn">Edit</a>
                                <a href="delete_tenant.php?id=<?php echo $tenant['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this tenant?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No unassigned tenants found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
