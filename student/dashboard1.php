<?php
session_start();
require_once "../config/db.php";

// 1️⃣ Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// 2️⃣ Check role
if ($_SESSION['role'] !== 'student') {
    die("Access denied.");
}

// 3️⃣ Get user ID from session
$user_id = $_SESSION['user_id'];

// 4️⃣ Fetch student info including section and adviser
$sql = "
    SELECT s.student_number, s.full_name, u.email, 
           p.program_name, 
           CONCAT(sec.year_level, sec.section_name) AS section,
           adv.full_name AS adviser_name
    FROM users u
    JOIN students s ON u.student_id = s.id
    LEFT JOIN programs p ON s.program_id = p.programs_id
    LEFT JOIN sections sec ON s.section_id = sec.id
    LEFT JOIN staff adv ON sec.adviser_id = adv.staff_id
    WHERE u.users_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student information not found.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background-color: #f0f4ff; }
        .dashboard { max-width: 600px; margin: auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0,0,0,0.1); }
        h1 { color: #0478FF; text-align: center; }
        table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        td { padding: 10px; border-bottom: 1px solid #ddd; }
        td.label { font-weight: bold; width: 150px; color: #333; }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Student Dashboard</h1>
        <table>
            <tr>
                <td class="label">Student Number</td>
                <td><?= htmlspecialchars($student['student_number']) ?></td>
            </tr>
            <tr>
                <td class="label">Full Name</td>
                <td><?= htmlspecialchars($student['full_name']) ?></td>
            </tr>
            <tr>
                <td class="label">Program</td>
                <td><?= htmlspecialchars($student['program_name']) ?></td>
            </tr>
            <tr>
                <td class="label">Section</td>
                <td><?= htmlspecialchars($student['section']) ?></td>
            </tr>
            <tr>
                <td class="label">Adviser</td>
                <td><?= htmlspecialchars($student['adviser_name'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td><?= htmlspecialchars($student['email']) ?></td>
            </tr>
        </table>
        <p style="text-align:center; margin-top:20px;">
            <a href="../auth/login.php">Logout</a>
        </p>
    </div>
</body>
</html>
