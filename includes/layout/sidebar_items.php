<?php

$user       = $_SESSION['user'] ?? [];
$user_role  = $user_role ?? ($user['role'] ?? '');
$activePage = $activePage ?? '';
$sidebar = [];

switch ($user_role) {

    case 'student':
        $sidebar = [
            'Dashboard' => ['icon' => 'dashboard', 'link' => '../student/dashboard.php'],
            'Enrolled Subjects' => ['icon' => 'menu_book', 'link' => '../student/subjects.php'],
            'Grades' => ['icon' => 'grading', 'link' => '../student/grades.php'],
            'Checklist' => ['icon' => 'checklist', 'link' => '../registrar/curriculum_view.php'],
            'Enrollment' => ['icon' => 'app_registration', 'link' => '../enrollment/index.php'],
        ];
        break;

    case 'registrar':
        $sidebar = [
            'Dashboard' => ['icon' => 'dashboard', 'link' => '../registrar/dashboard.php'],
            'Curriculum Management' => ['icon' => 'menu_book', 'link' => '../registrar/curriculum.php'],
            'Students' => ['icon' => 'groups', 'link' => '../registrar/students.php'],
            'Enrollment' => ['icon' => 'app_registration', 'link' => '../enrollment/index.php'],
        ];
        break;

    case 'admin':
        $sidebar = [
            'Dashboard' => ['icon' => 'dashboard', 'link' => '../admin/dashboard.php'],
            'User Management' => ['icon' => 'manage_accounts', 'link' => '../admin/users.php'],
            'Staff Management' => ['icon' => 'badge', 'link' => '../admin/staff.php'],
            'Student Management' => ['icon' => 'groups', 'link' => '../admin/students.php'],
            'Curriculum Management' => ['icon' => 'menu_book', 'link' => '../registrar/curriculum.php'],
            'Enrollment' => ['icon' => 'app_registration', 'link' => '../enrollment/index.php'],
        ];
        break;

    case 'adviser':
        $sidebar = [
            'Dashboard' => ['icon' => 'dashboard', 'link' => '../adviser/dashboard.php'],
            'Student Management' => ['icon' => 'groups', 'link' => '../adviser/students.php'],
            'Enrollment' => ['icon' => 'app_registration', 'link' => '../enrollment/index.php'],
        ];
        break;
}

?>

<?php foreach ($sidebar as $name => $item): ?>
<a href="<?= $item['link'] ?>"
   class="menu-item <?= ($activePage === $name) ? 'active' : '' ?>">

   <span class="material-symbols-outlined sidebar-icon">
       <?= $item['icon'] ?>
   </span>

   <span class="sidebar-text text-sm">
       <?= $name ?>
   </span>

</a>
<?php endforeach; ?>

