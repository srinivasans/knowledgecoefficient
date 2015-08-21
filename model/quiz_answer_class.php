<?php
require_once('model_class.php');
class QuizAnswer extends Model
{
protected $Qid;
protected $Oids;
protected $Correct;
protected $Wrong;
protected $Unanswered;


protected function defineTableName()
{
return('quiz_answer');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"qid"=>"Qid",
			"oids"=>"Oids",
			"correct"=>"Correct",
			"wrong"=>"Wrong",
			"unanswered"=>"Unanswered",
));
}


public function deleteByQid($qid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new QuizAnswer($this->objPDO,$arRow['id']);
$test->markForDeletion();
}
return true;
}
else
{
return false;
}

}

public function getByQid($qid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$this->setID($arRow['id']);
}
return $this;
}
else
{
return new QuizAnswer($this->objPDO);
}

}

}

?>