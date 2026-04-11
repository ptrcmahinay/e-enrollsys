<?php
session_start();
require_once $_SERVER['DOCUMENT_ROOT'] . "/enrollmentSystem/config/db.php";

if ($_SESSION['role'] !== 'admin') {
    die("Access denied");
}

$page_title = "User Management";
$activePage = "User Management";

// Fetch users
$result = $conn->query("
    SELECT 
        u.Users_id,
        u.Username,
        u.email,
        GROUP_CONCAT(r.Role_name SEPARATOR ', ') AS roles
    FROM Users u
    LEFT JOIN User_roles ur ON u.Users_id = ur.User_id
    LEFT JOIN Roles r ON ur.Role_id = r.Roles_id
    GROUP BY u.Users_id
    ORDER BY u.Username
");

$rows = '';
while ($row = $result->fetch_assoc()) {
    $rows .= "
        <tr>

            <td>{$row['Username']}</td>
            <td>{$row['email']}</td>
            <td>{$row['roles']}</td>
            <td>
                <button class='reset-btn text-blue-600' data-id='{$row['Users_id']}'>
                    Reset Password
                </button>
            </td>
        </tr>
    ";
}

// Fetch roles
$roles = $conn->query("SELECT * FROM Roles");
$roleOptions = '';
while ($r = $roles->fetch_assoc()) {
    $roleOptions .= "<option value='{$r['roles_id']}'>{$r['role_name']}</option>";
}

$totalUsers = $conn->query("SELECT COUNT(*) as total FROM Users")
                   ->fetch_assoc()['total'];

$totalAdmins = $conn->query("
    SELECT COUNT(*) as total
    FROM User_roles ur
    JOIN Roles r ON ur.Role_id = r.Roles_id
    WHERE r.Role_name = 'admin'
")->fetch_assoc()['total'];

$totalStudents = $conn->query("
    SELECT COUNT(*) as total
    FROM User_roles ur
    JOIN Roles r ON ur.Role_id = r.Roles_id
    WHERE r.Role_name = 'student'
")->fetch_assoc()['total'];
$lastMonthUsers = $conn->query("
    SELECT COUNT(*) as total
    FROM Users
    WHERE MONTH(created_at) = MONTH(CURDATE()) - 1
")->fetch_assoc()['total'] ?? 0;

$thisMonthUsers = $totalUsers;

$userGrowth = ($lastMonthUsers > 0)
    ? round((($thisMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1)
    : 0;
ob_start();
?>

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold">User Management</h1>
        <p class="text-sm text-gray-500">
            Manage your users and their accounts
        </p>
    </div>
    <!-- Add User Button -->
    <button id="addUserBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-symbols-outlined">person_add</span>
        Add User
    </button>

    <?php include "add_modal.php"; ?>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    <!-- TOTAL USERS -->
    <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between">

        <div>
            <p class="text-gray-500 text-sm">Total Users</p>
            <h2 class="text-2xl font-bold"><?= $totalUsers ?></h2>

            <p class="text-xs mt-1 <?= $userGrowth >= 0 ? 'text-green-600' : 'text-red-500' ?>">
                <?= $userGrowth >= 0 ? '+' : '' ?><?= $userGrowth ?>% this month
            </p>
        </div>

        <div class="bg-blue-100 p-3 rounded-full">
            <span class="material-symbols-outlined text-blue-600 text-3xl">
                group
            </span>
        </div>

    </div>

    <!-- ADMINS -->
    <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between">

        <div>
            <p class="text-gray-500 text-sm">Admins</p>
            <h2 class="text-2xl font-bold"><?= $totalAdmins ?></h2>
        </div>

        <div class="bg-red-100 p-3 rounded-full">
            <span class="material-symbols-outlined text-red-600 text-3xl">
                shield_person
            </span>
        </div>

    </div>

    <!-- STUDENTS -->
    <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition flex items-center justify-between">

        <div>
            <p class="text-gray-500 text-sm">Students</p>
            <h2 class="text-2xl font-bold"><?= $totalStudents ?></h2>
        </div>

        <div class="bg-green-100 p-3 rounded-full">
            <span class="material-symbols-outlined text-green-600 text-3xl">
                school
            </span>
        </div>

    </div>

</div>
<div class="bg-white rounded-xl shadow p-4">
    <table id="usersTable" class="display w-full">
        <thead>
            <tr>
                <!-- <th>Name</th> -->
                <th>Username</th>
                <th>Email</th>
                <th>Role(s)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?= $rows ?>
        </tbody>
    </table>
</div>


<!-- RESET PASSWORD MODAL -->
<div id="resetPasswordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg w-96 p-6">
        <h2 class="text-lg font-semibold mb-4">Reset Password</h2>
        <form id="resetPasswordForm">
            <input type="hidden" name="user_id" id="resetUserId">
            <div class="mb-3">
                <label class="block font-medium mb-1">New Password</label>
                <input type="password" name="new_password" required
                       class="w-full border rounded px-3 py-2" placeholder="Enter new password">
            </div>
            <div class="mb-3">
                <label class="block font-medium mb-1">Confirm Password</label>
                <input type="password" name="confirm_password" required
                       class="w-full border rounded px-3 py-2" placeholder="Confirm new password">
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closeResetModal" class="px-4 py-2 border rounded hover:bg-gray-100">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Reset</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
    $('#usersTable').DataTable();

    $('#addUserBtn').click(() => $('#addUserModal').removeClass('hidden'));
    $('#closeModal').click(() => $('#addUserModal').addClass('hidden'));

    $('#addUserForm').submit(function(e){
        e.preventDefault();
        $.post('user_create.php', $(this).serialize(), function(){
            location.reload();
        });
    });
});

// Open reset password modal
$('.reset-btn').click(function(){
    const userId = $(this).data('id');
    $('#resetUserId').val(userId);
    $('#resetPasswordModal').removeClass('hidden');
});

// Close modal
$('#closeResetModal').click(function(){
    $('#resetPasswordModal').addClass('hidden');
});

// Submit reset password form
$('#resetPasswordForm').submit(function(e){
    e.preventDefault();
    const form = $(this);
    const newPassword = form.find('input[name="new_password"]').val();
    const confirmPassword = form.find('input[name="confirm_password"]').val();

    if(newPassword !== confirmPassword){
        alert("Passwords do not match!");
        return;
    }

    $.post('user_reset_password.php', form.serialize(), function(res){
        if(res.status === 'success'){
            alert("Password has been reset!");
            $('#resetPasswordModal').addClass('hidden');
            form[0].reset();
        } else {
            alert(res.message || 'Error resetting password');
        }
    }, 'json');
});


// add modal
document.addEventListener("DOMContentLoaded", function () {

    const addBtn = document.getElementById("addUserBtn");
    const modal = document.getElementById("addUserModal");
    const closeBtn = document.getElementById("closeModal");

    addBtn.addEventListener("click", function () {
        modal.classList.remove("hidden");
    });

    closeBtn.addEventListener("click", function () {
        modal.classList.add("hidden");
    });

});
</script>
<?php
$main_content = ob_get_clean();
include "../includes/template.php";