<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

date_default_timezone_set('America/New_York');

$currentDate = date("Y-m-d H:i:s");

$SetID = $_SESSION["SetID"];

for ($x = 1; $x < ($_POST["Total"]+1); $x++) {
    $answerId = $_POST['AnswerID'.$x];
    $questionId = $_POST['QuestionID'.$x];
    $answerText = str_replace("'", "&#39;", $_POST['A'.$x]);

    if ($answerId > -1) {
        // Existing answer check if it needs to be updated
        $oldAnswer = $QW_DAO->getAnswerById($answerId);

        if ($answerText !== $oldAnswer['Answer']) {
            // Answer has changed so update
            $QW_DAO->updateAnswer($answerId, $answerText, $currentDate);
        }
    } else if ($answerText != '') {
        // New answer
        $QW_DAO->createAnswer($USER->id, $SetID, $questionId, $answerText, $currentDate);
    }
}

header( 'Location: '.addSession('../student-home.php') ) ;

