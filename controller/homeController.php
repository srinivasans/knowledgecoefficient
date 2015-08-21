<?php
include_once('controller.php');
include_once('model/user_class.php');
class HomeController extends Controller
{
protected function index()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"dashboard",
);
if($session->isAdmin())
{
$name=$user->getFirstName();
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/evaluate_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/update_class.php');
$test=new Test($objPDO);
$quiz=new Quiz($objPDO);
$tc=$test->getTestsActiveOnBoard(5);
$tests_active=array();

$tests_trend=$test->getTrendingTests(5);
$quizs_trend=$quiz->getTrendingQuizs(5);


foreach($quizs_trend as $key=>$value)
{
$quizs_trending[$key]['Name']=$value->getName();
$quizs_trending[$key]['ID']=$value->getID();
$quizs_trending[$key]['Date']=$value->getDate();
$quizs_trending[$key]['Time']=$value->getTime();
$quizs_trending[$key]['Access']=$value->getAccess();
}

foreach($tests_trend as $key=>$value)
{
$tests_trending[$key]['Name']=$value->getName();
$tests_trending[$key]['ID']=$value->getID();
$tests_trending[$key]['Date']=$value->getDate();
$tests_trending[$key]['Time']=$value->getTime();
$tests_trending[$key]['Access']=$value->getAccess();
}

$update=new Update($objPDO);
$upds=$update->getByUserGroup('admin');
$updates=array();
if($upds)
{
foreach($upds as $key=>$update)
{
$updates[$key]['ID']=$update->getID();
$updates[$key]['Heading']=$update->getHeading();
$updates[$key]['Link']=$update->getLink();
}
}

foreach($tc as $key=>$value)
{
$tests_active[$key]['Name']=$value->getName();
$tests_active[$key]['ID']=$value->getID();
$tests_active[$key]['Date']=$value->getDate();
$tests_active[$key]['Time']=$value->getTime();
$tests_active[$key]['Access']=$value->getAccess();
}
$reg=new Register($objPDO);
$attempted=$reg->getTidsByUidCompleted($user->getID(),5);
$performance=array();
if($attempted)
{
foreach($attempted as $k=>$attempt)
{
$t=new Evaluate($objPDO);
$t=$t->getByUserIdTestId($user->getID(),$attempt);
$test->setID($attempt);
$performance[$k]['Name']=$test->getName();
$performance[$k]['ID']=$attempt;

if($t && $t->getExamTotal()!=0)
{
$performance[$k]['Percentage']=round(($t->getTotal()/$t->getExamTotal())*100,2);
}
else
{
$performance[$k]['Percentage']=0;
$performance[$k]['EvaluateNow']=$attempt;
$performance[$k]['Evaluated']=false;
}

}

}

$tu=$test->getTestsUpcomingOnBoard(5);
$tests_upcoming=array();
foreach($tu as $key=>$value)
{
$tests_upcoming[$key]['Name']=$value->getName();
$tests_upcoming[$key]['ID']=$value->getID();
$tests_upcoming[$key]['Date']=$value->getDate();
$tests_upcoming[$key]['Time']=$value->getTime();
$tests_upcoming[$key]['Access']=$value->getAccess();
}


//Quiz values
$quiz=new Quiz($objPDO);

$qc=$quiz->getQuizsByCreatorId($user->getID(),5);
$quizs_created=array();
if($qc)
{
foreach($qc as $key=>$value)
{
$quizs_created[$key]['Name']=$value->getName();
$quizs_created[$key]['ID']=$value->getID();
$quizs_created[$key]['Date']=$value->getDate();
$quizs_created[$key]['Time']=$value->getTime();
$quizs_created[$key]['Access']=$value->getAccess();
}
}

$qo=$quiz->getQuizsByCreatorIdOnBoard($user->getID(),5);
$quizs_onboard=array();
if($qo)
{
foreach($qo as $key=>$value)
{
$quizs_onboard[$key]['Name']=$value->getName();
$quizs_onboard[$key]['ID']=$value->getID();
$quizs_onboard[$key]['Date']=$value->getDate();
$quizs_onboard[$key]['Time']=$value->getTime();
$quizs_onboard[$key]['Access']=$value->getAccess();
}
}


$qc=$quiz->getQuizsActiveOnBoard(5);
$quizs_active=array();

foreach($qc as $key=>$value)
{
$quizs_active[$key]['Name']=$value->getName();
$quizs_active[$key]['ID']=$value->getID();
$quizs_active[$key]['Date']=$value->getDate();
$quizs_active[$key]['Time']=$value->getTime();
$quizs_active[$key]['Access']=$value->getAccess();
}
$qreg=new QuizRegister($objPDO);
$attempted=$qreg->getQidsByUidCompleted($user->getID(),5);
$quiz_performance=array();
if($attempted)
{
foreach($attempted as $k=>$attempt)
{
$q=new QuizEvaluate($objPDO);
$q=$q->getByUserIdQuizId($user->getID(),$attempt);
$quiz->setID($attempt);
$quiz_performance[$k]['Name']=$quiz->getName();
$quiz_performance[$k]['ID']=$attempt;

if($q)
{
$quiz_performance[$k]['Percentage']=round(($q->getTotal()/$q->getExamTotal())*100,2);
}
else
{
$quiz_performance[$k]['EvaluateNow']=$attempt;
$quiz_performance[$k]['Evaluated']=false;
}

}

}

$qu=$quiz->getQuizsUpcomingOnBoard(5);
$quizs_upcoming=array();
foreach($qu as $key=>$value)
{
$quizs_upcoming[$key]['Name']=$value->getName();
$quizs_upcoming[$key]['ID']=$value->getID();
$quizs_upcoming[$key]['Date']=$value->getDate();
$quizs_upcoming[$key]['Time']=$value->getTime();
$quizs_upcoming[$key]['Access']=$value->getAccess();
}





$tc=$test->getTestsByCreatorId($user->getID(),5);
$tests_created=array();
if($tc)
{
foreach($tc as $key=>$value)
{
$tests_created[$key]['Name']=$value->getName();
$tests_created[$key]['ID']=$value->getID();
$tests_created[$key]['Date']=$value->getDate();
$tests_created[$key]['Time']=$value->getTime();
$tests_created[$key]['Access']=$value->getAccess();
}
}

$update=new Update($objPDO);
$upds=$update->getByUserGroup('admin');
$updates=array();
if($upds)
{
foreach($upds as $key=>$update)
{
$updates[$key]['ID']=$update->getID();
$updates[$key]['Heading']=$update->getHeading();
$updates[$key]['Link']=$update->getLink();
}
}

$to=$test->getTestsByCreatorIdOnBoard($user->getID(),5);
$tests_onboard=array();
if($to)
{
foreach($to as $key=>$value)
{
$tests_onboard[$key]['Name']=$value->getName();
$tests_onboard[$key]['ID']=$value->getID();
$tests_onboard[$key]['Date']=$value->getDate();
$tests_onboard[$key]['Time']=$value->getTime();
$tests_onboard[$key]['Access']=$value->getAccess();
}
}

include($_SERVER['DOCUMENT_ROOT'].'/jee/view/admin_home.php');
}
else if($session->isUser())
{
$name=$user->getFirstName();

include($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/register_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/evaluate_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/update_class.php');
$test=new Test($objPDO);
$quiz=new Quiz($objPDO);

$tests_trend=$test->getTrendingTests(5);
$quizs_trend=$quiz->getTrendingQuizs(5);


foreach($quizs_trend as $key=>$value)
{
$quizs_trending[$key]['Name']=$value->getName();
$quizs_trending[$key]['ID']=$value->getID();
$quizs_trending[$key]['Date']=$value->getDate();
$quizs_trending[$key]['Time']=$value->getTime();
$quizs_trending[$key]['Access']=$value->getAccess();
}

foreach($tests_trend as $key=>$value)
{
$tests_trending[$key]['Name']=$value->getName();
$tests_trending[$key]['ID']=$value->getID();
$tests_trending[$key]['Date']=$value->getDate();
$tests_trending[$key]['Time']=$value->getTime();
$tests_trending[$key]['Access']=$value->getAccess();
}

$tc=$test->getTestsActiveOnBoard(5);
$tests_active=array();

$update=new Update($objPDO);
$upds=$update->getByUserGroup('admin');
$updates=array();
if($upds)
{
foreach($upds as $key=>$update)
{
$updates[$key]['ID']=$update->getID();
$updates[$key]['Heading']=$update->getHeading();
$updates[$key]['Link']=$update->getLink();
}
}

foreach($tc as $key=>$value)
{
$tests_active[$key]['Name']=$value->getName();
$tests_active[$key]['ID']=$value->getID();
$tests_active[$key]['Date']=$value->getDate();
$tests_active[$key]['Time']=$value->getTime();
$tests_active[$key]['Access']=$value->getAccess();
}
$reg=new Register($objPDO);
$attempted=$reg->getTidsByUidCompleted($user->getID(),5);
$performance=array();
if($attempted)
{
foreach($attempted as $k=>$attempt)
{
$t=new Evaluate($objPDO);
$t=$t->getByUserIdTestId($user->getID(),$attempt);
$test->setID($attempt);
$performance[$k]['Name']=$test->getName();
$performance[$k]['ID']=$attempt;

if($t)
{
$performance[$k]['Percentage']=round(($t->getTotal()/$t->getExamTotal())*100,2);
}
else
{
$performance[$k]['EvaluateNow']=$attempt;
$performance[$k]['Evaluated']=false;
}

}

}

$tu=$test->getTestsUpcomingOnBoard(5);
$tests_upcoming=array();
foreach($tu as $key=>$value)
{
$tests_upcoming[$key]['Name']=$value->getName();
$tests_upcoming[$key]['ID']=$value->getID();
$tests_upcoming[$key]['Date']=$value->getDate();
$tests_upcoming[$key]['Time']=$value->getTime();
$tests_upcoming[$key]['Access']=$value->getAccess();
}


//Quiz values
$quiz=new Quiz($objPDO);

$qc=$quiz->getQuizsByCreatorId($user->getID(),5);
$quizs_created=array();
if($qc)
{
foreach($qc as $key=>$value)
{
$quizs_created[$key]['Name']=$value->getName();
$quizs_created[$key]['ID']=$value->getID();
$quizs_created[$key]['Date']=$value->getDate();
$quizs_created[$key]['Time']=$value->getTime();
$quizs_created[$key]['Access']=$value->getAccess();
}
}

$qo=$quiz->getQuizsByCreatorIdOnBoard($user->getID(),5);
$quizs_onboard=array();
if($qo)
{
foreach($qo as $key=>$value)
{
$quizs_onboard[$key]['Name']=$value->getName();
$quizs_onboard[$key]['ID']=$value->getID();
$quizs_onboard[$key]['Date']=$value->getDate();
$quizs_onboard[$key]['Time']=$value->getTime();
$quizs_onboard[$key]['Access']=$value->getAccess();
}
}


$qc=$quiz->getQuizsActiveOnBoard(5);
$quizs_active=array();

foreach($qc as $key=>$value)
{
$quizs_active[$key]['Name']=$value->getName();
$quizs_active[$key]['ID']=$value->getID();
$quizs_active[$key]['Date']=$value->getDate();
$quizs_active[$key]['Time']=$value->getTime();
$quizs_active[$key]['Access']=$value->getAccess();
}
$qreg=new QuizRegister($objPDO);
$attempted=$qreg->getQidsByUidCompleted($user->getID(),5);
$quiz_performance=array();
if($attempted)
{
foreach($attempted as $k=>$attempt)
{
$q=new QuizEvaluate($objPDO);
$q=$q->getByUserIdQuizId($user->getID(),$attempt);
$quiz->setID($attempt);
$quiz_performance[$k]['Name']=$quiz->getName();
$quiz_performance[$k]['ID']=$attempt;

if($q)
{
$quiz_performance[$k]['Percentage']=round(($q->getTotal()/$q->getExamTotal())*100,2);
}
else
{
$quiz_performance[$k]['EvaluateNow']=$attempt;
$quiz_performance[$k]['Evaluated']=false;
}

}

}

$qu=$quiz->getQuizsUpcomingOnBoard(5);
$quizs_upcoming=array();
foreach($qu as $key=>$value)
{
$quizs_upcoming[$key]['Name']=$value->getName();
$quizs_upcoming[$key]['ID']=$value->getID();
$quizs_upcoming[$key]['Date']=$value->getDate();
$quizs_upcoming[$key]['Time']=$value->getTime();
$quizs_upcoming[$key]['Access']=$value->getAccess();
}





include($_SERVER['DOCUMENT_ROOT'].'/jee/view/user_home.php');
}
else if($session->isPro())
{
$name=$user->getFirstName();
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/pro_home.php');
}

}


