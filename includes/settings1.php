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
<!-- Breadcrumbs -->
<nav class="text-sm text-gray-500 mb-4 flex items-center gap-2">
    <a href="dashboard.php" class="flex items-center gap-1 hover:text-gray-700">
        <span class="material-symbols-outlined text-base">home</span>
    </a>
    <span>/</span>
    <span class="text-gray-900 font-medium"><?= $page_title ?></span>
</nav>

<div class="mb-6">
    <h1 class="text-3xl font-bold text-secondary-900">Settings</h1>
    <p class="text-secondary-600">
        Manage your account preferences
    </p>
</div>
<div class="flex gap-6">

    <!-- SIDEBAR (SMALL CARD) -->
    <div class="w-64 bg-white rounded-xl shadow p-4 h-fit">

        <h2 class="text-sm font-semibold text-gray-500 mb-3">SETTINGS</h2>

        <div class="space-y-1">

            <!-- Profile -->
            <button onclick="loadTab('profile')"
                class="tab-btn w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100">
                <span class="material-symbols-outlined text-gray-600">person</span>
                <span>Profile</span>
            </button>

            <!-- Academic -->
            <?php if (in_array($_SESSION['user']['role'] ?? '', ['admin','registrar'])): ?>
            <button onclick="loadTab('academic')"
                class="tab-btn w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100">
                <span class="material-symbols-outlined text-gray-600">school</span>
                <span>Academic</span>
            </button>
            <?php endif; ?>
            
            <button onclick="loadTab('profile')"
                class="tab-btn w-full flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-gray-100">
                <span class="material-symbols-outlined text-gray-600">notifications</span>
                <span>Notifications</span>
            </button>

        </div>
    </div>

    <!-- DYNAMIC CONTENT -->
    <div class="flex-1 bg-white rounded-xl shadow p-6" id="settingsContent">
        <!-- Default load -->
        Loading...
    </div>

</div>

<script>
function loadTab(tab) {
    const container = document.getElementById("settingsContent");

    container.innerHTML = "Loading...";

    fetch("../includes/settings_tabs/" + tab + ".php") // ✅ FIXED
        .then(res => res.text())
        .then(data => {
            container.innerHTML = data;
        });

    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-gray-100', 'font-medium');
    });

    event.currentTarget.classList.add('bg-gray-100', 'font-medium');
}

window.onload = () => loadTab('profile');
</script>

<?php
$main_content = ob_get_clean();
include "../includes/template.php";