<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use QW\DAO\QW_DAO;
use Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$students = $QW_DAO->getUsersWithAnswers($_SESSION["qw_id"]);
$studentAndDate = array();
foreach ($students as $student) {
    $studentAndDate[$student["user_id"]] = new DateTime($QW_DAO->getMostRecentAnswerDate($student["user_id"], $_SESSION["qw_id"]));
}

$questions = $QW_DAO->getQuestions($_SESSION["qw_id"]);
$totalQuestions = count($questions);


include("menu.php");

// Start of the output
$OUTPUT->header();

include("tool-header.html");
?>
    <style>
        #results {
            display: none;
        }
    </style>
<?php
$OUTPUT->bodyStart();

$OUTPUT->topNav($menu);

echo '<div class="container-fluid">';

$OUTPUT->flashMessages();

$OUTPUT->pageTitle('Download Results', true, false);
?>
    <p class="lead">Click on the link below to download the student results.</p>
    <table id="results" class="table table-striped table-bordered" style="width:100%;">
        <thead>
        <tr>
            <th>Student Name</th>
            <th>Most Recent Submission</th>
            <?php
            for ($qnum = 1; $qnum <= $totalQuestions; $qnum++) {
                echo '<th>Question ' . $qnum . '</th>';
            }
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach ($studentAndDate as $student_id => $mostRecentDate) {
            if (!$QW_DAO->isUserInstructor($CONTEXT->id, $student_id)) {
                $formattedMostRecentDate = $mostRecentDate->format("m/d/y") . " " . $mostRecentDate->format("h:i A");
                $numberAnswered = $QW_DAO->getNumberQuestionsAnswered($student_id, $_SESSION["qw_id"]);
                ?>
                <tr>
                    <td><?= $QW_DAO->findDisplayName($student_id) ?></td>
                    <td><?= $formattedMostRecentDate ?></td>
                    <?php
                    foreach ($questions as $question) {
                        $response = $QW_DAO->getStudentAnswerForQuestion($question["question_id"], $student_id);
                        ?>
                        <td><?= $response["answer_txt"] ?></td>
                        <?php
                    }
                    ?>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
<?php
echo '</div>';
$OUTPUT->helpModal("Quick Write Help", __('
                        <h4>Downloading Results</h4>
                        <p>Click on the link to download an Excel file with all of the results for this Quick Write.</p>'));

$OUTPUT->footerStart();
?>
    <script>
        $(document).ready(function () {
            $("#results").DataTable({
                order: [[0, "asc"]],
                dom: '<"h4"B>t',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<span class="fa fa-download" aria-hidden="true"></span> Download All Results (.xlsx)',
                        title: '<?=$CONTEXT->title?>_QuickWrite_Results',
                        className: 'btn btn-primary'
                    }
                ]
            });
        });
    </script>
<?php
include("tool-footer.html");

$OUTPUT->footerEnd();
