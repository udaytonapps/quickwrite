<?php
?>
<div id="helpModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span class="fa fa-times" aria-hidden="true"></span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">Quick Write Help</h4>
            </div>
            <div class="modal-body">
                <?php
                switch(basename($_SERVER['PHP_SELF'])) {
                    case 'instructor-home.php':
                        ?>
                        <h4>General Help</H4>
                        <p>Use this page to add questions for your students to answer. Once you add a question it is immediately available to students.</p>
                        <h5>Adding a Question</h5>
                        <ol>
                            <li>Click "Add Question"</li>
                            <li>Enter the question text.</li>
                            <li>Click the save icon or press "Enter" on your keyboard</li>
                        </ol>
                        <h5>Modifying Questions</h5>
                        <p>Use the icons to the right of an added question to edit the text, move it up in the list, or delete it along with any answers.</p>
                        <h5>Editing the Title</h5>
                        <p>You can edit the title of this Quick Write by clicking the edit icon next to the title at the top of this page.</p>
                        <?php
                        break;
                    case 'student-home.php':
                        if ($USER->instructor) {
                            ?>
                            <h4>Student View</h4>
                            <p>You are seeing what a student will see when they access this tool. However, your answers will be cleared once you leave student view.</p>
                            <p>Your answers will not show up in any of the results.</p>
                            <?php
                        } else {
                            ?>
                            <h4>What do I do?</h4>
                            <p>Answer each question below. You must submit every question individually. Once you submit an answer to a question you can NOT edit your answer.</p>
                            <?php
                        }
                        break;
                    case 'results-question.php':
                        ?>
                        <h4>Viewing Results</H4>
                        <p>You are viewing the results by question. Click on a question below to see what students answered for that question.</p>
                        <p>For each question, students are sorted with the most recently modified at the top.</p>
                        <?php
                        break;
                    case 'results-student.php':
                        ?>
                        <h4>Viewing Results</H4>
                        <p>You are viewing the results by student. Click on a student below to see how that student answered each question.</p>
                        <p>Students are sorted with the most recently submitted at the top of the list.</p>
                        <?php
                        break;
                    case 'results-download.php':
                        ?>
                        <h4>Downloading Results</H4>
                        <p>Click on the link to download an Excel file with all of the results for this Quick Write.</p>
                        <?php
                        break;
                    default:
                        ?>
                        <em>No help for this page.</em>
                        <?php
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
