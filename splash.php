<?php
require_once('../config.php');
require_once('dao/QW_DAO.php');

use \Tsugi\Core\LTIX;
use \QW\DAO\QW_DAO;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

$p = $CFG->dbprefix;

$QW_DAO = new QW_DAO($PDOX, $p);

$skipSplash = $QW_DAO->skipSplash($USER->id);

// Start of the output
$OUTPUT->header();

echo ('<link rel="stylesheet" type="text/css" href="styles/splash.css">
        <link rel="stylesheet" type="text/css" href="styles/animations.css">');

$OUTPUT->bodyStart();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-5 col-sm-offset-1" id="splashMessage">

            <h1 class="fadeIn">Quick Write</h1>

            <p class="fadeIn text-justify">
                Use this tool to add questions to collect feedback from students quickly. Instructors can create any number of questions to which they would like their students to respond.
            </p>
            <p class="fadeIn text-justify">
                Students will be able to see all of the available questions and respond to each all at once or over several sessions. However, students will not be able to edit an answer to a question once it has been submitted.
            </p>

            <div class="toggle-container fadeIn">
                <div class="checkbox">
                    <input type="hidden" id="sess" value="<?php echo($_GET["PHPSESSID"]) ?>">
                    <label><input id="toggleSplash" type="checkbox" value="showsplash" <?php if(!$skipSplash) echo('checked="checked"'); ?>
                                  onchange="toggleSkipSplash();"> Show this screen when using this tool for the first time.</label>
                </div>
                <span id="spinner" class="fa fa-spinner fa-pulse" style="display:none;"></span>
                <span id="done" class="fa fa-check" style="display:none;"></span>
            </div>

            <a href="instructor-home.php" class="fadeInUp btn btn-success">Get Started</a>

        </div>
        <div class="col-sm-5" id="splashImage">
            <img src="images/lisidore.png" width="100%" class="slideInRight">
        </div>
    </div>
</div>

<?php
$OUTPUT->footerStart();
?>
<script type="text/javascript">
    function toggleSkipSplash() {
        $("#spinner").show();
        var sess = $('input#sess').val();
        $.ajax({
            url: "actions/ToggleSkipSplashPage.php?PHPSESSID="+sess,
            success: function(response){
                $("#spinner").hide();
                $("#done").show();
                setTimeout(function() {
                    $("#done").fadeOut("slow");
                }, 5);
            }
        });
    }
</script>
<?php
$OUTPUT->footerEnd();
