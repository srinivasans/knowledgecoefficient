<?php
include('controller.php');
class SignupController extends Controller
{
public function __construct()
{
$this->arNoAuthenticate['index']='1';
$this->arNoAuthenticate['validate']='1';
$this->arNoAuthenticate['vsignup']='1';
$this->arNoAuthenticate['facebook_login']='1';
}

protected function index()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/vsignup_view.php');
}

private function encrypt($pass)
{
return(md5($pass));
}

protected function vsignup()
{
if(!isset($_SERVER['HTTP_REFERER']))
{
header('Location: http://localhost/jee');
}

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/user_class.php');
GLOBAL $objPDO;
if(isset($_POST['firstname']) && isset($_POST['lastname']) &&  isset($_POST['email']) && isset($_POST['password']) && isset($_POST['cpassword']) && isset($_POST['institute']))
{
$firstname=$_POST['firstname'];
$lastname=$_POST['lastname'];
$institute=$_POST['institute'];
$email=$_POST['email'];
$pass=$_POST['password'];
$cpass=$_POST['cpassword'];
$user=new User($objPDO);
}

$err=NULL;

if(!isset($firstname) || $firstname=='')
{
$err='Enter your Firstname';
}
else if(!isset($lastname) || $lastname=='')
{
$err='Enter your Lastname';
}
else if(!isset($email) || $email=='' )
{
$err='Enter your Email Id';
}
else if(!isset($institute) || $institute=='')
{
$err='Enter your Institute';
}
else if(!isset($pass) || $pass=='')
{
$err='Enter your Password';
}
else if(!isset($cpass) || $cpass=='')
{
$err='Confirm your Password';
}
else if(isset($pass) && isset($cpass) && $pass!=$cpass)
{
$err='Confirm Password not Matching';
}
else if($user->loadByEmail($email))
{
$err='Email Already Exists';
}

if($err)
{
include($_SERVER['DOCUMENT_ROOT']."/jee/view/vsignup_view.php");
}
else
{
$this->validate($firstname,$lastname,$email,$pass,$institute);
}


}

private function validate($firstname,$lastname,$email,$pass,$institute)
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/user_class.php');
GLOBAL $objPDO;
$user=new User($objPDO);
$user->setFirstName($firstname);
$user->setLastName($lastname);
$user->setEmail($email);
$user->setPassword($this->encrypt($pass));
$user->setInstitute($institute);
$user->setacctType("user");
$user->setVerifyCode(md5(microtime()));
$user->save();

include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/points_class.php');
$points=new Points($objPDO);
$points->setUserId($user->getID());
$points->setPoints(0);
$points->save();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/controller/mailer.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/notifications_class.php');
$mail=new Mail();
$mail->To=$email;
$mail->From='admin@knowledgecoefficient.com';
$mail->Subject='Knowledge Coefficient Registration';
$message='Thankyou for Registering with Knowledge Coefficient - The Online Examination Destination.<br/><br/> Verify your account by clicking on this link http://knowledgecoefficient.com/profile/verify/'.$user->getID().'/'.$user->getVerifyCode();
$mail->send();

$notifications=new Notifications($objPDO);
$notifications->setUserId($user->getID());
$notifications->setTitle('Welcome to Knowledge Coefficient');
$notifications->setSubTitle('Attempt our challenging quizzes and earn knowledge');
$notifications->setType('admin');
$notifications->setLink('/jee/');
$notifications->save();


$notifications=new Notifications($objPDO);
$notifications->setUserId($user->getID());
$notifications->setTitle('Knowledge Coefficient Site Tour');
$notifications->setSubTitle('Go through the site tour.');
$notifications->setType('admin');
$notifications->setLink('/jee/welcome/tour');
$notifications->save();


echo '<META HTTP-EQUIV="Refresh" Content="0;url=http://localhost/jee/login/index&data=signin_success">';

}


public function facebook_login()
{
$user=null;
try{
   include_once($_SERVER['DOCUMENT_ROOT']."/jee/facebook/facebook.php");
   }
catch(Exception $o){
   echo '<pre>';
   print_r($o);
   echo '</pre>';
}
$callback="";
if(isset($_GET['callback']))
{
$callback=$_GET['callback'];
}
// Create our Application instance.
$config = array();
$config['appId'] = '455856014511119';
$config['secret'] = 'c043bd4ddf5bcdba6b0793561e36bce3';
$facebook = new Facebook($config);
//Facebook Authentication part
$user=$facebook->getUser();
$loginUrl=$facebook->getLoginUrl(array(
                'scope'=> 'email,user_education_history,user_about_me','display' => 'page')
				
);
if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me');
		GLOBAL $objPDO;
		$email=$user_profile['email'];
		$kc_user=new User($objPDO);
		$kc_user->loadByEmail($email);
		if($kc_user->getEmail()==$email)
		{
		GLOBAL $session;
		$session->login($kc_user->getID());
		$session->setFacebookSession(1);
		$session->save();
		if($callback!="")
		{
		echo '<META HTTP-EQUIV="Refresh" Content="0;url='.$callback.'">';	
		}
		else
		{
		echo '<META HTTP-EQUIV="Refresh" Content="0;url=/jee">';
		}
		}
		else
		{
		$firstname=$user_profile['first_name'];
		$lastname=$user_profile['last_name'];
		$email=$user_profile['email'];
		$pass=$facebook->getAccessToken()."_facebook";
		$education=$user_profile['education'];
		$pos=count($education)-1;
		$institute=NULL;
		if($pos>=0)
		{
		$institute=$education[$pos]['school']['name'];
		}
		if(!$institute)
		{
		$institute="Not Mentioned";
		}
		$this->validate($firstname,$lastname,$email,$pass,$institute);
		GLOBAL $session;
		$kc_user->loadByEmail($email);
		$session->login($kc_user->getID());
		$session->setFacebookSession(1);
		$session->save();
		if($callback!="")
		{
		echo '<META HTTP-EQUIV="Refresh" Content="0;url='.$callback.'">';	
		}
		else
		{
		echo '<META HTTP-EQUIV="Refresh" Content="0;url=/jee">';
		}
		}
		
		
		
      } catch (FacebookApiException $e) {
        //you should use error_log($e); instead of printing the info on browser
        die($e);  // d is a debug function defined at the end of this file
        $user = null;
      }
    }
if(!$user) {
        echo "<script type='text/javascript'>top.location.href = '$loginUrl';</script>";
        exit;
}

}



};

?>