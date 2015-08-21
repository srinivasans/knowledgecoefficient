<?php
require_once('model_class.php');
class Update extends Model
{

protected $Heading;
protected $Link;
protected $Time;
protected $UserGroup;

protected function defineTableName()
{
return('updates');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"heading"=>"Heading",
			"link"=>"Link",
			"time"=>"Time",
			"user_group"=>"UserGroup",
));
}


public function getByUserGroup($user_group)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `user_group` = :user_group ORDER BY `time` DESC;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_group',$user_group,PDO::PARAM_INT);
$objStatement->execute();
$updates=array();
if($objStatement->rowCount()>0)
{
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$update=new Update($this->objPDO,$arRow['id']);
$updates[]=$update;
}
return $updates;
}
else
{
return false;
}
}


};

?>