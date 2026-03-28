<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$activePage = 'Dashboard';

$main_content = '
<h1 class="text-3xl font-bold mb-4">Registrar Dashboard</h1>
<p>Welcome to your portal. Here you can view your enrolled subjects and grades.</p>
';



// Include template
include "../includes/template.php";