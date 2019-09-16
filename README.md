# quickwrite
A simple Tsugi tool to prompt users to respond to short answer questions.

## TO CONVERT THE SCHEMA FROM THE OLD QUICK WRITE:
### qw_main
* Rename SetID to qw_id and update primary key
* Rename UserID to user_id and make not null
* Make context_id not null
* Make link_id not null
* Rename Modified to modified

### qw_questions -> qw_question
* Move all data from qw_questions to qw_question into correct columns with new names

### qw_answer
* Rename AnswerID to answer_id
* Rename UserID to user_id and make not null
* Remove SetID
* Rename QID to question_id and make not null
* Rename Answer to answer_txt
* Rename Modified to modified
