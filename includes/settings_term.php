<?php
session_start();
require_once "../config/db.php";
require_once "../includes/current_term.php";

// Only admin/registrar
$role = $_SESSION['user']['role'] ?? '';
if (!in_array($role, ['admin', 'registrar'])) {
    die("Access denied.");
}

$page_title = "Manage Academic Term";
$activePage = 'Settings';
$username = $_SESSION['username'] ?? '';

// Fetch all Academic Years
$ay_result = $conn->query("SELECT * FROM academic_years ORDER BY start_date DESC");
$academicYears = $ay_result ? $ay_result->fetch_all(MYSQLI_ASSOC) : [];

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ----- Create new Academic Year -----
    if (isset($_POST['create_ay'])) {
        $start_date = $_POST['new_start_date'] ?? '';
        $end_date   = $_POST['new_end_date'] ?? '';

        if ($start_date && $end_date) {
            $year_label = date('Y', strtotime($start_date)) . '-' . date('Y', strtotime($end_date));

            // Deactivate all other AYs
            $conn->query("UPDATE academic_years SET is_active = 0");

            // Insert new AY as active
            $stmt = $conn->prepare("INSERT INTO academic_years (year_label, start_date, end_date, is_active) VALUES (?, ?, ?, 1)");
            $stmt->bind_param("sss", $year_label, $start_date, $end_date);
            $stmt->execute();

            header("Location: settings_term.php");
            exit;
        } else {
            $error = "Please fill both dates for the Academic Year.";
        }
    }

    // ----- Create new Term -----
    if (isset($_POST['create_term'])) {
        $academic_year_id = $_POST['academic_year_id'] ?? '';
        $semester         = $_POST['semester'] ?? '';
        $start_date       = $_POST['start_date'] ?? '';
        $end_date         = $_POST['end_date'] ?? '';

        if ($academic_year_id && $semester && $start_date && $end_date) {

            // Check for overlap in the same AY
            $stmt = $conn->prepare("SELECT id FROM academic_terms WHERE academic_year_id = ? AND start_date <= ? AND end_date >= ?");
            $stmt->bind_param("iss", $academic_year_id, $end_date, $start_date);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "This semester overlaps with another term in the same Academic Year.";
            } else {
                // Deactivate other terms for this AY
                $stmt2 = $conn->prepare("UPDATE academic_terms SET is_active = 0 WHERE academic_year_id = ?");
                $stmt2->bind_param("i", $academic_year_id);
                $stmt2->execute();

                // Insert new term
                $stmt3 = $conn->prepare("INSERT INTO academic_terms (academic_year_id, semester, start_date, end_date, is_active) VALUES (?, ?, ?, ?, 1)");
                $stmt3->bind_param("isss", $academic_year_id, $semester, $start_date, $end_date);
                $stmt3->execute();

                header("Location: settings_term.php");
                exit;
            }

        } else {
            $error = "All fields are required for creating a term.";
        }
    }
}

// Page content
ob_start();
?>

<div class="w-full">
    <!-- PAGE HEADER -->
    <div class="flex justify-between items-center mb-6">
        <a href="settings.php" class="flex items-center text-gray-600 hover:text-gray-800">
            <span class="material-symbols-outlined">arrow_back</span> Back
        </a>
        <h1 class="text-2xl font-semibold text-gray-800">Manage Academic Term</h1>
        <button id="toggleNewAY" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">add</span>
            Add Academic Year
        </button>
    </div>

    <?php if (!empty($error)): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- NEW ACADEMIC YEAR MODAL -->
    <div id="newAYForm" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Add New Academic Year</h2>
            <form method="POST">
                <div class="mb-3">
                    <label class="block mb-1 font-medium">Start Date</label>
                    <input type="date" name="new_start_date" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="mb-3">
                    <label class="block mb-1 font-medium">End Date</label>
                    <input type="date" name="new_end_date" class="w-full border rounded px-3 py-2" required>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="cancelNewAY" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                    <button type="submit" name="create_ay" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ADD TERM FORM -->
    <form method="POST" class="bg-white p-6 rounded-lg shadow mb-6 max-w-md">
        <div class="mb-3">
            <label class="block mb-1 font-medium">Academic Year</label>
            <select name="academic_year_id" required class="w-full border rounded px-3 py-2">
                <option value="">Select Academic Year</option>
                <?php foreach ($academicYears as $ay): ?>
                    <option value="<?= $ay['id'] ?>">
                        <?= htmlspecialchars($ay['year_label']) ?> (<?= date('M', strtotime($ay['start_date'])) ?> - <?= date('M', strtotime($ay['end_date'])) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-medium">Semester</label>
            <select name="semester" required class="w-full border rounded px-3 py-2">
                <option value="1">1st Semester</option>
                <option value="2">2nd Semester</option>
                <option value="mid">Midyear</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-medium">Start Date</label>
            <input type="date" name="start_date" required class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-medium">End Date</label>
            <input type="date" name="end_date" required class="w-full border rounded px-3 py-2">
        </div>

        <button name="create_term" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Term</button>
    </form>

    <!-- TERMS TABLE -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <table id="termsTable" class="display w-full text-sm table-auto border mb-4">
            <thead class="bg-blue-100 text-blue-600">
                <tr>
                    <th>Academic Year</th>
                    <th>Semester</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $semester_labels = ['1'=>'1st Semester','2'=>'2nd Semester','mid'=>'Midyear'];
                foreach ($academicYears as $ay):
                    $stmt = $conn->prepare("SELECT * FROM academic_terms WHERE academic_year_id = ? ORDER BY start_date");
                    $stmt->bind_param("i", $ay['id']);
                    $stmt->execute();
                    $terms = $stmt->get_result();
                    while($term = $terms->fetch_assoc()):
                ?>
                <tr>
                    <td><?= htmlspecialchars($ay['year_label']) ?> (<?= date('M', strtotime($ay['start_date'])) ?> - <?= date('M', strtotime($ay['end_date'])) ?>)</td>
                    <td><?= $semester_labels[$term['semester']] ?></td>
                    <td><?= date('M d, Y', strtotime($term['start_date'])) ?></td>
                    <td><?= date('M d, Y', strtotime($term['end_date'])) ?></td>
                    <td><?= $term['is_active'] ? '<span class="text-green-600 font-semibold">Active</span>' : '<span class="text-gray-600">Inactive</span>' ?></td>
                </tr>
                <?php endwhile; endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- DATA TABLES CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#termsTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        language: { search: "_INPUT_", searchPlaceholder: "Search terms..." },
    });

    // Toggle new Academic Year modal
    $('#toggleNewAY').click(function(){ $('#newAYForm').removeClass('hidden'); });
    $('#cancelNewAY').click(function(){ $('#newAYForm').addClass('hidden'); });
    $('#newAYForm').click(function(e){ if(e.target==this) $(this).addClass('hidden'); });
});
</script>



<?php
$main_content = ob_get_clean();
include "../includes/template.php";
?>
