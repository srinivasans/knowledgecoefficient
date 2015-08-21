<?php
include('controller.php');
class LoginController extends Controller
{
public function __construct()
{
$this->arNoAuthenticate['index']='1';
$this->arNoAuthenticate['embed']='1';
$this->arNoAuthenticate['validate']='1';
$this->arNoAuthenticate['vlogin']='1';
}

protected function index()
{
$callback="";
if(isset($_GET['callback']))
{
$callback=$_GET['callback'];
}
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/index.php');
}

protected function embed()
{
$callback="";
if(isset($_GET['callback']))
{
$callback=$_GET['callback'];
}
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/embed_login_index.php');
}

private function encrypt($pass)
{
return(md5($pass));
}

protected function vlogin()
{
if(!isset($_SERVER['HTTP_REFERER']))
{
header('Location: /jee');
return;
}
$callback="";
if(isset($_GET['callback']))
{
$callback=$_GET['callback'];
}
include_once($_SERVER['DOCUMENT_ROOT'].'/jee/model/user_class.php');
GLOBAL $objPDO;
$email="";
$pass="";
if(isset($_POST['email']) && isset($_POST['password']))
{
$email=$_POST['email'];
$pass=$_POST['password'];
}
$user=new User($objPDO);
$ld=$user->loadByEmail($email);
$err=NULL;
if(!isset($email) || $email=='' )
{
$err='Enter your Email Id';
}
else if(!isset($pass) || $pass=='')
{
$err='Enter your Password';
}
else if(!$ld)
{
$err='Incorrect Email';
}
else if($user->getPassword()!=$this->encrypt($pass))
{
$err='Incorrect Password';
}

if($err)
{
include($_SERVER['DOCUMENT_ROOT']."/jee/view/vlogin_view.php");
}
else
{
$this->validate($email,$pass,$callback);
}


}

private function validate($email,$pass,$callback="")
{
GLOBAL $objPDO;
$email=$email;
$pass=$pass;
$user=new User($objPDO);
$user->loadByEmail($email);
if($user->getEmail()==$email && $user->getPassword()==$this->encrypt($pass))
{
GLOBAL $session;
$session->login($user->getID());
if($callback!="")
{
echo '<META HTTP-EQUIV="Refresh" Content="0;url='.$callback.'">';
}
else
{
echo '<META HTTP-EQUIV="Refresh" Content="0;url=/jee">';
}

}

}






};

?>