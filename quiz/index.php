
<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Quizify</title>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="../assets/css/materialize.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">

    <script src="../assets/js/jquery.min.js"></script>
</head>
<body>
<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require("../functions.php");


$questionnairyId = isset($_GET['id']) ? $_GET['id'] : null;
if(!isset($questionnairyId)){
	header('Location: ../404error.html');
} else {

	$questionnaire_name= $Question->loadQuestionnaireToAnswer($questionnairyId);
	$questions_list= $Question->viewQuestionnaireToAnswer($questionnairyId);
}

// Juhul, kui sellel küsimustikul on staatus mitte aktiivne
$hasUserAnsweredQuiz = $Question->hasUserAnsweredQuiz($_GET['id'], get_client_ip());
if ($questionnaire_name->questionnaire_status == 0 ) {
    header('Location: ../404error.html');
};

if ($hasUserAnsweredQuiz == 1 ) {
    header('Location: ../aitah.html');
};
function get_client_ip()
{
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        return $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        return $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        return $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        return $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        return $_SERVER['REMOTE_ADDR'];
    else
        return 'UNKNOWN';
}
?>

<nav>
    <div class="nav-wrapper teal darken-1">
        <a href="" class="brand-logo center"><span class="logo grey-text text-lighten-5"><?=$questionnaire_name->questionnaire_name?></span></a>
    </div>
</nav>

<div class="container">
    <input id="questionnairyId" type="hidden" value="<?php echo $questionnairyId?>">
    <input id="userIp" type="hidden" value="<?php echo get_client_ip()?>">
    <div class="row" id="quizzes">
        <?php foreach ($questions_list as $q) { ?>

            <div class="col s12 m12 l12">
                <?php if ($q->question_type == "0") { ?>
                    <div class="input-field">
                        <h5><?php echo $q->question_name ?></h5>
                        <input type="text" id="<?php echo $q->question_id ?>" required placeholder="Vastuse tekst..."
                               oninvalid="this.setCustomValidity('See väli on kohustuslik!')"
                               oninput="setCustomValidity('')" required></textarea>
                    </div>
                <?php } elseif ($q->question_type == "2") { ?>
                    <div ="<?=rand()?>">
                        <h5><?php echo $q->question_name ?></h5>
                        <form action="#">
                            <?php if (sizeof($q->question_options) > 0) { ?>
                                <?php foreach ($q->question_options as $opt) { ?>
                                    <div>
                                        <input name="<?php echo $q->question_name ?>"
                                               type="radio" data-question-id="<?php echo $q->question_id ?>"
                                               id="<?php echo $opt->option_id ?>"/>
                                        <label for="<?php echo $opt->option_id ?>"><?php echo $opt->option_name ?></label>
                                    </div>
                                <?php }
                            } ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
        <div class="col s12 m12 l12">
            <a href="#" id="submitanswers" class="center btn-large waves-effect waves-light teal darken-1" style="margin-top: 20px">
                Salvesta
            </a>
        </div>
    </div>
</div>


<footer class="page-footer teal darken-2">
    <div class="footer-copyright teal darken-2">
        <div class="container center">
            © 2017 Vladislav Šutov. Mark Väljak. Gittan Kaus
            </div>
          </div>
        </footer>
<script src="../assets/js/jquery-ui.js"></script>
<script src="../assets/js/materialize.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>