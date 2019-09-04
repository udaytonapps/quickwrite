<?php

$questionsForImport = $QW_DAO->findQuestionsForImport($USER->id, $_SESSION["qw_id"]);

?>
<div id="importModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span class="fa fa-times" aria-hidden="true"></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Reuse Questions</h4>
            </div>
            <div class="modal-body import-body">
                <p class="lead">Add questions from a previously created Quick Write.</p>
                <?php
                if (!$questionsForImport) {
                    ?>
                    <p><em>No questions available from previous Quick Write instances.</em></p>
                    <?php
                } else {
                    $questionMap = array();
                    foreach ($questionsForImport as $question) {
                        if (!array_key_exists($question["sitetitle"], $questionMap)) {
                            $questionMap[$question["sitetitle"]] = array();
                        }
                        if (!array_key_exists($question["tooltitle"], $questionMap[$question["sitetitle"]])) {
                            $questionMap[$question["sitetitle"]][$question["tooltitle"]] = array();
                        }
                        array_push($questionMap[$question["sitetitle"]][$question["tooltitle"]], $question);
                    }
                    ?>
                    <form id="importForm" class="form" action="actions/ImportQuestions.php" method="post">
                        <div class="list-group">
                            <?php
                            $siteCount = 1;
                            foreach($questionMap as $sitetitle => $questions_in_site) {
                                ?>
                                <div class="list-group-item response-list-group-item">
                                    <div class="row">
                                        <div class="col-sm-12 header-col">
                                            <a data-toggle="collapse" class="h4 response-collapse-link" href="#site<?=$siteCount?>">
                                                <?=$sitetitle?>
                                                <span class="fa fa-chevron-down rotate" aria-hidden="true"></span>
                                            </a>
                                        </div>
                                        <div id="site<?=$siteCount?>" class="col-xs-12 results-collapse collapse">
                                        <?php
                                        foreach ($questions_in_site as $tooltitle => $questions_in_tool) {
                                            ?>
                                            <div class="row response-row">
                                                <div class="col-sm-3">
                                                    <h5><?=$tooltitle?></h5>
                                                </div>
                                                <div class="col-sm-offset-1 col-sm-8">
                                                    <?php
                                                    foreach ($questions_in_tool as $question) {
                                                        ?>
                                                        <div class="checkbox">
                                                            <input type="checkbox" id="question<?=$question["question_id"]?>" name="question[]" value="<?=$question["question_id"]?>">
                                                            <label for="question<?=$question["question_id"]?>"><?=$question["question_txt"]?></label>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $siteCount++;
                            }
                            ?>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="document.getElementById('importForm').submit();">Reuse Question(s)</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
