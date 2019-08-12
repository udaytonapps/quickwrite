<?php
if ($USER->instructor) {
    $menu = new \Tsugi\UI\MenuSet();
    $menu->setHome('Quick Write', 'index.php');
    if ('student-home.php' != basename($_SERVER['PHP_SELF'])) {
        $menu->addRight('<span class="fas fa-user-graduate" aria-hidden="true"></span> Student View', 'student-home.php');
        $menu->addRight('<span class="fas fa-clipboard-check" aria-hidden="true"></span> Grade', 'grade.php');
        $results = array(
            new \Tsugi\UI\MenuEntry("By Student", "results-student.php"),
            new \Tsugi\UI\MenuEntry("By Question", "results-question.php"),
            new \Tsugi\UI\MenuEntry("Download Results", "results-download.php")
        );
        $menu->addRight('<span class="fas fa-poll-h" aria-hidden="true"></span> Results', $results);
        $menu->addRight('<span class="fas fa-edit" aria-hidden="true"></span> Build', 'instructor-home.php');
    } else {
        $menu->addRight('Exit Student View <span class="fas fa-sign-out-alt" aria-hidden="true"></span>', 'instructor-home.php');
    }
} else {
    // No menu for students
    $menu = false;
}