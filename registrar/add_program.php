<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

require_once "../config/db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['role']) || $_SESSION['role']!=='registrar'){
    http_response_code(403);
    echo json_encode(['status'=>'error','message'=>'Access denied']);
    exit;
}

$program_code = trim($_POST['program_code'] ?? '');
$program_name = trim($_POST['program_name'] ?? '');
$department_id = (int)($_POST['department_id'] ?? 0);

if(!$program_code || !$program_name || $department_id<=0){
    http_response_code(400);
    echo json_encode(['status'=>'error','message'=>'All fields are required']);
    exit;
}

// Insert program
$stmt = $conn->prepare("INSERT INTO programs (program_code, program_name, department_id) VALUES (?, ?, ?)");
$stmt->bind_param("ssi", $program_code, $program_name, $department_id);

if($stmt->execute()){
    // Get department name
    $deptStmt = $conn->prepare("SELECT department_code FROM departments WHERE dept_id=?");
    $deptStmt->bind_param("i",$department_id);
    $deptStmt->execute();
    $deptStmt->bind_result($department_code);
    $deptStmt->fetch();
    $deptStmt->close();

    echo json_encode([
        'status'=>'success',
        'program'=>[
            'id'=>$stmt->insert_id,
            'program_code'=>$program_code,
            'program_name'=>$program_name,
            'department'=>$department_code
        ]
    ]);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Database error']);
}

$stmt->close();
$conn->close();
