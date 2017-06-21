<?php

if(!isset($_GET['id'])){
	die();
}

require("../../functions.php");
 
$query = "SELECT TAP_questions.name , TAP_useranswers.answer from TAP_useranswers left join TAP_questionnaires on TAP_useranswers.questionnaire_id=TAP_questionnaires.id left join TAP_questions on TAP_useranswers.question_id=TAP_questions.id where TAP_useranswers.questionnaire_id=".$_GET['id'];

if (!$result = mysqli_query($mysqli, $query)) {
    exit(mysqli_error($mysqli));
}
 
$answers = array();
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $answers[] = $row;
    }
}
 
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=vastused_'.$_GET['name'].'.csv');
$output = fopen('php://output', 'w');
fputcsv($output, array('Kusimus', 'Vastus'));
 
if (count($answers) > 0) {
    foreach ($answers as $row) {
        fputcsv($output, $row);
    }
}

?>