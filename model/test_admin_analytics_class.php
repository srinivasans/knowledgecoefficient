<?php
require_once('model_class.php');
class TestAdminAnalytics extends Model
{
protected $TestId;
protected $Type;
protected $Name;
protected $Value;
protected $Desc;

protected function defineTableName()
{
return('test_admin_analytics');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"test_id"=>"TestId",
			"type"=>"Type",
			"name"=>"Name",
			"value"=>"Value",
			"desc"=>"Desc",
));
}

public function getByTestId($test_id)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `test_id` = :testid ;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':testid',$test_id,PDO::PARAM_INT);
$objStatement->execute();
$ans=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$an=new TestAdminAnalytics($this->objPDO,$arRow['id']);
$an->load();
$ans[]=$an;
}

return $ans;
}



public function getByTestIdTypeNameDesc($test_id,$type,$name,$desc)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `test_id` = :testid AND `type`=:type AND `name`=:name AND `desc`=:desc;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':testid',$test_id,PDO::PARAM_INT);
$objStatement->bindParam(':type',$type,PDO::PARAM_INT);
$objStatement->bindParam(':name',$name,PDO::PARAM_INT);
$objStatement->bindParam(':desc',$desc,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$an=new TestAdminAnalytics($this->objPDO,$arRow['id']);
$an->load();
return $an;
}
$an=new TestAdminAnalytics($this->objPDO);
$an->setTestId($test_id);
$an->setType($type);
$an->setName($name);
$an->setDesc($desc);
$an->setValue(0);
$an->save();
return $an;
}



};