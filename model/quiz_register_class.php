<?php
require_once('model_class.php');
class QuizRegister extends Model
{
protected $QuizId;
protected $UserId;
protected $Status;
protected $TimeStarted;



protected function defineTableName()
{
return('quiz_register');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"quiz_id"=>"QuizId",
			"user_id"=>"UserId",
			"status"=>"Status",
			"time_started"=>"TimeStarted",
));
}

public function getQidsByUidCompleted($user_id,$limit=NULL)
{
if($limit)
{
$limit="LIMIT ".$limit;
}
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `status`=2 ORDER BY `time_started` DESC '.$limit.'';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->execute();
$tids=array();
if($objStatement->rowCount()>0)
{
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$tids[]=$arRow['quiz_id'];
}
return $tids;
}
else
{
return false;
}

}


public function getByUserIdQuizId($user_id,$quiz_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `quiz_id`=:quiz_id ORDER BY `time_started` DESC';
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
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id ORDER BY `time_started` DESC';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':quiz_id',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
$regs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$reg=new QuizRegister($this->objPDO,$arRow['id']);
$reg->load();
$regs[]=$reg;
}

return $regs;
}

public function getByQuizIdAttempting($quiz_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `quiz_id`=:quiz_id AND `status`=1 ORDER BY `time_started` DESC';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':quiz_id',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
$regs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$reg=new QuizRegister($this->objPDO,$arRow['id']);
$reg->load();
$regs[]=$reg;
}

return $regs;
}



};

?>