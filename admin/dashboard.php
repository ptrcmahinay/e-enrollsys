<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$activePage = 'Dashboard';

$page_title = 'Dashboard';
$main_content = '
<h1 class="text-3xl font-bold mb-4">Dashboard Overview</h1>
<p>"Welcome back! Here/s what/s happening with your business today. </p>
';



// Include template
include "../includes/template.php";