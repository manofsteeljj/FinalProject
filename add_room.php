<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = $_POST['room_number'];
    $room_type = $_POST['room_type'];
    $total_slots = (int) $_POST['total_slots'];
    $remaining_slots = $total_slots;

    try {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_number, room_type, total_slots, remaining_slots) VALUES (:room_number, :room_type, :total_slots, :remaining_slots)");
        $stmt->execute([
            ':room_number' => $room_number,
            ':room_type' => $room_type,
            ':total_slots' => $total_slots,
            ':remaining_slots' => $remaining_slots
        ]);
        header("Location: room_manage.php");
        exit;
    } catch (PDOException $e) {
        die("Error adding room: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
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
        <h2>Add New Room</h2>
        <form action="add_room.php" method="POST">
            <label for="room_number">Room Number:</label>
            <input type="text" id="room_number" name="room_number" required>

            <label for="room_type">Room Type:</label>
            <select id="room_type" name="room_type" required>
                <option value="Male Double">Male Double</option>
                <option value="Female Double">Female Double</option>
                <option value="Female Single">Female Single</option>
                <option value="Male Single">Male Single</option>
            </select>

            <label for="total_slots">Total Slots:</label>
            <input type="number" id="total_slots" name="total_slots" required>

            <button type="submit">Add Room</button>
        </form>
    </div>
</body>
</html>
