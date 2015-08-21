<?php
require_once('model_class.php');
class Section extends Model
{
protected $Tid;
protected $Name;
protected $NoQuestions;



protected function defineTableName()
{
return('section');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"tid"=>"Tid",
			"name"=>"Name",
			"no_questions"=>"NoQuestions",
));
}

public function getByTid($test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `tid`=:Tid';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':Tid',$test_id,PDO::PARAM_INT);
$objStatement->execute();
$sections=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$sections[$arRow['id']]=$arRow['name'];
}

if(isset($sections))
{
return $sections;
}
return false;

}



public function deleteByTid($test_id)
{
$strQuery='SELECT * FROM `'.$this->defineTableName().'` WHERE `tid`=:Tid';
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':Tid',$test_id,PDO::PARAM_INT);
$objStatement->execute();
$sections=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$section=new Section($this->objPDO,$arRow['id']);
$section->markForDeletion();
}

return true;

}




};

?>