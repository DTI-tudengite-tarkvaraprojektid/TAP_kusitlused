<?php

require("functions.php");


// POOLELI //
$obj = $_POST['toDbQuestionnaire'];
$obj = json_decode($obj);

if(isset($obj)&& $_SESSION['email']){
	$Question->createQuestionnaireWithNameAndEmail($obj->questionnaire_name, $_SESSION['email']);
}
// POOLELI //



if (isset($_GET['logout'])){
    session_destroy();
    header("Location: login.php");
}

if (!isset ($_SESSION["email"])){
    header("Location: login.php");
}


if(!empty($_POST['delValue'])) {
    $Question->delQuestionnaire($_POST['delValue']);
}

$questionnaires = $Question->getAllQuestionnaires($_SESSION['email']);

$allData = [];

foreach($questionnaires as $one){
	$o = new stdClass();
	$o->questionnaire = $one;
	$o->questions = $Question->viewQuestionnaire($one->questionnaire_id);
	array_push($allData, $o);
}

if(!empty($_POST['delValue'])) {
    $Question->delQuestionnaire($_POST['delValue']);
}


?>



<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<title>Tarkvarapraktika</title>

  <!-- Compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">

 <link rel="stylesheet" href="assets/css/styles.css">
      
</head>
<body>
	
	<nav>
		<div class="nav-wrapper teal darken-1">
			<a href="#!" class="brand-logo"><span class="logo grey-text text-lighten-5">Küsitlused</span></a>
			<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
			<ul class="right hide-on-med-and-down">
				<li><a href="">Pealeht</a></li>
				<li><a href="">Kontakt</a></li>
				<li><a href="">Abi</a></li>
				<li><a href="?logout">Logi välja</a></li>
				
			</ul>
			<ul class="side-nav" id="mobile-demo">
				<li><a href="">Pealeht</a></li>
				<li><a href="">Kontakt</a></li>
				<li><a href="">Abi</a></li>
				<li><a href="?logout">Logi välja</a></li>
			</ul>
		</div>
	</nav>
	
	<!-- Taustapilt alguses -->
    <div class="parallax-container valign-wrapper" >
	
		<div class="container">
			<div class="row">
				<div class="col l8 m8 s12 white-text">
					<h4 class="thin-text left-align valign">Tere, <?=$_SESSION['email']?>!<br> Sellel lehel saad luua uusi küsitlusi ning vaadata üle neid, mis on juba tehtud. Keri allpoole!</h4>
				</div>
			</div>	
			<!-- <a class="waves-effect btn-large hoverable  blue lighten-1 col s8">Uuri lähemalt</a> -->
		</div>
		<div class="parallax"><img src="assets/img/asd.jpg"></div>
    </div>
	
	

	<div class="scrollDownIcon" id="showdiv"></div>
		<!--
	<div class="row" id="codeInput">		
		<div class="input-field col s12 l6 m6" id="formCode">
			<input placeholder="Sisesta oma kood" type="text" class="validate">
			<label for="formCode">Kood</label>
			<a class="waves-effect btn-large hoverable  light-blue accent-3 col s12 m6 l3">Sisesta</a>
		</div>
	</div>
	
		<div class="row" id="iphone">
		
		<div class="col s12 m5">
        <div class="card-panel teal light-blue darken-1">
          <span class="white-text ">I am a very simple card. I am good at containing small bits of information.
          I am convenient because I require little markup to use effectively. I am similar to what is called a panel in other frameworks.
          </span>
        </div>
      </div>
		
		<div class="col m5 l3">
			<img class="responsive-img" src="assets/img/iphone.png">
		</div>
	-->	
	
	<div>
	
		<?php

        if(empty($allData)){
            echo '<div class="container">';
			echo '<div class="row center">';
            echo '<div class="col s12 m12 l12">';
			echo '<h2 class="center-align black-text cta">Tee oma küsitlus!</h2>';
            echo '<p class="guestFormText">Kui sul on soov teada saada, kuidas teatud hoonete või muu linnas oleva kohta arvatakse, siis alusta kohe...</p>';
            echo '<a href="#modal1" class="center btn-large waves-effect waves-light teal darken-1 modal-trigger">Alusta!</a>';
			echo '</div>';
			echo '</div>';
			echo '</div>';

        }else{
			echo '<div class="container">';
			echo '<div class="row">';
			echo '<div class="col s12 m12 l12">';
			echo '<div class="row center">';
			echo '<span class="center-align black-text cta">Sinu küsitlused:</span>&nbsp';
			echo '<a class="center waves-effect waves-light btn-floating modal-trigger" href="#newQuestionnaire"><i class="large material-icons">note_add</i></a>';
			echo '</div>';
			echo '<ul class="collapsible" data-collapsible="accordion">';
            foreach($allData as $q){
				echo '<li>';
				echo '<div class="collapsible-header active">';
				echo '<h5 class="black-text">',$q->questionnaire->questionnaire_name,'</h5>';
				echo '</div>';
				echo '<div class="collapsible-body">';
				echo '<ul class="collection">';
				foreach ($q->questions as $qu){
					echo '<li class="collection-item">',$qu->question_name,'</li>';
				}
				echo '</ul>';
				echo '<div class="row center">';
				echo '<div class="col s12 m12 l12">';
				echo '<form method="post">';
				echo '<button onclick="Export(',$q->questionnaire->questionnaire_id,', \'',$q->questionnaire->questionnaire_name,'\')"value="',$q->questionnaire->questionnaire_id,'" name="csvValue" id="csvValue" class="center btn-large waves-effect waves-light teal-darken-1"><i class="material-icons">file_download</i>Vastused CSV failina</button>&nbsp<button value="',$q->questionnaire->questionnaire_id,'" name="delValue" id="delValue" onclick=\'return confirm("Oled kindel et soovid seda küsitlust kustutada? Sa kaotad küsitluse, küsimusi ning kõiki vastusi!")\' class="center btn-large waves-effect waves-light red darken-2s"><i class="material-icons">delete</i>Kustuta küsitlus</button>';
				echo '</form>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</li>';

            }
			echo '</ul>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
        }



        ?>
	</div>
	
	
