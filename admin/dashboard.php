<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$activePage = 'Dashboard';

$page_title = 'Dashboard';

require_once "../config/db.php";

// Students (adjust table name if needed)
$totalStudents = $conn->query("
    SELECT COUNT(*) as total FROM students
")->fetch_assoc()['total'] ?? 0;

// Programs
$totalPrograms = $conn->query("
    SELECT COUNT(*) as total FROM programs
")->fetch_assoc()['total'] ?? 0;

// Departments
$totalDepartments = $conn->query("
    SELECT COUNT(*) as total FROM departments
")->fetch_assoc()['total'] ?? 0;


$main_content = <<<HTML
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Dashboard</h1>
    <p class="text-sm text-gray-500">
        Welcome back! Here\'s your system overview.
    </p>
</div>

<!-- KPI CARDS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <!-- Students -->
    <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm">Total Students</p>
            <h2 class="text-2xl font-bold">$totalStudents</h2>
        </div>
        <div class="bg-blue-100 p-3 rounded-full">
            <span class="material-symbols-outlined text-blue-600 text-3xl">
                groups
            </span>
        </div>
    </div>

    <!-- Programs -->
    <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm">Programs</p>
            <h2 class="text-2xl font-bold">$totalPrograms</h2>
        </div>
        <div class="bg-green-100 p-3 rounded-full">
            <span class="material-symbols-outlined text-green-600 text-3xl">
                school
            </span>
        </div>
    </div>

    <!-- Departments -->
    <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm">Departments</p>
            <h2 class="text-2xl font-bold">$totalDepartments</h2>
        </div>
        <div class="bg-purple-100 p-3 rounded-full">
            <span class="material-symbols-outlined text-purple-600 text-3xl">
                account_tree
            </span>
        </div>
    </div>

</div>

<!-- Placeholder Section -->
<div class="bg-white p-6 rounded-xl shadow">
    <h2 class="text-lg font-semibold mb-2">Quick Insights</h2>
    <p class="text-sm text-gray-500">
        Add charts, recent activities, or enrollment trends here.
    </p>
</div>
';
HTML;

// Include template
include "../includes/template.php";