<?php
include_once('controller.php');
include_once('model/user_class.php');
class TestController extends Controller
{

private function checkPermission($test_id)
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');

$reg=new Register($objPDO);
$test_id=$_GET['uid'];
$test=new Test($objPDO,$test_id);
$reg=$reg->getByUserIdTestId($user->getID(),$test_id);
$time_start=strtotime('now');
if($reg && $reg->getUserId()==$user->getID())
{
$time_start=strtotime($reg->getTimeStarted());
}
$time_limit=$time_start+strtotime($test->getTimeLimit())-strtotime($test->getTime());

}

protected function index()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
$test=new Test($objPDO);
$tests_act=$test->getTestsActiveOnBoard();
$tests_upc=$test->getTestsUpcomingOnBoard();

$register=new Register($objPDO);
$tests_list=$register->getTestsByUserId($user->getID());
$tests_active=array();
foreach($tests_act as $k=>$test)
{
$tests_active[$k]['Name']=$test->getName();
$tests_active[$k]['ID']=$test->getID();
$tests_active[$k]['TimeStart']=$test->getTime();
$tests_active[$k]['TimeLimit']=round((strtotime($test->getTimeLimit())-strtotime($test->getTime()))/60,2);
}

foreach($tests_upc as $k=>$test)
{
$tests_upcoming[$k]['Name']=$test->getName();
$tests_upcoming[$k]['ID']=$test->getID();
$tests_upcoming[$k]['TimeStart']=$test->getTime();
$tests_upcoming[$k]['TimeLimit']=round((strtotime($test->getTimeLimit())-strtotime($test->getTime()))/60,2);
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/test_index.php');

}


protected function exam()
{
include('question_sheet.php');
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if(isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');

if($reg && $reg->getStatus()==1 && $test->getName() && strtotime('now')>strtotime($test->getTime()) && strtotime('now')<$time_limit)
{
$quest=new QuestionSheet($test_id);
$quest->prepare();
$quest->show($reg);
}
else
{
echo 'Invalid URL';
header("Location: /jee/test/start/".$test_id);
}

}
else
{
echo "Error";
}


}

//Make Admin Only Access{Done}
protected function create()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/create_test.php');
}
else
{
echo "Error";
}

}

//Make Admin Only Access //Check Plain Form Submission{Done}
protected function create_test()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
extract($_POST);

if($user->checkAdmin())
{
if(isset($test_name) && isset($desc) && isset($section) && isset($date) && isset($time) && isset($time_limit) && isset($time_end) && isset($credits) && isset($access) && is_array($section))
{
if($test_name!="" && $desc!="" && $section!="" && $date!="" && $time!="" && $credits!="" && $access!="" && $time_end!="" && $time_limit!="")
{
$test=new Test($objPDO);
$test->setName($test_name);
$test->setDescription($desc);
$test->setDate(date("Y-j-d",strtotime($date)));
$test->setTime(date("Y-m-d H:i:s",strtotime($date." ".$time)));
$test->setTimeEnd(date("Y-m-d H:i:s",strtotime($date." ".$time." +".$time_end." minutes")));
$test->setTimeLimit(date("Y-m-d H:i:s",strtotime($date." ".$time." +".$time_limit." minutes")));
$test->setCreatorId($user->getID());
$test->setCredits($credits);
$test->setAccess($access);
$test->save();

foreach($section as $key=>$value)
{
$section=new Section($objPDO);
$section->setName($value);
$section->setTid($test->getID());
$section->save();
}

$test_id=$test->getID();
header("Location: add_settings/".$test_id);
}
}
}
else
{
echo "Error";
}

}


protected function delete()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
extract($_POST);

if(isset($tid) && $user->checkAdmin())
{
$test=new Test($objPDO,$tid);
$test->load();
if($test->getCreatorId()==$user->getID())
{
$test->markForDeletion();
header("Location:/jee");
}
else
{
echo "Error1";
}
}
else
{
echo "Error";
}

}


//Make Admin Only Access //Check Plain Form Submission{Done}
protected function edit_test()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
extract($_POST);

if($user->checkAdmin())
{
if(isset($test_name) && isset($desc) && isset($section) && isset($date) && isset($time) && isset($credits) && isset($access) && isset($test_id) && is_array($section) && isset($time_limit) && isset($time_end))
{
if($test_name!="" && $desc!="" && $section!="" && $date!="" && $time!="" && $credits!="" && $access!="" && $test_id!="" && $time_end!="" && $time_limit!="")
{
$test=new Test($objPDO,$test_id);
$test->load();
if($test->getName())
{
$test->setName($test_name);
$test->setDescription($desc);
$test->setDate(date("Y-m-d",strtotime($date)));
$test->setTime(date("Y-m-d H:i:s",strtotime($date." ".$time)));
$test->setTimeEnd(date("Y-m-d H:i:s",strtotime($date." ".$time." +".$time_end." minutes")));
$test->setTimeLimit(date("Y-m-d H:i:s",strtotime($date." ".$time." +".$time_limit." minutes")));
$test->setCreatorId($user->getID());
$test->setCredits($credits);
$test->setAccess($access);
$test->save();
$sect=new Section($objPDO);
$secs=$sect->getByTid($test_id);
foreach($section as $key=>$value)
{
if(!in_array($value,$secs))
{
$sect=new Section($objPDO);
echo $value;
$sect->setName($value);
$sect->setTid($test->getID());
$sect->save();
}

}

$test_id=$test->getID();
header("Location: view/".$test_id);
}
}

}
}
else
{
echo "Error";
}

}



