<?php
require_once('model_class.php');
class QuizAnalytics extends Model
{
protected $QuizId;
protected $UserId;
protected $Type;
protected $Value;
protected $Marks;
protected $Postives;
protected $Negatives;
protected $Correct;
protected $Wrong;
protected $Unanswered;
protected $TotalQuestions;
protected $TotalMarks;
protected $Section;


protected function defineTableName()
{
return('quiz_analytics');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"quiz_id"=>"QuizId",
			"user_id"=>"UserId",
			"type"=>"Type",
			"value"=>"Value",
			"marks"=>"Marks",
			"positives"=>"Positives",
			"negatives"=>"Negatives",
			"correct"=>"Correct",
			"wrong"=>"Wrong",
			"unanswered"=>"Unanswered",
			"total_questions"=>"TotalQuestions",
			"total_marks"=>"TotalMarks",
));
}


public function getByUserIdQuizId($user_id,$quiz_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `quiz_id`=:quiz_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->bindParam(':quiz_id',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
$a=array();

while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$analytic=new QuizAnalytics($this->objPDO);
$analytic->setID($arRow['id']);
$analytic->load();
$a[]=$analytic;
}
return $a;
}


public function getByUserIdQuizIdTypeValue($user_id,$quiz_id,$type,$value)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `quiz_id`=:quiz_id AND `type`=:type AND `value`=:value ';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->bindParam(':quiz_id',$quiz_id,PDO::PARAM_INT);
$objStatement->bindParam(':type',$type,PDO::PARAM_STR);
$objStatement->bindParam(':value',$value,PDO::PARAM_STR);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$this->setID($arRow['id']);
$this->load();
return $this;
}
else
{
return $this;
}

}


public function getAverageMarksByTypeValueTid()
{
$strQuery='SELECT avg(`marks`) as `avg` FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id AND `type`=:type AND `value`=:value';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':quiz_id',$this->QuizId,PDO::PARAM_INT);
$objStatement->bindParam(':type',$this->Type,PDO::PARAM_STR);
$objStatement->bindParam(':value',$this->Value,PDO::PARAM_STR);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return $arRow['avg'];
}
else
{
return 0;
}

}



public function deleteByUserIdQuizId($user_id,$quiz_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `quiz_id`=:quiz_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->bindParam(':quiz_id',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$obj=new QuizEvaluate($this->objPDO,$arRow['id']);
$obj->markForDeletion();
return true;
}
else
{
return false;
}

}






};

?>