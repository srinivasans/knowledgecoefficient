<?php
require_once('model_class.php');
class QuizAdminAnalytics extends Model
{
protected $QuizId;
protected $Type;
protected $Name;
protected $Value;
protected $Desc;

protected function defineTableName()
{
return('quiz_admin_analytics');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"quiz_id"=>"QuizId",
			"type"=>"Type",
			"name"=>"Name",
			"value"=>"Value",
			"desc"=>"Desc",
));
}

public function getByQuizId($quiz_id)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `quiz_id` = :quizid ;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':quizid',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
$ans=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$an=new QuizAdminAnalytics($this->objPDO,$arRow['id']);
$an->load();
$ans[]=$an;
}

return $ans;
}



public function getByQuizIdTypeNameDesc($quiz_id,$type,$name,$desc)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `quizid` = :quizid AND `type`=:type AND `name`=:name AND `desc`=:desc;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':quizid',$test_id,PDO::PARAM_INT);
$objStatement->bindParam(':type',$type,PDO::PARAM_INT);
$objStatement->bindParam(':name',$name,PDO::PARAM_INT);
$objStatement->bindParam(':desc',$desc,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$an=new QuizAdminAnalytics($this->objPDO,$arRow['id']);
$an->load();
return $an;
}
$an=new QuizAdminAnalytics($this->objPDO);
$an->setQuizId($quiz_id);
$an->setType($type);
$an->setName($name);
$an->setDesc($desc);
$an->setValue(0);
$an->save();
return $an;
}




};