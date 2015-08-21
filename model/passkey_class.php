<?php
require_once('model_class.php');
class Passkey extends Model
{
protected $EmailId;
protected $TestId;
protected $Key;

protected function defineTableName()
{
return('passkey');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"emailid"=>"EmailId",
			"testid"=>"TestId",
			"key"=>"Key",
));
}

public function getByEmailIdTestId($email_id,$test_id)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE (`emailid` = :emailid AND `testid`=:testid) OR (`testid`=:testid AND `emailid`='0');"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':emailid',$email_id,PDO::PARAM_INT);
$objStatement->bindParam(':testid',$test_id,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$arRow=$objStatement->fetch(PDO::FETCH_ASSOC);
$key=new Passkey($this->objPDO,$arRow['id']);
$key->load();
return $key;
}
else
{
return false;
}
}


};