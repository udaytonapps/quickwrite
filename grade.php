<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$students = $QW_DAO->getUsersWithAnswers($_SESSION["qw_id"]);
$studentAndDate = array();
foreach($students as $student) {
    $studentAndDate[$student["user_id"]] = new DateTime($QW_DAO->getMostRecentAnswerDate($student["user_id"], $_SESSION["qw_id"]));
}

$questions = $QW_DAO->getQuestions($_SESSION["qw_id"]);
$totalQuestions = count($questions);

include("menu.php");

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$OUTPUT->topNav($menu);

echo '<div class="container-fluid">';

$OUTPUT->flashMessages();

$OUTPUT->pageTitle('Grade', false, false);

?>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead>
        <th>Student Name</th>
        <th>Last Updated</th>
        <th>Completed</th>
        <th>Grade</th>
        </thead>
        <tbody>
<?php
// Sort students by mostRecentDate desc
arsort($studentAndDate);
foreach ($studentAndDate as $student_id => $mostRecentDate) {
    if (!$QW_DAO->isUserInstructor($CONTEXT->id, $student_id)) {
        $formattedMostRecentDate = $mostRecentDate->format("m/d/y") . " | " . $mostRecentDate->format("h:i A");
        $numberAnswered = $QW_DAO->getNumberQuestionsAnswered($student_id, $_SESSION["qw_id"]);
        ?>
        <tr>
            <td><?= $QW_DAO->findDisplayName($student_id) ?></td>
            <td><?= $formattedMostRecentDate ?></td>
            <td><?= $numberAnswered . '/' . $totalQuestions ?></td>
            <td></td>
        </tr>
        <?php
    }
}
?>
        </tbody>
    </table>
</div>
<?php

echo ("</div>"); // End container

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
