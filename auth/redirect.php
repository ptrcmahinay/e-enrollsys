<?php
session_start();

if (!isset($_SESSION['roles'])) {
    header("Location: login.php");
    exit();
}

// Redirect based on roles priority
if (in_array('admin', $_SESSION['roles'])) {
    header("Location: ../admin/dashboard.php");
} elseif (in_array('registrar', $_SESSION['roles'])) {
    header("Location: ../registrar/dashboard.php");
} elseif (in_array('instructor', $_SESSION['roles'])) {
    header("Location: ../instructor/dashboard.php");
} elseif (in_array('student', $_SESSION['roles'])) {
    header("Location: ../student/dashboard.php");
} else {
    header("Location: login.php");
}
exit();
?>
