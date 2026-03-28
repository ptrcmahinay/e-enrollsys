<?php
session_start();
require_once "../config/db.php";

// 1️⃣ Protect page (only admin)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// 2️⃣ Fetch all users with roles
$sql = "
SELECT u.users_id, u.username, s.student_number, s.full_name, r.role_name
FROM users u
LEFT JOIN students s ON u.student_id = s.id
JOIN user_roles ur ON u.users_id = ur.user_id
JOIN roles r ON ur.role_id = r.roles_id
ORDER BY u.users_id ASC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
th { background-color: #0478FF; color: white; }
</style>
</head>
<body>
<h1>Admin Dashboard</h1>

<h2>All Users</h2>
<table>
    <tr>
        <th>ID</th>
        <th>Username / Email</th>
        <th>Student Number</th>
        <th>Full Name</th>
        <th>Role</th>
    </tr>
    <?php while($user = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $user['users_id'] ?></td>
        <td><?= $user['username'] ?? '-' ?></td>
        <td><?= $user['student_number'] ?? '-' ?></td>
        <td><?= $user['full_name'] ?? '-' ?></td>
        <td><?= $user['role_name'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<h2>Create New Staff Account</h2>
<form action="create_user.php" method="POST">
    <label>Username / Email:</label>
    <input type="text" name="username" required><br><br>

    <label>Password:</label>
    <input type="password" name="password" required><br><br>

    <label>Role:</label>
    <select name="role_id" required>
    <!-- <option value="1">Student</option>     -->
    <option value="2">Instructor</option>
        <option value="3">Registrar</option>
        <option value="4">Cashier</option>
        <option value="5">Admin</option>
    </select><br><br>

    <button type="submit">Create User</button>
</form>

</body>
</html>
