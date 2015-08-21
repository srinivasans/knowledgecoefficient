<?php
require_once('model_class.php');
class QuizPasskey extends Model
{
protected $EmailId;
protected $QuizId;
protected $Key;

protected function defineTableName()
{
return('quiz_passkey');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"emailid"=>"EmailId",
			"quizid"=>"QuizId",
			"key"=>"Key",
));
}

public function getByEmailIdQuizId($email_id,$quiz_id)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE (`emailid` = :emailid AND `quizid`=:quizid ) OR (`quizid`=:quizid AND `emailid`='0');"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':emailid',$email_id,PDO::PARAM_INT);
$objStatement->bindParam(':quizid',$quiz_id,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$arRow=$objStatement->fetch(PDO::FETCH_ASSOC);
$key=new QuizPasskey($this->objPDO,$arRow['id']);
$key->load();
return $key;
}
else
{
return false;
}
}


};