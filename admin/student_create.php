<?php
session_start();
require_once "../config/db.php";

header('Content-Type: application/json');

// Check admin
if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Get POST data
$student_number = $_POST['Student_number'] ?? '';
$full_name      = $_POST['Full_name'] ?? '';
$program_id     = $_POST['Program_id'] ?? '';
$year_level     = $_POST['Year_level'] ?? '';
$section_id     = $_POST['Section_id'] ?? '';
$status         = $_POST['Status'] ?? '';

// Basic validation
if (!$student_number || !$full_name || !$program_id || !$year_level || !$section_id) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

// Insert query
$stmt = $conn->prepare("
    INSERT INTO Students (Student_number, Full_name, Program_id, Section_id, Status)
    VALUES (?, ?, ?, ?, ?)
");

$stmt->bind_param("ssiii", $student_number, $full_name, $program_id, $section_id, $status);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => $stmt->error
    ]);
}

$stmt->close();
$conn->close();