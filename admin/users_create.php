<?php
require_once "../config/db.php";

header('Content-Type: application/json');

$username = $_POST['username'] ?? '';
$email    = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role_id  = $_POST['role_id'] ?? '';

// Validate
if (!$username || !$email || !$password || !$role_id) {
    echo json_encode([
        'status' => 'error',
        'message' => 'All fields are required'
    ]);
    exit;
}

// Hash password
$password = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$sql1 = "INSERT INTO Users (Username, email, Password, Created_at)
         VALUES ('$username', '$email', '$password', NOW())";

if (!$conn->query($sql1)) {
    echo json_encode([
        'status' => 'error',
        'message' => $conn->error
    ]);
    exit;
}

$user_id = $conn->insert_id;

// Insert role
$sql2 = "INSERT INTO User_roles (User_id, Role_id)
         VALUES ($user_id, $role_id)";

if (!$conn->query($sql2)) {
    echo json_encode([
        'status' => 'error',
        'message' => $conn->error
    ]);
    exit;
}

echo json_encode(['status' => 'success']);