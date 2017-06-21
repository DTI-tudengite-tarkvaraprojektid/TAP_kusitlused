<?php
require("functions.php");


var_dump($_POST['quizId']);

if (!empty($_POST['quizId'])) {
    $Question->changeQuestionnaireStatus($_POST['quizId']);
}