//Make only admin access{Done}
protected function add_settings()
{

include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
if(isset($_GET['uid']))
{
$test_id=$_GET['uid'];
$test=new Test($objPDO,$test_id);
$test_name=$test->getName();
if($test_name && $test->getCreatorId()==$user->getID())
{
$section=new Section($objPDO);
$sections=$section->getByTid($test_id);
$tgroup=new TGroup($objPDO);
$tgrs=$tgroup->getGroupsByTid($test_id);
$tgroups=array();
$sec_questions=array();
foreach($sections as $k=>$sec)
{
$section->setID($k);
$sec_questions[$k]=$section->getNoQuestions();
}

foreach($tgrs as $key=>$tgr)
{
$tgroups[$key]['Name']=$tgr->getName();
$tgroups[$key]['ID']=$tgr->getID();
$tgroups[$key]['SectionId']=$tgr->getSectionId();
$tgroups[$key]['Description']=$tgr->getDescription();
$tgroups[$key]['NumQuestions']=$tgr->getNumQuestions();
}

include($_SERVER['DOCUMENT_ROOT'].'/jee/view/add_settings.php');
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}

}

//Make admin only access //Check Plain Form Submission{Done}
protected function save_add_settings()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
extract($_POST);

if($user->checkAdmin())
{
if(isset($test_id) && $test_id!="" && isset($group_name) && $group_name!="" && isset($no_questions) && $no_questions!="")
{
$total=0;
$num=1;
$nums=array();
foreach($group_name as $key=>$value)
{
$group=new TGroup($objPDO);
if($group_id[$key]!=0)
{
$group->setID($group_id[$key]);
$group->load();
}
$group->setName($value);
$group->setDescription($group_desc[$key]);
$group->setTid($test_id);
$group->setSectionId($sections[$key]);
$group->setNumQuestions($num_questions[$key]);
$group->save();

$total+=$group->getNumQuestions();
$question=new Question($objPDO);
$questions=$question->getByTgidOrderByQno($group->getID());
$num_max=$total;
$no=$group->getNumQuestions();
$num=$total;
$num_min=$total-$no+1;
foreach($questions as $k=>$quest)
{
if($quest->getQno() > $total || $quest->getQno()<($total-$no+1) )
{
while($num<=$num_max && $num>=$num_min && in_array($num,$nums))
{
$num--;
}
if($num<=$num_max && !in_array($num,$nums))
{
$quest->setQno($num);
$quest->save();
}

}
$nums[]=$quest->getQno();

}

}
foreach($no_questions as $section_id=>$num_q)
{
$section=new Section($objPDO,$section_id);
$section->setNoQuestions($num_q);
$section->save();
}
header("Location: add_questions/".$test_id);
}
else
{
echo "Please Compelete Form";
}
}
else
{
echo "Error";
}

}

//check Admin Access{Done}
protected function add_questions()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
if(isset($_GET['uid']))
{
$test_id=$_GET['uid'];
$test=new Test($objPDO,$test_id);
$test_name=$test->getName();
if($test_name && $test->getCreatorId()==$user->getID())
{
$section=new Section($objPDO);
$sections=$section->getByTid($test_id);
$group=new TGroup($objPDO);
$group_array=$group->getGroupsByTid($test_id);
$groups=array();
foreach($group_array as $value)
{
$groups[$value->getSectionId()][$value->getID()]['name']=$value->getName();
$groups[$value->getSectionId()][$value->getID()]['description']=$value->getName();
$groups[$value->getSectionId()][$value->getID()]['num_questions']=$value->getNumQuestions();
}

include($_SERVER['DOCUMENT_ROOT'].'/jee/view/add_questions.php');
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}

}


//Make admin only access //Check Plain Form Submission{Done}
protected function save_questions_temp()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
extract($_POST);
if(isset($group_id) && isset($question) && isset($qno))
{
foreach($group_id as $key=>$value)
{
foreach($question[$value] as $key=>$quest)
{
if(isset($quest) && $quest!="")
{
$q=new Question($objPDO);
$q->setQuestion(htmlentities($quest));
$q->setQno($qno[$value][$key]);
$q->setType($type[$value][$key]);
$q->setTgid($value);
$q->save();
}

}

}
//header("Location: add_questions/".$test_id);
}
else
{
echo "Please Compelete Form";
}
}
else
{
echo "Error";
}

}


//Make admin only access //Check Plain Form Submission
protected function save_questions()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
extract($_POST);
if(isset($group_id) && isset($question) && isset($qno) && isset($type) && !($group_id=="" || $question=="" || $qno==""))
{
if(isset($question) && $question!="")
{

$q=new Question($objPDO);
if($q->getByTgIdQno($group_id,$qno) instanceof Question)
{
$q=$q->getByTgIdQno($group_id,$qno);
}
$q->setQuestion(htmlspecialchars($question,ENT_QUOTES));
$q->setQno($qno);
$q->setType($type);
$q->setTgid($group_id);
$q->save();

$opt_arr=array("A","B","C","D");
//Storing is Based on Previous array
foreach($option as $key=>$option)
{
$options=new TOptions($objPDO);
//Only 4 Options Constraint
if($options->getByQidOid($q->getID(),$opt_arr[$key]) instanceof TOptions)
{
$options=$options->getByQidOid($q->getID(),$opt_arr[$key]);
}

if(isset($option) && $option!=NULL && $option!="")
{
$options->setQid($q->getID());
$options->setOid($opt_arr[$key]);
$options->setOption($option);
$options->save();
}

}

if(isset($tag))
{
foreach($tag as $key=>$t)
{
$tags=new TestTags($objPDO);
$tags->setTag($t);
$tags->setQid($q->getID());
$tags->save();
}
}

echo "<div class='label label-success'>Saved Successfully !</div>";

}
//header("Location: add_questions/".$test_id);
}
else
{
echo "<div class='label label-warning'>Please Complete Question</div>";
}
}
else
{
echo "Error";
}

}





