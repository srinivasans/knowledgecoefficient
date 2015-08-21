<?php
require_once('model_class.php');
class QuizQuestions extends Model
{
protected $Qid;
protected $Question;
protected $Solution;
protected $CorrectOption;
protected $Type;
protected $Qno;



protected function defineTableName()
{
return('quiz_questions');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"qid"=>"Qid",
			"question"=>"Question",
			"solution"=>"Solution",
			"correct_option"=>"CorrectOption",
			"type"=>"Type",
			"qno"=>"Qno",
));
}


public function getQuestionsByQid($qid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_INT);
$objStatement->execute();
$questions=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$question=new QuizQuestions($this->objPDO,$arRow['id']);
$question->load();
$questions[]=$question;
}
if(isset($questions))
{
return $questions;
}
return false;

}


public function getByQidOrderByQno($qid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid ORDER BY `qno` DESC;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_INT);
$objStatement->execute();
$questions=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$question=new QuizQuestions($this->objPDO,$arRow['id']);
$question->load();
$questions[]=$question;
}
if(isset($questions))
{
return $questions;
}
return false;


}

public function getByQidQno($qid,$qno)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid AND `qno`=:qno;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_INT);
$objStatement->bindParam(':qno',$qno,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$question=new QuizQuestions($this->objPDO,$arRow['id']);
$question->load();
}
if(isset($question))
{
return $question;
}
return false;


}





};

?>