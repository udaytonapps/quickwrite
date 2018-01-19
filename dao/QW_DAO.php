<?php
namespace QW\DAO;

class QW_DAO {

    private $PDOX;
    private $p;

    public function __construct($PDOX, $p) {
        $this->PDOX = $PDOX;
        $this->p = $p;
    }

    function getSetID($context_id, $link_id) {
        $query = "SELECT SetID FROM {$this->p}qw_main WHERE context_id = :context_id AND link_id = :link_id";
        $arr = array(':context_id' => $context_id, ':link_id' => $link_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["SetID"];
    }

    function siteExists($context_id, $link_id) {
        return $this->getSetId($context_id, $link_id) !== false;
    }

    function createMain($userId, $context_id, $link_id) {
        $query = "INSERT INTO {$this->p}qw_main (UserID, context_id, link_id) VALUES (:userId, :contextId, :link_id);";
        $arr = array(':userId' => $userId, ':contextId' => $context_id, ':link_id' => $link_id);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function getQuestions($SetID) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE SetID=".$SetID." order by QNum;";
        return $this->PDOX->allRowsDie($query);
    }

    function createQuestion($SetID, $QNum, $Question) {
        $query = "INSERT INTO {$this->p}qw_questions (SetID, QNum, Question) VALUES (:SetID, :QNum, :Question);";
        $arr = array(':SetID' => $SetID, ':QNum' => $QNum, ':Question' => $Question);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function updateQuestion($QID, $Question) {
        $query = "UPDATE {$this->p}qw_questions set Question = :Question where QID = :QID;";
        $arr = array(':QID' => $QID, ':Question' => $Question);
        $this->PDOX->queryDie($query, $arr);
    }

    function getQuestionById($QID) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getQuestionBySetAndNumber($SetID, $QNum) {
        $query = "SELECT * FROM {$this->p}qw_questions WHERE QNum = :QNum AND SetID = :SetID;";
        $arr = array(':QNum' => $QNum, ':SetID' => $SetID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function deleteQuestion($QID) {
        $query = "DELETE FROM {$this->p}qw_questions WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
    }

    function createAnswer($UserID, $SetID, $QID, $Answer, $Date) {
        $query = "INSERT INTO {$this->p}qw_answer (SetID, UserID, QID, Answer, Modified) VALUES (:SetID, :UserID, :QID, :Answer, :Modified);";
        $arr = array(':SetID' => $SetID,':UserID' => $UserID, ':QID' => $QID, ':Answer' => $Answer, ':Modified' => $Date);
        $this->PDOX->queryDie($query, $arr);
        return $this->PDOX->lastInsertId();
    }

    function updateAnswer($AnswerID, $Answer, $Modified) {
        $query = "UPDATE {$this->p}qw_answer set Answer = :Answer, Modified = :Modified where AnswerID = :AnswerID;";
        $arr = array(':AnswerID' => $AnswerID, ':Answer' => $Answer, ':Modified' => $Modified);
        $this->PDOX->queryDie($query, $arr);
    }

    function userDataExists($SetID, $userId) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE SetID = :SetID AND UserID = :userId";
        $arr = array(':SetID' => $SetID, ':userId' => $userId);
        $result = $this->PDOX->rowDie($query, $arr);
        return $result !== false;
    }

    function getAllAnswersForUser($SetID, $UserID) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE SetID = :SetID AND UserID = :UserID";
        $arr = array(':SetID' => $SetID, ':UserID' => $UserID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getAllAnswersToQuestion($SetID, $QID) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE SetID = :SetID AND QID = :QID and Answer != '';";
        $arr = array(':SetID' => $SetID, ':QID' => $QID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getStudentAnswerForQuestion($QID, $userId) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE QID = :QID AND UserID = :userId; ";
        $arr = array(':QID' => $QID, ':userId' => $userId);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getAnswerById($AnswerID) {
        $query = "SELECT * FROM {$this->p}qw_answer WHERE AnswerID = :AnswerID;";
        $arr = array(':AnswerID' => $AnswerID);
        return $this->PDOX->rowDie($query, $arr);
    }

    function getMostRecentAnswerDate($UserID, $SetID) {
        $query = "SELECT max(Modified) as Modified FROM {$this->p}qw_answer WHERE UserID = :UserID AND SetID = :SetID;";
        $arr = array(':UserID' => $UserID, ':SetID' => $SetID);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context['Modified'];
    }

    function getUsersWithAnswers($SetID) {
        $query = "SELECT DISTINCT UserID FROM {$this->p}qw_answer WHERE SetID = :SetID AND Answer != '';";
        $arr = array(':SetID' => $SetID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function getUsersWithAnswersToQuestion($SetID, $QID) {
        $query = "SELECT DISTINCT UserID FROM {$this->p}qw_answer WHERE SetID = :SetID AND QID = :QID and Answer != '';";
        $arr = array(':SetID' => $SetID, ':QID' => $QID);
        return $this->PDOX->allRowsDie($query, $arr);
    }

    function deleteAnswersToQuestion($QID) {
        $query = "DELETE FROM {$this->p}qw_answer WHERE QID = :QID;";
        $arr = array(':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
    }

    function updateQNumber($QID, $QNum) {
        $query = "UPDATE {$this->p}qw_questions set QNum = :QNum where QID = :QID;";
        $arr = array(':QNum' =>$QNum, ':QID' => $QID);
        $this->PDOX->queryDie($query, $arr);
    }

    function findUserID($user_key) {
        $query = "SELECT user_id FROM {$this->p}lti_user WHERE user_key = :user_key;";
        $arr = array(':user_key' => $user_key);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["user_id"];
    }

    function findEmail($user_id) {
        $query = "SELECT email FROM {$this->p}lti_user WHERE user_id = :user_id;";
        $arr = array(':user_id' => $user_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["email"];
    }

    function findDisplayName($user_id) {
        $query = "SELECT displayname FROM {$this->p}lti_user WHERE user_id = :user_id;";
        $arr = array(':user_id' => $user_id);
        $context = $this->PDOX->rowDie($query, $arr);
        return $context["displayname"];
    }
}
