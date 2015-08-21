<?php
include_once('controller.php');
include_once('model/user_class.php');
class QuizController extends Controller
{

private function checkCreatePermission()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/points_class.php');
$points=new Points($objPDO);
$points=$points->getByUserId($user->getID());
if($user->checkAdmin() || $points->getReputation()>=100)
{
return true;
}
return false;
}


private function checkAdminPermission()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$quiz=new Quiz($objPDO);

if($user->checkAdmin() || $quiz->getQuizsByCreatorId($user->getID()))
{
return true;
}
return false;
}


protected function admin_analytics()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_passkey_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');

if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
if($quiz->getCreatorId()==$user->getID())
{
$reg=new QuizRegister($objPDO);
$regs=$reg->getByQuizId($quiz_id);

$quiz_details=array();
$quiz_details['ID']=$quiz->getID();
$quiz_details['Name']=$quiz->getName();
$quiz_details['Access']=$quiz->getAccess();
$quiz_details['Credits']=$quiz->getCredits();
$quiz_details['Status']=$quiz->getStatus();
$quiz_details['Time']=$quiz->getTime();
$quiz_details['TimeEnd']=$quiz->getTimeEnd();
$quiz_details['TestType']=$quiz->getTestType();
if($quiz_details['TestType']==1)
{
$quiz_details['ViewType']="All In One";
}
else
{
$quiz_details['ViewType']="One By One";
}

if($quiz_details['Status']==0)
{
$quiz_details['Status']="Not Set On-board";
}
else if($quiz_details['Status']==1)
{
if(strtotime($quiz_details['TimeEnd'])<strtotime('now'))
{
$quiz_details['Status']="Closed";
}
else
{
$quiz_details['Status']="On-board";
}
}

$key=new QuizPasskey($objPDO);
$key=$key->getByEmailIdQuizId('0',$quiz_id);
if(!$key)
{
$pass=0;
}
else
{
$pass=$key->getKey();
}
$reg_count=count($regs);

$eval=new QuizEvaluate($objPDO);
$eval->setQuizId($quiz_id);
$exam_total=$eval->getTotalMarks();
$average=$eval->getAverageMarks();
$highest=$eval->getMaxMarks();
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/graphs.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_admin_analytics.php');

}
}
else
{
echo 'error';
}


}
}





protected function import_passkeys()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_passkey_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');

if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
if($quiz->getCreatorId()==$user->getID())
{
if(isset($$_FILES['passkey_list']['tmp_name']))
{

$row = 1;
if (($handle = fopen($_FILES['passkey_list']['tmp_name'], "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        $row++;
		if($num==2)
		{
            $client=new User($objPDO);
			if($data[0])
			{
			$key=new QuizPasskey($objPDO);
			$key->setEmailId($data[0]);
			$key->setQuizId($quiz_id);
			$key->setKey($data[1]);
			$key->save();
			}
			
        }
		else
		{
		echo 'Please enter a csv file in format <email>,<passkey> only';
		return;
		}
		
    }
	echo 'data imported .<a href="/jee/quiz/admin_analytics/'.$quiz_id.'"> Go Back</a>';
    fclose($handle);

}

}
}
}
else
{
echo 'error';
}


}
}


protected function generate_ranks()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');

if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
if($quiz->getCreatorId()==$user->getID())
{
$eval=new QuizEvaluate($objPDO);
$evals=$eval->getByQuizId($quiz_id);
$rank=1;
$total=count($evals);
foreach($evals as $k=>$value)
{
$value->setRank($rank);
$percentile=(($total-$rank+1)/$total)*100;
$value->setPercentile($percentile);
$value->save();
$rank++;
}

echo '<meta http-equiv="Refresh" content="0;url=/jee/quiz/user_based_analytics/'.$_GET['uid'].'">';
}
}
else
{
echo 'error';
}


}
}





protected function user_based_analytics()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');

if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
if($quiz->getCreatorId()==$user->getID())
{
$eval=new QuizEvaluate($objPDO);
$reg=new QuizRegister($objPDO);
$evals=$eval->getByQuizId($quiz_id);
$regs=$reg->getByQuizIdAttempting($quiz_id);
$completed=array();
$attempting=array();

foreach($evals as $k=>$val)
{
$client=new User($objPDO,$val->getUserId());
$client->load();
$completed[$k]['ID']=$client->getID();
$completed[$k]['Name']=$client->getFirstName()." ".$client->getLastName();
$completed[$k]['Marks']=$val->getTotal();
$completed[$k]['Rank']=$val->getRank();
}
foreach($regs as $k=>$value)
{
$client=new User($objPDO,$value->getUserId());
$attempting[$k]['ID']=$client->getID();
$attempting[$k]['Name']=$client->getFirstName()." ".$client->getLastName();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_user_list.php');
}
}
else
{
echo 'error';
}


}
}





protected function export_users()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/controller/utility_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');

if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
if($quiz->getCreatorId()==$user->getID())
{
$eval=new QuizEvaluate($objPDO);
$reg=new QuizRegister($objPDO);
$evals=$eval->getByQuizId($quiz_id);
$regs=$reg->getByQuizIdAttempting($quiz_id);
$completed=array();
$attempting=array();
foreach($evals as $k=>$val)
{
$client=new User($objPDO,$val->getUserId());
$completed[$k]['ID']=$client->getID();
$completed[$k]['Name']=$client->getFirstName()." ".$client->getLastName();
$completed[$k]['Marks']=$val->getTotal();
$completed[$k]['Rank']=$val->getRank();
}

Utility::download_send_headers("users_export_".$quiz->getName(). date("Y-m-d") . ".csv");
echo Utility::array2csv($completed);
die();


}
}
else
{
echo 'error';
}


}
}


