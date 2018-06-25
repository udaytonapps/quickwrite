<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use QW\DAO\QW_DAO;
use Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

// Start of the output
$OUTPUT->header();

include("tool-header.html");

?>
<style type="text/css">
    body {
        background: #efefef;
    }
</style>
<?php

$OUTPUT->bodyStart();

$mainId = $_SESSION["qw_id"];

$questions = $QW_DAO->getQuestions($mainId);

$totalQuestions = count($questions);

$StudentList = $QW_DAO->getUsersWithAnswers($mainId);

?>
<div class="container-fluid">
    <ol class="breadcrumb">
        <li><a href="instructor-home.php">Home</a></li>
        <li class="active">All Results</li>
    </ol>

    <div class="row">

        <div class="col-sm-10 col-sm-offset-1">

            <h2>All Results<span class="pull-right export-link"><a href="actions/ExportToFile.php"><span class="fa fa-cloud-download" aria-hidden="true"></span> Export Results</a></span></h2>

            <?php
            if (!$StudentList) {
                echo ('<h4 class="text-center"><em>No students have answered yet.</em></h4>');
            } else {
            ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped fadeInFast" id="allResultsTable">
                        <thead>
                        <tr>
                            <th>Student</th>
                            <th>Answers</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ( $StudentList as $student ) {
                            $userId = $student['user_id'];
                            $displayName = $QW_DAO->findDisplayName($userId);
                            $mostRecentDate = new DateTime($QW_DAO->getMostRecentAnswerDate($userId, $mainId));
                            $formattedDate = $mostRecentDate->format("m-d-y")." at ".$mostRecentDate->format("h:i A");

                            echo ('<tr>
                                    <th rowspan="'.($totalQuestions*2).'" class="col-sm-2">
                                        '.$displayName.'<br /><small class="text-muted date">'.$formattedDate.'</small>
                                    </th>
                                   ');

                            $firstQuestion = true;
                            foreach ( $questions as $question ) {

                                $question_id = $question['question_id'];

                                if (!$firstQuestion) {
                                    echo ('<tr>');
                                }

                                echo ('<td class="question-col">
                                        '.$question["question_txt"].'
                                       </td>');

                                if ($firstQuestion) {
                                    $firstQuestion = false;
                                    echo ('</tr>');
                                }

                                $answer = $QW_DAO->getStudentAnswerForQuestion($question_id, $userId);

                                $answerText = "";
                                if ($answer) {
                                    $answerText = $answer["answer_txt"];
                                } else {
                                    $answerText = '<span class="text-muted"><em>No answer</em></span>';
                                }
                                echo ('<tr><td class="answer-col">'.$answerText.'</td></tr>');

                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>

            <?php
            }
            ?>
            <a href="instructor-home.php" class="btn btn-primary fadeInFast big-shadow">Back</a>
        </div>
    </div>
</div>

<?php
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
