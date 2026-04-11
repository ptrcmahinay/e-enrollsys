<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/db.php";
require_once "../includes/current_term.php";

$page_title   = $page_title   ?? 'Portal';
$activePage   = $activePage   ?? '';
$main_content = $main_content ?? '';
$username     = $_SESSION['username'] ?? 'User';
$user_role    = $_SESSION['role'] ?? '';

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

    <div class="main">
        <?php include __DIR__ . "/layout/header.php"; ?>

        <div class="content-area">
            <?= $main_content ?>
        </div>
    </div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.getElementById("menuToggle");
    const layout = document.getElementById("layout");

    toggleBtn.addEventListener("click", function () {
        layout.classList.toggle("collapsed");
    });

});
</script>
</body>
</html>

</body>
</html>