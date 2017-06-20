<?php
require_once("app/config/config.php");

if (!isset($_SESSION)) {
    session_start();
}

// MySQLi connection

$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $dbName);
$mysqli->set_charset("utf8");

// Require classes
require("app/class/Users.php");
$Users = new Users($mysqli);
require("app/class/Helper.php");
$Helper = new Helper();
require("app/class/Question.php");
$Question = new Question($mysqli);
