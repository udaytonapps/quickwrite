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

    $questionId = $_POST["questionId"];
    $questionText = $_POST["questionText"];

    $currentTime = new DateTime('now', new DateTimeZone($CFG->timezone));
    $currentTime = $currentTime->format("Y-m-d H:i:s");

    if (isset($questionText) && trim($questionText) != '') {
        if ($questionId > -1) {
            // Existing question
            $QW_DAO->updateQuestion($questionId, $questionText, $currentTime);
        } else {
            // New question
            $questionId = $QW_DAO->createQuestion($_SESSION["qw_id"], $questionText, $currentTime);

            $question = $QW_DAO->getQuestionById($questionId);

            // Create new question markup
            ob_start();
            ?>
            <div id="questionRow<?=$question["question_id"]?>" class="h3 inline flx-cntnr flx-row flx-nowrap flx-start question-row" data-question-number="<?=$question["question_num"]?>">
                <div class="question-number"><?=$question["question_num"]?>.</div>
                <div class="flx-grow-all question-text">
                    <span class="question-text-span" onclick="editQuestionText(<?=$question["question_id"]?>)" id="questionText<?=$question["question_id"]?>"><?= $question["question_txt"] ?></span>
                    <form id="questionTextForm<?=$question["question_id"]?>" onsubmit="return confirmDeleteQuestionBlank(<?=$question["question_id"]?>)" action="actions/AddOrEditQuestion.php" method="post" style="display:none;">
                        <input type="hidden" name="questionId" value="<?=$question["question_id"]?>">
                        <label for="questionTextInput<?=$question["question_id"]?>" class="sr-only">Question Text</label>
                        <textarea class="form-control" id="questionTextInput<?=$question["question_id"]?>" name="questionText" rows="2" required><?=$question["question_txt"]?></textarea>
                    </form>
                </div>
                <a id="questionEditAction<?=$question["question_id"]?>" href="javascript:void(0);" onclick="editQuestionText(<?=$question["question_id"]?>)">
                    <span class="fa fa-fw fa-pencil" aria-hidden="true"></span>
                    <span class="sr-only">Edit Question Text</span>
                </a>
                <a id="questionReorderAction<?=$question["question_id"]?>" href="javascript:void(0);" onclick="moveQuestionUp(<?=$question["question_id"]?>)">
                    <span class="fa fa-fw fa-chevron-circle-up" aria-hidden="true"></span>
                    <span class="sr-only">Move Question Up</span>
                </a>
                <a id="questionDeleteAction<?=$question["question_id"]?>" href="javascript:void(0);" onclick="deleteQuestion(<?=$question["question_id"]?>)">
                    <span aria-hidden="true" class="fa fa-fw fa-trash"></span>
                    <span class="sr-only">Delete Question</span>
                </a>
                <a id="questionSaveAction<?=$question["question_id"]?>" href="javascript:void(0);" style="display:none;">
                    <span aria-hidden="true" class="fa fa-fw fa-save"></span>
                    <span class="sr-only">Save Question</span>
                </a>
                <a id="questionCancelAction<?=$question["question_id"]?>" href="javascript:void(0);" style="display: none;">
                    <span aria-hidden="true" class="fa fa-fw fa-times"></span>
                    <span class="sr-only">Cancel Question</span>
                </a>
            </div>
            <?php
            $result["new_question"] = ob_get_clean();
        }
        $_SESSION['success'] = 'Question Saved.';
    } else {
        if ($questionId > -1) {
            // Blank text means delete question
            $QW_DAO->deleteQuestion($questionId);
            // Set question id to false to remove question line
            $questionId = false;
            $_SESSION['success'] = 'Question Deleted.';
        } else {
            $_SESSION['error'] = 'Unable to save blank question.';
        }
    }

    $OUTPUT->buffer=true;
    $result["flashmessage"] = $OUTPUT->flashMessages();

    header('Content-Type: application/json');

    echo json_encode($result, JSON_HEX_QUOT | JSON_HEX_TAG);

    exit;
} else {
    header( 'Location: '.addSession('../student-home.php') ) ;
}

