<?php
session_start();
require_once "../../config/db.php";
require_once "../../includes/current_term.php";

$role = $_SESSION['role'] ?? '';
$username = $_SESSION['username'] ?? '';

$semester_labels = [
    '1'   => 'First Semester',
    '2'   => 'Second Semester',
    'mid' => 'Midyear'
];

$semester_text = $semester_labels[$current_term['semester'] ?? ''] ?? '';

// Check if any academic year exists
$ay_result = $conn->query("SELECT id FROM academic_years LIMIT 1");
$ay_exists = $ay_result->num_rows > 0;

?>

<h2 class="text-lg font-semibold mb-4">Academic Term</h2>

<p class="text-gray-600 mb-4">Manage academic year and semester</p>

<p class="font-medium">
    <?php if ($current_term): ?>
        A.Y. <?= $current_term['year_label'] ?> • <?= $semester_text ?>
    <?php else: ?>
        No active term
    <?php endif; ?>
</p>

<a href="settings_term.php" class="text-blue-600 mt-4 inline-block">
    Manage →
</a>

