<?php
class Question
{
    private $connection;

    function __construct($mysqli)
    {
        $this->connection = $mysqli;
    }

    function getAllQuestionnaires($email){
		$stmt = $this->connection->prepare("SELECT id, name FROM TAP_questionnaires WHERE author_email=?;");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id, $name);

		$stmt->execute();

		$result = array();

		while ($stmt->fetch()) {
			$questionnaires = new StdClass();
			$questionnaires->questionnaire_id= $id;
			$questionnaires->questionnaire_name = $name;
			array_push($result, $questionnaires);
		}

		$stmt->close();

		return $result;
	}
	
	function viewQuestionnaire($id){
		
		$stmt = $this->connection->prepare("SELECT questionnaire_id, id, name FROM TAP_questions WHERE questionnaire_id=?;");
		$stmt->bind_param("i", $id);
		$stmt->bind_result($qu_id, $id, $name);
	
		$stmt->execute();
		
		$result = array();
		
		while ($stmt->fetch()){
			$questions = new StdClass();
			$questions->question_id= $id;
			$questions->question_name= $name;
			$questions->questionnaire_id= $qu_id;
			array_push($result, $questions);
		}
		
		$stmt->close();
		
		return $result;
	}

	
	function delQuestionnaire($id){
		$stmt = $this->connection->prepare("DELETE FROM TAP_questions WHERE questionnaire_id=?");
		$stmt2 = $this->connection->prepare("DELETE FROM TAP_questionnaires WHERE id=?");
        $stmt->bind_param("i", $id);
		$stmt2->bind_param("i", $id);
        $stmt->execute();
		$stmt2->execute();
        $stmt->close();
		$stmt2->close();
	}

	
	function createQuestionnaireWithNameAndEmail($name, $email){

		$stmt = $this->connection->prepare("INSERT INTO TAP_questionnaires (name, author_email) VALUES (?, ?);");
		$stmt->bind_param("ss", $name, $email);
		$stmt->execute();
		$stmt->close;
	}
	
	
}
