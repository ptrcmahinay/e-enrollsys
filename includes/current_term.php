<?php
require_once "../config/db.php";

/*
    Current Term = active semester joined with its Academic Year
*/

$sql = "
    SELECT 
        at.id AS term_id,
        at.semester,
        at.start_date AS term_start,
        at.end_date AS term_end,
        ay.id AS academic_year_id,
        ay.year_label,
        ay.start_date AS ay_start,
        ay.end_date AS ay_end
    FROM academic_terms at
    INNER JOIN academic_years ay
        ON ay.id = at.academic_year_id
    WHERE at.is_active = 1
      AND ay.is_active = 1
    LIMIT 1
";

$result = $conn->query($sql);
$current_term = $result ? $result->fetch_assoc() : null;
