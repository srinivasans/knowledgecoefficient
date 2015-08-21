<?php
require_once('model_class.php');
class Register extends Model
{
protected $TestId;
protected $UserId;
protected $Status;
protected $TimeStarted;



protected function defineTableName()
{
return('register');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"test_id"=>"TestId",
			"user_id"=>"UserId",
			"status"=>"Status",
			"time_started"=>"TimeStarted",
));
}

public function getTidsByUidCompleted($user_id,$limit=NULL)
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
$tids[]=$arRow['test_id'];
}
return $tids;
}
else
{
return false;
}

}


public function getByUserIdTestId($user_id,$test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `test_id`=:test_id ORDER BY `time_started` DESC';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
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



public function getByTestId($test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `test_id`=:test_id ORDER BY `time_started` DESC';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
$regs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$reg=new Register($this->objPDO,$arRow['id']);
$reg->load();
$regs[]=$reg;
}

return $regs;
}

public function getByTestIdAttempting($test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `test_id`=:test_id AND `status`=1 ORDER BY `time_started` DESC';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
$regs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$reg=new Register($this->objPDO,$arRow['id']);
$reg->load();
$regs[]=$reg;
}

return $regs;
}




};

?>