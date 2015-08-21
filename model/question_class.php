<?php
require_once('model_class.php');
class Question extends Model
{
protected $Tgid;
protected $Question;
protected $Solution;
protected $CorrectOption;
protected $Type;
protected $Qno;



protected function defineTableName()
{
return('question');
}

protected function getAlliedTableName()
{
return 'tgroup';
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"tgid"=>"Tgid",
			"question"=>"Question",
			"solution"=>"Solution",
			"correct_option"=>"CorrectOption",
			"type"=>"Type",
			"qno"=>"Qno",
));
}


public function getQuestionsByTgid($tgid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `tgid` = :tgid;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':tgid',$tgid,PDO::PARAM_INT);
$objStatement->execute();
$questions=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$question=new Question($this->objPDO,$arRow['id']);
$question->load();
$questions[]=$question;
}
if(isset($questions))
{
return $questions;
}
return false;

}


public function getByTgidOrderByQno($tgid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `tgid` = :tgid ORDER BY `qno` DESC;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':tgid',$tgid,PDO::PARAM_INT);
$objStatement->execute();
$questions=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$question=new Question($this->objPDO,$arRow['id']);
$question->load();
$questions[]=$question;
}
if(isset($questions))
{
return $questions;
}
return false;


}

public function getByTgIdQno($tgid,$qno)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `tgid` = :tgid AND `qno`=:qno;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':tgid',$tgid,PDO::PARAM_INT);
$objStatement->bindParam(':qno',$qno,PDO::PARAM_INT);
$objStatement->execute();
if($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$question=new Question($this->objPDO,$arRow['id']);
$question->load();
}
if(isset($question))
{
return $question;
}
return false;


}


public function getQuestionsByTid($tid)
{
$strQuery="SELECT `".$this->defineTableName()."`.`id` as `id` FROM `".$this->defineTableName()."`,`".$this->getAlliedTableName()."` WHERE `".$this->getAlliedTableName()."`.`id` = `".$this->defineTableName()."`.`tgid` AND `".$this->getAlliedTableName()."`.`tid`=:tid;";
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':tid',$tid,PDO::PARAM_INT);
$objStatement->execute();
$questions=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$question=new Question($this->objPDO,$arRow['id']);
$question->load();
$questions[]=$question;
}
if(isset($questions))
{
return $questions;
}
return false;

}





};

?>