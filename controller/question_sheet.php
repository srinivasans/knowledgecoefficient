<?php
class QuestionSheet
{
private $ID;
private $Test;
private $arQuestions;
private $arGroups;
private $arOptions;
private $arAnswers;
private $arUserAnswers;


public function __construct($tid)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
GLOBAL $objPDO;
$this->ID=$tid;
$this->Test=new Test($objPDO,$this->ID);
$this->arQuestions=array();
$this->arGroups=array();
$this->arOptions=array();
$this->arAnswers=array();
$this->arUserAnswers=array();
}

public function prepare()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answers_class.php');
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());

$gr_cls=new TGroup($objPDO);
$groups=$gr_cls->getGroupsByTid($this->ID);
$question_set=array();
$q_cls=new Question($objPDO);
foreach($groups as $key=>$value)
{
$question_set[$groups[$key]->getID()]=$q_cls->getQuestionsByTgid($groups[$key]->getID());
}

$o_cls=new TOptions($objPDO);
$ans=new Answers($objPDO);
$options_array=array();
$answers_array=array();

foreach($question_set as $key=>$value)
{
	foreach($value as $k=>$v)
	{
	$options_array[$v->getID()]=$o_cls->getOptionsByQid($v->getID());
	$answers_array[$v->getID()]=$ans->getByQidUid($v->getID(),$user->getID());
	}
		
}

$this->arGroups=$groups;
$this->arQuestions=$question_set;
$this->arOptions=$options_array;
$this->arAnswers=$answers_array;
}



public function prepare_user_ans()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answers_class.php');
if(count($this->arQuestions)>0)
{
foreach($this->arQuestions as $key=>$question_set)
{
foreach($question_set as $k=>$question)
{
$ans=new Answers($objPDO);
$this->arUserAnswers[$question->getID()]=$ans->getByQidUid($question->getID(),$user->getID());
}
}
}
}



public function showAdminUserReport($client)
{
GLOBAL $session;
GLOBAL $objPDO;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);


include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
$eval=new Evaluate($objPDO);
$eval=$eval->getByUserIdTestId($client->getID(),$this->ID);
if($eval)
{
$evaluation=array();
$evaluation['Marks']=$eval->getTotal();
$evaluation['Name']=$this->Test->getName();
$evaluation['Rank']=$eval->getRank();
$evaluation['Percentile']=$eval->getPercentile();
$evaluation['Average']=$eval->getAverageMarks();
$evaluation['Percentage']=round(($eval->getTotal()/$eval->getExamTotal())*100,2);
$evaluation['Highest']=$eval->getMaxMarks();
$evaluation['TotalMarks']=$eval->getExamTotal();
}

$test_set=$this->Test;
$test=array();
$test['ID']=$test_set->getID();
$test['Name']=$test_set->getName();
$test['Description']=$test_set->getDescription();
$test['Credits']=$test_set->getCredits();
$test['Time']=$test_set->getTime();
$test['TimeLimit']=$test_set->getTimeLimit();
$test['TimeEnd']=$test_set->getTimeEnd();

$groups_set=$this->arGroups;
$question_set=$this->arQuestions;
$options_array=$this->arOptions;
$answers_array=$this->arAnswers;


$groups=array();
$sections=array();
$section_groups=array();
foreach($groups_set as $k=>$value)
{
$key=$value->getID();
$groups[$key]['ID']=$value->getID();
$groups[$key]['Name']=$value->getName();
$groups[$key]['Description']=$value->getDescription();
$groups[$key]['Section']=$value->getSectionId();
$sec=new Section($objPDO,$value->getSectionId());
$groups[$key]['SectionName']=$sec->getName();
$sections[$sec->getID()]=$sec->getName();
$section_groups[$sec->getID()][]=$value->getID();
}

$questions=array();
foreach($question_set as $key=>$value)
{
	foreach($value as $no=>$question)
	{
	$questions[$key][$no]['ID']=$question->getID();
	$questions[$key][$no]['Question']=$question->getQuestion();
	$questions[$key][$no]['Type']=$question->getType();
	$questions[$key][$no]['Qno']=$question->getQno();
	$questions[$key][$no]['CorrectAnswer']=$question->getCorrectOption();
	$questions[$key][$no]['Set']=explode(',',$answers_array[$question->getID()]->getOids());
	}
}

$options=array();
foreach($options_array as $key=>$value)
{
	foreach($value as $k=>$v)
	{
	$options[$key][$k]=array('ID'=>$v->getID(),'Oid'=>$v->getOid(),'Option'=>$v->getOption());
	}
}