//Make admin only access //Check Plain Form Submission
protected function save_answers()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/answer_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
extract($_POST);
if(isset($group_id) && isset($qno) && isset($qid) && isset($type) && isset($ans) && !($group_id=="" || $qid=="" || $qno==""))
{
$q=new Question($objPDO,$qid);
if($q->getQuestion())
{
if(isset($solution) && $solution!="")
{
$q->setSolution($solution);
$q->save();
}

if(!is_array($ans))
{
$ans=array($ans);
}
$q->setCorrectOption(implode(',',$ans));
$answer=new Answer($objPDO);
$answer->deleteByQid($qid);
$answer->setQid($qid);
$answer->setOids(implode(',',$ans));
if(isset($correct))
{
$answer->setCorrect($correct);
}
if(isset($wrong))
{
$answer->setWrong($wrong);
}
if(isset($unanswered))
{
$answer->setUnanswered($unanswered);
}
$answer->save();

echo "<div class='label label-success'>Saved Successfully !</div>";
}
else
{
echo "<div class='label label-error'>Error</div>";
}

}
else
{
echo "<div class='label label-warning'>Please Complete Question</div>";
}
//header("Location: add_questions/".$test_id);
}
else
{
echo "Error";
}

}



protected function get_tags()
{
GLOBAL $objPDO;
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');
$like="";
if(isset($_GET['uid']) && $_GET['uid']!="")
{
$like=urldecode($_GET['uid']);
}
$test_tags=new TestTags($objPDO);
$list=$test_tags->getTagsLikeName($like);
$html="";
foreach($list as $key=>$value)
{
$html.='<li><a onclick="addTag(\''.$value.'\')">'.$value.'</a></li>';
}
$html.='<li class="divider"></li><li><a onclick="addTag(\''.$like.'\')">'.$like.'</a></li>';
echo $html;

}


protected function deletequestion()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
if(isset($_POST['qid']) && isset($_POST['tid']))
{
$question=new Question($objPDO,$_POST['qid']);
$question->markForDeletion();
header("Location:add_questions/".$_POST['tid']);
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}
}


protected function deletegroup()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
if(isset($_POST['tid']) && isset($_POST['tgid']))
{
$test=new Test($objPDO,$_POST['tid']);
if($test->getCreatorId()==$user->getID())
{
$tgroup=new TGroup($objPDO,$_POST['tgid']);
$tgroup->markForDeletion();
header("Location:/jee/add_settings/".$_POST['tid']);
}

}
else
{
echo "Error";
}
}
else
{
echo "Error";
}
}


protected function question()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
include('utility_class.php');
$tgid="";
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$tgid=$_GET['uid'];
$qno=$_GET['ref'];
}

$question=new Question($objPDO);
$quest=new Question($objPDO);
$quest=$question->getByTgIdQno($tgid,$qno);
if($quest)
{
$options=new TOptions($objPDO);
$opts=array();
$opts=$options->getOptionsByQid($quest->getID());
$strOption=array();
foreach($opts as $key=>$opt)
{
$strOption[$key]['ID']=$opt->getID();
$strOption[$key]['Option']=$opt->getOption();
}
Utility::AddQuestion($qno,$quest->getQuestion(),$strOption);
echo "<a onclick='editQuestion(this.id)' id='edit-".$tgid."-".$qno."' class='btn btn-warning'><i class='icon-edit'/>Edit</a> <a onclick='deleteQuestion(this.id)' id='".$quest->getID()."' class='btn btn-danger'><i class='icon-white icon-remove'/>Delete</a>";
}
else
{
return false;
}
}
else
{
echo "Error";
}

}


protected function question_ans()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/answer_class.php');
include('utility_class.php');
$tgid="";
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$tgid=$_GET['uid'];
$qno=$_GET['ref'];
}

$question=new Question($objPDO);
$quest=new Question($objPDO);
$quest=$question->getByTgIdQno($tgid,$qno);
if($quest)
{
$options=new TOptions($objPDO);
$opts=array();
$opts=$options->getOptionsByQid($quest->getID());
$strOption=array();
foreach($opts as $key=>$opt)
{
$strOption[$key]['ID']=$opt->getID();
$strOption[$key]['Option']=$opt->getOption();
}
Utility::AddQuestion($qno,$quest->getQuestion(),$strOption);
echo "<a onclick='setAnswer(this.id)' id='edit-".$tgid."-".$qno."' class='btn btn-warning'><i class='icon-pencil'/>Set Answer</a>";
$answer=new Answer($objPDO);
if($answer->getByQid($quest->getID())->getID())
{
echo '  <div class="label label-success">Answer Set</div>';
}
else
{
echo '  <div class="label label-important">Answer Not Set</div>';
}

if($quest->getSolution())
{
echo '  <div class="label label-success">Solution Set</div>';
}
else
{
echo '  <div class="label label-important">Solution Not Set</div>';
}


}
else
{
return false;
}
}
else
{
echo "Error";
}

}


