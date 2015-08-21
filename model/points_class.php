<?php
require_once('model_class.php');
class Points extends Model
{
protected $UserId;
protected $Points;
protected $Reputation;


protected function defineTableName()
{
return('points');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"user_id"=>"UserId",
			"points"=>"Points",
			"reputation"=>"Reputation",
));
}


public function getByUserId($uid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `user_id` = :uid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':uid',$uid,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$this->setID($arRow['id']);
$this->load();
}
return $this;
}
else
{
return new Points($this->objPDO);
}

}

}

?>