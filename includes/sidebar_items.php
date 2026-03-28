<?php

$user_role = $user_role ?? ($_SESSION['role'] ?? '');
$activePage = $activePage ?? '';
$sidebar = [];

switch ($user_role) {
    case 'student':
        $sidebar = [
            'Dashboard' => '../student/dashboard.php',
            'Enrolled Subjects' => '../student/subjects.php',
            'Grades' => '../student/grades.php',
            'Checklist' => '../registrar/curriculum_view.php',
            'Online Enrollment' => '../student/enrollment.php'
        ];
        break;

    case 'registrar':
        $sidebar = [
            'Dashboard' => '../registrar/dashboard.php',
            'Curriculum Management' => '../registrar/curriculum.php',
            'Students' => '../registrar/students.php',
            'Enrollment' => '../registrar/enrollment.php',
        ];
        break;

    case 'admin':
        $sidebar = [
            'Dashboard' => '../admin/dashboard.php',
            'User Management' => '../admin/users.php',
            'Staff Management' => '../admin/staff.php',
            'Student Management' => '../admin/students.php',
            'Curriculum Management' => '../registrar/curriculum.php',
        ];
        break;
        
}
?>

<?php foreach ($sidebar as $name => $link): ?>
    <a href="<?= $link ?>"
       class="menu-item <?= ($activePage === $name) ? 'active' : '' ?>">
        <?= $name ?>
    </a>
<?php endforeach; ?>