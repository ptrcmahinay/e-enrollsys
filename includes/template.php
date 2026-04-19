<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once $_SERVER['DOCUMENT_ROOT'] . "/enrollmentSystem/config/db.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/enrollmentSystem/includes/current_term.php";

$sidebarState = $_SESSION['sidebar'] ?? 'expanded';
$page_title   = $page_title   ?? 'Portal';
$activePage   = $activePage   ?? '';
$main_content = $main_content ?? '';
$user         = $_SESSION['user'] ?? [];
$username     = $user['name'] ?? 'User';
$user_role    = $user['role'] ?? '';

$email = ($user_role === 'student')
    ? ($user['student_id'] ?? '')
    : ($_SESSION['email'] ?? '');

$semester_labels = [
    '1'   => 'First Semester',
    '2'   => 'Second Semester',
    'mid' => 'Midyear'
];

$semester_text = $semester_labels[$current_term['semester'] ?? ''] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?= htmlspecialchars($page_title) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="../includes/ui.css">
<link rel="stylesheet" href="../includes/layout/sidebar.css">
</head>
<body class="bg-gray-100">

<div class="layout" id="layout">
    <?php include __DIR__ . "/layout/sidebar.php"; ?>
    <?php include __DIR__ . "/layout/header.php"; ?>
    <div class="main">
        <div class="content-area">
            <?= $main_content ?>
        </div>
    </div>
</div>

</body>
</html>

</body>
</html>
<!--  -->