<?php
// Start session safely
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$activePage = 'Dashboard';

require_once "../config/db.php";

// Protect page: only students can access
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    die("Access denied.");
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch student info including section and adviser
$sql = "
    SELECT 
        s.student_number,
        s.full_name,
        s.status,
        u.email,
        p.program_name,
        CONCAT(sec.year_level, ' - ', sec.section_name) AS section,
        adv.full_name AS adviser_name
    FROM users u
    JOIN students s ON u.student_id = s.id
    LEFT JOIN programs p ON s.program_id = p.programs_id
    LEFT JOIN sections sec ON s.section_id = sec.id
    LEFT JOIN staff adv ON sec.adviser_id = adv.staff_id
    WHERE u.users_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die("Student information not found.");
}
$student_number = htmlspecialchars($student['student_number']);
$full_name      = htmlspecialchars($student['full_name']);
$status         = htmlspecialchars($student['status']);
$email          = htmlspecialchars($student['email']);
$program        = htmlspecialchars($student['program_name']);
$section        = htmlspecialchars($student['section']);
$adviser        = htmlspecialchars($student['adviser_name'] ?? 'Not assigned');

$main_content = <<<HTML

<section class="bg-[#eaf2fb] p-6 rounded-2xl shadow-md mx-5">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5 mb-5">
        <div>
            <label class="text-sm text-gray-500">Student Number</label>
            <p class="text-base text-[#101820] mt-1">{$student_number}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Full Name</label>
            <p class="text-base text-[#101820] mt-1 font-semibold">{$full_name}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Status</label>
            <p class="text-base text-[#101820] mt-1">{$status}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Email</label>
            <p class="text-base text-[#101820] mt-1">{$email}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        <div>
            <label class="text-sm text-gray-500">Program</label>
            <p class="text-base text-[#101820] mt-1">{$program}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Year / Section</label>
            <p class="text-base text-[#101820] mt-1">{$section}</p>
        </div>

        <div>
            <label class="text-sm text-gray-500">Adviser</label>
            <p class="text-base text-[#101820] mt-1">{$adviser}</p>
        </div>
    </div>

</section>

HTML;


// Include master template
include "../includes/template.php";
