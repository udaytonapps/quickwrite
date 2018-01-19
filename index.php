<?php
require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$Main = $QW_DAO->getSetID($CONTEXT->id, $LINK->id);

if (!$Main) {
    $_SESSION["SetID"] = $QW_DAO->createMain($USER->id, $CONTEXT->id, $LINK->id);
} else {
    $_SESSION["SetID"] = $Main;
}

if ( $USER->instructor ) {

    header( 'Location: '.addSession('instructor-home.php') ) ;

} else { // student

	header( 'Location: '.addSession('student-home.php') ) ;
}
