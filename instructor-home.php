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

include("menu.php");

$SetID = $_SESSION["SetID"];

$questions = $QW_DAO->getQuestions($SetID);
$totalQuestions = count($questions);

$_SESSION["Next"] = $totalQuestions + 1;

$numQuestionsMsg = $totalQuestions . " " . (($totalQuestions == 1) ? "question" : "questions") . " posted";

echo ('<div class="container-fluid">');

echo('<div><h2 class="tool-title">Quick Write</h2></div>');

echo('<div id="Btn01" class="pull-right">
        <span><em>'.$numQuestionsMsg.'</em></span>
        </div>');

echo ('<div><p>Add questions to quickly collect feedback from your students.</p></div>');

echo ('<div id="questionContainer">');
foreach ($questions as $question ) {

    $QID = $question['QID'];

    $answers = $QW_DAO->getAllAnswersToQuestion($SetID, $QID);
    $numAnswers = count($answers);

    // Question Row
    echo('<div class="row">           
            <div class="col-sm-1 text-right question-number">
                <h4>'.$question["QNum"].'.</h4>
            </div>
            
            <div class="col-sm-8 question-text">
                <h4>'.$question["Question"].'</h4>
            </div>
            
            <div class="col-sm-3 text-right question-actions">
                <a href="#'.$QID.'"  class="btn btn-primary"  data-toggle="modal">Report ('.$numAnswers.') </a>
                <a href="#Edit_'.$QID.'" class="btn btn-success" data-toggle="modal">Edit</a>
                <a class="btn btn-danger" href="actions/Delete.php?QID='.$QID.'" onclick="return confirmDelete();">
                    <span class="fa fa-trash"></span>
                </a>
            </div>
          </div>');

    ?>

    <!-- Modal with student answers -->
    <div class="modal fade" id="<?php echo $QID; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-primary ">
                    <button type="button" class="close" data-dismiss="modal"><span class="fa fa-times bg-primary" aria-hidden="true"></span><span class="sr-only">Close</span></button>
                    <?php echo ('<h3 id="qTitle">Question '.$question["QNum"].'</h3><h4>'.$question["Question"].'</h4>');?>
                </div>
                <div class="modal-body student-answers">
                    <?php

                    if ($numAnswers === 0) {
                        echo("<h4 class='text-center'><em>No students have answered this question yet.</em></h4>");
                    } else {
                        foreach ( $answers as $answer ) {
                            $UserID = $answer["UserID"];

                            $displayName = $QW_DAO->findDisplayName($UserID);

                            $answerText = $answer["Answer"];
                            $answerDate = new DateTime($answer["Modified"]);

                            $formattedDate = $answerDate->format("m-d-y")." at ".$answerDate->format("h:i A");

                            echo('<div class="row">
                                <div class="col-sm-3"><strong>'.$displayName.'</strong><br /><small>'.$formattedDate.'</small></div>
                                <div class="col-sm-9">'.$answerText.'</div>
                              </div>');
                        }
                    }
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Question Text Modal -->
    <div class="modal fade" id="Edit_<?php echo $QID; ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Edit Question Text</h4>
                </div>
                <?php
                echo ('<form method="post" action="actions/AddQ_Submit.php">
                        <div class="modal-body">
                            <input type="hidden" name="QID" value="'.$QID.'"/>
                            <textarea class="form-control" name="Question" rows="4" autofocus required>'.$question["Question"].'</textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-success" value="Save">
                        </div>
                      </form>');
                ?>
            </div>
        </div>
    </div>

    <?php
}
echo("</div>"); // End question container

// Add Question Modal
?>

    <div class="modal fade" id="addQuestion" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Question <?php echo($_SESSION["Next"]); ?></h4>
                </div>
                <?php
                echo ('<form method="post" action="actions/AddQ_Submit.php">
                        <div class="modal-body">
                            <input type="hidden" name="QID" value="-1"/>
                            <input type="hidden" name="QNum" value="'.$_SESSION["Next"].'"/>
                            <textarea class="form-control" name="Question" id="questionText" rows="4" autofocus required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            <input type="submit" class="btn btn-success" value="Save">
                        </div>
                      </form>');
                ?>
            </div>
        </div>
    </div>

<?php

echo ('<div class="col-sm-8 col-sm-offset-1 add-button"><a class="btn btn-success" href="#addQuestion" data-toggle="modal"><span class="fa fa-plus" aria-hidden="true"></span> Add Question</a></div>');

echo ('</div>'); // End container

$OUTPUT->footerStart();

include("tool-footer.html");

?>
<script type="text/javascript">
    $(document).ready(function(){
        // Clear any entered question text on modal hide
        var addModal = $("#addQuestion");
        addModal.on('hidden.bs.modal', function() {
            $("#questionText").val('');
        });
        addModal.on('shown.bs.modal', function() {
            $("#questionText").focus();
        })
    });
</script>
<?php
$OUTPUT->footerEnd();