protected function user_report()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');

if(isset($_GET['uid']) && isset($_GET['ref']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
$client=new User($objPDO,$_GET['ref']);
$client_name=$client->getFirstName()." ".$client->getLastName() ;
if($quiz->getCreatorId()==$user->getID())
{
//Analytics Code
$quiz_name=$quiz->getName();
$register=new QuizRegister($objPDO);
$register=$register->getByUserIdQuizId($client->getID(),$quiz->getID());

if($quiz->getName() && $register && $register->getStatus()==2)
{
$analytics=array();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_analytics_class.php');
$analyt=new QuizAnalytics($objPDO);
$eval=new QuizEvaluate($objPDO);
$eval=$eval->getByUserIdQuizId($client->getID(),$quiz->getID());
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

$analytics_array=$analyt->getByUserIdQuizId($client->getID(),$quiz->getID());
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


include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/graphs.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_admin_user_analytics.php');
//Analytics Code
include_once('quiz_sheet.php');
$quiz_sheet=new QuizSheet($quiz_id);
$quiz_sheet->prepare();
$quiz_sheet->prepare_user_ans();
$quiz_sheet->showAdminUserReport($client);

}
}
else
{
echo 'error';
}


}
}

}




private function generate_analytics()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answers_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_admin_analytics_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');

if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);

if($quiz->getCreatorId()==$user->getID())
{
$reg=new QuizRegister($objPDO);
$regs=$reg->getByQuizId($quiz_id);

$reg_count=count($regs);
$finish_count=0;
foreach($regs as $k=>$reg)
{
if($reg->getStatus()==2)
{
$finish_count++;
}
}


$quest=new QuizQuestions($objPDO);
$questions=$quest->getQuestionsByQid($quiz_id);

foreach($questions as $k=>$question)
{
$answer=new QuizAnswer($objPDO);
$answer=$answer->getByQid($question->getID());
$anss=new QuizAnswers($objPDO);
$answers=$anss->getByQid($question->getID());
$correct_count=0;
$wrong_count=0;
foreach($answers as $key=>$ans)
{
if($ans->getOids()==$answer->getOids())
{
$correct_count++;
}
else
{
$wrong_count++;
}
}

$admin=new QuizAdminAnalytics($objPDO);
$admin->setQuizId($quiz->getID());
$admin->setType('question');
$admin->setName($question->getID());
$admin->setDesc('correct');
$admin->setValue($correct_count);
$admin->save();

$admin=new QuizAdminAnalytics($objPDO);
$admin->setQuizId($quiz->getID());
$admin->setType('question');
$admin->setName($question->getID());
$admin->setDesc('wrong');
$admin->setValue($correct_count);
$admin->save();

}

}
}
else
{
echo 'error';
}

}
}


protected function index()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsActiveOnBoard();
$quizs_upc=$quiz->getQuizsUpcomingOnBoard();
$quizs_arc=$quiz->getQuizsArchived();