protected function question_answer()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/answer_class.php');
include('utility_class.php');
$tgid="";
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$tgid=$_GET['uid'];
$qno=$_GET['ref'];
}
$tgroup=new TGroup($objPDO,$tgid);
$test=new Test($objPDO,$tgroup->getTid());
if($test->getCreatorId()==$user->getID())
{
$question=new Question($objPDO);
$quest=new Question($objPDO);
$quest=$question->getByTgIdQno($tgid,$qno);
if($quest)
{
$options=new TOptions($objPDO);
$opts=array();
$opts=$options->getOptionsByQid($quest->getID());
$strOption=array();
foreach($opts as $key=>$opt)
{
$strOption[$key]['ID']=$opt->getID();
$strOption[$key]['Option']=$opt->getOption();
}
$type=$quest->getType();
$qnum=$quest->getQno();
$correct_opt=$quest->getCorrectOption();
$ans=array();
if(isset($correct_opt))
{
$ans=explode(',',$correct_opt);
}
Utility::AddQuestion($qno,$quest->getQuestion(),$strOption);
Utility::inputHidden("type","type",$type);
Utility::inputHidden("qid","qid",$quest->getID()); 
Utility::inputHidden("sol","sol",$quest->getSolution()); 
			if($type=="single"|| $type=="multiple")
			{
				Utility::StartOptionsTable();
				Utility::AddRow("ans","ans",$qnum,Utility::$Type[$type],NULL,NULL,$ans);
			}
			else if($type=="numerical")
			{
				Utility::AddNumericalTable("ans","ans",$qnum,NULL,$ans);
			}
			else if($type=="match")
			{
			Utility::AddMatchTable("ans","ans",$qnum,NULL,NULL,NULL,NULL,$ans);
			}
			
			}		
			Utility::CloseTable();	
			Utility::CloseAccordion();
$answer=new Answer($objPDO);
$answer=$answer->getByQid($quest->getID());
echo '<table class="table table-bordered"><tr>';
echo '<td>Marks (Correct | Wrong | Unanswered) :</td><td>';
Utility::inputText("correct","correct","Correct Points",NULL,NULL,$answer->getCorrect());
echo '</td><td>';
Utility::inputText("wrong","wrong","Wrong Points",NULL,NULL,$answer->getWrong());
echo '</td><td>';
Utility::inputText("unanswered","unanswered","Unanswered Points",NULL,NULL,$answer->getUnanswered());
echo '</td></tr></table>';
}
else
{
echo "Error";
return false;
}
}
else
{
echo "Error";
return false;
}

}


protected function getquestion()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
$tgid="";
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$tgid=$_GET['uid'];
$qno=$_GET['ref'];
}
$tgroup=new TGroup($objPDO,$tgid);
$test=new Test($objPDO,$tgroup->getTid());
if($test->getCreatorId()==$user->getID())
{
$question=new Question($objPDO);
$quest=new Question($objPDO);
$quest=$question->getByTgIdQno($tgid,$qno);
if($quest)
{

echo '{"ID":"'.$quest->getID().'","question":"'.urlencode(htmlspecialchars_decode($quest->getQuestion())).'","type":"'.$quest->getType().'",';
$this->getoptionsbyqid($quest->getID());
echo ",";
$this->gettagsbyqid($quest->getID());
echo '}';
}
}
else
{
echo "Error";
}

}
else
{
echo "Error";
}
}

protected function getoptionsbyqid($qid)
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
$option=new TOptions($objPDO);
$options=array();
$options=$option->getOptionsByQid($qid);
$json='"options":{ ';
foreach($options as $key=>$option)
{
$json.= '"'.$key.'":"'.$option->getOption().'",';
}
$json=substr($json,0,-1);
$json.='}';
echo $json;
}
else
{
echo "Error";
}

}

protected function gettagsbyqid($qid)
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');
$tag=new TestTags($objPDO);
$tags=$tag->getTagsByQid($qid);
$json='"tags":{';
if($tags)
{
foreach($tags as $key=>$option)
{
$json.= '"'.$key.'":"'.$option->getTag().'",';
}
$json=substr($json,0,-1);
}
$json.='}';
echo $json;
}
else
{
echo "Error";
}
}


protected function set_answers()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
if(isset($_GET['uid']))
{
$test_id=$_GET['uid'];
$test=new Test($objPDO,$test_id);
$test_name=$test->getName();
if($test_name && $test->getCreatorId()==$user->getID())
{
$section=new Section($objPDO);
$sections=$section->getByTid($test_id);
$group=new TGroup($objPDO);
$group_array=$group->getGroupsByTid($test_id);
$groups=array();
foreach($group_array as $value)
{
$groups[$value->getSectionId()][$value->getID()]['name']=$value->getName();
$groups[$value->getSectionId()][$value->getID()]['description']=$value->getName();
$groups[$value->getSectionId()][$value->getID()]['num_questions']=$value->getNumQuestions();
}

include($_SERVER['DOCUMENT_ROOT'].'/jee/view/add_answers.php');
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}
}


protected function view()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if($user->checkAdmin() && isset($_GET['uid']))
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
$test=new Test($objPDO,$_GET['uid']);
if($test->getName()!=NULL && $test->getCreatorId()==$user->getID())
{
$test_details['Name']=$test->getName();
$test_details['ID']=$test->getID();
$test_details['Date']=$test->getDate();
$test_details['Time']=$test->getTime();
$test_details['Description']=$test->getDescription();
$test_details['Credits']=$test->getCredits();
$test_details['Access']=$test->getAccess();
$test_details['TimeLimit']=$test->getTimeLimit();
$test_details['TimeEnd']=$test->getTimeEnd();
$sec=new Section($objPDO);
$section=$sec->getByTid($test->getID());
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/view_test.php');
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}

}


