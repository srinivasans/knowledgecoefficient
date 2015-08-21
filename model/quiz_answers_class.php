<?php
require_once('model_class.php');
class QuizAnswers extends Model
{
protected $Qid;
protected $Uid;
protected $Oids;


protected function defineTableName()
{
return('quiz_answers');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"qid"=>"Qid",
			"uid"=>"Uid",
			"oids"=>"Oids",
));
}

public function getByQidUid($qid,$uid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid AND `uid`=:uid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_INT);
$objStatement->bindParam(':uid',$uid,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$ans=new QuizAnswers($this->objPDO,$arRow['id']);
}
return $ans;
}
else
{
return new QuizAnswers($this->objPDO);
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
$anss=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$ans=new QuizAnswers($this->objPDO,$arRow['id']);
$anss[]=$ans;
}
return $anss;
}
else
{
return new QuizAnswers($this->objPDO);
}
}


public function deleteByQidUid($qid,$uid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid AND `uid`=:uid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_INT);
$objStatement->bindParam(':uid',$uid,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$ans=new QuizAnswers($this->objPDO,$arRow['id']);
$ans->markForDeletion();
}
return true;
}
else
{
return false;
}

}



}

?>