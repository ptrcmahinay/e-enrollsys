<?php
require_once "../config/db.php";

if(!isset($_SESSION['role'])) die("Access not.");

$isAdmin = in_array($_SESSION['role'], ['registrar', 'admin']);

// Fetch current active term
$termRes = $conn->query("SELECT * FROM academic_term WHERE is_active = 1 LIMIT 1");
$term = $termRes->fetch_assoc() ?? ['school_year'=>'', 'semester'=>'1'];

if($isAdmin && $_SERVER['REQUEST_METHOD']=='POST'){
    $school_year = $_POST['school_year'];
    $semester = $_POST['semester'];

    // Set all terms to inactive
    $conn->query("UPDATE academic_term SET is_active = 0");
    // Insert new active term
    $conn->query("INSERT INTO academic_term (school_year, semester, is_active) VALUES ('$school_year', '$semester', 1)");
    
    header("Location: settings.php?success=1");
    exit;
}
?>
<form method="POST">
    <label>Academic Year</label>
    <input type="text" name="school_year" value="<?= htmlspecialchars($term['school_year']) ?>" <?= $isAdmin?'':'readonly' ?>>
    <label>Semester</label>
    <select name="semester" <?= $isAdmin?'':'disabled' ?>>
        <option value="1" <?= $term['semester']=='1'?'selected':'' ?>>1st Semester</option>
        <option value="2" <?= $term['semester']=='2'?'selected':'' ?>>2nd Semester</option>
        <option value="MidYear" <?= $term['semester']=='MidYear'?'selected':'' ?>>Mid Year</option>
    </select>
    <?php if($isAdmin): ?>
    <button type="submit">Set Term</button>
    <?php endif; ?>
</form>
