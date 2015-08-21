<?php
require_once('model_class.php');
class Evaluate extends Model
{
protected $TestId;
protected $UserId;
protected $Total;
protected $Rank;
protected $Percentile;
protected $ExamTotal;



protected function defineTableName()
{
return('evaluation');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"test_id"=>"TestId",
			"user_id"=>"UserId",
			"total"=>"Total",
			"rank"=>"Rank",
			"percentile"=>"Percentile",
			"exam_total"=>"ExamTotal",
));
}


public function getByUserIdTestId($user_id,$test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `test_id`=:test_id';
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
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `test_id`=:test_id ORDER BY `total` DESC';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
$evals=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$e=new Evaluate($this->objPDO);
$e->setID($arRow['id']);
$e->load();
$evals[]=$e;
}
return $evals;

}



public function getAverageMarks()
{
$strQuery='SELECT avg(`total`) as `avg` FROM `'.$this->defineTableName().'` WHERE `test_id`=:test_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$test_id=$this->TestId;
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return round($arRow['avg'],2);
}
return 0;
}


public function getTotalMarks()
{
$strQuery='SELECT avg(`exam_total`) as `avg` FROM `'.$this->defineTableName().'` WHERE `test_id`=:test_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$test_id=$this->TestId;
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return round($arRow['avg'],2);
}
return 0;
}


public function getMaxMarks()
{
$strQuery='SELECT max(`total`) as `max` FROM `'.$this->defineTableName().'` WHERE `test_id`=:test_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$test_id=$this->TestId;
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return $arRow['max'];
}
return 0;
}

public function deleteByUserIdTestId($user_id,$test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `test_id`=:test_id';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$obj=new Evaluate($this->objPDO,$arRow['id']);
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