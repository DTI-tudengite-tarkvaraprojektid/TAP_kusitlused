<!-- LOGIN -->

<?php
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

require ("header.php");
?>

<head>
 <style>
	 body { 
	  background: url(tallinn-old-town-1500-cs.jpg) no-repeat center fixed; 
	  background-size: cover;
	}

 </style>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One|Roboto:100|Yanone+Kaffeesatz:200" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>Tarkvarapraktika</title>

  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">

 <link rel="stylesheet" href="assets/css/styles.css">

</head>


<body>
	<div class="container" id="wrap">
		<form id="loginForm" class="col s12 m6" method="POST">
			<br><br><br><br><br><br><br>
			<div class="login_box row">
				<div class ="center-align border col s16 m6 l6">
				<img src="assets/img/izi_logo.png">
				<br><span style="font-size: 60px; vertical-align: text-top; " class="login navbar-menu white-text text-lighten-5">iZ!quiZ</span>
				<br>
				<a class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Praegusel hetkel veebileht on beta-testis, seetõttu avalik registreerimine pole võimalik."><i style="font-size: 30px " class="material-icons brown-text text-darken-1">help_outline</i></a>
				</div>
				
				<div class ="center-align col s16 m6 l6">
				<br><br><br>
				<div class="input-field col s12 m5 offset-m3 ">
					<input type="email" id="loginEmail" name="loginEmail" class="validate" required value="<?=$loginEmail?>"></input>
					<label for="loginEmail" data-error="Palun sisestage reaalne e-posti aadress!" data-success="Korras!">E-post</label>
				</div>
			
			<div class="row">
				<div class="input-field col s12 m5 offset-m3">
					<input type="password" id="loginPassword" name="loginPassword" class="validate" required></input>
          			<label for="loginPassword">Parool</label>
				</div>
			</div>
			<div class="row center">
				<div class="input-field col s12 m5 offset-m3">
					<button id="loginSubmit" class="btn waves-effect waves-light brown lighten-1" type="submit" name="action">Logi sisse</button>
				</div>
			</div>
			<div class="row center">
                <div class="col s12 m5 offset-m3">
                    <span class="bold red-text"><?=$loginNotice;?></span>
                </div>
            </div>
			</div>
			</div>
		</form>
	</div>
</body>

<?php require ("footer.php");?>