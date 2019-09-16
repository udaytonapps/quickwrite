<?php
require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
$currentTime = $currentTime->format("Y-m-d H:i:s");

if ( $USER->instructor ) {

    $_SESSION["qw_id"] = $QW_DAO->getOrCreateMain($USER->id, $CONTEXT->id, $LINK->id, $currentTime);

    $seenSplash = $QW_DAO->hasSeenSplash($_SESSION["qw_id"]);

    if ($seenSplash) {
        // Instructor has already setup this instance
        header( 'Location: '.addSession('instructor-home.php') ) ;
    } else {
        header('Location: '.addSession('splash.php'));
    }
} else { // student

    $mainId = $QW_DAO->getMainID($CONTEXT->id, $LINK->id);

    if (!$mainId) {
        header('Location: '.addSession('splash.php'));
    } else {
        $_SESSION["qw_id"] = $mainId;

        $questions = $QW_DAO->getQuestions($_SESSION["qw_id"]);

        if (!$questions || count($questions) == 0) {
            header('Location: '.addSession('splash.php'));
        } else {
            header( 'Location: '.addSession('student-home.php') ) ;
        }
    }
}
