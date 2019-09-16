<?php
require_once "../../config.php";
require_once('../dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
$currentTimeForDB = $currentTime->format("Y-m-d H:i:s");

$questionId = $_POST["questionId"];
$answerText = $_POST["answerText"];

$result = array();

if (!isset($answerText) || trim($answerText) == "") {
    $_SESSION['error'] = "Your answer cannot be blank.";
    $result["answer_content"] = false;
} else {
    $QW_DAO->createAnswer($USER->id, $questionId, $answerText, $currentTimeForDB);

    $question = $QW_DAO->getQuestionById($questionId);
    $formattedDate = $currentTime->format("m/d/y")." | ".$currentTime->format("h:i A");

    ob_start();
    ?>
    <h3 class="sub-hdr"><?= $question["question_txt"] ?></h3>
    <p><?=$formattedDate?></p>
    <p><?=$answerText?></p>
    <?php
    $result["answer_content"] = ob_get_clean();

    $_SESSION['success'] = "Answer saved.";

    // Notify elearning that there is a new answer
    // the message
    $msg = "A new quick write was submitted on Learn by ".$QW_DAO->findDisplayName($USER->id)." (".$QW_DAO->findEmail($USER->id).").\n
    Question: ".$question["question_txt"]."\n
    Answer: ".$answerText;

    // use wordwrap() if lines are longer than 70 characters
    $msg = wordwrap($msg,70);

    $headers  = "From: LEARN < no-reply@learn.udayton.edu >\n";

    // send email
    //mail("elearning@udayton.edu", "A new quickwrite has been submitted on Learn", $msg, $headers);
}

$OUTPUT->buffer=true;
$result["flashmessage"] = $OUTPUT->flashMessages();

header('Content-Type: application/json');

echo json_encode($result, JSON_HEX_QUOT | JSON_HEX_TAG);

exit;

