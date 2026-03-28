<?php
session_start();
require_once "../config/db.php";

// Only logged-in users
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$page_title = "User Profile";
$activePage = 'Settings';
$user_id = $_SESSION['user_id'];

// Fetch current user info
$stmt = $conn->prepare("SELECT username, fullname, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $fullname = $_POST['fullname'] ?? '';
    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $errors = [];

    // Password validation if changing
    if ($password || $confirm) {
        if ($password !== $confirm) {
            $errors[] = "Passwords do not match.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Password must be at least 6 characters.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        }
    }

    if (empty($errors)) {
        // Update user info
        if (!empty($password)) {
            $stmt = $conn->prepare("UPDATE users SET username=?, fullname=?, email=?, password=? WHERE id=?");
            $stmt->bind_param("ssssi", $username, $fullname, $email, $hashed_password, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET username=?, fullname=?, email=? WHERE id=?");
            $stmt->bind_param("sssi", $username, $fullname, $email, $user_id);
        }
        $stmt->execute();
        $success = "Profile updated successfully.";
        // Refresh user info
        $user['username'] = $username;
        $user['fullname'] = $fullname;
        $user['email'] = $email;
    }
}

// Capture content
ob_start();
?>

<h1 class="text-2xl font-semibold mb-6">Edit Profile</h1>

<?php if (!empty($errors)): ?>
    <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
        <?php foreach ($errors as $err) echo htmlspecialchars($err) . "<br>"; ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        <?= htmlspecialchars($success) ?>
    </div>
<?php endif; ?>

<form method="POST" class="bg-white p-6 rounded-lg shadow max-w-md">
    <div class="mb-4">
        <label class="block font-medium mb-1">Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" class="w-full border rounded px-3 py-2" required>
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Full Name</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($user['fullname']) ?>" class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="w-full border rounded px-3 py-2">
    </div>

    <hr class="my-4">

    <div class="mb-4">
        <label class="block font-medium mb-1">New Password</label>
        <input type="password" name="password" class="w-full border rounded px-3 py-2">
    </div>

    <div class="mb-4">
        <label class="block font-medium mb-1">Confirm Password</label>
        <input type="password" name="confirm_password" class="w-full border rounded px-3 py-2">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Save Changes</button>
</form>

<?php
$main_content = ob_get_clean();
include "../includes/template.php";
