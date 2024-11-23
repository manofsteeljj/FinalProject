<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$room_id = $_GET['id'];

try {
    // Fetch room details
    $roomStmt = $pdo->prepare("SELECT * FROM rooms WHERE id = :id");
    $roomStmt->execute([':id' => $room_id]);
    $room = $roomStmt->fetch(PDO::FETCH_ASSOC);

    if (!$room) {
        die("Room not found.");
    }

    // Fetch tenants in the room
    $tenantStmt = $pdo->prepare("SELECT * FROM tenants WHERE room_id = :room_id");
    $tenantStmt->execute([':room_id' => $room_id]);
    $tenants = $tenantStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching room data: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Room</title>
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
        <h2>Manage Room: <?= htmlspecialchars($room['room_number']) ?></h2>
        <p><strong>Room Type:</strong> <?= htmlspecialchars($room['room_type']) ?></p>
        <p><strong>Total Slots:</strong> <?= $room['total_slots'] ?></p>
        <p><strong>Remaining Slots:</strong> <?= $room['remaining_slots'] ?></p>

        <h3>Tenants</h3>
        <table>
            <thead>
                <tr>
                    <th>ID#</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Contact#</th>
                    <th>Stay From</th>
                    <th>Stay To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tenants as $tenant): ?>
                    <tr>
                        <td><?= htmlspecialchars($tenant['id']) ?></td>
                        <td><?= htmlspecialchars($tenant['full_name']) ?></td>
                        <td><?= htmlspecialchars($tenant['gender']) ?></td>
                        <td><?= htmlspecialchars($tenant['mobile_number']) ?></td>
                        <td><?= htmlspecialchars($tenant['stay_from']) ?></td>
                        <td><?= htmlspecialchars($tenant['stay_to']) ?></td>
                        <td>
                            <button onclick="checkoutTenant(<?= $tenant['id'] ?>)">Check Out</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        function checkoutTenant(id) {
            if (confirm("Are you sure you want to check out this tenant?")) {
                window.location.href = `checkout_tenant.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
