<?php
include('controller.php');
class SearchController extends Controller
{

protected function index()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_tags_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/test_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_class.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/quiz_tags_class.php');
global $objPDO;
global $session;

$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"none",
);

if(isset($_POST['query']))
{
$query=$_POST['query'];
$test=new Test($objPDO);
$test_tags=new TestTags($objPDO);
$tests=$test->getTestsSearch(10,mysql_real_escape_string($query));
$tests_tag=$test_tags->getTagsSearch(10,mysql_real_escape_string($query));
$quiz=new Quiz($objPDO);
$quiz_tags=new QuizTags($objPDO);
$quizs=$quiz->getQuizsSearch(10,mysql_real_escape_string($query));
$quizs_tag=$quiz_tags->getTagsSearch(10,mysql_real_escape_string($query));


$test_details=array();
$quiz_details=array();

foreach($tests as $key=>$value)
{
$k=$value->getID();
$test_details[$k]['ID']=$value->getID();
$test_details[$k]['Name']=$value->getName();
$test_details[$k]['Access']=$value->getAccess();
$test_details[$k]['Description']=$value->getDescription();
$test_details[$k]['Status']=$value->getStatus();
}

foreach($tests_tag as $key=>$value)
{
$k=$value->getID();
$test_details[$k]['ID']=$value->getID();
$test_details[$k]['Name']=$value->getName();
$test_details[$k]['Access']=$value->getAccess();
$test_details[$k]['Description']=$value->getDescription();
$test_details[$k]['Status']=$value->getStatus();
}

foreach($quizs as $key=>$value)
{
$k=$value->getID();
$quiz_details[$k]['ID']=$value->getID();
$quiz_details[$k]['Name']=$value->getName();
$quiz_details[$k]['Access']=$value->getAccess();
$quiz_details[$k]['Description']=$value->getDescription();
$quiz_details[$k]['Status']=$value->getStatus();
}

foreach($quizs_tag as $key=>$value)
{
$k=$value->getID();
$quiz_details[$k]['ID']=$value->getID();
$quiz_details[$k]['Name']=$value->getName();
$quiz_details[$k]['Access']=$value->getAccess();
$quiz_details[$k]['Description']=$value->getDescription();
$quiz_details[$k]['Status']=$value->getStatus();
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/search_results.php');
}
else
{
echo 'error';
}

}

};

?>