protected function setonboard()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if($user->checkAdmin() && isset($_GET['uid']))
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
$test=new Test($objPDO,$_GET['uid']);
if($test->getName()!=NULL && $test->getCreatorId()==$user->getID())
{
$test_id=$test->getID();
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/setonboard.php');
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}
}


protected function check_constraints()
{

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if($user->checkAdmin() && isset($_GET['uid']))
{
$flag=1;
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answer_class.php');
$test=new Test($objPDO,$_GET['uid']);
$test->load();
if($test->getName()!=NULL && $test->getCreatorId()==$user->getID())
{
$result=array();
$test_id=$test->getID();
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/load_page.php');
if($test->getName() && $test->getDate() && $test->getTime() && $test->getAccess() && $test->getCreatorId() && $test->getStatus()==0)
{
$result[]='<div class="label label-success">Test Details Verified. </div>';
}
else
{
$result[]='<div class="label label-important">Test Details Invalid</div>';
if($test->getStatus()!=0)
{
$result[]='<div class="label label-important">Test already On-Board</div>';
}

$flag=0;
}

$sec=new Section($objPDO);
$sections=$sec->getByTid($test_id);
foreach($sections as $key=>$sect)
{
$section=new Section($objPDO,$key);
if($section->getName() && $section->getNoQuestions())
{
$result[]='<div class="label label-success">Section Settings Verified. </div>';
}
else
{
$result[]='<div class="label label-important">Section Settings Invalid</div>';
$flag=0;
}

$grp=new TGroup($objPDO);
$groups=$grp->getGroupsByTidSectionId($test->getID(),$section->getID());
$total=0;
foreach($groups as $k=>$group)
{
if($group->getName() && $group->getTid() && $group->getSectionId() && $group->getNumQuestions())
{
$total+=$group->getNumQuestions();
$result[]='<div class="label label-success">Groups Settings Verified. </div>';
}
else
{
$result[]='<div class="label label-important">Groups Settings Invalid. </div>';
$flag=0;
}

$quest=new Question($objPDO);
$questions=$quest->getQuestionsByTgid($group->getID());

if(count($questions)==$group->getNumQuestions())
{
$result[]='<div class="label label-success">Question Settings Verified. </div>';
}
else
{
$result[]='<div class="label label-important">Number of Questions Invalid (All Questions Not Set). </div>';
$flag=0;
}


foreach($questions as $t=>$question)
{

if($question->getQuestion() && $question->getCorrectOption() && $question->getType() && $question->getQno())
{
$result[]='<div class="label label-success">Questions Verified. </div>';
}
else
{
$result[]='<div class="label label-important">Questions Invalid </div>';
$flag=0;
}

$answer=new Answer($objPDO);
$answer->getByQid($question->getID());
if($answer->getOids() && $answer->getCorrect())
{
$result[]='<div class="label label-success">Answers Verified. </div>';
}
else
{
$result[]='<div class="label label-important">Answers Not Set (Check for Correct answer Marks Also)</div>';
$flag=0;
}

}



}

if($total==$section->getNoQuestions())
{
$result[]='<div class="label label-success">Number of Questions Verified. </div>';
}
else
{
$section->setNoQuestions($total);
$result[]='<div class="label label-important">Number of Questions Inconsistancy Corrected..</div>';
$flag=0;
}

}

if($flag==1)
{
$this->constraint_success($test->getID(),$result,"#98678");
}
else
{
$this->constraint_failure($test->getID(),$result);
}

}
else
{
echo "Error";
}
}
else
{
echo "Error";
}



}


private function constraint_success($tid,$result,$key)
{
GLOBAL $objPDO;
GLOBAL $session;
echo '<script type="text/javascript">$("#content").html("");</script>';
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if($key=="#98678" && $user->checkAdmin())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
$test=new Test($objPDO,$tid);
if($test->getCreatorId()==$user->getID())
{
$test->setStatus(1);
$test->save();

$html="";
foreach($result as $res)
{
$html.=$res."<br/>";
}
$head='<div class="pull-right"><a class="btn btn-success" href="/jee/test/exam/'.$test->getID().'">Go To Test</a></div>';
echo '<script type="text/javascript">$("#content").html(\''.$html.'\');$("#head").append(\''.$head.'\');</script>';

}
else
{
echo "Error";
}

}
else
{
echo "Error";
}

}

private function constraint_failure($tid,$result)
{
GLOBAL $objPDO;
GLOBAL $session;
echo '<script type="text/javascript">$("#content").html("");</script>';
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if($user->checkAdmin())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
$test=new Test($objPDO,$tid);
if($test->getCreatorId()==$user->getID())
{
$html="";
foreach($result as $res)
{
$html.=$res."<br/>";
}
$head='<div class="pull-right"><a class="btn btn-danger" href="/jee/test/view/'.$test->getID().'">Check Back</a></div>';
echo '<script type="text/javascript">$("#content").html(\''.$html.'\');$("#head").append(\''.$head.'\');</script>';
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}


}


