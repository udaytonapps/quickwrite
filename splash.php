<?php
require_once('../config.php');

use \Tsugi\Core\LTIX;

// Retrieve the launch data if present
$LAUNCH = LTIX::requireData();

// Start of the output
$OUTPUT->header();

$OUTPUT->bodyStart();

$OUTPUT->topNav();

if ($USER->instructor) {
    $OUTPUT->splashPage(
        "Quick Write",
        __("Add questions to quickly collect<br />feedback from your students."),
        "actions/MarkSeenGoToHome.php"
    );
} else {
    $OUTPUT->splashPage(
        "Quick Write",
        __("Your instructor has not yet configured this learning app.")
    );
}

$OUTPUT->footerStart();

$OUTPUT->footerEnd();
