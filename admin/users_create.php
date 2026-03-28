<?php
require_once "../config/db.php";

$username = $_POST['username'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role_id  = $_POST['role_id'];

$conn->query("
    INSERT INTO Users (Username, email, Password, Created_at)
    VALUES ('$username', '$email', '$password', NOW())
");

$user_id = $conn->insert_id;

$conn->query("
    INSERT INTO User_roles (User_id, Role_id)
    VALUES ($user_id, $role_id)
");

echo json_encode(['status'=>'success']);