<!-- Modal Structure -->
<div id="newQuestionnaire" class="modal">
  <div class="modal-content">
    <h4>Loo uus küsitlus</h4>
	<form action="javascript:saveQuestionnaire()">
	<div class="row">
		<input type="text" id="newQuestionnaireName" placeholder="Küsitluse nimetus" oninvalid="this.setCustomValidity('See väli on kohustuslik!')" oninput="setCustomValidity('')" required>
		<div class="input-field col s12 m12 l12">

			<div id="questionnaireDiv">

			</div>
			
			<div class="row center">
			<button class="btn waves-effect waves-green" onclick="newTextQuestion()">+ Tektsiküsimus</button>
			<button class="btn waves-effect waves-green" onclick="newSelectQuestion()">+ Valikvastustega küsimus</button>
			<button class="btn waves-effect waves-green" onclick="newMapQuestion()">+ Kaardiga küsimus</button>
			</div>
		</div>
	</div>
  </div>
  <div class="modal-footer">
    <button type="submit" value="Salvesta"  class="btn waves-effect waves-green">Salvesta</button>
  </div>
  </form>
</div>


	
		<footer class="page-footer teal darken-1">
          <div class="container">
            <div class="row">
              <div class="col s12 m12 l12 center grey-text text-lighten-4">
                <a href="#" class="grey-text text-lighten-4">Pealeht</a>  |  <a href="#" class="grey-text text-lighten-4">Kontakt</a>  |  <a href="#" class="grey-text text-lighten-4">Abi</a>
              </div>
            </div>
          </div>
          <div class="footer-copyright teal darken-2">
            <div class="container center">
            © 2017 Vladislav Šutov, Mark Väljak, Gittan Kaus
            </div>
          </div>
        </footer>
		

	
	
	<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<script src="assets/js/script.js"></script>
</body>
</html>