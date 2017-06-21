<?php
class Question
{
    private $connection;

    function __construct($mysqli)
    {
        $this->connection = $mysqli;
    }


// Kõikide küsimustikude tõmbamine andmebaasist
    function getAllQuestionnaires($email){
		$stmt = $this->connection->prepare("SELECT tap_questionnaires.id, tap_questionnaires.name, tap_questionnaires.active,  COUNT(tap_useranswers_ip.ip) FROM TAP_questionnaires LEFT JOIN TAP_useranswers_ip ON tap_questionnaires.id = tap_useranswers_ip.questionnaire_id WHERE author_email = ? GROUP BY tap_questionnaires.id, tap_questionnaires.name, tap_questionnaires.active;");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $name, $active, $count);

		$stmt->execute();

		$result = array();

		while ($stmt->fetch()) {
			$questionnaires = new StdClass();
			$questionnaires->questionnaire_id= $id;
			$questionnaires->questionnaire_name = $name;
			$questionnaires->questionnaire_status = $active;
			$questionnaires->questionnaire_answercount = $count;
			array_push($result, $questionnaires);
		}

		$stmt->close();

		return $result;
	}
	
// Küsimustiku vaatamise jaoks admin panelis
	function viewQuestionnaireAdmin($id){
		$questionStmt = $this->connection->prepare("SELECT id, type, name FROM TAP_questions WHERE questionnaire_id=?;");
		$questionStmt->bind_param("i", $id);
		$questionStmt->bind_result($id, $type, $name);
		$questionStmt->execute();
		$result = array();

		while ($questionStmt->fetch()) {
			$questions = new StdClass();
			$questions->question_id = $id;
			$questions->question_type = $type;
			$questions->question_name = $name;

			array_push($result, $questions);
		}

		$questionStmt->close();


		return $result;
	}

// Küsimustiku kustutamine
	function delQuestionnaire($id){
		$stmt = $this->connection->prepare("DELETE FROM TAP_questionnaires WHERE id=?");
		$stmt->bind_param("i", $id);
		$stmt->execute();
		$stmt->close();
	}

// Küsimustiku loomine	
	function createQuestionnaireWithNameAndEmail($name, $email, $questions){
		$active = 1;
		$stmt = $this->connection->prepare("INSERT INTO tap_questionnaires (name, author_email, active) VALUES (?, ?, ?);");
		$stmt->bind_param("ssi", $name, $email, $active);

		$stmt->execute();
        $quizId = $stmt->insert_id;

        $stmt->prepare("INSERT INTO tap_questions (questionnaire_id, type, name) VALUES(?, ?, ?)");
        foreach($questions as $question){
        	if($question->type == "0"){
        		$stmt->bind_param('iis', $quizId, $question->type, $question->name);
            	$stmt->execute();
        	}elseif($question->type=="2"){
        		$stmt->bind_param('iis', $quizId, $question->type, $question->name);
        		$stmt->execute();
        		$questionId = $stmt->insert_id;

        		foreach($question->options as $opt){
        			$stmtOptions= $this->connection->prepare("INSERT INTO tap_options (question_id, options) VALUES (?,?)");
        			$stmtOptions->bind_param('is', $questionId, $opt);
        			$stmtOptions->execute();
        			$stmtOptions->close();
        		}

        	}

        }
        $stmt->close();
	}

// Küsimustiku tõmbamine /quiz/index.php lehe jaoks et kontrollida, kas küsimustik on aktiivne või mitte
	function loadQuestionnaireToAnswer($id){
		$questionnaireStmt = $this->connection->prepare("SELECT name, active FROM TAP_questionnaires WHERE id=?;");
		$questionnaireStmt->bind_param("i", $id);
		$questionnaireStmt->bind_result($qu_name, $active);
		$questionnaireStmt->execute();


		while ($questionnaireStmt->fetch()){
			$questionnaire = new StdClass();
			$questionnaire->questionnaire_name = $qu_name;
			$questionnaire->questionnaire_status = $active;
		}
		$questionnaireStmt->close();

		return $questionnaire;

	}
// Küsimustiku tõmbamine /quiz/index.php lehe jaoks et vastata	
    function viewQuestionnaireToAnswer($id)
    {
        $questionStmt = $this->connection->prepare("SELECT TAP_questions.id, TAP_questions.type, TAP_questions.name, TAP_options.id, TAP_options.options from TAP_questions LEFT JOIN TAP_options on TAP_questions.id=TAP_options.question_id WHERE TAP_questions.questionnaire_id = ?;");

        $questionStmt->bind_param("i", $id);
        $questionStmt->bind_result($question_id, $question_type, $question_name, $option_id, $option_name);
        $questionStmt->execute();
        $result = array();

        while ($questionStmt->fetch()) {
            $questions = new StdClass();

            $questions->question_id = $question_id;
            $questions->question_type = $question_type;
            $questions->question_name = $question_name;
            $questions->question_options = array();

            if($question_type == 2){
                $options = new StdClass();
                $options->option_id = $option_id;
                $options->option_name = $option_name;
                array_push($questions->question_options, $options);
            }

            if(array_key_exists($question_id, $result) && isset($questions->question_options)){
                array_push($result[$question_id]->question_options, $questions->question_options[0]);
            } else {
                $result[$question_id] = $questions;
            }
        }
        $questionStmt->close();

        $temp = array();
        foreach ($result as $key => $value) {
            array_push($temp, $value);
        }

        $result = $temp;

        return $result;
    }
// Checkboxiga (switch) küsimustiku staatuse muutmine admin panelil
    function changeQuestionnaireStatus($id){

    	$stmt = $this->connection->prepare("SELECT active FROM TAP_questionnaires WHERE id=?;");
    	$stmt->bind_param("i",$id);
    	$stmt->bind_result($qu_status);
    	$stmt->execute();

		while ($stmt->fetch()){
			$questionnaire_status=$qu_status;
		}

		$stmt->close();

    	if($questionnaire_status==0){
    		$stmt2 = $this->connection->prepare("UPDATE TAP_questionnaires SET active=1 WHERE id=?;");
    		$stmt2->bind_param("i", $id);
    		$stmt2->execute();
    		$stmt2->close();
    	}else{
    		$stmt2 = $this->connection->prepare("UPDATE TAP_questionnaires SET active=0 WHERE id=?;");
    		$stmt2->bind_param("i", $id);
    		$stmt2->execute();
    		$stmt2->close();
    	}

    }
// Kontroll, kas inimene juba vastas küsimusele või mitte
    function hasUserAnsweredQuiz($quizId, $userIp){
        $stmt = $this->connection->prepare("SELECT EXISTS(SELECT * FROM TAP_useranswers_ip WHERE questionnaire_id = ? AND ip = ?);");
        $stmt->bind_param("is", $quizId, $userIp);
        $stmt->bind_result($exists);
        $stmt->execute();

        $stmt->fetch();
        $stmt->close();

        return boolval($exists);
    }

// Küsimustikule vastuste lisamine andmebaasi
    function addAnswersToDb($quizId, $userIp, $answers){

		$stmt = $this->connection->prepare("INSERT INTO tap_useranswers_ip (questionnaire_id, ip) VALUES (?, ?);");
		$stmt->bind_param('is', $quizId, $userIp);
		$stmt->execute();


        $stmt->prepare("INSERT INTO tap_useranswers (questionnaire_id, question_id, answer) VALUES (?, ?, ?)");
        foreach($answers as $answer){
        	$stmt->bind_param('iis', $quizId, $answer->id, $answer->value);
            $stmt->execute();
        }

        $stmt->close();
	}
}
