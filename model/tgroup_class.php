<?php
require_once('model_class.php');
class TGroup extends Model
{
protected $Tid;
protected $Name;
protected $SectionId;
protected $Description;
protected $NumQuestions;


protected function defineTableName()
{
return('tgroup');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"section_id"=>"SectionId",
			"tid"=>"Tid",
			"name"=>"Name",
			"description"=>"Description",
			"num_questions"=>"NumQuestions",
));
}

public function getGroupsByTid($tid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `tid` = :tid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':tid',$tid,PDO::PARAM_STR);
$objStatement->execute();
$groups=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$groups[]=new TGroup($this->objPDO,$arRow['id']);
}

if(isset($groups))
{
return $groups;
}
return false;


}


public function getGroupsByTidSectionId($tid,$section_id)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `tid` = :tid AND `section_id`=:section_id;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':tid',$tid,PDO::PARAM_STR);
$objStatement->bindParam(':section_id',$section_id,PDO::PARAM_STR);
$objStatement->execute();
$groups=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$groups[]=new TGroup($this->objPDO,$arRow['id']);
}

if(isset($groups))
{
return $groups;
}
return false;


}




};

?>