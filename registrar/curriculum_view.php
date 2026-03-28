<?php
if(session_status()===PHP_SESSION_NONE) session_start();

require_once "../config/db.php";
$activePage = 'Curriculum Management';
$activePage = 'Checklist';
// Role-based access
$allowed_roles = ['student', 'registrar', 'admin', 'instructor'];
if(!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowed_roles)){
    die("Access denied.");
}

$role = $_SESSION['role'];
$is_student = ($role === 'student');
$student_id = $_SESSION['student_id'] ?? null;

// Get program_id
if($is_student && $student_id){
    // Fetch program for student
    $res = $conn->prepare("SELECT program_id FROM students WHERE id = ?");
    $res->bind_param("i", $student_id);
    $res->execute();
    $prog_res = $res->get_result();
    if($prog_res->num_rows === 0) die("Student not found.");
    $program_data = $prog_res->fetch_assoc();
    $program_id = (int)$program_data['program_id'];
} else {
    if(!isset($_GET['program_id'])) die("Program not specified.");
    $program_id = (int)$_GET['program_id'];
}

// Get program name
$programRes = $conn->prepare("SELECT program_name FROM programs WHERE programs_id=?");
$programRes->bind_param("i", $program_id);
$programRes->execute();
$programResult = $programRes->get_result();
if($programResult->num_rows === 0) die("Program not found.");
$program = $programResult->fetch_assoc();

// Fetch curriculum with grades + instructor
$sql = "
SELECT pc.curriculum_id, s.subject_code, s.subject_description, s.units,
       pc.year_level, pc.semester,
       g.grade, st.full_name AS instructor_name
FROM program_curriculum pc
JOIN subjects s ON pc.subject_id = s.subject_id
LEFT JOIN grades g ON g.curriculum_id = pc.curriculum_id AND g.student_id = ?
LEFT JOIN staff st ON g.instructor_id = st.staff_id
WHERE pc.program_id = ?
ORDER BY pc.year_level, pc.semester, s.subject_code
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $student_id, $program_id);
$stmt->execute();
$result = $stmt->get_result();

$subjects = [];
while($row = $result->fetch_assoc()){
    $subjects[] = $row;
}

// Build table
$main_content = '<div class="w-full">
    <div class="flex items-center mb-4 gap-3">
        <a href="curriculum.php" class="flex items-center text-gray-600 hover:text-gray-800">
            <span class="material-icons">arrow_back</span> Back
        </a>
        <h1 class="text-2xl font-bold">'.htmlspecialchars($program['program_name']).'</h1>
    </div>

    <table id="curriculumTable" class="display w-full text-sm table-auto border mb-4">
        <thead>
            <tr class="bg-gray-200 text-gray-700">
                <th>Year Level</th>
                <th>Semester</th>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Units</th>
                <th>Grade</th>
                <th>Instructor</th>';
if(!$is_student){
    $main_content .= '<th>Actions</th>';
}
$main_content .= '</tr></thead><tbody>';

foreach($subjects as $sub){
    $grade      = $sub['grade'] ?? '-';
    $instructor = $sub['instructor_name'] ?? '-';

    $main_content .= '<tr>
        <td>'.$sub['year_level'].'</td>
        <td>'.$sub['semester'].'</td>
        <td>'.$sub['subject_code'].'</td>
        <td>'.$sub['subject_description'].'</td>
        <td>'.$sub['units'].'</td>
        <td>'.$grade.'</td>
        <td>'.$instructor.'</td>';
    if(!$is_student){
        $main_content .= '<td>
            <a href="edit_subject.php?id='.$sub['curriculum_id'].'" class="px-2 py-1 text-white bg-green-600 rounded hover:bg-green-700 text-xs">Edit</a>
            <a href="delete_subject.php?id='.$sub['curriculum_id'].'" class="px-2 py-1 text-white bg-red-600 rounded hover:bg-red-700 text-xs" onclick="return confirm(\'Are you sure?\')">Delete</a>
        </td>';
    }
    $main_content .= '</tr>';
}

$main_content .= '</tbody></table></div>';
?>

<!-- DataTables CSS/JS + RowGroup + Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function(){
    $('#curriculumTable').DataTable({
        paging:true,
        searching:true,
        ordering:true,
        info:true,
        pageLength:10,
        order:[[0,'asc'],[1,'asc'],[2,'asc']],
        columnDefs: [
            { targets: [0,1], visible: false }
        ],
        rowGroup: {
            dataSrc: [0,1],
            startRender: function(rows, group, level){
                if(level === 0){
                    let text = group + ' Year' ;
                    return $('<tr/>').addClass('font-bold text-center').css({'background-color':'#FFEB3B','color':'#000'})
                        .append($('<td/>').attr('colspan', 7+(<?=$is_student?0:1?>)).text(text));
                } else if(level === 1){
                    let semText = group; // if DB already has "1st", "2nd", "Midyear"
                    return $('<tr/>')
                        .addClass('font-semibold text-center')
                        .css({'background-color':'#64B5F6','color':'#000'})
                        .append($('<td/>').attr('colspan',8).text(semText));
                }
            }
        },
        dom: '<"flex justify-end mb-2"Bf>rtip',
        buttons: [
            { extend: 'collection', text: 'Export', buttons: ['copy','csv','excel','pdf'] },
            { extend: 'print', text: 'Print' }
        ],
        language:{ search:"_INPUT_", searchPlaceholder:"Search subjects..." }
    });
});
</script>

<?php include "../includes/template.php"; ?>
