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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link href="https://fonts.googleapis.com/css?family=Archivo+Black|Bubbler+One|Cantarell:700|Fjalla+One|Fredoka+One|Khand:700|Krona+One|Orbitron:700|Quicksand|Righteous|Unica+One" rel="stylesheet">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>Tarkvarapraktika</title>

  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">

 <link rel="stylesheet" href="assets/css/styles.css">
      

</head>


<body>
<nav>
		<div class="nav-wrapper brown lighten-1">
			<a href="#!" class="brand-logo title"><span class="logo grey-text text-lighten-5">iZ!quiZ</span></a>
		</div>
</nav>
	<div class="container" id="wrap">
		<form id="loginForm" class="col s12 m6" method="POST">
			<div class="row">
				<div class="col s12 m5 offset-m3 ">
					<h3 class="center">Sisselogimine <a class="tooltipped" data-position="bottom" data-delay="50" data-tooltip="Praegusel hetkel veebileht on beta-testis, seetõttu avalik registreerimine pole võimalik."><i style="font-size: 18px " class="material-icons brown-text text-darken-1">help_outline</i></a></h3>
				</div>
			</div>		
			<div class="row">
				<div class="input-field col s12 m5 offset-m3 ">
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
					<button id="loginSubmit" class="btn waves-effect waves-light brown lighten-1" type="submit" name="action">Logi sisse</button>
				</div>
			</div>
			<div class="row center">
                <div class="col s12 m5 offset-m3">
                    <span class="bold red-text"><?=$loginNotice;?></span>
                </div>
            </div>

		</form>
	</div>
	<footer class="page-footer brown lighten-2 footer-login">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12 center grey-text text-lighten-4">
                <a href="#" class="grey-text text-lighten-4">Pealeht</a>  |  <a href="#" class="grey-text text-lighten-4">Kontakt</a>  |  <a href="#" class="grey-text text-lighten-4">Abi</a>
              </div>
            </div>
          </div>
          <div class="footer-copyright brown lighten-1">
            <div class="container center">
            © 2017 Vladislav Šutov, Mark Väljak, Gittan Kaus
            </div>
          </div>
        </footer>
</body>

<?php require ("footer.php");?>