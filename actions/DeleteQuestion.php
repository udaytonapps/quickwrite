<?php
require_once "../../config.php";
require_once "../dao/QW_DAO.php";

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$question_id = isset($_POST["question_id"]) ? $_POST["question_id"] : false;

if ( $USER->instructor && $question_id ) {

    $QW_DAO->deleteQuestion($question_id);

    $QW_DAO->fixUpQuestionNumbers($_SESSION["qw_id"]);

    $_SESSION['success'] = "Question Deleted.";

    $OUTPUT->buffer=true;
    $result["flashmessage"] = $OUTPUT->flashMessages();

    header('Content-Type: application/json');

    echo json_encode($result, JSON_HEX_QUOT | JSON_HEX_TAG);

    exit;
} else {
    header( 'Location: '.addSession('../student-home.php') ) ;
}

