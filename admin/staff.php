<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] !== 'admin') die("Access denied");

$page_title = "Staff Management";
$activePage = "Staff Management";

// Fetch staff list
$result = $conn->query("
    SELECT s.Staff_id, s.full_name, s.Email, d.Department_code, r.Role_name, u.Username
    FROM Staff s
    LEFT JOIN Departments d ON s.Dept_id = d.Dept_id
    LEFT JOIN Roles r ON s.Role_id = r.Roles_id
    LEFT JOIN Users u ON s.Users_id = u.Users_id
    ORDER BY s.full_name
");

$rows = '';
while ($row = $result->fetch_assoc()) {
    $rows .= "
        <tr>
            <td>{$row['full_name']}</td>
            <td>{$row['Email']}</td>
            <td>{$row['Username']}</td>
            <td>{$row['Role_name']}</td>
            <td>{$row['Department_code']}</td>
            <td>
                <button class='edit-btn text-blue-600' data-id='{$row['Staff_id']}'>Edit</button>
            </td>
        </tr>
    ";
}

// Fetch departments
$deptResult = $conn->query("SELECT Dept_id, Department_code FROM Departments ORDER BY Department_code");
$deptOptions = '';
while($dept = $deptResult->fetch_assoc()){
    $deptOptions .= "<option value='{$dept['Dept_id']}'>{$dept['Department_code']}</option>";
}

// Fetch roles
$rolesResult = $conn->query("SELECT Roles_id, Role_name FROM Roles");
$roleOptions = '';
while($role = $rolesResult->fetch_assoc()){
    $roleOptions .= "<option value='{$role['Roles_id']}'>{$role['Role_name']}</option>";
}

// Fetch users (only users without staff yet)
$userResult = $conn->query("
    SELECT u.Users_id, u.Username 
    FROM Users u 
    LEFT JOIN Staff s ON u.Users_id = s.Users_id
    WHERE s.Users_id IS NULL
");
$userOptions = '';
while($user = $userResult->fetch_assoc()){
    $userOptions .= "<option value='{$user['Users_id']}'>{$user['Username']}</option>";
}

ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">Staff Management</h1>
    <button id="addStaffBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-icons">add</span>
        Add Staff
    </button>
</div>

<div class="bg-white rounded-xl shadow p-4">
    <table id="staffTable" class="display w-full">
        <thead>
            <tr>
                <th>Full Name</th>
                <th>Email</th>
                <th>Username</th>
                <th>Role</th>
                <th>Department</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?= $rows ?>
        </tbody>
    </table>
</div>

<!-- ADD STAFF MODAL -->
<div id="addStaffModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg w-96 p-6">
        <h2 class="text-lg font-semibold mb-4">Add Staff</h2>
        <form id="addStaffForm">
            <div class="mb-3">
                <label class="block font-medium mb-1">Link User Account</label>
                <select name="Users_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select User</option>
                    <?= $userOptions ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Full Name</label>
                <input type="text" name="full_name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Email</label>
                <input type="email" name="Email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Role</label>
                <select name="Role_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Role</option>
                    <?= $roleOptions ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Department</label>
                <select name="Dept_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">Select Department</option>
                    <?= $deptOptions ?>
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeStaffModal" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
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
    $('#staffTable').DataTable();

    $('#addStaffBtn').click(() => $('#addStaffModal').removeClass('hidden'));
    $('#closeStaffModal').click(() => $('#addStaffModal').addClass('hidden'));

    $('#addStaffForm').submit(function(e){
        e.preventDefault();
        $.post('staff_create.php', $(this).serialize(), function(res){
            if(res.status==='success'){
                location.reload();
            } else {
                alert(res.message || 'Error adding staff');
            }
        }, 'json');
    });
});
</script>

<?php
$main_content = ob_get_clean();
include "../includes/template.php";
