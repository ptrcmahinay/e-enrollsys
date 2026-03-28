<?php
if(session_status() === PHP_SESSION_NONE) session_start();
require_once "../config/db.php";

$activePage = 'Settings';

// Only Registrar
if(!isset($_SESSION['role']) || $_SESSION['role']!=='registrar'){
    die("Access denied.");
}

// Handle form submission
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $school_year = $_POST['school_year'];
    $semester = $_POST['semester'];

    // Set all other terms inactive
    $conn->query("UPDATE academic_terms SET is_active = 0");

    // Insert or update active term
    $stmt = $conn->prepare("INSERT INTO academic_terms (school_year, semester, is_active) VALUES (?,?,1)");
    $stmt->bind_param("ss", $school_year, $semester);
    $stmt->execute();
    $stmt->close();

    $success = "Active academic term updated!";
}

// Get current active term
$currentTerm = $conn->query("SELECT * FROM academic_terms WHERE is_active = 1")->fetch_assoc();

// Get all terms for history
$termsResult = $conn->query("SELECT * FROM academic_terms ORDER BY created_at DESC");

$main_content = <<<HTML
<div class="px-4 md:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-4">Academic Term Settings</h1>

    <!-- Active Term Info -->
HTML;

if($currentTerm){
    $main_content .= "<p class='mb-4'>Current Active Term: <span class='font-semibold'>{$currentTerm['school_year']} - ";
    $semText = ($currentTerm['semester']=='1')?'1st Semester':($currentTerm['semester']=='2')?'2nd Semester':'Midyear';
    $main_content .= "{$semText}</span></p>";
} else {
    $main_content .= "<p class='mb-4 text-red-500'>No active term set.</p>";
}

// Success message
if(!empty($success)){
    $main_content .= "<p class='mb-4 text-green-600'>$success</p>";
}

// Form
$main_content .= <<<HTML
<form method="POST" class="mb-6 bg-white p-4 rounded shadow-sm w-full md:w-1/2">
    <div class="mb-3">
        <label class="block font-medium mb-1">School Year</label>
        <input type="text" name="school_year" placeholder="e.g., 2025-2026" required
            class="w-full border px-3 py-2 rounded">
    </div>
    <div class="mb-3">
        <label class="block font-medium mb-1">Semester</label>
        <select name="semester" required class="w-full border px-3 py-2 rounded">
            <option value="">Select Semester</option>
            <option value="1">1st Semester</option>
            <option value="2">2nd Semester</option>
            <option value="Mid">Midyear</option>
        </select>
    </div>
    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Set Active</button>
</form>

<h2 class="text-xl font-semibold mb-2">Term History</h2>
<div class="overflow-x-auto bg-white rounded shadow-sm">
    <table class="w-full text-sm table-auto border">
        <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="px-4 py-2">School Year</th>
                <th class="px-4 py-2">Semester</th>
                <th class="px-4 py-2">Active</th>
                <th class="px-4 py-2">Created At</th>
            </tr>
        </thead>
        <tbody>
HTML;

while($term = $termsResult->fetch_assoc()){
    $semText = ($term['semester']=='1')?'1st Semester':($term['semester']=='2')?'2nd Semester':'Midyear';
    $activeText = $term['is_active'] ? '✅' : '';
    $main_content .= "<tr class='border-t'>
        <td class='px-4 py-2'>{$term['school_year']}</td>
        <td class='px-4 py-2'>{$semText}</td>
        <td class='px-4 py-2 text-center'>{$activeText}</td>
        <td class='px-4 py-2'>{$term['created_at']}</td>
    </tr>";
}

$main_content .= "</tbody></table></div></div>";

include "../includes/template.php";
?>
