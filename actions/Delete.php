<?php
require_once "../../config.php";
require_once "../dao/QW_DAO.php";

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$SetID = $_SESSION["SetID"];
$QID = isset($_GET["QID"]) ? $_GET["QID"] : -1;

if ( $USER->instructor ) {

    if ($QID > -1) {
        // Delete all answers
        $QW_DAO->deleteAnswersToQuestion($QID);

        // Delete the quesiton
        $QW_DAO->deleteQuestion($QID);

        // Fix numbering
        $remainingQ = $QW_DAO->getQuestions($SetID);
        $QNum = 0;
        foreach ( $remainingQ as $question ) {
            $QNum++;
            $QW_DAO->updateQNumber($question["QID"], $QNum);
        }
    }

    header( 'Location: '.addSession('../instructor-home.php') ) ;
} 
