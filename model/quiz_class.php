<?php
require_once('model_class.php');
class Quiz extends Model
{
protected $Name;
protected $Date;
protected $TimeCreated;
protected $Access;
protected $CreatorId;
protected $Description;
protected $Credits;
protected $Status;
protected $TimeLimit;
protected $TimeEnd;
protected $TestType;
protected $Time;


protected function defineTableName()
{
return('quiz');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"name"=>"Name",
			"date"=>"Date",
			"time_created"=>"TimeCreated",
			"time_end"=>"TimeEnd",
			"access"=>"Access",
			"creator_id"=>"CreatorId",
			"description"=>"Description",
			"credits"=>"Credits",
			"status"=>"Status",
			"time_limit"=>"TimeLimit",
			"test_type"=>"TestType",
			"time"=>"Time",
));
}

public function getQuizsByCreatorId($creator_id,$limit=NULL)
{
if($limit)
{
$limit="LIMIT ".$limit;
}
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `creator_id` = :creator_id ORDER BY `time` DESC ".$limit.";"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':creator_id',$creator_id,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$quizs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$quiz=new Quiz($this->objPDO,$arRow['id']);
$quiz->load();
$quizs[]=$quiz;
}
return $quizs;
}
else
{
return false;
}
}


public function getQuizsByCreatorIdOnBoard($creator_id,$limit=NULL)
{
if($limit)
{
$limit="LIMIT ".$limit;
}
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `creator_id` = :creator_id AND `status`=1 ORDER BY `time` DESC ".$limit.";"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':creator_id',$creator_id,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$quizs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$quiz=new Quiz($this->objPDO,$arRow['id']);
$quiz->load();
$quizs[]=$quiz;
}
return $quizs;
}
else
{
return false;
}
}



public function getQuizsActiveOnBoard($limit=NULL,$filter="")
{
if($limit)
{
$limit="LIMIT ".$limit;
}
if($filter!="")
{
$filter=strtolower($filter);
$filter='AND (LOWER(`name`) LIKE \'%'.$filter.'%\' OR LOWER(`credits`) LIKE \'%'.$filter.'%\')';
}
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `status`=1 ".$filter." AND `time` < NOW() AND `time_end` > NOW() ORDER BY `time` DESC ".$limit." ;"; 

unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$quizs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$quiz=new Quiz($this->objPDO,$arRow['id']);
$quiz->load();
$quizs[]=$quiz;
}
return $quizs;
}
else
{
return array();
}
}


public function getQuizsArchived($limit=NULL,$filter="")
{
if($limit)
{
$limit="LIMIT ".$limit;
}

if($filter!="")
{
$filter=strtolower($filter);
$filter='AND (LOWER(`name`) LIKE \'%'.$filter.'%\' OR LOWER(`credits`) LIKE \'%'.$filter.'%\')';
}
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `access`='archive' ".$filter." AND `time` < NOW() AND `time_end` > NOW() ORDER BY `time` DESC ".$limit." ;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$quizs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$quiz=new Quiz($this->objPDO,$arRow['id']);
$quiz->load();
$quizs[]=$quiz;
}
return $quizs;
}
else
{
return array();
}
}




public function getQuizsUpcomingOnBoard($limit=NULL,$filter="")
{
if($limit)
{
$limit="LIMIT ".$limit;
}

if($filter!="")
{
$filter=strtolower($filter);
$filter='AND (LOWER(`name`) LIKE \'%'.$filter.'%\' OR LOWER(`credits`) LIKE \'%'.$filter.'%\')';
}
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `status`=1 ".$filter." AND `time` > NOW()  ORDER BY `time` DESC ".$limit.";"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$quizs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$quiz=new Quiz($this->objPDO,$arRow['id']);
$quiz->load();
$quizs[]=$quiz;
}
return $quizs;
}
else
{
return array();
}

}


public function getQuizsSearch($limit=NULL,$filter="")
{
if($limit)
{
$limit="LIMIT ".$limit;
}

if($filter!="")
{
$filter=strtolower($filter);
$filter='AND (LOWER(`quiz`.`name`) LIKE \'%'.$filter.'%\' OR LOWER(`quiz`.`credits`) LIKE \'%'.$filter.'%\' OR LOWER(`quiz`.`description`) LIKE \'%'.$filter.'%\')';
}
$strQuery="SELECT `quiz`.`id` as `id`, count(`quiz_register`.`id`) as `count` FROM `".$this->defineTableName()."`,`quiz_register` WHERE `quiz`.`id`=`quiz_register`.`quiz_id` ".$filter." GROUP BY `quiz`.`id`  ORDER BY count,`quiz`.`time` DESC ".$limit.";"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$quizs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$quiz=new Quiz($this->objPDO,$arRow['id']);
$quiz->load();
$quizs[]=$quiz;
}
return $quizs;
}
else
{
return array();
}

}


public function getTrendingQuizs($limit=NULL)
{
if($limit)
{
$limit="LIMIT ".$limit;
}


$strQuery="SELECT `quiz`.`id` as `id`, count(`quiz_register`.`id`) as `count`,count(`quiz_register`.`id`)/(NOW()-`quiz`.`time`) as `trend` FROM `".$this->defineTableName()."`,`quiz_register` WHERE `quiz`.`id`=`quiz_register`.`quiz_id` GROUP BY `quiz`.`id`  ORDER BY `trend` DESC ".$limit.";"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$quizs=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$quiz=new Quiz($this->objPDO,$arRow['id']);
$quiz->load();
$quizs[]=$quiz;
}
return $quizs;
}
else
{
return array();
}

}



};

?>