$register=new QuizRegister($objPDO);
//$quizs_list=$register->getTestsByUserId($user->getID());
$quizs_active=array();
foreach($quizs_act as $k=>$quiz)
{
$quizs_active[$k]['Name']=$quiz->getName();
$quizs_active[$k]['ID']=$quiz->getID();
$quizs_active[$k]['TimeStart']=$quiz->getTime();
$quizs_active[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
}
$quizs_upcoming=array();
foreach($quizs_upc as $k=>$quiz)
{
$quizs_upcoming[$k]['Name']=$quiz->getName();
$quizs_upcoming[$k]['ID']=$quiz->getID();
$quizs_upcoming[$k]['TimeStart']=$quiz->getTime();
$quizs_upcoming[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
}

$quizs_archived=array();
foreach($quizs_arc as $k=>$quiz)
{
$quizs_archived[$k]['Name']=$quiz->getName();
$quizs_archived[$k]['ID']=$quiz->getID();
$quizs_archived[$k]['TimeStart']=$quiz->getTime();
$quizs_archived[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_index.php');

}



protected function get_filter()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
if(!isset($_POST['filter']))
{
return;
}
$filter=$_POST['filter'];
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsActiveOnBoard(NULL,$filter);
$quizs_upc=$quiz->getQuizsUpcomingOnBoard(NULL,$filter);
$quizs_arc=$quiz->getQuizsArchived(NULL,$filter);

$register=new QuizRegister($objPDO);
//$quizs_list=$register->getTestsByUserId($user->getID());
$quizs_active=array();
foreach($quizs_act as $k=>$quiz)
{
$quizs_active[$k]['Name']=$quiz->getName();
$quizs_active[$k]['ID']=$quiz->getID();
$quizs_active[$k]['TimeStart']=$quiz->getTime();
$quizs_active[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
}
$quizs_upcoming=array();
foreach($quizs_upc as $k=>$quiz)
{
$quizs_upcoming[$k]['Name']=$quiz->getName();
$quizs_upcoming[$k]['ID']=$quiz->getID();
$quizs_upcoming[$k]['TimeStart']=$quiz->getTime();
$quizs_upcoming[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
}

$quizs_archived=array();
foreach($quizs_arc as $k=>$quiz)
{
$quizs_archived[$k]['Name']=$quiz->getName();
$quizs_archived[$k]['ID']=$quiz->getID();
$quizs_archived[$k]['TimeStart']=$quiz->getTime();
$quizs_archived[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_filter.php');
}




protected function performance()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsActiveOnBoard();

$qreg=new QuizRegister($objPDO);
$attempted=$qreg->getTidsByUidCompleted($user->getID());
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
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_performance.php');

}




protected function active()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsActiveOnBoard();

$register=new QuizRegister($objPDO);
//$quizs_list=$register->getTestsByUserId($user->getID());
$quizs=array();
foreach($quizs_act as $k=>$quiz)
{
$quizs[$k]['Name']=$quiz->getName();
$quizs[$k]['ID']=$quiz->getID();
$quizs[$k]['TimeStart']=$quiz->getTime();
$quizs[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
$quizs[$k]['Status']='active';
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_view.php');

}


protected function created()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsByCreatorId($user->getID());

$register=new QuizRegister($objPDO);
//$quizs_list=$register->getTestsByUserId($user->getID());
$quizs=array();
foreach($quizs_act as $k=>$quiz)
{
$quizs[$k]['Name']=$quiz->getName();
$quizs[$k]['ID']=$quiz->getID();
$quizs[$k]['TimeStart']=$quiz->getTime();
$quizs[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
$quizs[$k]['Status']='created';
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_view_created.php');
}
}


protected function created_onboard()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsCreatorIdOnBoard($user->getID());

$register=new QuizRegister($objPDO);
//$quizs_list=$register->getTestsByUserId($user->getID());
$quizs=array();
foreach($quizs_act as $k=>$quiz)
{
$quizs[$k]['Name']=$quiz->getName();
$quizs[$k]['ID']=$quiz->getID();
$quizs[$k]['TimeStart']=$quiz->getTime();
$quizs[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
$quizs[$k]['Status']='onboard';
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_view.php');
}
}


protected function created_analytics()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsByCreatorIdOnBoard($user->getID());

$register=new QuizRegister($objPDO);
//$quizs_list=$register->getTestsByUserId($user->getID());
$quizs=array();
foreach($quizs_act as $k=>$quiz)
{
$quizs[$k]['Name']=$quiz->getName();
$quizs[$k]['ID']=$quiz->getID();
$quizs[$k]['TimeStart']=$quiz->getTime();
$quizs[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
$quizs[$k]['Status']='onboard';
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_view_list_analytics.php');
}
}


protected function upcoming()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO);
$quizs_act=$quiz->getQuizsUpcomingOnBoard();

$register=new QuizRegister($objPDO);
//$quizs_list=$register->getTestsByUserId($user->getID());
$quizs=array();
foreach($quizs_act as $k=>$quiz)
{
$quizs[$k]['Name']=$quiz->getName();
$quizs[$k]['ID']=$quiz->getID();
$quizs[$k]['TimeStart']=$quiz->getTime();
$quizs[$k]['TimeLimit']=round((strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()))/60,2);
$quizs[$k]['Status']='active';
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_view.php');

}




protected function exam()
{
include_once('quiz_sheet.php');
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}

$embed=false;
if($ref && $ref=='embed')
{
$embed=true;
}



include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$reg=new QuizRegister($objPDO);
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
$reg=$reg->getByUserIdQuizId($user->getID(),$quiz_id);
$time_start=strtotime('now');
if($reg && $reg->getUserId()==$user->getID())
{
$time_start=strtotime($reg->getTimeStarted());
$time_limit=$time_start+strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime());
if($reg && $reg->getStatus()==1 && $quiz->getName() && strtotime('now')>strtotime($quiz->getTime()) && strtotime('now')<$time_limit)
{
$quest=new QuizSheet($quiz_id);
$quest->prepare();
$quest->show($reg,$embed);
}
else
{
echo 'Invalid URL';
header("Location: /jee/quiz/start/".$quiz_id."/".$ref);
}

}
else
{
header("Location: /jee/quiz/start/".$quiz_id."/".$ref);
}


}




//Question wise Analytics
protected function question_analytics()
{
include_once('quiz_sheet.php');
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
if($this->checkAdminPermission() && $quiz->getCreatorId()==$user->getID())
{
$quest=new QuizSheet($quiz_id);
$quest->prepare();
$quest->showQuestionAnalytics();
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
"active"=>"quiz",
);

if($this->checkCreatePermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/create_quiz.php');
}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/cannot_create_quiz.php');
}

}

//Make Admin Only Access //Check Plain Form Submission{Done}
protected function create_quiz()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/points_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
extract($_POST);
if($this->checkCreatePermission())
{
if(isset($quiz_name) && isset($desc) && isset($date) && isset($time) && isset($time_limit) && isset($time_end) && isset($credits) && isset($access) && isset($test_type))
{
if($time_end=="")
{
$time_end='long';
}
if($time_limit=="")
{
$time_limit='long';
}

if($quiz_name!="" && $desc!="" && $date!="" && $time!="" && $credits!="" && $access!="" && $time_end!="" && $time_limit!="" && $test_type)
{
$limit=$date." ".$time." +".$time_limit." minutes";
if($time_end=='long')
{
$time_end=$date." ".$time." + 10 years";
}
if($time_limit=='long')
{
$limit=$date." ".$time." + 10 years";
}
$quiz_name=substr($quiz_name,0,25);
$quiz=new Quiz($objPDO);
$quiz->setName($quiz_name);
$quiz->setDescription($desc);
$quiz->setDate(date("Y-m-d",strtotime($date)));
$quiz->setTime(date("Y-m-d H:i:s",strtotime($date." ".$time)));
$quiz->setTimeEnd(date("Y-m-d H:i:s",strtotime($time_end)));
$quiz->setTimeLimit(date("Y-m-d H:i:s",strtotime($limit)));
$quiz->setCreatorId($user->getID());
$quiz->setCredits($credits);
$quiz->setAccess($access);
$quiz->setTestType($test_type);
$quiz->save();

$points=new Points($objPDO);
$points=$points->getByUserId($user->getID());
$points->setReputation($points->getReputation()-100);
$points->save();

$quiz_id=$quiz->getID();
echo '<meta http-equiv="Refresh" content="0;url=/jee/quiz/add_questions/'.$quiz_id.'">';
}
else
{
echo 'Please Fill in All Details ...';
}
}
}
else
{
echo "Error";
}

}

