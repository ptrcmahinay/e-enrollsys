<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Access denied']);
    exit;
}

$user_id = $_POST['user_id'];
$new_password = $_POST['new_password'];

if (!$user_id || !$new_password) {
    echo json_encode(['status'=>'error','message'=>'Invalid data']);
    exit;
}

$hashed = password_hash($new_password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE Users SET Password = ? WHERE Users_id = ?");
$stmt->bind_param("si", $hashed, $user_id);
if($stmt->execute()){
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error','message'=>'Database error']);
}
