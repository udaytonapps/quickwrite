<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if ($USER->instructor) {

    $questions = isset($_POST["question"]) ? $_POST["question"] : false;

    if (!$questions) {
        $_SESSION["error"] = "Question(s) failed to save. Please try again.";
    } else {
        $currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
        $currentTime = $currentTime->format("Y-m-d H:i:s");

        foreach($questions as $question) {
            $origQuestion = $QW_DAO->getQuestionById($question);

            if($origQuestion) {
                $QW_DAO->createQuestion($_SESSION["qw_id"], $origQuestion["question_txt"], $currentTime);
            }
        }

        $_SESSION['success'] = 'Question(s) Saved.';
    }

    header( 'Location: '.addSession('../instructor-home.php') ) ;
} else {
    header( 'Location: '.addSession('../student-home.php') ) ;
}
