<?php
require_once('model_class.php');
class QuizTags extends Model
{
protected $Qid;
protected $Tag;


protected function defineTableName()
{
return('quiz_tags');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"tag"=>"Tag",
			"qid"=>"Qid",
));
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
$tags[]=new QuizTags($this->objPDO,$arRow['id']);
}

if(isset($tags))
{
return $tags;
}
return array();


}


public function getTagsSearch($limit=NULL,$filter="")
{
if($limit)
{
$limit="LIMIT ".$limit;
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
if($filter!="")
{
$filter=strtolower($filter);
$filter='(LOWER(`quiz_tags`.`tag`) LIKE \'%'.$filter.'%\')';
}
$strQuery="SELECT `quiz`.`id` as `id`,count(`quiz_tags`.`id`) as `count` FROM `quiz_tags`,`quiz_questions`,`quiz` WHERE ".$filter." AND `quiz_tags`.`qid`=`question`.`id` AND `quiz`.`id`=`quiz_questions`.`qid`  GROUP BY `quiz`.`id` ORDER BY `quiz`.`time`,`count` DESC ".$limit.";"; 
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