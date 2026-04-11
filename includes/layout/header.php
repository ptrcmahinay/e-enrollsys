<?php
$semester_labels = [
    '1'   => 'First Semester',
    '2'   => 'Second Semester',
    'mid' => 'Midyear'
];

$semester_text = $semester_labels[$current_term['semester'] ?? ''] ?? '';
?>

<link rel="stylesheet" href="../layout/sidebar.css">

<header class="right-0 h-16 bg-white border-b flex items-center justify-between px-6 header">

  <div class="flex items-center gap-4">

  <span class="material-symbols-outlined cursor-pointer" id="menuToggle">
    menu
  </span>

  <div class="text-sm text-gray-600">
  <?= $current_term
      ? "A.Y. {$current_term['year_label']} • {$semester_text}"
      : "No active term"
  ?>
  </div>

  </div>

  <div class="flex items-center gap-4">

<span class="material-symbols-outlined">
notifications
</span>

  <div class="flex items-center gap-2">
  <span class="material-symbols-outlined">account_circle</span>

  <div>
  <div class="font-medium"><?= htmlspecialchars($username) ?></div>
  <!-- <div class="text-xs text-gray-500"></div> -->
  </div>

  </div>

  </div>

</header>