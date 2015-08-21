<?php
require_once('model_class.php');
class Notifications extends Model
{

protected $UserId;
protected $Title;
protected $SubTitle;
protected $Read;
protected $Time;
protected $Type;
protected $Link;

protected function defineTableName()
{
return('notification');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"user_id"=>"UserId",
			"title"=>"Title",
			"subtitle"=>"SubTitle",
			"read"=>"Read",
			"time"=>"Time",
			"type"=>"Type",
			"link"=>"Link",
));
}


public function getByUserId($user_id,$start,$limit=10)
{
//Change the Javascript load also wen dis is changed
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `user_id` = :user_id ORDER BY `read` ASC ,`time` DESC LIMIT ".$start.", ".$limit.";"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->execute();
$notifications=array();
if($objStatement->rowCount()>0)
{
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$notification=new Notifications($this->objPDO,$arRow['id']);
$notification->load();
$notifications[]=$notification;
}
return $notifications;
}
else
{
return false;
}
}


public function getNumUnread($user_id)
{
//Change the Javascript load also wen dis is changed
$strQuery="SELECT count(*) as `count` FROM `".$this->defineTableName()."` WHERE `user_id` = :user_id  AND `read`=0;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->execute();
$notifications=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return $arRow['count'];
}
else
{
return "";
}
}


public function getMaxNum($user_id)
{
//Change the Javascript load also wen dis is changed
$strQuery="SELECT max(`id`) as `max` FROM `".$this->defineTableName()."` WHERE `user_id` = :user_id  ;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':user_id',$user_id,PDO::PARAM_INT);
$objStatement->execute();
$notifications=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
return $arRow['max'];
}
else
{
return 0;
}
}


};

?>