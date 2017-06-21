<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

require ("functions.php");


$loginEmail = "";
$loginNotice = "";

if (isset ($_SESSION["userId"])){
    header("Location: index.php");
}

if (isset ($_POST ["loginEmail"])){
    $loginEmail = $_POST["loginEmail"];
}
if(isset($_POST["loginEmail"]) && isset($_POST['loginPassword']) && !empty($_POST["loginEmail"]) && !empty($_POST['loginPassword'])){
    $loginNotice = $Users->login($Helper->cleanInput($_POST["loginEmail"]), $Helper->cleanInput($_POST['loginPassword']));
}

?>
<!doctype html>
<html lang="et">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>Quizify</title>
    <link rel="stylesheet" href="assets/css/materialize.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
	<div class="row center" style="padding-right: 150px; margin-top: 50px"><img style="height: 200px" src="assets/img/logologin.png"></div>
	<div class="container" id="wrap">
		<form id="loginForm" class="col s12 m6" method="POST">
			<div class="row">
				<div class="input-field col s12 m5 offset-m3">
					<input type="email" id="loginEmail" name="loginEmail" class="validate" required value="<?=$loginEmail?>"></input>
					<label for="loginEmail" data-error="Palun sisestage reaalne e-posti aadress!" data-success="Korras!">E-post</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s12 m5 offset-m3">
					<input type="password" id="loginPassword" name="loginPassword" class="validate" required></input>
          			<label for="loginPassword">Parool</label>
				</div>
			</div>
			<div class="row center">
				<div class="input-field col s12 m5 offset-m3">
					<button id="loginSubmit" class="btn waves-effect waves-light teal darken-1" type="submit" name="action">Logi sisse</button>  <a class="tooltipped" style=""data-position="bottom" data-delay="50" data-tooltip="Praegusel hetkel veebileht on beta-testis, seetõttu avalik registreerimine pole võimalik."><i style="font-size: 18px " class="material-icons teal-text text-darken-1">help_outline</i></a>
				</div>
			</div>
			<div class="row center">
                <div class="col s12 m5 offset-m3">
                    <span class="bold red-text"><?=$loginNotice;?></span>
                </div>
            </div>

		</form>
	</div>
    <script src="assets/js/jquery-2.1.1.min.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/materialize.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>