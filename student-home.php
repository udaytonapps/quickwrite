<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$SetID = $_SESSION["qw_id"];

$toolTitle = $QW_DAO->getMainTitle($_SESSION["qw_id"]);

if (!$toolTitle) {
    $toolTitle = "Quick Write";
}

$questions = $QW_DAO->getQuestions($SetID);
$totalQuestions = count($questions);

$moreToSubmit = false;

include("menu.php");

// Start of the output
$OUTPUT->header();

include("tool-header.html");

$OUTPUT->bodyStart();

$OUTPUT->topNav($menu);

echo '<div class="container-fluid">';

$OUTPUT->flashMessages();

$OUTPUT->pageTitle($toolTitle, true, false);

if ($totalQuestions > 0) {
        foreach ($questions as $question) {
            $answer = $QW_DAO->getStudentAnswerForQuestion($question["question_id"], $USER->id);
            ?>
            <h2 class="small-hdr <?= $question["question_num"] == 1 ? 'hdr-notop-mrgn' : '' ?>">
                <small>Question <?= $question["question_num"] ?></small>
            </h2>
            <div id="questionAnswer<?= $question["question_id"] ?>">
                <?php
                if (!$answer) {
                    ?>
                    <form id="answerForm<?= $question["question_id"] ?>" action="actions/AnswerQuestion.php"
                          method="post">
                        <input type="hidden" name="questionId" value="<?= $question["question_id"] ?>">
                        <div class="form-group">
                            <label class="h3"
                                   for="answerText<?= $question["question_id"] ?>"><?= $question["question_txt"] ?></label>
                            <textarea class="form-control" id="answerText<?= $question["question_id"] ?>"
                                      name="answerText" rows="5"></textarea>
                        </div>
                        <button type="button" class="btn btn-success"
                                onclick="answerQuestion(<?= $question["question_id"] ?>);">Submit
                        </button>
                    </form>
                    <?php
                } else {
                    $dateTime = new DateTime($answer['modified']);
                    $formattedDate = $dateTime->format("m/d/y") . " | " . $dateTime->format("h:i A");
                    ?>
                    <h3 class="sub-hdr"><?= $question["question_txt"] ?></h3>
                    <p><?= $formattedDate ?></p>
                    <p><?= $answer["answer_txt"] ?></p>
                    <?php
                }
                ?>
            </div>
            <?php
        }
        ?>
    </div>
    <?php
} else {
    ?>
        <p class="lead">Your instructor has not yet configured this learning app.</p>
    </div>
    <?php
}

if ($USER->instructor) {
    $OUTPUT->helpModal("Quick Write Help", __('
                        <h4>Student View</h4>
                        <p>You are seeing what a student will see when they access this tool. However, your answers will be cleared once you leave student view.</p>
                        <p>Your answers will not show up in any of the results.</p>'));
} else {
    $OUTPUT->helpModal("Quick Write Help", __('
                        <h4>What do I do?</h4>
                        <p>Answer each question below. You must submit every question individually. Once you submit an answer to a question you cannot edit your answer.</p>'));
}

$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