$user_answers=array();

if(count($this->arQuestions)>0 && count($this->arUserAnswers)>0)
{
foreach($this->arQuestions as $key=>$question_set)
{
foreach($question_set as $k=>$question)
{
$user_answers[$question->getID()]=$this->arUserAnswers[$question->getID()]->getOids();
$user_solution[$question->getID()]=$this->arAnswers[$question->getID()]->getSolution();
}
}
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/test_admin_user_evaluate.php');


}


public function showEvaluate()
{
GLOBAL $session;
GLOBAL $objPDO;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);


include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answer_class.php');

$eval=new Evaluate($objPDO);
$eval=$eval->getByUserIdTestId($user->getID(),$this->ID);
if($eval)
{
$evaluation=array();
$evaluation['Marks']=$eval->getTotal();
$evaluation['Name']=$this->Test->getName();
$evaluation['Rank']=$eval->getRank();
$evaluation['Percentile']=$eval->getPercentile();
$evaluation['Average']=$eval->getAverageMarks();
if($eval->getExamTotal()!=0)
{
$evaluation['Percentage']=round(($eval->getTotal()/$eval->getExamTotal())*100,2);
}
else
{
$evaluation['Percentage']=0;
}
$evaluation['Highest']=$eval->getMaxMarks();
$evaluation['TotalMarks']=$eval->getExamTotal();
}

$test_set=$this->Test;
$test=array();
$test['ID']=$test_set->getID();
$test['Name']=$test_set->getName();
$test['Description']=$test_set->getDescription();
$test['Credits']=$test_set->getCredits();
$test['Time']=$test_set->getTime();
$test['TimeLimit']=$test_set->getTimeLimit();
$test['TimeEnd']=$test_set->getTimeEnd();


if((strtotime('now')-strtotime($this->Test->getTimeEnd())>1000) || $this->Test->getAccess()=='archive' ||(strtotime($this->Test->getTimeEnd())-strtotime($this->Test->getTime())>5000000))
{
$groups_set=$this->arGroups;
$question_set=$this->arQuestions;
$options_array=$this->arOptions;
$answers_array=$this->arAnswers;


$groups=array();
$sections=array();
$section_groups=array();
foreach($groups_set as $k=>$value)
{
$key=$value->getID();
$groups[$key]['ID']=$value->getID();
$groups[$key]['Name']=$value->getName();
$groups[$key]['Description']=$value->getDescription();
$groups[$key]['Section']=$value->getSectionId();
$sec=new Section($objPDO,$value->getSectionId());
$groups[$key]['SectionName']=$sec->getName();
$sections[$sec->getID()]=$sec->getName();
$section_groups[$sec->getID()][]=$value->getID();
}

$questions=array();
foreach($question_set as $key=>$value)
{
	foreach($value as $no=>$question)
	{
	$questions[$key][$no]['ID']=$question->getID();
	$questions[$key][$no]['Question']=$question->getQuestion();
	$questions[$key][$no]['Type']=$question->getType();
	$questions[$key][$no]['Qno']=$question->getQno();
	$questions[$key][$no]['CorrectAnswer']=$question->getCorrectOption();
	$questions[$key][$no]['Set']=explode(',',$answers_array[$question->getID()]->getOids());
	$answer=new Answer($objPDO);
	$answer=$answer->getByQid($question->getID());
	$questions[$key][$no]['CorrectMarks']=$answer->getCorrect();
	$questions[$key][$no]['WrongMarks']=$answer->getWrong();
	$questions[$key][$no]['UnansweredMarks']=$answer->getUnanswered();
	$answer=NULL;
	}
}

$options=array();
foreach($options_array as $key=>$value)
{
	foreach($value as $k=>$v)
	{
	$options[$key][$k]=array('ID'=>$v->getID(),'Oid'=>$v->getOid(),'Option'=>$v->getOption());
	}
}

$user_answers=array();

if(count($this->arQuestions)>0 && count($this->arUserAnswers)>0)
{
foreach($this->arQuestions as $key=>$question_set)
{
foreach($question_set as $k=>$question)
{
$user_answers[$question->getID()]=$this->arUserAnswers[$question->getID()]->getOids();
$user_solution[$question->getID()]=$this->arAnswers[$question->getID()]->getSolution();
}
}
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/evaluation.php');

}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/evaluation_only.php');
}


}