protected function archive_quiz()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if(isset($_GET['uid']) && $this->checkAdminPermission())
{
$qid=$_GET['uid'];
$quiz=new Quiz($objPDO,$qid);
$quiz->load();
if($quiz->getCreatorId()==$user->getID())
{
$quiz->setAccess('archive');
header("Location:/jee/quiz");
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



protected function delete()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
extract($_POST);

if(isset($qid) && $this->checkAdminPermission())
{
$quiz=new Quiz($objPDO,$qid);
$quiz->load();
if($quiz->getCreatorId()==$user->getID())
{
$quiz->markForDeletion();
$points=new Points($objPDO);
$points=$points->getByUserId($user->getID());
$points->setReputation($points->getReputation()+100);
$points->save();
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
protected function edit_quiz()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
extract($_POST);

if($this->checkAdminPermission())
{
if(isset($quiz_name) && isset($desc)  && isset($date) && isset($time) && isset($credits) && isset($access) && isset($quiz_id) && isset($time_limit) && isset($time_end) && isset($test_type))
{
if($time_end=="")
{
$time_end='long';
}
if($time_limit=="")
{
$time_limit='long';
}
if($quiz_name!="" && $desc!="" && $date!="" && $time!="" && $credits!="" && $access!="" && $time_limit!="" && $time_end!="" && $quiz_id!="" && $test_type!="" && $test_type!=0)
{
$limit=$date." ".$time." +".$time_limit." minutes";
if($time_end=='long')
{
$time_end=$date." ".$time." + 10 years";
}
if($time_limit=='long')
{
$limit=$date." ".$time." + 10 years";
}
$quiz=new Quiz($objPDO,$quiz_id);
$quiz->load();
if($quiz->getName() && $quiz->getCreatorId()==$user->getID())
{
$quiz_name=substr($quiz_name,0,25);
$quiz->setName($quiz_name);
$quiz->setDescription($desc);
$quiz->setDate(date("Y-m-d",strtotime($date)));
$quiz->setTime(date("Y-m-d H:i:s",strtotime($date." ".$time)));
$quiz->setTimeEnd(date("Y-m-d H:i:s",strtotime($time_end)));
$quiz->setTimeLimit(date("Y-m-d H:i:s",strtotime($limit)));
$quiz->setCredits($credits);
$quiz->setAccess($access);
$quiz->setTestType($test_type);
$quiz->save();

$quiz_id=$quiz->getID();
header("Location: view/".$quiz_id);
}
else
{
echo 'error1';
}

}
else
{
echo 'error';
}

}
else
{
echo 'error';
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
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
$quiz_name=$quiz->getName();
if($quiz_name && $quiz->getCreatorId()==$user->getID())
{
$questions=new QuizQuestions($objPDO);
$questions=$questions->getQuestionsByQid($quiz_id);
$question=array();
foreach($questions as $k=>$v)
{
$question[]=$v->getID();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/add_quiz_questions.php');
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
/*protected function save_questions_temp()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/tgroup_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/question_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
extract($_POST);
if(isset($question) && isset($qno))
{
foreach($question as $key=>$quest)
{
if(isset($quest) && $quest!="")
{
$q=new Question($objPDO);
$q->setQuestion(htmlentities($quest));
$q->setQno($qno[$key]);
$q->setType($type[$key]);
$q->setTgid($value);
$q->save();
}


}
//header("Location: add_questions/".$quiz_id);
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

}*/


//Make admin only access //Check Plain Form Submission
protected function save_questions()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
extract($_POST);
if(isset($question) && isset($qno) && isset($type) && isset($quiz_id) && !($question=="" || $qno=="" || $quiz_id==""))
{
if($qno>25)
{
return;
}
if(isset($question) && $question!="")
{
$q=new QuizQuestions($objPDO);
if($q->getByQidQno($quiz_id,$qno) instanceof QuizQuestions)
{
$q=$q->getByQidQno($quiz_id,$qno);
}
$q->setQuestion(htmlspecialchars($question,ENT_QUOTES));
$q->setQno($qno);
$q->setType($type);
$q->setQid($quiz_id);
$q->save();

$opt_arr=array("A","B","C","D");
//Storing is Based on Previous array
foreach($option as $key=>$option)
{
$options=new QOptions($objPDO);
//Only 4 Options Constraint
if($options->getByQidOid($q->getID(),$opt_arr[$key]) instanceof QOptions)
{
echo 'Done';
$options=$options->getByQidOid($q->getID(),$opt_arr[$key]);
}

if(isset($options) && $options!=NULL && $options!="")
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
$tags=new QuizTags($objPDO);
$tags->setTag($t);
$tags->setQid($q->getID());
$tags->save();
}
}

echo "<div class='label label-success'>Saved Successfully !</div>";

}
//header("Location: add_questions/".$quiz_id);
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
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
extract($_POST);
if(isset($quiz_id) && isset($qno) && isset($qid) && isset($type) && isset($ans) && !($quiz_id=="" || $qid=="" || $qno==""))
{
$q=new QuizQuestions($objPDO,$qid);
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
$answer=new QuizAnswer($objPDO);
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
//header("Location: add_questions/".$quiz_id);
}
else
{
echo "Error";
}

}



protected function get_tags()
{
GLOBAL $objPDO;
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');
$like="";
if(isset($_GET['uid']) && $_GET['uid']!="")
{
$like=urldecode($_GET['uid']);
}
$quiz_tags=new QuizTags($objPDO);
$list=$quiz_tags->getTagsLikeName($like);
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
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
if(isset($_POST['qid']) && isset($_POST['quiz_id']))
{
$question=new QuizQuestions($objPDO,$_POST['qid']);
$question->markForDeletion();
header("Location:add_questions/".$_POST['quiz_id']);
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
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
include_once('utility_class.php');
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$qid=$_GET['uid'];
$qno=$_GET['ref'];
}
$question=new QuizQuestions($objPDO);
$quest=new QuizQuestions($objPDO);
$quest=$question->getByQidQno($qid,$qno);
if($quest)
{
$options=new QOptions($objPDO);
$opts=array();
$opts=$options->getOptionsByQid($quest->getID());
$strOption=array();
foreach($opts as $key=>$opt)
{
$strOption[$key]['ID']=$opt->getID();
$strOption[$key]['Option']=$opt->getOption();
}
Utility::AddQuestion($qno,$quest->getQuestion(),$strOption);
echo "<a onclick='editQuestion(this.id)' id='".$qid."-".$qno."' class='btn btn-warning'><i class='icon-edit'/>Edit</a> <a onclick='deleteQuestion(this.id)' id='".$quest->getID()."' class='btn btn-danger'><i class='icon-white icon-remove'/>Delete</a>";
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
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');
include_once('utility_class.php');
$quiz_id="";
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$quiz_id=$_GET['uid'];
$qno=$_GET['ref'];
}

$question=new QuizQuestions($objPDO);
$quest=new QuizQuestions($objPDO);
$quest=$question->getByQidQno($quiz_id,$qno);
if($quest)
{
$options=new QOptions($objPDO);
$opts=array();
$opts=$options->getOptionsByQid($quest->getID());
$strOption=array();
foreach($opts as $key=>$opt)
{
$strOption[$key]['ID']=$opt->getID();
$strOption[$key]['Option']=$opt->getOption();
}
Utility::AddQuestion($qno,$quest->getQuestion(),$strOption);
echo "<a onclick='setAnswer(this.id)' id='edit-".$quiz_id."-".$qno."' class='btn btn-warning'><i class='icon-pencil'/>Set Answer</a>";
$answer=new QuizAnswer($objPDO);
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
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');
include_once('utility_class.php');
$qid="";
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$qid=$_GET['uid'];
$qno=$_GET['ref'];
}
$quiz=new Quiz($objPDO,$qid);
if($quiz->getCreatorId()==$user->getID())
{
$question=new QuizQuestions($objPDO);
$quest=new QuizQuestions($objPDO);
$quest=$question->getByQidQno($qid,$qno);
if($quest)
{
$options=new QOptions($objPDO);
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
$answer=new QuizAnswer($objPDO);
$answer=$answer->getByQid($quest->getID());
echo '<table class="table table-bordered"><tr>';
echo '<td>Marks (Correct | Wrong | Unanswered) :</td><td>';
if($answer->getCorrect()=="")
{
$correct="3";
}
else
{
$correct=$answer->getCorrect();
}
if($answer->getWrong()=="")
{
$wrong="-1";
}
else
{
$wrong=$answer->getWrong();
}
if($answer->getUnanswered()=="")
{
$unanswered="0";
}
else
{
$unanswered=$answer->getUnanswered();
}
Utility::inputText("correct","correct","Correct Points",NULL,NULL,$correct);
echo '</td><td>';
Utility::inputText("wrong","wrong","Wrong Points",NULL,NULL,$wrong);
echo '</td><td>';
Utility::inputText("unanswered","unanswered","Unanswered Points",NULL,NULL,$unanswered);
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
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$qno="";
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$qid=$_GET['uid'];
$qno=$_GET['ref'];
}
$quiz=new Quiz($objPDO,$qid);
if($quiz->getCreatorId()==$user->getID())
{
$question=new QuizQuestions($objPDO);
$quest=new QuizQuestions($objPDO);
$quest=$question->getByQidQno($qid,$qno);
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
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
$option=new QOptions($objPDO);
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


protected function setpasskey()
{
GLOBAL $objPDO;
GLOBAL $session;
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_passkey_class.php');
$user=new User($objPDO,$session->getuserId());


if($this->checkAdminPermission() && isset($_GET['uid']))
{
$pass=substr(md5(microtime()),0,10);
$key=new QuizPasskey($objPDO);
$key=$key->getByEmailIdQuizId('0',$_GET['uid']);
if($key)
{
$key->markForDeletion();
}
$key=new QuizPasskey($objPDO);
$key->setQuizId($_GET['uid']);
$key->setEmailId('0');
$key->setKey($pass);
$key->save();
echo 'Passkey : '.$pass;
}
else
{
echo 'error';
}


}


protected function gettagsbyqid($qid)
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');
$tag=new QuizTags($objPDO);
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
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');

GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
if(isset($_GET['uid']))
{
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$quiz_id);
$quiz_name=$quiz->getName();
if($quiz_name && $quiz->getCreatorId()==$user->getID())
{
$questions=new QuizQuestions($objPDO);
$questions=$questions->getQuestionsByQid($quiz_id);
foreach($questions as $k=>$val)
{
$question[]=$val->getID();
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_add_answers.php');
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
"active"=>"quiz",
);
if($this->checkAdminPermission() && isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$quiz=new Quiz($objPDO,$_GET['uid']);
if($quiz->getName()!=NULL && $quiz->getCreatorId()==$user->getID())
{
$quiz_details['Name']=$quiz->getName();
$quiz_details['ID']=$quiz->getID();
$quiz_details['Date']=$quiz->getDate();
$quiz_details['Time']=$quiz->getTime();
$quiz_details['Description']=$quiz->getDescription();
$quiz_details['Credits']=$quiz->getCredits();
$quiz_details['Access']=$quiz->getAccess();
$quiz_details['TestType']=$quiz->getTestType();
$quiz_details['TimeLimit']=$quiz->getTimeLimit();
$quiz_details['TimeEnd']=$quiz->getTimeEnd();

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/view_quiz.php');
}
else
{
echo "error";
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
"active"=>"quiz",
);
if($this->checkAdminPermission()&& isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$quiz=new Quiz($objPDO,$_GET['uid']);
if($quiz->getName()!=NULL && $quiz->getCreatorId()==$user->getID())
{
$quiz_id=$quiz->getID();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_setonboard.php');
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
"active"=>"quiz",
);
if($this->checkAdminPermission()&& isset($_GET['uid']))
{
$flag=1;
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');
$quiz=new Quiz($objPDO,$_GET['uid']);
$quiz->load();
if($quiz->getName()!=NULL && $quiz->getCreatorId()==$user->getID())
{
$result=array();
$quiz_id=$quiz->getID();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_load_page.php');
if($quiz->getName() && $quiz->getDate() && $quiz->getTime() && $quiz->getAccess() && $quiz->getCreatorId() && $quiz->getStatus()==0)
{
$result[]='<div class="label label-success">Quiz Details Verified. </div>';
}
else
{
$result[]='<div class="label label-important">Quiz Details Invalid</div>';
if($quiz->getStatus()!=0)
{
$result[]='<div class="label label-important">Quiz already On-Board</div>';
}

$flag=0;
}

$quest=new QuizQuestions($objPDO);
$questions=$quest->getQuestionsByQid($quiz_id);

if(count($questions))
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

$answer=new QuizAnswer($objPDO);
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





if($flag==1)
{
$this->constraint_success($quiz->getID(),$result,"#98678");
}
else
{
$this->constraint_failure($quiz->getID(),$result);
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


private function constraint_success($qid,$result,$key)
{
GLOBAL $objPDO;
GLOBAL $session;
echo '<script type="text/javascript">$("#content").html("");</script>';
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
if($key=="#98678" && $this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$quiz=new Quiz($objPDO,$qid);
if($quiz->getCreatorId()==$user->getID())
{
$quiz->setStatus(1);
$quiz->save();

$html="";
foreach($result as $res)
{
$html.=$res."<br/>";
}
$head='<div class="pull-right"><a class="btn btn-success" href="/jee/quiz/start/'.$quiz->getID().'">Go To Quiz</a></div>';
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

private function constraint_failure($qid,$result)
{
GLOBAL $objPDO;
GLOBAL $session;
echo '<script type="text/javascript">$("#content").html("");</script>';
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);

if($this->checkAdminPermission())
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$quiz=new Quiz($objPDO,$qid);
if($quiz->getCreatorId()==$user->getID())
{
$html="";
foreach($result as $res)
{
$html.=$res."<br/>";
}
$head='<div class="pull-right"><a class="btn btn-danger" href="/jee/quiz/view/'.$quiz->getID().'">Check Back</a></div>';
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
"active"=>"quiz",
);
if(isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');

$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}

$quiz=new Quiz($objPDO,$_GET['uid']);
if($quiz->getName()!=NULL)
{
$register=new QuizRegister($objPDO);
$register=$register->getByUserIdQuizId($user->getID(),$_GET['uid']);
if($register && $register->getStatus()==2)
{
header("Location: /jee/quiz/evaluate/".$quiz->getID()."/".$ref);
return;
}

$quiz_details['Name']=$quiz->getName();
$quiz_details['ID']=$quiz->getID();
$quiz_details['Date']=$quiz->getDate();
$quiz_details['Time']=$quiz->getTime();
$quiz_details['Description']=$quiz->getDescription();
$quiz_details['Credits']=$quiz->getCredits();
$quiz_details['Access']=$quiz->getAccess();
$quiz_details['TimeLimit']=$quiz->getTimeLimit();
$quiz_details['TimeEnd']=$quiz->getTimeEnd();
$quiz_details['Started']=0;
if($register && $register->getStatus()==1)
{
$quiz_details['Started']=1;
}

if($ref=='embed')
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_start_embed.php');
}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_start.php');
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


protected function start_quiz()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);
if(isset($_GET['uid']))
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_passkey_class.php');
$quiz=new Quiz($objPDO,$_GET['uid']);
$register=new QuizRegister($objPDO);
$register=$register->getByUserIdQuizId($user->getID(),$_GET['uid']);
$time_start=strtotime('now');
$time_lim=strtotime($quiz->getTimeEnd());
if($register && $register->getStatus()==1)
{
$time_start=strtotime($register->getTimeStarted());
$time_lim=$time_start+strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime());
}
else if($register && $register->getUserId()==$user->getID() && $register->getStatus()!=1)
{
echo "Error1";
return;
}
else if(!($register instanceof QuizRegister))
{
if($quiz->getAccess()=='public' || $quiz->getAccess()=='archive')
{
$register=new QuizRegister($objPDO);
}
else
{
if(!isset($_POST['key']))
{
header("Location: /jee/quiz/start/".$quiz->getID()."/".$ref);
return;
}
$key=new QuizPasskey($objPDO);
$key=$key->getByEmailIdQuizId($user->getEmail(),$quiz->getID());
if($key && $key->getKey()==$_POST['key'])
{
$register=new QuizRegister($objPDO);
}
else
{
header("Location: /jee/quiz/start/".$quiz->getID()."/".$ref);
return;
}
}

}

$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}

if($quiz->getName() && strtotime('now')-$time_lim<0 && strtotime('now')-strtotime($quiz->getTime())>0)
{
$register->setQuizId($_GET['uid']);
$register->setUserId($user->getID());
$register->setStatus(1);
$register->setTimeStarted(date("Y-m-d H:i:s",time()));
$register->save();
header("Location:/jee/quiz/exam/".$_GET['uid'].'/'.$ref);
}
else
{
$quiz_details['ID']=$quiz->getID();
$quiz_details['Name']=$quiz->getName();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_time_limit_exceeded.php');
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

$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}

extract($_POST);
if(isset($quiz_id))
{

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/qoptions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answers_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
$quiz=new Quiz($objPDO,$quiz_id);
$quiz->load();
$register=new QuizRegister($objPDO);
$register->getByUserIdQuizId($user->getID(),$quiz->getID());

$timeLimit=date("Y-m-d H:i:s",strtotime($register->getTimeStarted())+strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()));
date_default_timezone_set('Asia/Calcutta');
if(strtotime($timeLimit)-strtotime('now')<-120)
{
echo "<b>Quiz Closed</b>";
echo '<meta http-equiv="Refresh" content="0;url=/jee/quiz/temp/'.$quiz_id.'/'.$ref.'">';
header("Location: /jee/".$ref);
die();
return;
}

if((strtotime($timeLimit)-strtotime('now'))>-120 && $register->getStatus()==1)
{
if($quiz->getName()!=NULL)
{
$result=array();
$quiz_id=$quiz->getID();
$quest=new QuizQuestions($objPDO);
$questions=$quest->getQuestionsByQid($quiz_id);
foreach($questions as $t=>$question)
{
$qno=$question->getQno();
$answers=new QuizAnswers($objPDO);
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

$time_l=date("Y-m-d H:i:s",strtotime($register->getTimeStarted())+strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime()));

if(isset($time_l))
{
if(strtotime($time_l)-strtotime("now")<0 || (isset($_POST['finish']) && $_POST['finish']==1))
{
$register->getByUserIdQuizId($user->getID(),$quiz_id);
$register->setStatus(2);
$register->save();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/points_class.php');
$points=new Points($objPDO);
$points=$points->getByUserId($quiz->getCreatorId());
$points->setReputation($points->getReputation()+1);
$points->save();

echo '<script type="text/javascript">window.location.reload();</script>';
echo '<meta http-equiv="Refresh" content="0;url=/jee/quiz/temp/'.$quiz_id.'/'.$ref.'">';
header('Location: /jee/quiz/temp/'.$quiz_id.'/'.$ref);

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
echo '<script type="text/javascript">window.location.reload();</script>';
header('Location: /jee/'.$ref);
echo '<meta http-equiv="Refresh" content="0;url=/jee/'.$ref.'">';


return;
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
"active"=>"quiz",
);

$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}

if(isset($_GET['uid']))
{
if($ref=='embed')
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/complete_quiz_embed.php');
}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/complete_quiz.php');
}
echo '<meta http-equiv="Refresh" content="0;url=/jee/quiz/evaluate/'.$_GET['uid'].'/'.$ref.'">';
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
"active"=>"quiz",
);
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/points_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/notifications_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_questions_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answer_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_answers_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_analytics_class.php');
if($user->getID() && isset($_GET['uid']))
{
$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}
$quiz_id=$_GET['uid'];
$quiz=new Quiz($objPDO,$_GET['uid']);
$register=new QuizRegister($objPDO);
$register->getByUserIdQuizId($user->getID(),$quiz->getID());

if($register->getStatus()==1)
{
$time_start=strtotime($register->getTimeStarted());
$time_lim=$time_start+strtotime($quiz->getTimeLimit())-strtotime($quiz->getTime());
}

if($quiz->getName() && $register->getStatus()==2 || ($register->getStatus()==1 && strtotime('now')-$time_lim>0 ))
{
$total=0;
$exam_total=0;
$correct=0;
$unanswered=0;
$wrong=0;
$analytics=array();
$analytics['tag']=array();

$question=new QuizQuestions($objPDO);
$questions=array();
$questions=$question->getQuestionsByQid($quiz_id);
if($questions)
{
foreach($questions as $key=>$question)
{
$tag=new QuizTags($objPDO);
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
$answers=new QuizAnswers($objPDO);
$answers=$answers->getByQidUid($question->getID(),$user->getID());
$answer=new QuizAnswer($objPDO);
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

if(!array_key_exists($tag_value,$analytics['tag']))
{
$analytics['tag'][$tag_value]['Marks']=0;
$analytics['tag'][$tag_value]['Positives']=0;
$analytics['tag'][$tag_value]['Negatives']=0;
$analytics['tag'][$tag_value]['Correct']=0;
$analytics['tag'][$tag_value]['Wrong']=0;
$analytics['tag'][$tag_value]['Unanswered']=0;
$analytics['tag'][$tag_value]['TotalQuestions']=0;
$analytics['tag'][$tag_value]['TotalMarks']=0;
}
$analytics['tag'][$tag_value]['Marks']+=$analyts['Marks'];
$analytics['tag'][$tag_value]['Positives']+=$analyts['Positives'];
$analytics['tag'][$tag_value]['Negatives']+=$analyts['Negatives'];
$analytics['tag'][$tag_value]['Correct']+=$analyts['Correct'];
$analytics['tag'][$tag_value]['Wrong']+=$analyts['Wrong'];
$analytics['tag'][$tag_value]['Unanswered']+=$analyts['Unanswered'];
$analytics['tag'][$tag_value]['TotalQuestions']+=$analyts['TotalQuestions'];
$analytics['tag'][$tag_value]['TotalMarks']+=$analyts['TotalMarks'];

}




}

$evaluate=new QuizEvaluate($objPDO);
$check=$evaluate->getByUserIdQuizId($user->getID(),$quiz->getID());
//$evaluate->deleteByUserIdQuizId($user->getID(),$quiz->getID());
if(!$check)
{
$evaluate=new QuizEvaluate($objPDO);
}
else
{
$evaluate=$evaluate->getByUserIdQuizId($user->getID(),$quiz->getID());
}
$evaluate->setUserId($user->getID());
$evaluate->setQuizId($quiz->getID());
$evaluate->setTotal($total);
$evaluate->setExamTotal($exam_total);
$evaluate->save();


if(!$check)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_admin_analytics_class.php');

$quest=new QuizQuestions($objPDO);
$questions=$quest->getQuestionsByQid($quiz_id);

foreach($questions as $k=>$question)
{
$answer=new QuizAnswer($objPDO);
$answer=$answer->getByQid($question->getID());
$anss=new QuizAnswers($objPDO);
$answers=$anss->getByQid($question->getID());
$correct_count=0;
$wrong_count=0;
foreach($answers as $key=>$ans)
{
if($ans->getOids()==$answer->getOids())
{
$correct_count++;
}
else
{
$wrong_count++;
}
}

$admin=new QuizAdminAnalytics($objPDO);
$admin=$admin->getByQuizIdTypeNameDesc($quiz_id,'question',$question->getID(),'correct');
$admin->setValue($admin->getValue()+$correct_count);
$admin->save();

$admin=new QuizAdminAnalytics($objPDO);
$admin=$admin->getByQuizIdTypeNameDesc($quiz_id,'question',$question->getID(),'wrong');
$admin->setValue($admin->getValue()+$wrong_count);
$admin->save();

$tags=new QuizTags($objPDO);
$tags=$tags->getTagsByQid($question->getID());
foreach($tags as $k=>$tag)
{
$admin=new QuizAdminAnalytics($objPDO);
$admin=$admin->getByQuizIdTypeNameDesc($quiz_id,'tag',$tag->getTag(),'wrong');
$admin->setValue($admin->getValue()+$wrong_count);
$admin->save();

$admin=new QuizAdminAnalytics($objPDO);
$admin=$admin->getByQuizIdTypeNameDesc($quiz_id,'tag',$tag->getTag(),'correct');
$admin->setValue($admin->getValue()+$correct_count);
$admin->save();

}

}



$points=new Points($objPDO);
$points=$points->getByUserId($user->getID());
$pt_add=($total/$exam_total);
$points->setPoints($pt_add);
$points->save();
$notifications=new Notifications($objPDO);
$notifications->setUserId($user->getID());
$notifications->setTitle($quiz->getName().' Analytics');
$notifications->setSubTitle('Check out your quiz Scores and Analytics');
$notifications->setType('analytics');
$notifications->setLink('http://localhost/jee/quiz/analytics/'.$quiz->getID());
$notifications->save();
$notifications=new Notifications($objPDO);
$notifications->setUserId($user->getID());
$notifications->setTitle($quiz->getName().' Solutions');
$notifications->setSubTitle('Check out answers and solutions');
$notifications->setType('evaluate');
$notifications->setLink('http://localhost/jee/quiz/evaluate/'.$quiz->getID());
$notifications->save();
}

foreach($analytics as $type=>$value)
{
foreach($value as $val=>$data)
{
$a=new QuizAnalytics($objPDO);
$a->getByUserIdQuizIdTypeValue($user->getID(),$quiz->getID(),$type,$val);
$a->setType($type);
$a->setUserId($user->getID());
$a->setQuizId($quiz->getID());
$a->setValue($val);
$a->setMarks($data['Marks']);
$a->setPositives($data['Positives']);
$a->setNegatives($data['Negatives']);
$a->setCorrect($data['Correct']);
$a->setWrong($data['Wrong']);
$a->setUnanswered($data['Unanswered']);
$a->setTotalQuestions($data['TotalQuestions']);
$a->setTotalMarks($data['TotalMarks']);
$a->save();
}
}

}

$embed=false;
if($ref=='embed')
{
$embed=true;
}

include_once('quiz_sheet.php');
$quiz_sheet=new QuizSheet($quiz_id);
$quiz_sheet->prepare();
$quiz_sheet->prepare_user_ans();
$quiz_sheet->showEvaluate($embed);

}
else
{
header('Location: /jee/quiz/start/'.$quiz->getID());
}


}



}



protected function analytics()
{
GLOBAL $objPDO;
GLOBAL $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"quiz",
);


if(isset($_GET['uid']))
{
$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_register_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
$quiz=new Quiz($objPDO,$_GET['uid']);
$quiz_name=$quiz->getName();
$register=new QuizRegister($objPDO);
$register=$register->getByUserIdQuizId($user->getID(),$quiz->getID());
if($quiz->getName() && $register && $register->getStatus()==1 &&((strtotime('now')-strtotime($quiz->getTimeEnd())>0) || (strtotime('now')-strtotime($quiz->getTimeLimit())-strtotime($register->getTimeStarted())>0)))
{
header('Location: /jee/quiz/evaluate/'.$quiz->getID());
return;
}
if($quiz->getName() && $register && $register->getStatus()==2)
{
$analytics=array();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_analytics_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_evaluate_class.php');
$analyt=new QuizAnalytics($objPDO);
$eval=new QuizEvaluate($objPDO);
$eval=$eval->getByUserIdQuizId($user->getID(),$quiz->getID());
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

$analytics['tag']=array();
$analytics_array=$analyt->getByUserIdQuizId($user->getID(),$quiz->getID());
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


include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/graphs.php');
if($ref=='embed')
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_view_analytics_embed.php');
}
else
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/quiz_view_analytics.php');
}

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
