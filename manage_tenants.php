<?php
require_once('db.php');

$query = "SELECT t.id, t.full_name, t.gender, t.mobile_number, t.stay_from, t.stay_to, t.room_id, r.room_number, r.room_type 
          FROM tenants t
          LEFT JOIN rooms r ON t.room_id = r.id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$tenants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tenants</title>
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
        <h2>Manage Tenants</h2>

        <table>
            <thead>
                <tr>
                    <th>ID#</th>
                    <th>Name</th>
                    <th>Room Type</th>
                    <th>Stay From</th>
                    <th>Stay To</th>
                    <th>Period Remaining</th>
                    <th>Gender</th>
                    <th>Mobile#</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tenants as $tenant): ?>
                    <tr>
                        <td><?php echo $tenant['id']; ?></td>
                        <td><?php echo htmlspecialchars($tenant['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($tenant['room_number'] . ' (' . $tenant['room_type'] . ')'); ?></td>
                        <td><?php echo $tenant['stay_from']; ?></td>
                        <td><?php echo $tenant['stay_to']; ?></td>
                        <td>
                            <?php
                            $stayTo = new DateTime($tenant['stay_to']);
                            $today = new DateTime();
                            $interval = $stayTo->diff($today);
                            echo $interval->format('%a days remaining');
                            ?>
                        </td>
                        <td><?php echo htmlspecialchars($tenant['gender']); ?></td>
                        <td><?php echo htmlspecialchars($tenant['mobile_number']); ?></td>
                        <td>
                            <a href="edit_tenant.php?id=<?php echo $tenant['id']; ?>">Edit</a> |
                            <a href="delete_tenant.php?id=<?php echo $tenant['id']; ?>" onclick="return confirm('Are you sure you want to delete this tenant?')">Delete</a> |
                            <a href="change_room.php?id=<?php echo $tenant['id']; ?>">Change Room</a> |
                            <a href="check_out_tenant.php?id=<?php echo $tenant['id']; ?>" onclick="return confirm('Are you sure you want to check out this tenant?')">Check Out</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
