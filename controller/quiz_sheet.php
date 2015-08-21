<?php
class QuizSheet
{
private $ID;
private $Quiz;
private $arQuestions;
private $arOptions;
private $arAnswers;
private $arUserAnswers;


public function __construct($qid)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
GLOBAL $objPDO;
$this->ID=$qid;
$this->Quiz=new Quiz($objPDO,$this->ID);
$this->arQuestions=array();
$this->arGroups=array();
$this->arOptions=array();
$this->arAnswers=array();
$this->arUserAnswers=array();
}

public function prepare()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answers_class.php');
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());

$question_set=array();
$q_cls=new QuizQuestions($objPDO);
$question_set=$q_cls->getQuestionsByQid($this->ID);
$o_cls=new QOptions($objPDO);
$ans=new QuizAnswers($objPDO);
$options_array=array();
$answers_array=array();

foreach($question_set as $key=>$value)
{
	$options_array[$value->getID()]=$o_cls->getOptionsByQid($value->getID());
	$answers_array[$value->getID()]=$ans->getByQidUid($value->getID(),$user->getID());
	
}

$this->arQuestions=$question_set;
$this->arOptions=$options_array;
$this->arAnswers=$answers_array;
}



public function prepare_user_ans()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answers_class.php');
if(count($this->arQuestions)>0)
{
foreach($this->arQuestions as $key=>$question)
{
$ans=new QuizAnswers($objPDO);
$this->arUserAnswers[$question->getID()]=$ans->getByQidUid($question->getID(),$user->getID());
}
}
}

public function showEvaluate($embed=false)
{
GLOBAL $session;
GLOBAL $objPDO;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');
$eval=new QuizEvaluate($objPDO);
$eval=$eval->getByUserIdQuizId($user->getID(),$this->ID);
if($eval)
{
$evaluation=array();
$evaluation['Marks']=$eval->getTotal();
$evaluation['Name']=$this->Quiz->getName();
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


$quiz_set=$this->Quiz;
$question_set=$this->arQuestions;
$options_array=$this->arOptions;
$answers_array=$this->arAnswers;

$quiz=array();
$quiz['ID']=$quiz_set->getID();
$quiz['Name']=$quiz_set->getName();
$quiz['Description']=$quiz_set->getDescription();
$quiz['Credits']=$quiz_set->getCredits();
$quiz['Time']=$quiz_set->getTime();
$quiz['TimeLimit']=$quiz_set->getTimeLimit();
$quiz['TimeEnd']=$quiz_set->getTimeEnd();

if((strtotime('now')-strtotime($this->Quiz->getTimeEnd())>1000) || $this->Quiz->getAccess()=='archive' || (strtotime($this->Quiz->getTimeEnd())-strtotime($this->Quiz->getTime())>5000000))
{
$questions=array();
foreach($question_set as $key=>$question)
{
	$questions[$key]['ID']=$question->getID();
	$questions[$key]['Question']=$question->getQuestion();
	$questions[$key]['Type']=$question->getType();
	$questions[$key]['Qno']=$question->getQno();
	$questions[$key]['CorrectAnswer']=$question->getCorrectOption();
	$questions[$key]['Set']=explode(',',$answers_array[$question->getID()]->getOids());
	$answer=new QuizAnswer($objPDO);
	$answer=$answer->getByQid($question->getID());
	$questions[$key]['CorrectMarks']=$answer->getCorrect();
	$questions[$key]['WrongMarks']=$answer->getWrong();
	$questions[$key]['UnansweredMarks']=$answer->getUnanswered();
	$answer=NULL;
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
foreach($this->arQuestions as $key=>$question)
{
$user_answers[$question->getID()]=$this->arUserAnswers[$question->getID()]->getOids();
$user_solution[$question->getID()]=$this->arAnswers[$question->getID()]->getSolution();
}
}
if($embed==true)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_evaluation_embed.php');
}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_evaluation.php');
}

}
else
{

if($embed==true)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_evaluation_only_embed.php');
}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_evaluation_only.php');
}

}

}

public function show($reg,$embed)
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

$quiz_set=$this->Quiz;
$question_set=$this->arQuestions;
$options_array=$this->arOptions;
$answers_array=$this->arAnswers;

$quiz=array();
$quiz['ID']=$quiz_set->getID();
$quiz['Name']=$quiz_set->getName();
$quiz['Description']=$quiz_set->getDescription();
$quiz['Credits']=$quiz_set->getCredits();
$quiz['Time']=$quiz_set->getTime();
$quiz['TimeLimit']=$quiz_set->getTimeLimit();
$quiz['TimeEnd']=$quiz_set->getTimeEnd();

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');

