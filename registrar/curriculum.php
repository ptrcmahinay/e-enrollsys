<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../config/db.php";

$allowed_roles = ['admin', 'registrar'];

if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)) {
    die("Access denied.");
}


$activePage = 'Curriculum Management';
$page_title = $page_title ?? 'Curriculum Management';
// Fetch departments for dropdown
$deptResult = $conn->query("SELECT dept_id, department_code FROM departments ORDER BY department_code");
$deptOptions = '';
while($dept = $deptResult->fetch_assoc()){
    $deptOptions .= "<option value='{$dept['dept_id']}'>{$dept['department_code']}</option>";
}

// Fetch programs with department name
$result = $conn->query("
    SELECT p.programs_id, p.program_code, p.program_name, d.department_code
    FROM programs p
    LEFT JOIN departments d ON p.department_id = d.dept_id
    ORDER BY p.program_code
");

$rows = '';
while ($row = $result->fetch_assoc()) {
    $id   = (int)$row['programs_id'];
    $code = htmlspecialchars($row['program_code']);
    $name = htmlspecialchars($row['program_name']);
    $dept = htmlspecialchars($row['department_code'] ?? '');

    $rows .= "
        <tr>
            <td>
                <a href='curriculum_view.php?program_id=$id' class='text-blue-600 hover:underline'>
                    $code
                </a>
            </td>
            <td>
                <a href='curriculum_view.php?program_id=$id' class='hover:underline'>
                    $name
                </a>
            </td>
            <td>
                <span class='hover:underline'>$dept</span>
            </td>
        </tr>
    ";
}

$main_content = <<<'HTML'
<div class="w-full">
    <!-- PAGE HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Curriculum Management</h1>
        </div>
        <button id="addProgramBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <span class="material-icons text-sm">add</span>
            Add Program
        </button>
    </div>

    <!-- PROGRAM LIST -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <table id="curriculumTable" class="display w-full text-sm table-auto border mb-4">
            <thead>
                <tr class="bg-blue-100 text-blue-600">
                    <th class="text-left px-4 py-3">Program Code</th>
                    <th class="text-left px-4 py-3">Program Name</th>
                    <th class="text-left px-4 py-3">Department</th>
                </tr>
            </thead>
            <tbody>
HTML;

$main_content .= $rows;

$main_content .= <<<'HTML'
            </tbody>
        </table>
    </div>

    <!-- ADD PROGRAM MODAL -->
    <div id="addProgramModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg w-96 p-6 relative">
            <h2 class="text-xl font-semibold mb-4">Add New Program</h2>
            <form id="addProgramForm" method="POST">
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Program Code</label>
                    <input type="text" name="program_code" required class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Program Name</label>
                    <input type="text" name="program_name" required class="w-full border rounded px-3 py-2">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-1">Department</label>
                    <select name="department_id" required class="w-full border rounded px-3 py-2">
                        <option value="">Select Department</option>
HTML;

$main_content .= $deptOptions;

$main_content .= <<<'HTML'
                    </select>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" id="closeModal" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-green-700">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DATA TABLES CSS/JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#curriculumTable').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 10,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search programs..."
        }
    });

    // Modal open/close
    $('#addProgramBtn').click(function() { $('#addProgramModal').removeClass('hidden'); });
    $('#closeModal').click(function() { $('#addProgramModal').addClass('hidden'); });
    $('#addProgramModal').click(function(e){ if(e.target==this) $(this).addClass('hidden'); });

    // AJAX submit
    $('#addProgramForm').submit(function(e){
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: 'add_program.php',
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(res){
                if(res.status==='success'){
                    table.row.add([
                        `<a href='curriculum_view.php?program_id=${res.program.id}' class='text-blue-600 hover:underline'>${res.program.program_code}</a>`,
                        `<a href='curriculum_view.php?program_id=${res.program.id}' class='hover:underline'>${res.program.program_name}</a>`,
                        `<span>${res.program.department}</span>`
                    ]).draw(false);
                    $('#addProgramModal').addClass('hidden');
                    form[0].reset();
                } else {
                    alert(res.message||'Error adding program');
                }
            },
            error: function(xhr){
                console.error(xhr);
                alert(xhr.responseJSON?.message||'Server error');
            }
        });
    });
});
</script>
HTML;

include "../includes/template.php";