protected function start()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if(isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');


$test=new Test($objPDO,$_GET['uid']);
if($test->getName()!=NULL)
{

$register=new Register($objPDO);
$register=$register->getByUserIdTestId($user->getID(),$_GET['uid']);
if($register && $register->getStatus()==2)
{
header("Location: /jee/");
return;
}

$test_details['Name']=$test->getName();
$test_details['ID']=$test->getID();
$test_details['Date']=$test->getDate();
$test_details['Time']=$test->getTime();
$test_details['Description']=$test->getDescription();
$test_details['Credits']=$test->getCredits();
$test_details['Access']=$test->getAccess();
$test_details['TimeLimit']=$test->getTimeLimit();
$test_details['TimeEnd']=$test->getTimeEnd();
$test_details['Started']=0;
if($register && $register->getStatus()==1)
{
$test_details['Started']=1;
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/test_start.php');
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}

}


protected function start_test()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if(isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
$test=new Test($objPDO,$_GET['uid']);
$register=new Register($objPDO);
$register=$register->getByUserIdTestId($user->getID(),$_GET['uid']);
$time_start=strtotime('now');
$time_lim=strtotime($test->getTimeEnd());
if($register && $register->getStatus()==1)
{
$time_start=strtotime($register->getTimeStarted());
$time_lim=$time_start+strtotime($test->getTimeLimit())-strtotime($test->getTime());
}
else if($register && $register->getUserId()==$user->getID() && $register->getStatus()!=1)
{
echo "Error1";
return;
}
else if(!($register instanceof Register))
{
$register=new Register($objPDO);
}


if($test->getName() && strtotime('now')-$time_lim<0 && strtotime('now')>strtotime($test->getTime()))
{
$register->setTestId($_GET['uid']);
$register->setUserId($user->getID());
$register->setStatus(1);
$register->setTimeStarted(date("Y-m-d H:i:s",time()));
$register->save();
header("Location:/jee/test/exam/".$_GET['uid']);
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}

}



protected function finish()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());

if($user->getID() && isset($_GET['uid']))
{
$flag=1;
extract($_POST);
if(isset($test_id))
{

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/toptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answers_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
$test=new Test($objPDO,$test_id);
$test->load();
$register=new Register($objPDO);
$register->getByUserIdTestId($user->getID(),$test->getID());

$timeLimit=date("Y-m-d H:i:s",strtotime($register->getTimeStarted())+strtotime($test->getTimeLimit())-strtotime($test->getTime()));
date_default_timezone_set('Asia/Calcutta');
if(strtotime($timeLimit)-strtotime('now')<-120)
{
echo "<b>Test Closed</b>";
echo '<meta http-equiv="Refresh" content="0;url=/jee/test/temp/'.$test_id.'">';
header("Location: /jee");
return;
}

if((strtotime($timeLimit)-strtotime('now'))>-120 && $register->getStatus()==1)
{
if($test->getName()!=NULL)
{
$result=array();
$test_id=$test->getID();
$sec=new Section($objPDO);
$sections=$sec->getByTid($test_id);
foreach($sections as $key=>$sect)
{
$section=new Section($objPDO,$key);
$grp=new TGroup($objPDO);
$groups=$grp->getGroupsByTidSectionId($test->getID(),$section->getID());
foreach($groups as $k=>$group)
{
$quest=new Question($objPDO);
$questions=$quest->getQuestionsByTgid($group->getID());
foreach($questions as $t=>$question)
{
$qno=$question->getQno();
$answers=new Answers($objPDO);
$answers=$answers->getByQidUid($question->getID(),$user->getID());
$answers->setOids("");
if(isset($_POST['Q'.$qno]))
{
$ans=$_POST['Q'.$qno];
if(!is_array($ans))
{
$ans=array($ans);
}
$answers->setUid($user->getID());
$answers->setQid($question->getID());
$answers->setOids(join(',',$ans));
$answers->save();
}
}
}
}


$time_l=date("Y-m-d H:i:s",strtotime($register->getTimeStarted())+strtotime($test->getTimeLimit())-strtotime($test->getTime()));

if(isset($time_l))
{
if(strtotime($time_l)-strtotime("now")<0 || (isset($_POST['finish']) && $_POST['finish']==1))
{
$register->getByUserIdTestId($user->getID(),$test_id);
$register->setStatus(2);
$register->save();
echo '<meta http-equiv="Refresh" content="0;url=/jee/test/temp/'.$test_id.'">';
return;
}

}

echo "<b>Responses Saved</b>";
}
else
{
echo "<b>Error in Saving : </b>Refresh to Correct.... ";
}
}
else
{
echo "You have Submitted Already";
header('Location: /jee/');
echo '<meta http-equiv="Refresh" content="0;url=/jee/">';
}
}
else
{
echo "Error";
}
}
else
{
echo "Error";
}



}


protected function temp()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);

if(isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/complete_test.php');
echo '<meta http-equiv="Refresh" content="0;url=/jee/test/evaluate/'.$_GET['uid'].'">';
}
else
{
echo 'Error';
}

}


