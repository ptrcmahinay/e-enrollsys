<?php
if(session_status()===PHP_SESSION_NONE) session_start();
require_once "../config/db.php";

$activePage = 'Curriculum Management';

if(!isset($_SESSION['role']) || $_SESSION['role']!=='registrar'){
    die("Access denied.");
}

if(!isset($_GET['program_id'])){
    die("Program not specified.");
}

$program_id = (int)$_GET['program_id'];
$activePage = 'Curriculum Checklist';

// Get program info
$programRes = $conn->query("SELECT program_name FROM programs WHERE programs_id = $program_id");
if($programRes->num_rows === 0) die("Program not found.");
$program = $programRes->fetch_assoc();

// Fetch subjects for this program
$result = $conn->query("
    SELECT pc.curriculum_id, s.subject_code, s.subject_description, s.units,
           pc.year_level, pc.semester, ps.subject_code AS prerequisite_code
    FROM program_curriculum pc
    JOIN subjects s ON pc.subject_id = s.subject_id
    LEFT JOIN subjects ps ON pc.prerequisite_subject_id = ps.subject_id
    WHERE pc.program_id = $program_id
    ORDER BY pc.year_level, pc.semester, s.subject_code
");

$subjects = [];
while($row = $result->fetch_assoc()){
    $subjects[] = $row;
}

// Build table body
$main_content = "<div class='px-4 md:px-6 lg:px-8'>
    <div class='flex justify-between items-center mb-4'>
        <h1 class='text-2xl font-bold'>{$program['program_name']}</h1>
    </div>
    <table id='curriculumTable' class='display w-full text-sm table-auto border mb-4'>
        <thead>
            <tr class='bg-blue-100 text-blue-600'>
                <th>Year Level</th>
                <th>Semester</th>
                <th>Course Code</th>
                <th>Course Title</th>
                <th>Units</th>
                <th>Prerequisite</th>
            </tr>
        </thead>
        <tbody>";

foreach($subjects as $sub){
    $prereq = $sub['prerequisite_code'] ?? '';
    $main_content .= "<tr>
        <td>{$sub['year_level']}</td>
        <td>{$sub['semester']}</td>
        <td>{$sub['subject_code']}</td>
        <td>{$sub['subject_description']}</td>
        <td>{$sub['units']}</td>
        <td>{$prereq}</td>
    </tr>";
}

$main_content .= "</tbody></table></div>";
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
        rowGroup: {
            dataSrc: ['0','1'], // group by Year Level then Semester
            startRender: function(rows, group, level){
                if(level === 0){
                    let text = '';
                    switch(group){
                        case '1': text = 'First Year'; break;
                        case '2': text = 'Second Year'; break;
                        case '2.5': text = 'Mid Year'; break; // Mid Year
                        case '3': text = 'Third Year'; break;
                        case '4': text = 'Fourth Year'; break;
                        default: text = 'Year '+group;
                    }
                    return $('<tr/>')
                        .addClass('font-bold text-center')
                        .css({'background-color':'#FFEB3B','color':'#000'})
                        .append($('<td/>').attr('colspan',6).text(text));
                } else if(level === 1){
                    let semText = (group=='1')?'First Semester':'Second Semester';
                    return $('<tr/>')
                        .addClass('font-semibold text-center')
                        .css({'background-color':'#64B5F6','color':'#000'})
                        .append($('<td/>').attr('colspan',6).text(semText));
                }
            }
        },
        dom: '<"flex items-center mb-2"Bf>rtip', // Buttons on the left, search on right
        buttons: [
            {
                extend: 'collection',
                text: 'Export',
                buttons: ['copy','csv','excel','pdf']
            },
            {
                extend: 'print',
                text: 'Print'
            }
        ],
        language:{
            search:"_INPUT_",
            searchPlaceholder:"Search subjects..."
        }
    });
});
</script>

<?php
include "../includes/template.php";
?>
