<?php
session_start();
require_once "../config/db.php";

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

ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold">User Management</h1>
    <button id="addUserBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2">
        <span class="material-icons">add</span>
        Add User
    </button>
</div>

<div class="bg-white rounded-xl shadow p-4">
    <table id="usersTable" class="display w-full">
        <thead>
            <tr>
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

<!-- ADD USER MODAL -->
<div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg w-96 p-6">
        <h2 class="text-lg font-semibold mb-4">Create User</h2>

        <form id="addUserForm">
            <input name="username" required placeholder="Username"
                   class="w-full border rounded px-3 py-2 mb-3">

            <input name="email" type="email" required placeholder="Email"
                   class="w-full border rounded px-3 py-2 mb-3">

            <input name="password" type="password" required placeholder="Password"
                   class="w-full border rounded px-3 py-2 mb-3">

            <select name="role_id" required
                    class="w-full border rounded px-3 py-2 mb-4">
                <option value="">Select Role</option>
                <?= $roleOptions ?>
            </select>

            <div class="flex justify-end gap-2">
                <button type="button" id="closeModal"
                        class="border px-4 py-2 rounded">
                    Cancel
                </button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    Create
                </button>
            </div>
        </form>
    </div>
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

</script>

<?php
$main_content = ob_get_clean();
include "../includes/template.php";
