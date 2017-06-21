<?php
class Users{
    private $connection;
    function __construct($mysqli){
        $this->connection = $mysqli;
    }

    function login ($email, $password){

        $loginNotice = "";

        $stmt = $this->connection->prepare("SELECT id, email, password, created FROM TAP_accounts WHERE email=?");
        $stmt->bind_param("s", $email);

        $stmt->bind_result($id, $emailFromDatabase, $passwordFromDatabase, $created);
        $stmt->execute();

        //küsin rea andmeid
        if($stmt->fetch()){
            //oli rida siis võrdlen paroole
            $hash = hash("sha512", $password);
            if ($hash == $passwordFromDatabase){
                
                $_SESSION["userId"] = $id;
                $_SESSION['email'] = $emailFromDatabase;

                //suunaks uuele lehele
                header("Location: index.php");
            }else{
                $loginNotice = "Vale parool!";
            }

        }else{
            //ei olnud
            $loginNotice ="Kasutajat sellise e-postiga pole olemas!";
        }
        return $loginNotice;
    }
}