protected function notification()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if($user->getFirstName() && isset($_POST['uid']))
{
$limit=10;
if(isset($_POST['limit']))
{
$limit=$_POST['limit'];
}
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/notifications_class.php');
$notification=new Notifications($objPDO);
$notify=$notification->getByUserId($user->getID(),$_POST['uid'],$limit);

if(!is_array($notify))
{
$notify=array();
}
$notifications=array();

foreach($notify as $k=>$notification)
{
$notifications[$k]['ID']=$notification->getID();
$notifications[$k]['Title']=$notification->getTitle();
$notifications[$k]['SubTitle']=$notification->getSubTitle();
$notifications[$k]['Type']=$notification->getType();
$notifications[$k]['Link']=$notification->getLink();
$notifications[$k]['Read']=$notification->getRead();
$notifications[$k]['Time']=$notification->getTime();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/notifications_view.php');

}
else
{
echo 'Error';
}

}


protected function read_notification()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"test",
);
if($user->getFirstName() && isset($_POST['uid']))
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/notifications_class.php');
$notification=new Notifications($objPDO,$_POST['uid']);
$notification->setRead(1);
$notification->save();
}
else
{
echo 'Error';
}

}



protected function points()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
);
if($user->getFirstName() && isset($_POST['uid']))
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/points_class.php');
$points=new Points($objPDO);
$points=$points->getByUserId($user->getID(),$_POST['uid']);

$point=$points->getPoints();
$reputation=$points->getReputation();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/points_view.php');

}
else
{
echo 'Error';
}

}



protected function num_notifications()
{

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('connection: keep-alive');
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
if($user->getFirstName())
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/model/notifications_class.php');
$notification=new Notifications($objPDO);
$num_notifications=$notification->getNumUnread($user->getID());
$num_max=$notification->getMaxNum($user->getID());
if($num_notifications==0)
{
$num_notifications="";
}
if(!$num_max)
{
$num_max=0;
}


 echo "id: ".PROC_ID."\n\n";
 echo 'data: {"time" : "'.date('d/m H:i:s').'", "numMax":"'.$num_max.'","numNotif" : "'.$num_notifications.'"}' . "\n\n";
    ob_flush();
    flush();
	
}

flush();
}


}
?>