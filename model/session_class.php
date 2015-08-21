<?php
include_once('model_class.php');
include_once('user_class.php');
class Session extends Model
{
protected $is_logged;
protected $cookie;
protected $session;
protected $sessionTimeOut;
protected $sessionExpiryTime;
protected $blLogout;
protected $userId;
protected $sessionId;
protected $loginTime;
protected $FacebookSession;


public function __construct($objPDO)
{
session_start();
$this->blLogout=false;
$this->is_logged=false;
if(isset($_SESSION['id']) && isset($_SESSION['session_id']) )
{
$this->is_logged=true;
}
//$this->cookie=$_COOKIE;
$this->session=$_SESSION;
$this->sessionTimeOut=600;
$this->sessionExpiryTime=5;
$this->FacebookSession=0;
if(isset($_COOKIE))
{
foreach($_COOKIE as $key=>$value)
{
$this->cookie[$key]=$value;
}
}
if(isset($_SESSION['key']))
{
$key=$_SESSION['key'];
parent::__construct($objPDO,$key);
}
else
{
parent::__construct($objPDO);
}
}

protected function defineTableName()
{
return('sessions');
}

protected function defineRelationMap()
{
return(array(
"id"=>"ID",
"session_id"=>"sessionId",
"user_id"=>"userId",
"login_time"=>"loginTime",
"facebook_session"=>"FacebookSession",
));
}

public function isAdmin()
{
GLOBAL $objPDO;
$user=new User($objPDO,$this->getuserId());
return($user->checkAdmin());
}


public function isPro()
{
GLOBAL $objPDO;
$user=new User($objPDO,$this->getuserId());
return($user->checkPro());
}

public function isUser()
{
GLOBAL $objPDO;
$user=new User($objPDO,$this->getuserId());
return($user->checkUser());
}

public function isLoggedIn()
{
return $this->is_logged;
}

public function login($id)
{
$this->setuserId($id);
$this->setsessionId(md5(microtime()));

$this->save();
$_SESSION['id']=$this->getuserId();
$_SESSION['key']=$this->getID();

$_SESSION['session_id']=$this->getsessionId();


$this->session=$_SESSION;
$this->is_logged=true;

}

public function sessionValidate()
{
if($this->getsessionId()!=$_SESSION['session_id'])
{
$this->logout();
return false;
}
return true;
}


public function logout()
{
unset($_SESSION);
$this->markForDeletion();
session_destroy();
unset($user);
}

}

?>