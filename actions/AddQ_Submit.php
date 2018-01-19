<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if ( $USER->instructor ) {
	$Question = str_replace("'", "&#39;", $_POST["Question"]);

	$QID = $_POST["QID"];

	if ($QID > -1) {
	    // Existing question
	    $QW_DAO->updateQuestion($QID, $Question);
    } else {
	    // New question
        $QNum = $_POST["QNum"];
        $SetID = $_SESSION["SetID"];
        $QW_DAO->createQuestion($SetID, $QNum, $Question);
    }

    header( 'Location: '.addSession('../instructor-home.php') ) ;
}
