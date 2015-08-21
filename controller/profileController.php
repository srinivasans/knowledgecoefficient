<?php
include_once('controller.php');
class ProfileController extends Controller
{

public function __construct()
{
$this->arNoAuthenticate['forgot_password']='1';
$this->arNoAuthenticate['send_forgot_mail']='1';
$this->arNoAuthenticate['change_forgot_password']='1';
$this->arNoAuthenticate['verify']='1';
}


protected function view()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"dashboard",
);

$user_details=array();
$user_details['ID']=$user->getID();
$user_details['FirstName']=$user->getFirstName();
$user_details['LastName']=$user->getLastName();
$user_details['AcctType']=$user->getacctType();
$user_details['Email']=$user->getEmail();
$user_details['Class']=$user->getClass();
$user_details['Institute']=$user->getInstitute();


include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/profile_view.php');
}


protected function view_profile()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"dashboard",
);
if(isset($_GET['uid']))
{
$client=new User($objPDO,$_GET['uid']);
$user_details=array();
$user_details['ID']=$client->getID();
$user_details['FirstName']=$client->getFirstName();
$user_details['LastName']=$client->getLastName();
$user_details['AcctType']=$client->getacctType();
$user_details['Email']=$client->getEmail();
$user_details['Class']=$client->getClass();
$user_details['Institute']=$client->getInstitute();
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/profile_view_only.php');
}
else
{
echo 'error';
}

}

protected function edit()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"dashboard",
);

$user_details=array();
$user_details['ID']=$user->getID();
$user_details['FirstName']=$user->getFirstName();
$user_details['LastName']=$user->getLastName();
$user_details['AcctType']=$user->getacctType();
$user_details['Email']=$user->getEmail();
$user_details['Class']=$user->getClass();
$user_details['Institute']=$user->getInstitute();


include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/profile_edit.php');
}



protected function edit_profile()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"dashboard",
);

if(isset($_POST))
{
extract($_POST);
if(isset($first_name) && isset($last_name) && isset($class) && isset($institute) && $first_name!="" && $last_name!="")
{
$user->setFirstName($first_name);
$user->setLastName($last_name);
$user->setClass($class);
$user->setInstitute($institute);
$user->save();
}

}

echo '<meta http-equiv="Refresh" content="0;url=/jee/profile/view">';
}


protected function change_password()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"dashboard",
);
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/change_password.php');
}


protected function forgot_password()
{
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/view/forgot_password.php');
}


protected function send_forgot_mail()
{
global $objPDO;
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/controller/mailer.php');
if(isset($_POST))
{
extract($_POST);
if(isset($email) && isset($_SESSION['captcha']) && $captcha==$_SESSION['captcha'])
{
unset($_SESSION['captcha']);
$user=new User($objPDO);
$user->loadByEmail($email);
$mail=new Mail();
$mail->To=$email;
$mail->From='admin@knowledgecoefficient.com';
$mail->Subject='Change Forgot Password';
$mail->Message='Click on the Link here to Change your Password<br/> http://www.knowledgecoefficient.com/jee/profile/change_forgot_password/'.$user->getID().'/'.$user->getVerifyCode();
$mail->send();
echo 'Check your Mail Inbox for Instructions';
}
else
{
echo 'Enter All Details. Retry .';
}
}

}


protected function verify()
{
global $objPDO;
if(isset($_GET['uid']) && isset($_GET['ref']))
{
$user=new User($objPDO,$_GET['uid']);
if($user->getVerifyCode()==$_GET['ref'])
{
$user->setVerified=1;
$user->save();
echo 'Verification Complete <a href="/jee">Go to Main Site</a>';

}

}

}

protected function change_forgot_password()
{

if(isset($_GET['ID']) && isset($_GET['ref']))
{
$user=new User($objPDO,$_GET['uid']);
if($user && $user->getVerifyCode()==$_GET['ref'])
{
echo '<form action="/jee/profile/save_change_forgot_password/'.$_GET['uid'].'/'.$_GET['ref'].'" method="POST"><input type="password" name="new_password" placeholder="new password"/><input type="password" name="confirm_new_password" placeholder="confirm new password"/></form>';
}
}

}


protected function save_change_forgot_password()
{

if(isset($_GET['ID']) && isset($_GET['ref']))
{
$user=new User($objPDO,$_GET['uid']);
if($user && $user->getVerifyCode()==$_GET['ref'] && isset($new_password) && isset($confirm_new_password) && $new_password==$confirm_new_password )
{
$user->setPassword($this->encrypt($new_password));
$user->save();
echo 'Password changed Successfully';
}
}

}

private function encrypt($pass)
{
return(md5($pass));
}


protected function save_change_password()
{
GLOBAL $objPDO;
GLOBAL $session;
$user=new User($objPDO,$session->getuserId());
$headMenu=array(
"username"=>$user->getFirstName(),
"active"=>"dashboard",
);

if(isset($_POST))
{
extract($_POST);
if(isset($password) && isset($new_password) && isset($confirm_new_password) && $new_password==$confirm_new_password && $this->encrypt($password)==$user->getPassword())
{
$user->setPassword($this->encrypt($new_password));
$user->save();
echo 'Password Changed Successfully <a href="/jee/profile/view">Go Back</a>';
}
else
{
echo 'Password Cannot Be Changed (Invalid Form) <a href="/jee/profile/view">Go Back</a>';
}

}

}







}
?>