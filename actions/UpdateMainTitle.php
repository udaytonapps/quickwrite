<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if ($USER->instructor) {

    $result = array();

    if (isset($_POST["toolTitle"])) {
        $currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
        $currentTime = $currentTime->format("Y-m-d H:i:s");

        $QW_DAO->updateMainTitle($_SESSION["qw_id"], $_POST["toolTitle"], $currentTime);

        $_SESSION['success'] = "Title saved.";
    } else {
        $_SESSION['error'] = "Title failed to save. Please try again.";
    }

    $OUTPUT->buffer=true;
    $result["flashmessage"] = $OUTPUT->flashMessages();

    header('Content-Type: application/json');

    echo json_encode($result, JSON_HEX_QUOT | JSON_HEX_TAG);

    exit;
} else {
    header( 'Location: '.addSession('../student-home.php') ) ;
}

