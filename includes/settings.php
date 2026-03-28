<?php
session_start();
require_once "../config/db.php";
require_once "../includes/current_term.php";

$page_title = "Settings";
$activePage = 'Settings';
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

// Capture main content
ob_start();
?>

<h1 class="text-2xl font-semibold mb-6">Settings</h1>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Academic Term / Year Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="font-semibold mb-2">Academic Term / Year</h2>

        <p class="text-gray-600 mb-3">
            <?php if ($current_term): ?>
                A.Y. <?= htmlspecialchars($current_term['year_label']) ?> • <?= $semester_text ?>
            <?php else: ?>
                No active term set
            <?php endif; ?>
        </p>

        <?php if (in_array($role, ['admin', 'registrar'])): ?>
            <a href="settings_term.php" class="text-blue-600 font-medium">
                <?= $ay_exists ? 'Manage Academic Year / Term →' : 'Create Academic Year →' ?>
            </a>
        <?php endif; ?>
    </div>

    <!-- User Card -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="font-semibold mb-2">User Information</h2>
        <p class="text-gray-600 mb-3">Update your name or password</p>
        <a href="settings_user.php" class="text-blue-600 font-medium">Edit Profile →</a>
    </div>

</div>

<?php
$main_content = ob_get_clean();
include "../includes/template.php";
