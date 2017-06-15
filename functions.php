<?php

require_once("/home/vladsuto/config.php");

if(!isset($_SESSION))
{
    session_start();
}

$dbName = "if16_vladsuto_TAP";
$mysqli = new mysqli($serverHost, $serverUsername, $serverPassword, $dbName);
$mysqli->set_charset("utf8");


require("assets/class/users.class.php");
$Users = new Users($mysqli);
require("assets/class/helper.class.php");
$Helper = new Helper();
require("assets/class/question.class.php");
$Question = new Question($mysqli);
