<?php
require("../functions.php");



$obj = isset($_POST['answeredQuestions']) ? $_POST['answeredQuestions'] : null;
$obj = json_decode($obj);

if (isset($obj)) {
    $Question->addAnswersToDb($obj->quizId, $obj->userIp, $obj->answers);
    header('Content-Type: application/json');
    echo json_encode("{result: true}");
}