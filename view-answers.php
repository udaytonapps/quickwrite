<?php

require_once('../config.php');
require_once('dao/QW_DAO.php');

use QW\DAO\QW_DAO;
use Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

if (!isset($_GET["question_id"])) {
    header( 'Location: '.addSession('instructor-home.php') ) ;
}

$question_id = $_GET["question_id"];

$question = $QW_DAO->getQuestionById($question_id);

$answers = $QW_DAO->getAllAnswersToQuestion($question_id);

$totalAnswers = count($answers);

// Start of the output
$OUTPUT->header();

include("tool-header.html");

if ($totalAnswers == 0) {
?>
<style type="text/css">
    body {
        background: #efefef url("images/crawling.png") no-repeat right 20px top 150px;
        background-size: 40% auto;
    }
</style>
<?php
}

$OUTPUT->bodyStart();

?>
<div class="container-fluid">
    <ol class="breadcrumb">
        <li><a href="instructor-home.php">Home</a></li>
        <li class="active">Answers</li>
    </ol>

    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <h3>Answers (<?php echo($totalAnswers); ?>)</h3>
            <div class="list-group fadeInFast" id="qwContentContainer">
                <div class="list-group-item">
                    <h4><?php echo($question["question_txt"]); ?></h4>
                </div>
                <?php
                foreach ($answers as $answer) {
                    ?>
                    <div class="list-group-item">
                        <div class="row">
                            <div class="col-sm-2">
                                <p>
                                    <strong><?php echo($QW_DAO->findDisplayName($answer["user_id"])); ?></strong>
                                    <?php
                                    $formattedAnswerDate = '';
                                    if ($answer) {
                                        $answerDateTime = new DateTime($answer['modified']);
                                        $formattedAnswerDate = $answerDateTime->format("m-d-y") . " at " . $answerDateTime->format("h:i A");
                                        echo('<br /><small>'.$formattedAnswerDate).'</small>';
                                    }
                                    ?>
                                </p>
                            </div>
                            <div class="col-sm-10">
                                <p>
                                    <?php echo($answer["answer_txt"]); ?>
                                </p>
                            </div>
                        </div>
                        </div>
                    <?php
                }
                ?>
            </div>
            <a href="instructor-home.php" class="btn btn-primary big-shadow fadeInFast">Back</a>
        </div>
    </div>
</div>

<?php
$OUTPUT->footerStart();

include("tool-footer.html");

$OUTPUT->footerEnd();
