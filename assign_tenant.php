<?php
require_once('db.php');

// Get tenant ID from URL
$tenantId = $_GET['id'];

// Fetch all available rooms
$query = "SELECT * FROM rooms WHERE remaining_slots > 0";
$stmt = $pdo->prepare($query);
$stmt->execute();
$rooms = $stmt->fetchAll();

// Process the form data when submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomId = $_POST['room_id'];
    $stayFrom = $_POST['stay_from'];
    $stayTo = $_POST['stay_to'];

    // Assign the tenant to the selected room
    $query = "UPDATE tenants SET room_id = :room_id, stay_from = :stay_from, stay_to = :stay_to WHERE id = :tenant_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'room_id' => $roomId,
        'stay_from' => $stayFrom,
        'stay_to' => $stayTo,
        'tenant_id' => $tenantId
    ]);

    // Update the remaining slots for the room
    $query = "UPDATE rooms SET remaining_slots = remaining_slots - 1 WHERE id = :room_id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['room_id' => $roomId]);

    // Redirect to the manage new tenant page
    header("Location: manage_new_tenant.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Tenant to Room</title>
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
        <h2>Assign Tenant to Room</h2>

        <form method="POST" action="assign_tenant.php?id=<?php echo $_GET['id']; ?>">
            <label for="room_id">Room:</label>
            <select id="room_id" name="room_id" required>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['id']; ?>"><?php echo $room['room_number']; ?> (<?php echo $room['room_type']; ?>)</option>
                <?php endforeach; ?>
            </select>

            <label for="stay_from">Stay From:</label>
            <input type="date" id="stay_from" name="stay_from" required>

            <label for="stay_to">Stay To:</label>
            <input type="date" id="stay_to" name="stay_to" required>

            <button type="submit">Assign Tenant</button>
        </form>
    </div>
</body>
</html>
