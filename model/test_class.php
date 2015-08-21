<?php
require_once('model_class.php');
class Test extends Model
{
protected $Name;
protected $Date;
protected $Time;
protected $Access;
protected $CreatorId;
protected $Description;
protected $Credits;
protected $Status;
protected $TimeLimit;
protected $TimeEnd;
protected $TestType;


protected function defineTableName()
{
return('test');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"name"=>"Name",
			"date"=>"Date",
			"time"=>"Time",
			"access"=>"Access",
			"creator_id"=>"CreatorId",
			"description"=>"Description",
			"credits"=>"Credits",
			"status"=>"Status",
			"time_limit"=>"TimeLimit",
			"time_end"=>"TimeEnd",
			"test_type"=>"TestType",
));
}

public function getTestsByCreatorId($creator_id)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `creator_id` = :creator_id ORDER BY `time` DESC;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':creator_id',$creator_id,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new Test($this->objPDO,$arRow['id']);
$test->load();
$tests[]=$test;
}
return $tests;
}
else
{
return false;
}
}


public function getTestsByCreatorIdOnBoard($creator_id)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `creator_id` = :creator_id AND `status`=1 ORDER BY `time` DESC;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':creator_id',$creator_id,PDO::PARAM_INT);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new Test($this->objPDO,$arRow['id']);
$test->load();
$tests[]=$test;
}
return $tests;
}
else
{
return false;
}
}



public function getTestsActiveOnBoard($limit=NULL,$filter="")
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
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new Test($this->objPDO,$arRow['id']);
$test->load();
$tests[]=$test;
}
return $tests;
}
else
{
return array();
}
}


public function getTestsArchived($limit=NULL,$filter="")
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
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new Test($this->objPDO,$arRow['id']);
$test->load();
$tests[]=$test;
}
return $tests;
}
else
{
return array();
}
}




public function getTestsUpcomingOnBoard($limit=NULL,$filter="")
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
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `status`=1 ".$filter." AND `time` > NOW() ORDER BY `time` DESC ".$limit." ;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new Test($this->objPDO,$arRow['id']);
$test->load();
$tests[]=$test;
}
return $tests;
}
else
{
return array();
}

}


public function getTestsSearch($limit=NULL,$filter="")
{
if($limit)
{
$limit="LIMIT ".$limit;
}
if($filter!="")
{
$filter=strtolower($filter);
$filter='AND (LOWER(`test`.`name`) LIKE \'%'.$filter.'%\' OR LOWER(`test`.`credits`) LIKE \'%'.$filter.'%\' OR LOWER(`test`.`description`) LIKE \'%'.$filter.'%\')';
}
$strQuery="SELECT `test`.`id` as `id`, count(`register`.`id`) as `count` FROM `".$this->defineTableName()."`,`register` WHERE `test`.`id`=`register`.`test_id` ".$filter."  GROUP BY `test`.`id` ORDER BY count,`test`.`time` DESC ".$limit.";"; 

unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new Test($this->objPDO,$arRow['id']);
$test->load();
$tests[]=$test;
}
return $tests;
}
else
{
return array();
}

}



public function getTrendingTests($limit=NULL)
{
if($limit)
{
$limit="LIMIT ".$limit;
}

$strQuery="SELECT `test`.`id` as `id`, count(`register`.`id`) as `count`,count(`register`.`id`)/(NOW()-`test`.`time`) as `trend` FROM `".$this->defineTableName()."`,`register` WHERE `test`.`id`=`register`.`test_id` GROUP BY `test`.`id`  ORDER BY `trend` DESC ".$limit.";"; 

unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
if($objStatement->rowCount()>0)
{
$tests=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$test=new Test($this->objPDO,$arRow['id']);
$test->load();
$tests[]=$test;
}
return $tests;
}
else
{
return array();
}

}




};

?>