<?php
require("functions.php");

$obj = $_POST['toDbQuestionnaire'];
$obj = json_decode($obj);

if (isset($obj) && isset($obj->name) && $_SESSION['email']) {
    $Question->createQuestionnaireWithNameAndEmail($obj->name, $_SESSION['email'], $obj->quiz);
    header('Content-Type: application/json');
    echo json_encode("{result: true}");
}
