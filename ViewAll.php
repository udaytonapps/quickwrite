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

$OUTPUT->bodyStart();

include("menu.php");

echo ('<div class="container">');

echo('<div class="pull-right">
        <a href="actions/ExportToFile.php"><span class="fa fa-cloud-download" aria-hidden="true"></span> Export Results</a>
      </div>');

echo('<h2 class="tool-title">All Submissions</h2>');

$SetID = $_SESSION["SetID"];

$StudentList = $QW_DAO->getUsersWithAnswers($SetID);

if (!$StudentList) {
    echo ('<h4 class="text-center"><em>No students have answered yet.</em></h4>');
}

$questions = $QW_DAO->getQuestions($SetID);

echo('<div id="allResultsContainer">');

foreach ( $StudentList as $student ) {

    $userId = $student['UserID'];

    $displayName = $QW_DAO->findDisplayName($userId);

    $mostRecentDate = new DateTime($QW_DAO->getMostRecentAnswerDate($userId, $SetID));

    $formattedDate = $mostRecentDate->format("m-d-y")." at ".$mostRecentDate->format("h:i A");

    echo('<div class="row">
            <div class="col-sm-3"><strong>'.$displayName.'</strong><br /><small>'.$formattedDate.'</small></div>
            <div class="col-sm-9">');

    foreach ( $questions as $question ) {

        $QID = $question['QID'];

        $answer = $QW_DAO->getStudentAnswerForQuestion($QID, $userId);

        $formattedAnswerDate = '';
        if ($answer) {
            $answerDateTime = new DateTime($answer['Modified']);
            $formattedAnswerDate = $answerDateTime->format("m-d-y") . " at " . $answerDateTime->format("h:i A");
        }

        echo('<div class="row">
                  <div class="col-sm-2 question-number"><strong>Question '.$question['QNum'].'</strong></div>
                  <div class="col-sm-10">
                    <div class="answer-text">');

                    if($answer && $answer['Answer'] !== '') {
                        echo($answer['Answer']);
                    } else {
                        echo('<em>No response</em>');
                    }

                    echo('
                    </div>
                    <div class="answer-date text-right">
                        <small><em>'.$formattedAnswerDate.'</em></small>
                    </div>
                  </div>
              </div>');
    }

    echo('</div></div>'); // End column and row
}

echo ('</div></div>'); // End both containers

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
