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

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../includes/style.css?v=999">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=menu" />
</head>
<body>
<div class="layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            E-EnrollSys
        </div>

        <nav class="menu">
            <?php include "sidebar_items.php"; ?>
        </nav>

        <div class="sidebar-footer">
            <a href="../includes/settings.php" class="menu-item <?= ($activePage === 'Settings') ? 'active' : '' ?>">
                <span class="material-icons sidebar-icon">settings</span>
                Settings
            </a>
            <a href="../auth/login.php" class="menu-item">
                <span class="material-icons sidebar-icon">logout</span>
                Logout
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">
            <div class="topbar-left">
                <span class="material-symbols-outlined">
                menu
                </span>
                <div class="term-display">
                    <?= $current_term
                        ? "A.Y. {$current_term['year_label']} • {$semester_text}"
                        : "No active term"
                    ?>
                </div>
            </div>

            <div class="topbar-right">
                <span class="material-icons notification-icon">notifications</span>
                <div class="user-pill">
                    <span class="material-icons user-icon">account_circle</span>
                    <span class="username"><?= htmlspecialchars($username) ?></span>
                    <span class="email"><?= htmlspecialchars($email) ?></span>
                </div>
            </div>
        </header>

        <!-- SCROLLABLE CONTENT -->
        <div class="content-area">
            <?= $main_content ?>
        </div>

    </main>
</div>
</body>
</html>
