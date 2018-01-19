<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();    

echo('<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Quick Write</a>
        </div>
    </div>
    </nav>');

echo ('<div class="container-fluid">
        <h2 class="tool-title col-sm-offset-1">Quick Write</h2>');

$SetID = $_SESSION["SetID"];

$questions = $QW_DAO->getQuestions($SetID);	
$totalQuestions = count($questions);

if($totalQuestions == 0) {
    echo ('<h4 style="margin:50px;">No question prompts have been created.</h4>');
} else {

    echo('<form method="post" action="actions/Answer_Submit.php">');

    echo('<div id="answerContainer">');
    $moreToSubmit = false;
    foreach ( $questions as $question ) {
        $answerText = "";
        $QID = $question["QID"];
        $answerId = -1;

        $answer = $QW_DAO->getStudentAnswerForQuestion($QID, $USER->id);

        if ($answer) {
            $answerId = $answer['AnswerID'];
            $answerText = $answer['Answer'];
        }

        if (!$answer || $answerText == "") {
            echo('<div class="row">
                    <div class="col-sm-2 text-right question-number"><h4>'.$question["QNum"].'.</h4></div>
                    <div class="col-sm-8 question-text">'.$question["Question"].'</div>
                  </div>');
            echo('<div class="row">
                    <div class="col-sm-8 col-sm-offset-2 answer-date">
                        <textarea class="form-control" name="A'.$question["QNum"].'" rows="3" autofocus></textarea>');
            $moreToSubmit = true;
        } else {
            $dateTime = new DateTime($answer['Modified']);
            $formattedDate = $dateTime->format("m-d-y")." at ".$dateTime->format("h:i A");

            echo('<div class="row">
                    <div class="col-sm-2 text-right question-number">
                      <h4><span class="fa fa-check fa-lg text-success checkmark"></span> '.$question["QNum"].'.</h4>
                    </div>
                    <div class="col-sm-8 question-text">'.$question["Question"].'</div>
                  </div>');
            echo('<div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="answer-text">'.$answerText.'<br />
                            <input type="hidden" name="A'.$question["QNum"].'" value="'.$answerText.'" />
                        </div>
                        <div class="answer-date text-right">
                            <small><em>'.$formattedDate.'</em></small>
                        </div>');
        }

        echo('<input type="hidden" name="QuestionID'.$question["QNum"].'" value="'.$QID.'"/>');
        echo('<input type="hidden" name="AnswerID'.$question["QNum"].'" value="'.$answerId.'"/>');

        echo('</div></div>'); // End last column and row
    }

    if ($moreToSubmit) {
        echo('<div class="row"><div class="col-sm-10 col-sm-offset-2 answer-submit"><input type="submit" class="btn btn-success" value="Submit"></div></div>');
    }

    echo('</div>'); // End question container

    echo ('<input type="hidden" name="Total" value="'.$totalQuestions.'"/>');

    echo('</form>');
}

echo ('</div>'); // End container

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
