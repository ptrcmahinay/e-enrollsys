<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Access denied']);
    exit;
}

$Users_id   = $_POST['Users_id'];
$full_name  = $_POST['full_name'];
$Email      = $_POST['Email'];
$Role_id    = $_POST['Role_id'];
$Dept_id    = $_POST['Dept_id'];

$stmt = $conn->prepare("
    INSERT INTO Staff (Users_id, full_name, Email, Role_id, Dept_id, Created_at)
    VALUES (?, ?, ?, ?, ?, NOW())
");
$stmt->bind_param("issii", $Users_id, $full_name, $Email, $Role_id, $Dept_id);

if($stmt->execute()){
    echo json_encode(['status'=>'success']);
} else {
    echo json_encode(['status'=>'error','message'=>'Database error']);
}
