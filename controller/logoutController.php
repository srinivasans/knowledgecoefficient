<?php
include_once('controller.php');
class LogoutController extends Controller
{
protected function index()
{
GLOBAL $session;
if($session->getFacebookSession()==1)
{
$user=null;
try{
   include_once($_SERVER['DOCUMENT_ROOT']."/jee/facebook/facebook.php");
   }
catch(Exception $o){
}
// Create our Application instance.
$config = array();
$config['appId'] = '455856014511119';
$config['secret'] = 'c043bd4ddf5bcdba6b0793561e36bce3';
$facebook = new Facebook($config);
//Facebook Authentication part
$user=$facebook->getUser();
$logoutUrl=$facebook->getLogoutUrl();
$session->logout();
 echo "<script type='text/javascript'>top.location.href = '$logoutUrl';</script>";
}
else
{
$session->logout();
echo '<meta http-equiv="Refresh" content="0;url=/jee">';
}

}

}
?>