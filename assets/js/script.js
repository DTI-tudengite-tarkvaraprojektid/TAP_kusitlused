/*Materialized.css-i jaoks vajalik kood, et scroll efecti lisada*/
$(document).ready(function () {
    $(".button-collapse").sideNav();
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
function Export(id, name) {
    if (window.confirm("Lae alla vastused CSV failina?") == true) {
        window.open("app/class/exporttocsv.php?id=" + id + '&name=' + name, '_blank');
    }
}




function cleanInput(string){
    string = string.replace(/[<>{}]/g, '');

    return string;
}




// Uus küsimus tekstiväljaga
function newTextQuestion() {
  var div = document.createElement('div');

  div.classList.add("newDiv");
  div.classList.add("q");

  div.setAttribute('data-type', '0');

  var name = "random-" + parseInt(Math.random() * 1000000000);
  var input = document.createElement("input");

  input.type = "text";
  input.classList.add("text");
  input.placeholder = "Küsimuse tekst...";
  input.required = true;

  input.addEventListener("invalid", function(){
    this.setCustomValidity("\'See väli on kohustuslik!\'");
  });

  input.addEventListener("input", function(){
    this.setCustomValidity("");
  });


  var btn = document.createElement("a");
  btn.textContent = "Kustuta rida";
  btn.className = "center btn-flat waves-effect waves-light";

  div.appendChild(input);
  div.appendChild(btn);

  btn.addEventListener("click", function(){
    document.getElementById('questionnaireDiv').removeChild(div);
  });
    document.getElementById('questionnaireDiv').appendChild(div);
}


// Uus küsimus valikvastustega
function newSelectQuestion() {
    var div = document.createElement('div');

    div.classList.add("newDiv");
    div.classList.add("q");

    div.setAttribute('data-type', '2');

    var name = "random-" + parseInt(Math.random() * 1000000000);
    var input = document.createElement("input");

    input.type = "text";
    input.classList.add("text");
    input.placeholder = "Küsimuse tekst...";
    input.name = name;
    input.required = true;

    input.addEventListener("invalid", function(){
        this.setCustomValidity('See väli on kohustuslik!');
    });

    input.addEventListener("input", function(){
        this.setCustomValidity("");
    });

    div.appendChild(input);

    name = "random-" + parseInt(Math.random() * 1000000000);
    input = document.createElement("input");

    input.type = "text";
    input.classList.add("options");
    input.placeholder = "Vastused (eralda komaga)";
    input.name = name;
    input.required = true;


    input.addEventListener("invalid", function(){
        this.setCustomValidity("See väli on kohustuslik!");
    });

    input.addEventListener("input", function(){
        this.setCustomValidity("");
    });

    var btn = document.createElement("a");
    btn.textContent = "Kustuta rida";
    btn.className = "center btn-flat waves-effect waves-light";


    div.appendChild(input);
    div.appendChild(btn);

    btn.addEventListener("click", function(){
        document.getElementById('questionnaireDiv').removeChild(div);
    });
    document.getElementById('questionnaireDiv').appendChild(div);


}


function generateQrCode(id){
    var urlToQr = 'http://localhost/tarkvaraarenduse_praktika/quiz/?id='+id;
    console.log(urlToQr);
    var qr = new QRious({
          element: document.getElementById('qrcode'+id),
          value: urlToQr
    });
}

function saveQuestionnaire() {
    var questionnaire = cleanInput(document.getElementById("newQuestionnaireName").value),
        questions = document.querySelectorAll(".q"),
        request = {
            name: questionnaire,
            quiz: []
        },
        toDbJson;

    for (var i = 0; i < questions.length; i++) {
        var question_type = questions[i].getAttribute('data-type'),
            requestQuiz = {
                type: question_type,
                name: cleanInput(questions[i].getElementsByClassName('text')[0].value)
            };

        if (question_type === "2") {
            requestQuiz['options'] = questions[i].getElementsByClassName('options')[0].value.split(',');
        }

        request.quiz.push(requestQuiz);
    }

    toDbJson = JSON.stringify(request);

    console.log(toDbJson);

    $.ajax({
        type: "POST",
        url: "createquestionnaire.php",
        data: {toDbQuestionnaire: toDbJson},
        success: function(data){
            Materialize.toast('Küsimustik salvestatud. Värskendan...', 3000, 'rounded')
            setTimeout(function(){
                window.location.reload();
            }, 2500);
        }
    });
}


function changeStatus(quizId) {
    console.log(quizId);
    $.ajax({
        type: "POST",
        url: "changestatus.php",
        data: {quizId: quizId},
        success: function(data){
            Materialize.toast('Küsimustiku staatus edukalt muudetud', 3000)
        }
    });
}

$(function () {
    $('a#submitanswers').on('click', function (e) {
        e.preventDefault();

        var quizId = $('#questionnairyId').val(),
            userIp = $('#userIp').val(),
            data = {
                quizId: quizId,
                userIp: userIp,
                answers: []
            };

        $('#quizzes input:text,input:checked').each(function () {
            var input = $(this),
                items = {
                    id: '',
                    value: '',
                };
            //validate if input is not empty? before posting
            if (input.attr('type') == 'radio') {
                items['id'] = input.data('questionId');
                items['value'] = cleanInput($(this).next("label").html());
            } else {
                items['id'] = input.attr('id');
                items['value'] = cleanInput(input.val());
            }
            data.answers.push(items)
        });

        console.log(data);

        $.ajax({
            type: "POST",
            url: "saveanswers.php",
            data: {answeredQuestions: JSON.stringify(data)},
            success: function(data){
                Materialize.toast('Vastused salvestatud. Redigeerin...', 3000, 'rounded')
                setTimeout(function(){
                    window.location = "http://localhost/tarkvaraarenduse_praktika/aitah.html";
                }, 2500);
            }
         });
    });
});

// jquery sortable

$(function () {
    $("#questionnaireDiv").sortable();
    $("#questionnaireDiv").disableSelection();
});




