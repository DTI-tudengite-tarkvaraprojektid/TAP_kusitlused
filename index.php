<!doctype html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <title>Quizify</title>

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="assets/js/jquery.min.js"></script>
</head>
<body>
<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require("functions.php");


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
}

if (!isset ($_SESSION["email"])) {
    header("Location: login.php");
}


if (!empty($_POST['delValue'])) {
    $Question->delQuestionnaire($_POST['delValue']);
}

$questionnaires = $Question->getAllQuestionnaires($_SESSION['email']);


$allData = [];

foreach ($questionnaires as $one) {
    $o = new stdClass();
    $o->questionnaire = $one;
    $o->questions = $Question->viewQuestionnaireAdmin($one->questionnaire_id);
    array_push($allData, $o);
}

if (!empty($_POST['delValue'])) {
    $Question->delQuestionnaire($_POST['delValue']);
}


?>
<nav>
    <div class="nav-wrapper teal darken-1">
        <a href="#!" class="brand-logo"><span class="logo grey-text text-lighten-5">Quizify</span></a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="mailto:shxtov@tlu.ee"><i class="material-icons">email</i></a></li>
            <li class="teal darken-2"><a href="?logout"><i class="material-icons">exit_to_app</i></a></li>

        </ul>
        <ul class="side-nav" id="mobile-demo">
            <li><a href="mailto:shxtov@tlu.ee"><i class="material-icons">email</i>Kontakt</a></li>
            <li><a href="?logout"><i class="material-icons">exit_to_app</i>Logi välja</a></li>
        </ul>
    </div>
</nav>

<!-- Taustapilt alguses -->
<div class="parallax-container valign-wrapper">

    <div class="container">
        <div class="row">
            <div class="col l8 m8 s12 white-text">
                <h4 class="thin-text left-align valign">Tere, <?= $_SESSION['email'] ?>!<br> Sellel lehel saad luua uusi
                    küsitlusi ning vaadata üle neid, mis on juba tehtud. Keri allpoole!</h4>
            </div>
        </div>
    </div>
    <div class="parallax"><img src="./assets/img/asd.jpg"></div>
</div>

<div class="scrollDownIcon" id="showdiv"></div>

<?php if (empty($allData)) { ?>
    <div class="container">
        <div class="row center">
            <div class="col s12 m12 l12">
                <h2 class="center-align">Loo oma esimene küsitlus!</h2>
                <a class="center waves-effect waves-light btn-large btn-floating modal-trigger"
                   href="#newQuestionnaire"><i class="large material-icons">note_add</i></a></div>
        </div>
    </div>

<?php } else {
        echo '<div class="container">';
        echo '<div class="row">';
        echo '<div class="col s12 m12 l12">';
        echo '<div class="row center">';
        echo '<div><br><a class="center waves-effect waves-light btn-large btn-floating modal-trigger" href="#newQuestionnaire"><i class="large material-icons">note_add</i></a></div>';
        echo '</div>';
        echo '<ul class="collapsible white" data-collapsible="accordion">';
        foreach (array_reverse($allData) as $q) {
?>
            <div id="modal<?=$q->questionnaire->questionnaire_id?>" class="modal modal-fixed-footer">
                <div class="modal-content center">

                    <h2>Sheeri:</h2><br><br>
                    <h5><i class="material-icons title-icn">link</i><a href="http://localhost/tarkvaraarenduse_praktika/quiz/?id=<?=$q->questionnaire->questionnaire_id?>"> http://localhost/tarkvaraarenduse_praktika/quiz/?id=<?=$q->questionnaire->questionnaire_id?></a></h5><br>
                    <canvas id="qrcode<?=$q->questionnaire->questionnaire_id?>" class="qrcode"></canvas>

                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Sule</a>
                </div>
            </div>
<?php
            echo '<li>';
            echo '<div class="collapsible-header active">';
            echo '<div class="row col-header" style="margin-top: 20px;">';
            echo '<div class="col s10 m10 l10">';
            echo '<h4 class="black-text">';
            echo $q->questionnaire->questionnaire_name, '</h4>';
            echo '</div>';
            echo '<div class="col s2 m2 l2">';
            echo '<form method="post">';
            echo '<div>';
            echo '<button onclick="Export(', $q->questionnaire->questionnaire_id, ', \'', $q->questionnaire->questionnaire_name, '\')"value="', $q->questionnaire->questionnaire_id, '" name="csvValue" id="csvValue" class="center btn-floating waves-effect waves-light teal-darken-1"><i class="material-icons title-icn">file_download</i></button>&nbsp<a href="#modal'.$q->questionnaire->questionnaire_id.'" onclick="generateQrCode(',$q->questionnaire->questionnaire_id,');"class="center btn-floating waves-effect waves-light blue darken-2"><i class="material-icons title-icn">share</i></a>&nbsp<button value="', $q->questionnaire->questionnaire_id, '" name="delValue" id="delValue" onclick=\'return confirm("Oled kindel et soovid seda küsitlust kustutada? Sa kaotad küsitluse, küsimusi ning kõiki vastusi!")\' class="center btn-floating waves-effect waves-light red darken-2"><i class="material-icons title-icn">delete</i></button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="collapsible-body">';
            echo '<div class="row">';
            echo '<div class="col s10 m10 l10">';
            echo '<ul class="collection">';
            foreach ($q->questions as $qu) {
                echo '<li class="collection-item">', $qu->question_name, '</li>';
            }
            echo '</ul>';
            echo '</div>';
            echo '<div class="center col s2 m2 l2">';
            echo '<span>Staatus:  ';
?>
            <div class="switch">
                <form method="post">
                    <label style="font-size: 10px">
                        <input type="checkbox" value="<?php echo $q->questionnaire->questionnaire_id?>" name="statusValue" id="statusValue" onchange='changeStatus(<?=$q->questionnaire->questionnaire_id?>)' <?php echo $q->questionnaire->questionnaire_status == 1 ? 'checked="checked"' : '';?>>
                        <span class="lever"></span>
                    </label>
                </form>
            </div>
<?php
            echo '</span><br>';
            echo '<span>Vastajaid:   ';
            echo '<h5>'.$q->questionnaire->questionnaire_answercount.'</h5></span><br>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</li>';

        }
        echo '</ul>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
} ?>

<!-- New questionnaire modal 	 -->
<div id="newQuestionnaire" class="modal">
    <div class="modal-content">
        <h4>Loo uus küsitlus</h4>
        <form action="javascript:saveQuestionnaire()">
            <div class="row">
                <input type="text" id="newQuestionnaireName" placeholder="Küsitluse nimetus"
                       oninvalid="this.setCustomValidity('See väli on kohustuslik!')" oninput="setCustomValidity('')"
                       required>
                <div class="input-field col s12 m12 l12">

                    <div id="questionnaireDiv">

                    </div>

                    <div class="row center">
                        <button class="btn waves-effect waves-green" onclick="newTextQuestion()">+ Tektsiküsimus
                        </button>
                        <button class="btn waves-effect waves-green" onclick="newSelectQuestion()">+ Valikvastustega
                            küsimus
                        </button>
                    </div>
                </div>
            </div>
    </div>
    <div class="modal-footer">
        <button type="submit" value="Salvesta" class="btn waves-effect waves-green modal-close">Salvesta</button>
    </div>
    </form>
</div>


<footer class="page-footer teal darken-2">
    <div class="footer-copyright teal darken-2">
        <div class="container center">
            © 2017 Vladislav Šutov, Mark Väljak, Gittan Kaus
            </div>
          </div>
        </footer>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/materialize.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="assets/js/qrcode.min.js"></script>
</body>
</html>