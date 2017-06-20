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


// Uus küsimus tekstiväljaga
function newTextQuestion() {
    var div = document.createElement('div');

    div.style.borderLeft = "3px solid #00897b";
    div.style.marginBottom = "20px";
    div.style.paddingLeft = "10px";
    div.style.backgroundColor = "white";

    div.className = 'q';
    div.setAttribute('data-type', '0');

    var name = "random-" + parseInt(Math.random() * 1000000000);
    div.innerHTML = '<h5>Tekstiküsimus:</h5><input class="text" name="' + name + '" type="text" placeholder="Küsimuse tekst..." oninvalid="this.setCustomValidity(\'\See väli on kohustuslik!\'\)" oninput="setCustomValidity(\'\'\)" required>';
    document.getElementById('questionnaireDiv').appendChild(div);

}


// Uus küsimus valikvastustega
function newSelectQuestion() {
    var div = document.createElement('div');

    div.style.borderLeft = "3px solid #00897b";
    div.style.paddingLeft = "10px";
    div.style.marginBottom = "20px";
    div.style.backgroundColor = "white";

    div.className = 'q';
    div.setAttribute('data-type', '2');

    var name = "random-" + parseInt(Math.random() * 1000000000);
    div.innerHTML = '<h5>Valikvastustega küsimus:</h5><input class="text" name="' + name + '" type="text" placeholder="Küsimuse tekst..." oninvalid="this.setCustomValidity(\'\See väli on kohustuslik!\'\)" oninput="setCustomValidity(\'\'\)"  required>';

    name = "random-" + parseInt(Math.random() * 1000000000);
    div.innerHTML += '<span>Vastused (eralda komaga):</span><input id="' + name + '" name="' + name + '" class="options" oninvalid="this.setCustomValidity(\'\See väli on kohustuslik!\'\)" oninput="setCustomValidity(\'\'\)" type="text" placeholder="Vastus №1,vastus №2..." required>';
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
    var questionnaire = document.getElementById("newQuestionnaireName").value,
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
                name: questions[i].getElementsByClassName('text')[0].value
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
                items['value'] = input.attr('id');
            } else {
                items['id'] = input.attr('id');
                items['value'] = input.val();
            }
            data.answers.push(items)
        });

        console.log(data);

        $.ajax({
            type: "POST",
            url: "saveanswers.php",
            data: {answeredQuestions: JSON.stringify(data)},
            success: function(data){
                Materialize.toast('Vastused salvestatud. Redigeerin...!', 3000, 'rounded')
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




