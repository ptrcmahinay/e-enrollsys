<?php
$semester_labels = [
    '1'   => 'First Semester',
    '2'   => 'Second Semester',
    'mid' => 'Midyear'
];

$semester_text = $semester_labels[$current_term['semester'] ?? ''] ?? '';
$email = $user['email'] ?? '';

function getInitials($name) {
    $words = explode(' ', trim($name));
    $initials = '';

    foreach ($words as $w) {
        $initials .= strtoupper($w[0]);
        if (strlen($initials) >= 2) break;
    }

    return $initials ?: 'U';
}
?>

<link rel="stylesheet" href="../layout/sidebar.css">

<header class="right-0 h-16 bg-white border-b flex items-center justify-between px-6 header">

  <div class="flex items-center gap-4">
    <div class="text-sm text-gray-600">
    <?= $current_term
        ? "A.Y. {$current_term['year_label']} • {$semester_text}"
        : "No active term"
    ?>
    </div>
  </div>

  <div class="flex items-center gap-2 hover:rounded-lg">
    <div class="relative flex items-center justify-center w-10 h-10 rounded-lg cursor-pointer hover:bg-gray-100 transition-all duration-200">
      <span class="material-symbols-outlined">
          notifications
      </span>

      <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
    </div>

    <div class="flex items-center gap-2 border-l-3 border-transparent cursor-pointer hover:bg-gray-100 hover:rounded-lg px-3 py-2 transition-all duration-200">
      <?php $initials = getInitials($username); ?>

      <div class="w-9 h-9 rounded-full bg-gray-700 text-white flex items-center justify-center font-semibold text-sm">
          <?= $initials ?>
      </div>

      <div>
        <div class="font-medium text-sm">
          <?= htmlspecialchars($_SESSION['user']['name']) ?>
        </div>

        <div class="text-xs text-gray-500">
          <?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>
        </div>
      </div>

    </div>
  </div>
</header>