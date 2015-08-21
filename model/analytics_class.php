<?php
require_once('model_class.php');
class Analytics extends Model
{
protected $TestId;
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
return('analytics');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"test_id"=>"TestId",
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
			"section"=>"Section",
));
}


public function getByUserIdTestId($user_id,$test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `test_id`=:test_id ';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->execute();
$a=array();

while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$analytic=new Analytics($this->objPDO);
$analytic->setID($arRow['id']);
$analytic->load();
$a[]=$analytic;
}
return $a;
}


public function getByUserIdTestIdTypeValueSection($user_id,$test_id,$type,$value,$section)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `user_id`=:user_id AND `test_id`=:test_id AND `type`=:type AND `value`=:value AND `section`=:section';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->bindParam(':test_id',$test_id,PDO::PARAM_INT);
$objStatement->bindParam(':type',$type,PDO::PARAM_STR);
$objStatement->bindParam(':value',$value,PDO::PARAM_STR);
$objStatement->bindParam(':section',$section,PDO::PARAM_STR);
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
$strQuery='SELECT avg(`marks`) as `avg` FROM `'.$this->defineTableName().'` WHERE `test_id`=:test_id AND `type`=:type AND `value`=:value';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':test_id',$this->TestId,PDO::PARAM_INT);
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