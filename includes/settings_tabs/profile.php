<?php
session_start();
require_once "../../config/db.php";
require_once "../../includes/current_term.php";

$parts = explode(' ', $_SESSION['user']['name']);
$first_name = $parts[0] ?? '';
$last_name = $parts[count($parts)-1] ?? '';

?>

<h2 class="text-lg font-semibold mb-4">Profile Information</h2>

<form method="POST" action="update_profile.php" class="space-y-4">
    <!-- NAME SECTION -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">First Name</p>
            <input type="text"
                name="first_name"
                value="<?= $first_name ?>"
                class="border rounded-lg px-3 py-2 w-full">
        </div>

        <div>
            <p class="text-sm text-gray-500">Last Name</p>
            <input type="text"
                name="last_name"
                    value="<?= $last_name ?>"
                class="border rounded-lg px-3 py-2 w-full">
        </div>
    </div>

    <!-- ACCOUNT SECURITY SECTION -->
    <div class="space-y-4">

        <h3 class="text-md font-semibold text-gray-700">Account Security</h3>

        <div>
            <p class="text-sm text-gray-500">Email</p>
            <div class="flex items-center gap-2">
                <input type="email"
                    id="emailInput"
                    name="email"
                    value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>"
                    class="border rounded-lg px-3 py-2 w-full bg-gray-100 transition"
                    readonly>

                <button type="button"
                        id="editEmailBtn"
                        class="px-3 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300 whitespace-nowrap">
                    Change
                </button>
            </div>
        </div>

        <div>
            <p class="text-sm text-gray-500">New Password</p>
            <input type="password"
                   name="password"
                   placeholder="Enter new password"
                   class="border rounded-lg px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
            <p class="text-xs text-gray-400 mt-1">Leave blank if you don't want to change password</p>
        </div>
    </div>

    <button type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
        Save Changes
    </button>

</form>
<script>
document.addEventListener("DOMContentLoaded", () => {

    const input = document.getElementById("emailInput");
    const btn = document.getElementById("editEmailBtn");

    if (!input || !btn) return;

    btn.addEventListener("click", () => {

        const isReadOnly = input.readOnly;

        input.readOnly = !isReadOnly;

        input.classList.toggle("bg-gray-100");
        input.classList.toggle("bg-white");

        btn.textContent = isReadOnly ? "Lock" : "Change";

        if (!isReadOnly) {
            input.focus();
        }

    });

});
</script>