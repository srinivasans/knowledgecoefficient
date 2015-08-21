<?php
require_once('model_class.php');
class QOptions extends Model
{
protected $Qid;
protected $Option;
protected $Oid;


protected function defineTableName()
{
return('qoptions');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"qid"=>"Qid",
			"option"=>"Option",
			"oid"=>"Oid",
));
}

public function getOptionsByQid($qid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_STR);
$objStatement->execute();
$options=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$options[]=new QOptions($this->objPDO,$arRow['id']);
}

if(isset($options))
{
return $options;
}
return false;


}


public function getByQidOid($qid,$oid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid` = :qid AND `oid`=:oid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':qid',$qid,PDO::PARAM_STR);
$objStatement->bindParam(':oid',$oid,PDO::PARAM_STR);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$option=new QOptions($this->objPDO,$arRow['id']);
}

if(isset($option))
{
return $option;
}
return new QOptions($this->objPDO);


}




};

?>