protected function evaluate()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/evaluate_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answer_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/answers_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/section_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/analytics_class.php');
if($user->getID() && isset($_GET['uid']))
{
$test_id=$_GET['uid'];
$test=new Test($objPDO,$_GET['uid']);
$register=new Register($objPDO);
$register->getByUserIdTestId($user->getID(),$test->getID());
if($test->getName() && $register->getStatus()==2)
{
$total=0;
$exam_total=0;
$correct=0;
$unanswered=0;
$wrong=0;
$analytics=array();
$tgroup=new TGroup($objPDO);
$groups=$tgroup->getGroupsByTid($test->getID());
if($groups)
{
$analytics['group']=array();
$analytics['tag']=array();
$analytics['section']=array();



foreach($groups as $k=>$tgroup)
{
$section=new Section($objPDO,$tgroup->getSectionId());

$section=new Section($objPDO,$tgroup->getSectionId());
$group_name=$tgroup->getName();
//$group_name=$tgroup->getID();
$section_name=$section->getName();
//$section_name=$section->getID();
if(!array_key_exists($section_name,$analytics['group']))
{
$analytics['group'][$section_name]=array();
}
if(!array_key_exists($group_name,$analytics['group'][$section_name]))
{
$analytics['group'][$section_name][$group_name]['Marks']=0;
$analytics['group'][$section_name][$group_name]['Positives']=0;
$analytics['group'][$section_name][$group_name]['Negatives']=0;
$analytics['group'][$section_name][$group_name]['Correct']=0;
$analytics['group'][$section_name][$group_name]['Wrong']=0;
$analytics['group'][$section_name][$group_name]['Unanswered']=0;
$analytics['group'][$section_name][$group_name]['TotalQuestions']=0;
$analytics['group'][$section_name][$group_name]['TotalMarks']=0;
$analytics['group'][$section_name][$group_name]['Section']=$section_name;
}

if(!array_key_exists($section_name,$analytics['section']))
{
$analytics['section'][$section_name]=array();
}
if(!array_key_exists($section_name,$analytics['section'][$section_name]))
{
$analytics['section'][$section_name][$section_name]['Marks']=0;
$analytics['section'][$section_name][$section_name]['Positives']=0;
$analytics['section'][$section_name][$section_name]['Negatives']=0;
$analytics['section'][$section_name][$section_name]['Correct']=0;
$analytics['section'][$section_name][$section_name]['Wrong']=0;
$analytics['section'][$section_name][$section_name]['Unanswered']=0;
$analytics['section'][$section_name][$section_name]['TotalQuestions']=0;
$analytics['section'][$section_name][$section_name]['TotalMarks']=0;
$analytics['section'][$section_name][$section_name]['Section']=$section_name;
}


$question=new Question($objPDO);
$questions=array();
$questions=$question->getQuestionsByTgid($tgroup->getID());
if($questions)
{
foreach($questions as $key=>$question)
{
$tag=new TestTags($objPDO);
$tags=$tag->getTagsByQid($question->getID());
if(!$tags)
{
$tags=array();
}
$analyts=array();
$analyts['Marks']=0;
$analyts['Positives']=0;
$analyts['Negatives']=0;
$analyts['Correct']=0;
$analyts['Wrong']=0;
$analyts['Unanswered']=0;
$analyts['TotalQuestions']=0;
$analyts['TotalMarks']=0;

$analyts['TotalQuestions']+=1;
$answers=new Answers($objPDO);
$answers=$answers->getByQidUid($question->getID(),$user->getID());
$answer=new Answer($objPDO);
$answer=$answer->getByQid($question->getID());
$correct_ans=$answer->getOids();
$your_ans=$answers->getOids();
if($correct_ans)
{
$correct_points=$answer->getCorrect();
$exam_total+=$correct_points;
$analyts['TotalMarks']+=$correct_points;
$wrong_points=$answer->getWrong();
$unanswered_points=$answer->getUnanswered();
if($your_ans && $your_ans!="")
{
$correct_ans=explode(',',$correct_ans);
$your_ans=explode(',',$your_ans);
$corr=1;
foreach($correct_ans as $val)
{
if(!in_array($val,$your_ans))
{
$corr=0;
}
}

if($corr==0)
{
$wrong++;
$total+=$wrong_points;
$analyts['Wrong']+=1;
$analyts['Negatives']+=$wrong_points;
$analyts['Marks']+=$wrong_points;
}
else if($corr==1)
{
$correct++;
$total+=$correct_points;
$analyts['Correct']+=1;
$analyts['Positives']+=$correct_points;
$analyts['Marks']+=$correct_points;
}

}
else
{
$unanswered++;
$total+=$unanswered_points;
$analyts['Unanswered']+=1;
$analyts['Marks']+=$unanswered_points;
}
}


foreach($tags as $tag)
{
$tag_value=$tag->getTag();
if(!array_key_exists($section_name,$analytics['tag']))
{
$analytics['tag'][$section_name]=array();
}
if(!array_key_exists($tag_value,$analytics['tag'][$section_name]))
{
$analytics['tag'][$section_name][$tag_value]['Marks']=0;
$analytics['tag'][$section_name][$tag_value]['Positives']=0;
$analytics['tag'][$section_name][$tag_value]['Negatives']=0;
$analytics['tag'][$section_name][$tag_value]['Correct']=0;
$analytics['tag'][$section_name][$tag_value]['Wrong']=0;
$analytics['tag'][$section_name][$tag_value]['Unanswered']=0;
$analytics['tag'][$section_name][$tag_value]['TotalQuestions']=0;
$analytics['tag'][$section_name][$tag_value]['TotalMarks']=0;
$analytics['tag'][$section_name][$tag_value]['Section']=$section_name;
}
$analytics['tag'][$section_name][$tag_value]['Marks']+=$analyts['Marks'];
$analytics['tag'][$section_name][$tag_value]['Positives']+=$analyts['Positives'];
$analytics['tag'][$section_name][$tag_value]['Negatives']+=$analyts['Negatives'];
$analytics['tag'][$section_name][$tag_value]['Correct']+=$analyts['Correct'];
$analytics['tag'][$section_name][$tag_value]['Wrong']+=$analyts['Wrong'];
$analytics['tag'][$section_name][$tag_value]['Unanswered']+=$analyts['Unanswered'];
$analytics['tag'][$section_name][$tag_value]['TotalQuestions']+=$analyts['TotalQuestions'];
$analytics['tag'][$section_name][$tag_value]['TotalMarks']+=$analyts['TotalMarks'];

}


$analytics['group'][$section_name][$group_name]['Marks']+=$analyts['Marks'];
$analytics['group'][$section_name][$group_name]['Positives']+=$analyts['Positives'];
$analytics['group'][$section_name][$group_name]['Negatives']+=$analyts['Negatives'];
$analytics['group'][$section_name][$group_name]['Correct']+=$analyts['Correct'];
$analytics['group'][$section_name][$group_name]['Wrong']+=$analyts['Wrong'];
$analytics['group'][$section_name][$group_name]['Unanswered']+=$analyts['Unanswered'];
$analytics['group'][$section_name][$group_name]['TotalQuestions']+=$analyts['TotalQuestions'];
$analytics['group'][$section_name][$group_name]['TotalMarks']+=$analyts['TotalMarks'];

$analytics['section'][$section_name][$section_name]['Marks']+=$analyts['Marks'];
$analytics['section'][$section_name][$section_name]['Positives']+=$analyts['Positives'];
$analytics['section'][$section_name][$section_name]['Negatives']+=$analyts['Negatives'];
$analytics['section'][$section_name][$section_name]['Correct']+=$analyts['Correct'];
$analytics['section'][$section_name][$section_name]['Wrong']+=$analyts['Wrong'];
$analytics['section'][$section_name][$section_name]['Unanswered']+=$analyts['Unanswered'];
$analytics['section'][$section_name][$section_name]['TotalQuestions']+=$analyts['TotalQuestions'];
$analytics['section'][$section_name][$section_name]['TotalMarks']+=$analyts['TotalMarks'];



}

}

}

}

$evaluate=new Evaluate($objPDO);
$evaluate->deleteByUserIdTestId($user->getID(),$test->getID());
$evaluate->setUserId($user->getID());
$evaluate->setTestId($test->getID());
$evaluate->setTotal($total);
$evaluate->setExamTotal($exam_total);
$evaluate->save();
foreach($analytics as $type=>$value)
{
foreach($value as $section=>$sec)
{
foreach($sec as $val=>$data)
{
$a=new Analytics($objPDO);
$a->getByUserIdTestIdTypeValueSection($user->getID(),$test->getID(),$type,$val,$section);
$a->setType($type);
$a->setUserId($user->getID());
$a->setTestId($test->getID());
$a->setValue($val);
$a->setMarks($data['Marks']);
$a->setPositives($data['Positives']);
$a->setNegatives($data['Negatives']);
$a->setCorrect($data['Correct']);
$a->setWrong($data['Wrong']);
$a->setUnanswered($data['Unanswered']);
$a->setTotalQuestions($data['TotalQuestions']);
$a->setTotalMarks($data['TotalMarks']);
$a->setSection($section);
$a->save();
}
}
}

}
include_once('question_sheet.php');
$question_sheet=new QuestionSheet($test_id);
$question_sheet->prepare();
$question_sheet->prepare_user_ans();
$question_sheet->showEvaluate();

}
else
{
echo "Error";
}


}



