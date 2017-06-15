
/*Materialized.css-i jaoks vajalik kood, et scroll efecti lisada*/
$(document).ready(function(){ $(".button-collapse").sideNav(); });
$(".button-collapse").sideNav();
$(document).ready(function(){
  $(".parallax").parallax();
});
 
//et kohe alguses ei oleks näha seda elementi
$("#showdiv").hide();
//scroll effect, mis näitab või peidab scroll noolt mobiili versioonis  
$(function () {
	$(window).scroll(function () {
		var scrollval = $(window).scrollTop();
		//kontrollin ekraani suurust, et triggeriks funktsiooni ainult mobiilsetel seadmetel
		if (screen.width < 500) {
			if (scrollval >= 160) {
				$("#showdiv").hide();
			} else {
				$("#showdiv").show();
			}
		} else {
			$("#showdiv").hide();
		}
	});
});

(function ($) {
    $(function () {

        //initialize all modals           
        $('.modal').modal();

        //now you can open modal from code
        $('#viewQuestionnaireModal').modal('open');

        //or by click on trigger
        $('.trigger-modal').modal();

    }); // end of document ready
})(jQuery); // end of jQuery name space


// Export CSV faili
function Export(id, name){
	if (window.confirm("Lae alla CSV fail?")== true) {
        window.open("assets/class/exporttocsv.php?id="+id+'&name='+name,'_blank');
    } 
}


// Uus küsimus tekstiväljaga
function newTextQuestion(){
	var div = document.createElement('div');
	
	div.style.borderLeft = "3px solid #00897b";
	div.style.marginBottom = "20px";
	div.style.paddingLeft = "10px";
	div.style.backgroundColor = "white";
	
	div.className = 'q';
	div.setAttribute('data-type','textQuestion');
	
	var name = "random-"+parseInt(Math.random()*1000000000);
	div.innerHTML = '<h5>Tekstiküsimus:</h5><input class="text" name="'+name+'" type="text" placeholder="Küsimuse tekst..." oninvalid="this.setCustomValidity(\'\See väli on kohustuslik!\'\)" oninput="setCustomValidity(\'\'\)" required>';
	document.getElementById('questionnaireDiv').appendChild(div);
	
}


// Uus küsimus kaardiga
function newMapQuestion(){
	var div = document.createElement('div');
	
	div.style.borderLeft = "3px solid #00897b";
	div.style.marginBottom = "20px";
	div.style.paddingLeft = "10px";
	div.style.backgroundColor = "white";
	
	div.className = 'q';
	div.setAttribute('data-type','mapQuestion');
	
	var name = "random-"+parseInt(Math.random()*1000000000);
	div.innerHTML = '<h5>Kaardiga küsimus:</h5><input class="text" name="'+name+'" type="text" placeholder="Küsimuse tekst..." oninvalid="this.setCustomValidity(\'\See väli on kohustuslik!\'\)" oninput="setCustomValidity(\'\'\)" required>';
	document.getElementById('questionnaireDiv').appendChild(div);
}


// Uus küsimus valikvastustega
function newSelectQuestion(){ 
	var div = document.createElement('div');
	
	div.style.borderLeft = "3px solid #00897b";
	div.style.paddingLeft = "10px";
	div.style.marginBottom = "20px";
	div.style.backgroundColor = "white";
	
	div.className = 'q';
	div.setAttribute('data-type','selectQuestion');
	
	var name = "random-"+parseInt(Math.random()*1000000000);
	div.innerHTML = '<h5>Valikvastustega küsimus:</h5><input class="text" name="'+name+'" type="text" placeholder="Küsimuse tekst..." oninvalid="this.setCustomValidity(\'\See väli on kohustuslik!\'\)" oninput="setCustomValidity(\'\'\)"  required>';
	
	name = "random-"+parseInt(Math.random()*1000000000);
	div.innerHTML += '<span>Vastused (eralda komaga):</span><input id="'+name+'" name="'+name+'" class="options" oninvalid="this.setCustomValidity(\'\See väli on kohustuslik!\'\)" oninput="setCustomValidity(\'\'\)" type="text" placeholder="Vastus №1,vastus №2..." required>';
	document.getElementById('questionnaireDiv').appendChild(div);
	
	
}
	

// küsitluse salvestamine !!!POOLELI!!!
function saveQuestionnaire (){
	
	var questionnaire = document.getElementById("newQuestionnaireName").value;
	
	var questions = document.querySelectorAll(".q");
	var toDb = [];
	
	var l = {
			questionnaire_name: questionnaire
	}
	
	toDb.push(l);
	
	for(var i = 0; i < questions.length; i++){
		
		var question_type = questions[i].getAttribute('data-type');
		
		var question_text = questions[i].getElementsByClassName('text')[0].value;
		
		if(question_type === "textQuestion"){	
			var o = {
				question_type: question_type,
				question_name: question_text
			};
		} else if(question_type === "selectQuestion"){
			var o = {
				question_type: question_type,
				question_name: question_text,
				question_options: questions[i].getElementsByClassName('options')[0].value.split(',')
			};
		} else if(question_type === "mapQuestion"){
			var o = {
				question_type: question_type,
				question_name: question_text
			};
		}
		toDb.push(o);
	}
		
		
	var toDbJson = JSON.stringify(toDb);
		
	console.log(toDbJson);
		
	$.ajax({ 
		type: "POST", 
		url: "index.php", 
		data: {toDbQuestionnaire: toDbJson}, 
		async: false,
	}); 
		

}
	

// jquery sortable

$( function() {
    $( "#questionnaireDiv" ).sortable();
    $( "#questionnaireDiv" ).disableSelection();
});