public function show($reg)
{
GLOBAL $session;
GLOBAL $objPDO;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

$register=array();
$register['TimeStarted']=$reg->getTimeStarted();

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answer_class.php');
$groups_set=$this->arGroups;
$test_set=$this->Test;
$question_set=$this->arQuestions;
$options_array=$this->arOptions;
$answers_array=$this->arAnswers;

$test=array();
$test['ID']=$test_set->getID();
$test['Name']=$test_set->getName();
$test['Description']=$test_set->getDescription();
$test['Credits']=$test_set->getCredits();
$test['Time']=$test_set->getTime();
$test['TimeLimit']=$test_set->getTimeLimit();
$test['TimeEnd']=$test_set->getTimeEnd();

$groups=array();
$sections=array();
$section_groups=array();
foreach($groups_set as $k=>$value)
{
$key=$value->getID();
$groups[$key]['ID']=$value->getID();
$groups[$key]['Name']=$value->getName();
$groups[$key]['Description']=$value->getDescription();
$groups[$key]['Section']=$value->getSectionId();
$sec=new Section($objPDO,$value->getSectionId());
$groups[$key]['SectionName']=$sec->getName();
$sections[$sec->getID()]=$sec->getName();
$section_groups[$sec->getID()][]=$value->getID();
}

$questions=array();
foreach($question_set as $key=>$value)
{
	foreach($value as $no=>$question)
	{
	$questions[$key][$no]['ID']=$question->getID();
	$questions[$key][$no]['Question']=$question->getQuestion();
	$questions[$key][$no]['Type']=$question->getType();
	$questions[$key][$no]['Qno']=$question->getQno();
	$questions[$key][$no]['Set']=explode(',',$answers_array[$question->getID()]->getOids());
	$answer=new Answer($objPDO);
	$answer=$answer->getByQid($question->getID());
	$questions[$key][$no]['CorrectMarks']=$answer->getCorrect();
	$questions[$key][$no]['WrongMarks']=$answer->getWrong();
	$questions[$key][$no]['UnansweredMarks']=$answer->getUnanswered();
	$answer=NULL;
	}
}

$options=array();
foreach($options_array as $key=>$value)
{
	foreach($value as $k=>$v)
	{
	$options[$key][$k]=array('ID'=>$v->getID(),'Oid'=>$v->getOid(),'Option'=>$v->getOption());
	}
}
if($test_set->getTestType()==1)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/mocktest.php');
}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/mocktestobo.php');
}
}




public function showQuestionAnalytics()
{
GLOBAL $session;
GLOBAL $objPDO;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);


include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
$groups_set=$this->arGroups;
$test_set=$this->Test;
$question_set=$this->arQuestions;

$test=array();
$test['ID']=$test_set->getID();
$test['Name']=$test_set->getName();
$test['Description']=$test_set->getDescription();
$test['Credits']=$test_set->getCredits();
$test['Time']=$test_set->getTime();
$test['TimeLimit']=$test_set->getTimeLimit();
$test['TimeEnd']=$test_set->getTimeEnd();

$groups=array();
$sections=array();
$section_groups=array();
foreach($groups_set as $k=>$value)
{
$key=$value->getID();
$groups[$key]['ID']=$value->getID();
$groups[$key]['Name']=$value->getName();
$groups[$key]['Description']=$value->getDescription();
$groups[$key]['Section']=$value->getSectionId();
$sec=new Section($objPDO,$value->getSectionId());
$groups[$key]['SectionName']=$sec->getName();
$sections[$sec->getID()]=$sec->getName();
$section_groups[$sec->getID()][]=$value->getID();
}

$questions=array();
foreach($question_set as $key=>$value)
{
	foreach($value as $no=>$question)
	{
	$questions[$key][$no]['ID']=$question->getID();
	$questions[$key][$no]['Question']=$question->getQuestion();
	$questions[$key][$no]['Type']=$question->getType();
	$questions[$key][$no]['Qno']=$question->getQno();
	}
}

$test_id=$test_set->getID();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_admin_analytics_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/evaluate_class.php');
$admin=new TestAdminAnalytics($objPDO);
$eval=new Evaluate($objPDO);
$eval->setTestId($test_id);
$total_takers=$eval->getTotalEvaluated();
if($total_takers==0)
{
$total_takers=1;
}
$admin=$admin->getByTestId($test_id);
$analytics=array();
foreach($admin as $k=>$value)
{
$analytics[$value->getType()][$value->getName()][$value->getDesc()]=$value->getValue();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/test_question_analytics.php');



}




}