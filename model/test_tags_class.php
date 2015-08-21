<?php
require_once('model_class.php');
class TestTags extends Model
{
protected $Qid;
protected $Tag;


protected function defineTableName()
{
return('test_tags');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"tag"=>"Tag",
			"qid"=>"Qid",
));
}


public function getTagsSearch($limit=NULL,$filter="")
{
if($limit)
{
$limit="LIMIT ".$limit;
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
if($filter!="")
{
$filter=strtolower($filter);
$filter='(LOWER(`test_tags`.`tag`) LIKE \'%'.$filter.'%\')';
}
$strQuery="SELECT `test`.`id` as `id`,count(`test_tags`.`id`) as `count` FROM `test_tags`,`question`,`tgroup`,`test` WHERE ".$filter." AND `test_tags`.`qid`=`question`.`id` AND `tgroup`.`id`=`question`.`tgid` AND `tgroup`.`tid`=`test`.`id` GROUP BY `test`.`id` ORDER BY `test`.`time`,`count` DESC ".$limit.";"; 
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

public function getTagsLikeName($like)
{
$strQuery="SELECT distinct(`tag`) FROM `".$this->defineTableName()."` WHERE `tag` LIKE '%".$like."%' LIMIT 5;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
$tags=array();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$tags[]=$arRow['tag'];
}

if(isset($tags))
{
return $tags;
}
return false;


}

public function getTagsByQid($qid)
{
$strQuery="SELECT * FROM `".$this->defineTableName()."` WHERE `qid`=".$qid.";"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->execute();
while($arRow=$objStatement->fetch(PDO::FETCH_ASSOC))
{
$tags[]=new TestTags($this->objPDO,$arRow['id']);
}

if(isset($tags))
{
return $tags;
}
return array();
}




};

?>