<?php
require 'db.php'; // Include the database connection
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch rooms from the database
try {
    $stmt = $pdo->query("SELECT * FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching rooms: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <!-- Sidebar -->
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

    <!-- Main Content -->
    <div class="content">
        <h2>Manage Rooms</h2>
        <button onclick="window.location.href='add_room.php'" class="add-button">Add New Room</button>
        <input type="text" id="searchBar" placeholder="Search Rooms" onkeyup="searchTable()">

        <table id="roomsTable">
            <thead>
                <tr>
                    <th>ID#</th>
                    <th>Room Number</th>
                    <th>Room Type</th>
                    <th>Total Slots</th>
                    <th>Remaining Slots</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?= htmlspecialchars($room['id']) ?></td>
                        <td><?= htmlspecialchars($room['room_number']) ?></td>
                        <td><?= htmlspecialchars($room['room_type']) ?></td>
                        <td><?= htmlspecialchars($room['total_slots']) ?></td>
                        <td><?= htmlspecialchars($room['remaining_slots']) ?></td>
                        <td>
                            <button onclick="window.location.href='manage_room.php?id=<?= $room['id'] ?>'">Manage</button>
                            <button onclick="window.location.href='edit_room.php?id=<?= $room['id'] ?>'">Edit</button>
                            <button onclick="deleteRoom(<?= $room['id'] ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        // Search functionality for the table
        function searchTable() {
            const input = document.getElementById("searchBar").value.toLowerCase();
            const table = document.getElementById("roomsTable");
            const rows = table.getElementsByTagName("tr");

            for (let i = 1; i < rows.length; i++) {
                let match = false;
                const cells = rows[i].getElementsByTagName("td");
                for (let j = 0; j < cells.length; j++) {
                    if (cells[j].textContent.toLowerCase().includes(input)) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
            }
        }

        // Delete room functionality
        function deleteRoom(id) {
            if (confirm("Are you sure you want to delete this room?")) {
                window.location.href = `delete_room.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