$questions=array();
foreach($question_set as $key=>$question)
{
	$questions[$key]['ID']=$question->getID();
	$questions[$key]['Question']=$question->getQuestion();
	$questions[$key]['Type']=$question->getType();
	$questions[$key]['Qno']=$question->getQno();
	
	$answer=new QuizAnswer($objPDO);
	$answer=$answer->getByQid($question->getID());
	$questions[$key]['CorrectMarks']=$answer->getCorrect();
	$questions[$key]['WrongMarks']=$answer->getWrong();
	$questions[$key]['UnansweredMarks']=$answer->getUnanswered();
	$answer=NULL;
	
	if($answers_array[$question->getID()] instanceof QuizAnswers && $answers_array[$question->getID()]->getOids()!="")
	{
	$questions[$key]['Set']=explode(',',$answers_array[$question->getID()]->getOids());
	}
	else
	{
	$questions[$key]['Set']=array();
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

$quiz_id=$quiz_set->getID();

if($quiz_set->getTestType()==1)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/mockquizaio.php');
}
else if($quiz_set->getTestType()==2)
{
if($embed==false)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/mockquiz.php');
}
else if($embed==true)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/mockquiz_embed.php');
}

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

$quiz_set=$this->Quiz;
$question_set=$this->arQuestions;

$quiz=array();
$quiz['ID']=$quiz_set->getID();
$quiz['Name']=$quiz_set->getName();
$quiz['Description']=$quiz_set->getDescription();
$quiz['Credits']=$quiz_set->getCredits();
$quiz['Time']=$quiz_set->getTime();
$quiz['TimeLimit']=$quiz_set->getTimeLimit();
$quiz['TimeEnd']=$quiz_set->getTimeEnd();

$questions=array();
foreach($question_set as $key=>$question)
{
	$questions[$key]['ID']=$question->getID();
	$questions[$key]['Question']=$question->getQuestion();
	$questions[$key]['Type']=$question->getType();
	$questions[$key]['Qno']=$question->getQno();
}

$quiz_id=$quiz_set->getID();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_admin_analytics_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
$admin=new QuizAdminAnalytics($objPDO);
$eval=new QuizEvaluate($objPDO);
$eval->setQuizId($quiz_id);
$total_takers=$eval->getTotalEvaluated();
$admin=$admin->getByQuizId($quiz_id);
$analytics=array();
foreach($admin as $k=>$value)
{
$analytics[$value->getType()][$value->getName()][$value->getDesc()]=$value->getValue();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_question_analytics.php');

}





public function showAdminUserReport($client)
{
GLOBAL $session;
GLOBAL $objPDO;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

$eval=new QuizEvaluate($objPDO);
$eval=$eval->getByUserIdQuizId($client->getID(),$this->ID);
if($eval)
{
$evaluation=array();
$evaluation['Marks']=$eval->getTotal();
$evaluation['Name']=$this->Quiz->getName();
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


$quiz_set=$this->Quiz;
$question_set=$this->arQuestions;
$options_array=$this->arOptions;
$answers_array=$this->arAnswers;

$quiz=array();
$quiz['ID']=$quiz_set->getID();
$quiz['Name']=$quiz_set->getName();
$quiz['Description']=$quiz_set->getDescription();
$quiz['Credits']=$quiz_set->getCredits();
$quiz['Time']=$quiz_set->getTime();
$quiz['TimeLimit']=$quiz_set->getTimeLimit();
$quiz['TimeEnd']=$quiz_set->getTimeEnd();

$questions=array();
foreach($question_set as $key=>$question)
{
	$questions[$key]['ID']=$question->getID();
	$questions[$key]['Question']=$question->getQuestion();
	$questions[$key]['Type']=$question->getType();
	$questions[$key]['Qno']=$question->getQno();
	$questions[$key]['CorrectAnswer']=$question->getCorrectOption();
	$questions[$key]['Set']=explode(',',$answers_array[$question->getID()]->getOids());
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
foreach($this->arQuestions as $key=>$question)
{
$user_answers[$question->getID()]=$this->arUserAnswers[$question->getID()]->getOids();
$user_solution[$question->getID()]=$this->arAnswers[$question->getID()]->getSolution();
}
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_admin_user_evaluate.php');


}




}