protected function analytics()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);


if(isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
$test=new Test($objPDO,$_GET['uid']);
$test_name=$test->getName();
$register=new Register($objPDO);
$register=$register->getByUserIdTestId($user->getID(),$test->getID());
if($test->getName() && $register && $register->getStatus()==2)
{
$analytics=array();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/analytics_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/evaluate_class.php');
$analyt=new Analytics($objPDO);
$eval=new Evaluate($objPDO);
$eval=$eval->getByUserIdTestId($user->getID(),$test->getID());
if($eval)
{
$evaluation=array();
$evaluation['Marks']=$eval->getTotal();
$evaluation['Rank']=$eval->getRank();
$evaluation['Percentile']=$eval->getPercentile();
$evaluation['Average']=$eval->getAverageMarks();
$evaluation['Percentage']=round(($eval->getTotal()/$eval->getExamTotal())*100,2);
$evaluation['Highest']=$eval->getMaxMarks();
$evaluation['TotalMarks']=$eval->getExamTotal();
}

$analytics_array=$analyt->getByUserIdTestId($user->getID(),$test->getID());
foreach($analytics_array as $k=>$analytic)
{
$analytics[$analytic->getType()][$analytic->getValue()]['Marks']=$analytic->getMarks();
$analytics[$analytic->getType()][$analytic->getValue()]['Positives']=$analytic->getPositives();
$analytics[$analytic->getType()][$analytic->getValue()]['Negatives']=$analytic->getNegatives();
$analytics[$analytic->getType()][$analytic->getValue()]['Correct']=$analytic->getCorrect();
$analytics[$analytic->getType()][$analytic->getValue()]['Wrong']=$analytic->getWrong();
$analytics[$analytic->getType()][$analytic->getValue()]['Section']=$analytic->getSection();
$analytics[$analytic->getType()][$analytic->getValue()]['Unanswered']=$analytic->getUnanswered();
$analytics[$analytic->getType()][$analytic->getValue()]['TotalQuestions']=$analytic->getTotalQuestions();
$analytics[$analytic->getType()][$analytic->getValue()]['TotalMarks']=$analytic->getTotalMarks();
$analytics[$analytic->getType()][$analytic->getValue()]['Average']=$analytic->getAverageMarksByTypeValueTid();
}
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/graphs.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/view_analytics.php');
}
else
{
echo 'Error';
}

}
else
{
echo 'error';
}

}



}
?>
