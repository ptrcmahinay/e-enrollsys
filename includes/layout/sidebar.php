<aside class="sidebar" id="sidebar">

<div class="sidebar-header flex items-center justify-between px-4 py-4 sidebar-header">
  <span class="sidebar-text font-semibold">
    E-EnrollSys
  </span>

  <button id="toggleSidebar" class="sidebar-toggle menu-button">
    <span class="material-symbols-outlined sidebar-icon">menu</span>
  </button>

</div>

<nav>
<?php include __DIR__ . "/sidebar_items.php"; ?>
</nav>

<div class="sidebar-footer">
  <a href="../includes/settings1.php" class="menu-item <?= ($activePage === 'Settings') ? 'active' : '' ?>">
      <span class="material-symbols-outlined sidebar-icon">settings</span>
      <span class="sidebar-text">Settings</span>
  </a>

  <a href="../auth/login.php" class="menu-item">
      <span class="material-symbols-outlined sidebar-icon">logout</span>
      <span class="sidebar-text">Logout</span>
  </a>
</div>

</aside>


<script>
document.addEventListener("DOMContentLoaded", () => {
    const toggleBtn = document.getElementById("toggleSidebar");

    // Apply final state
    if (document.documentElement.classList.contains("pre-collapsed")) {
        document.documentElement.classList.add("collapsed");
    }

    // Re-enable animations AFTER first paint
    requestAnimationFrame(() => {
        document.documentElement.classList.remove("pre-collapsed");
    });

    toggleBtn.addEventListener("click", () => {
        document.documentElement.classList.toggle("collapsed");

        localStorage.setItem(
            "sidebar",
            document.documentElement.classList.contains("collapsed")
                ? "collapsed"
                : "expanded"
        );
    });
});
</script>