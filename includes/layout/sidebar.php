<aside class="sidebar" id="sidebar">

<div class="sidebar-header flex items-center justify-between px-4 py-4">
  
  <div class="flex items-center gap-2">
    <span class="material-symbols-outlined">
      school
    </span>

    <span class="sidebar-text font-semibold">
      E-EnrollSys
    </span>
  </div>
</div>

<nav>
<?php include __DIR__ . "/sidebar_items.php"; ?>
</nav>

<div class="sidebar-footer">
  <a href="../includes/settings.php" class="menu-item <?= ($activePage === 'Settings') ? 'active' : '' ?>">
      <span class="material-symbols-outlined sidebar-icon">settings</span>
      <span class="sidebar-text">Settings</span>
  </a>

  <a href="../auth/login.php" class="menu-item">
      <span class="material-symbols-outlined sidebar-icon">logout</span>
      <span class="sidebar-text">Logout</span>
  </a>
</div>

</aside>