<?php

class Utils {
    // Comparator for student last name used for sorting roster
    public static function compareStudentsLastName($a, $b) {
        return strcmp($a["person_name_family"], $b["person_name_family"]);
    }

}