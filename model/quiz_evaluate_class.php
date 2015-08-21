<?php
require_once('model_class.php');
class QuizEvaluate extends Model
{
protected $QuizId;
protected $UserId;
protected $Total;
protected $Rank;
protected $Percentile;
protected $ExamTotal;



protected function defineTableName()
{
return('quiz_evaluation');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"quiz_id"=>"QuizId",
			"user_id"=>"UserId",
			"total"=>"Total",
			"rank"=>"Rank",
			"percentile"=>"Percentile",
			"exam_total"=>"ExamTotal",
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
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$this->setID($arRow['id']);
$this->load();
return $this;
}
else
{
return false;
}

}


public function getByQuizId($quiz_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id ORDER BY `total` DESC';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':quiz_id',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
$evals=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$e=new QuizEvaluate($this->objPDO);
$e->setID($arRow['id']);
$e->load();
$evals[]=$e;
}
return $evals;

}


public function getTotalEvaluated()
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$test_id=$this->QuizId;
$objStatement->bindParam(':quiz_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
return $objStatement->rowCount();
}

public function getTotalMarks()
{
$strQuery='SELECT avg(`exam_total`) as `avg` FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$test_id=$this->QuizId;
$objStatement->bindParam(':quiz_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return round($arRow['avg'],2);
}
return 0;
}

public function getAverageMarks()
{
$strQuery='SELECT avg(`total`) as `avg` FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$test_id=$this->QuizId;
$objStatement->bindParam(':quiz_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return round($arRow['avg'],2);
}
return 0;
}

public function getMaxMarks()
{
$strQuery='SELECT max(`total`) as `max` FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$quiz_id=$this->QuizId;
$objStatement->bindParam(':quiz_id',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return $arRow['max'];
}
return 0;
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