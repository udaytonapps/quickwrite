<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
    // Nothing
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
    array( "{$CFG->dbprefix}qw_splash",
        "create table {$CFG->dbprefix}qw_splash (
    user_id       INTEGER NULL,
    skip_splash   BOOL NOT NULL DEFAULT 0,
    PRIMARY KEY(user_id)
	
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}qw_main",
        "create table {$CFG->dbprefix}qw_main (
    qw_id       INTEGER NOT NULL AUTO_INCREMENT,
    user_id     INTEGER NULL,
    context_id  INTEGER NULL,
	link_id     INTEGER NULL,
    modified    datetime NULL,
    
    PRIMARY KEY(qw_id)
	
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),    
    array( "{$CFG->dbprefix}qw_question",
        "create table {$CFG->dbprefix}qw_question (
    question_id   INTEGER NOT NULL AUTO_INCREMENT,
    qw_id         INTEGER NOT NULL,
    question_num  INTEGER NULL,
    question_txt  TEXT NULL,   
    modified      datetime NULL,
    
    CONSTRAINT `{$CFG->dbprefix}qw_ibfk_1`
        FOREIGN KEY (`qw_id`)
        REFERENCES `{$CFG->dbprefix}qw_main` (`qw_id`)
        ON DELETE CASCADE,

    PRIMARY KEY(question_id)
	
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}qw_answer",
        "create table {$CFG->dbprefix}qw_answer (
    answer_id    INTEGER NOT NULL AUTO_INCREMENT,
    user_id      INTEGER NOT NULL,
    question_id  INTEGER NOT NULL,
	answer_txt   TEXT NULL,
    modified     datetime NULL,
    
    CONSTRAINT `{$CFG->dbprefix}qw_ibfk_2`
        FOREIGN KEY (`question_id`)
        REFERENCES `{$CFG->dbprefix}qw_question` (`question_id`)
        ON DELETE CASCADE,
    
    PRIMARY KEY(answer_id)
    
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);