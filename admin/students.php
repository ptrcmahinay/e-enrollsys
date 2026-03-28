<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] !== 'admin') die("Access denied");

$page_title = "Student Management";
$activePage = "Student Management";
$activeSubPage = $activeSubPage ?? 'Student Management'; 
// Fetch students with program and section
$result = $conn->query("
    SELECT s.Id, s.Student_number, p.Program_code, sec.Section_name, sec.Year_level, s.Status
    FROM Students s
    LEFT JOIN Programs p ON s.Program_id = p.Programs_id
    LEFT JOIN Sections sec ON s.Section_id = sec.Id
    ORDER BY s.Student_number
");


$rows = '';
while ($row = $result->fetch_assoc()) {
    $statusText = $row['Status'] ? 'Active' : 'Inactive';
    $rows .= "
        <tr>
            <td>{$row['Student_number']}</td>
            <td>{$row['Program_code']}</td>
            <td>{$row['Year_level']}</td>
            <td>{$row['Section_name']}</td>
            <td>{$statusText}</td>
            <td>
                <button class='edit-btn text-blue-600' data-id='{$row['Id']}'>Edit</button>
            </td>
        </tr>
    ";
}


// Fetch programs
$progResult = $conn->query("SELECT Programs_id, Program_code FROM Programs ORDER BY Program_code");
$progOptions = '';
while($prog = $progResult->fetch_assoc()){
    $progOptions .= "<option value='{$prog['Programs_id']}'>{$prog['Program_code']}</option>";
}

// Fetch sections
$secResult = $conn->query("SELECT Id, Section_name FROM Sections ORDER BY Section_name");
$secOptions = '';
while($sec = $secResult->fetch_assoc()){
    $secOptions .= "<option value='{$sec['Id']}'>{$sec['Section_name']}</option>";
}

ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Student Management</h1>
    <button id="addStudentBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-icons">add</span>
        Add Student
    </button>
</div>

<div class="bg-white rounded-xl shadow p-4">
    <table id="studentsTable" class="display w-full">
        <thead>
            <tr>
                <th>Student Number</th>
                <th>Program</th>
                <th>Year Level</th>
                <th>Section</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?= $rows ?>
        </tbody>
    </table>
</div>

<!-- ADD STUDENT MODAL -->
<div id="addStudentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg w-96 p-6">
        <h2 class="text-lg font-semibold mb-4">Add Student</h2>
        <form id="addStudentForm">
            <div class="mb-3">
                <label class="block font-medium mb-1">Student Number</label>
                <input type="text" name="Student_number" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Program</label>
                <select name="Program_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Program</option>
                    <?= $progOptions ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Year Level</label>
                <input type="number" name="Year_level" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Section</label>
                <select name="Section_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Section</option>
                    <?= $secOptions ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Status</label>
                <select name="Status" class="w-full border rounded px-3 py-2" required>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeStudentModal" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
    $('#studentsTable').DataTable();

    $('#addStudentBtn').click(() => $('#addStudentModal').removeClass('hidden'));
    $('#closeStudentModal').click(() => $('#addStudentModal').addClass('hidden'));

    $('#addStudentForm').submit(function(e){
        e.preventDefault();
        $.post('student_create.php', $(this).serialize(), function(res){
            if(res.status==='success'){
                location.reload();
            } else {
                alert(res.message || 'Error adding student');
            }
        }, 'json');
    });
});
</script>

<?php
$main_content = ob_get_clean();
include "../includes/template.php";
