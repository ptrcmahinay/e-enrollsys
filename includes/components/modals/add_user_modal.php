<div id="addUserModal"
     class="modal fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-lg w-96 p-6">

        <h2 class="text-lg font-semibold mb-4">Create User</h2>

        <form id="addUserForm">

            <input name="username" class="input">
            <input name="email" class="input">
            <input name="password" class="input">

            <div class="flex justify-end gap-2 mt-4">

                <button type="button"
                        data-close
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