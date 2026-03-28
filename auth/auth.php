<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check permission for page
function require_permission($permission) {
    if (!in_array($permission, $_SESSION['permissions'])) {
        header("HTTP/1.1 403 Forbidden");
        echo "You do not have permission to access this page.";
        exit();
    }
}
?>
