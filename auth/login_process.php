<?php
session_start();
require_once "../config/db.php";

// 1️⃣ Get input
$identifier = $_POST['identifier'] ?? '';
$password   = $_POST['password'] ?? '';

if (empty($identifier) || empty($password)) {
    die("Please enter your credentials.");
}

// 2️⃣ Initialize user variable
$user = null;

// 3️⃣ Attempt STUDENT login first
$sql = "
    SELECT 
        u.users_id AS user_id,
        u.password,
        r.role_name,
        u.username,
        u.email,
        s.id AS student_id,
        s.full_name
    FROM students s
    JOIN users u ON u.student_id = s.id
    JOIN user_roles ur ON u.users_id = ur.user_id
    JOIN roles r ON ur.role_id = r.roles_id
    WHERE s.student_number = ?
    LIMIT 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $identifier);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// 4️⃣ If not found, attempt STAFF/ADMIN login
if (!$user) {
    $sql = "
    SELECT 
        u.users_id AS user_id,
        u.password,
        r.role_name,
        u.username,
        u.email,
        s.staff_id AS staff_id,
        s.full_name
    FROM users u
    JOIN staff s ON s.users_id = u.users_id
    JOIN user_roles ur ON u.users_id = ur.user_id
    JOIN roles r ON ur.role_id = r.roles_id
    WHERE u.username = ?
    LIMIT 1
";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// 5️⃣ No user found
if (!$user) {
    $_SESSION['error'] = "No user found. Check your username or student number.";
    header("Location: login.php");
    exit;
}

// 6️⃣ Verify password
if (!password_verify($password, $user['password'])) {
    $_SESSION['error'] = "Incorrect password.";
    header("Location: login.php");
    exit;
}

session_regenerate_id(true);

// 7️⃣ Set session variables
// $_SESSION['user_id'] = $user['user_id'];
// $_SESSION['role']     = $user['role_name'];
// $_SESSION['email']     = $user['email'];

$_SESSION['user'] = [
    'id' => $user['user_id'] ?? null,
    'name' => $user['full_name'] ?? $user['username'] ?? '',
    'username' => $user['username'] ?? '',
    'email' => $user['email'] ?? '',
    'role' => $user['role_name'] ?? '',
    'student_id' => $user['student_id'] ?? null,
    'staff_id' => $user['staff_id'] ?? null
];

if ($_SESSION['user']['role'] === 'student') {
    $_SESSION['username']   = $user['full_name'];
    $_SESSION['student_id'] = $user['student_id']; 
} else {
    $_SESSION['username'] = $user['username'];
}

// 8️⃣ Redirect by role
switch ($_SESSION['user']['role']) {
    case 'student':
        header("Location: ../student/dashboard.php");
        break;
    case 'instructor':
        header("Location: ../instructor/dashboard.php");
        break;
    case 'registrar':
        header("Location: ../registrar/dashboard.php");
        break;
    case 'cashier':
        header("Location: ../cashier/dashboard.php");
        break;
    case 'admin':
        header("Location: ../admin/dashboard.php");
        break;
    default:
        die("Role not recognized.");
}
exit;
?>


