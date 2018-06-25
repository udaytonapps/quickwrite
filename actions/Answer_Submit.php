<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
$currentTime = $currentTime->format("Y-m-d H:i:s");

for ($x = 1; $x < ($_POST["Total"]+1); $x++) {
    $answerId = $_POST['AnswerID'.$x];
    $questionId = $_POST['QuestionID'.$x];
    $answerText = $_POST['A'.$x];

    if ($answerId > -1) {
        // Existing answer check if it needs to be updated
        $oldAnswer = $QW_DAO->getAnswerById($answerId);

        if ($answerText !== $oldAnswer['answer_txt']) {
            // Answer has changed so update
            $QW_DAO->updateAnswer($answerId, $answerText, $currentTime);
        }
    } else if ($answerText != '') {
        // New answer
        $QW_DAO->createAnswer($USER->id, $questionId, $answerText, $currentTime);
    }
}

header( 'Location: '.addSession('../student-home.php') ) ;

