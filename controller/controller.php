<?php

abstract class Controller
{
protected $arNoAuthenticate;

public function __construct()
{
$this->arNoAuthenticate=array();
}

public function authenticate($strFunction)
{
if(array_key_exists($strFunction,$this->arNoAuthenticate))
{
eval('$this->'.$strFunction.'();');
}
else
{
GLOBAL $session;

if($session->isLoggedIn()==true && $session->sessionValidate()==true)
{
eval('$this->'.$strFunction.'();');
}
else
{
$key="";
$action="";
$uid="";
if(isset($_GET['key']))
{
$key=$_GET['key'];
}
if(isset($_GET['action']))
{
$action=$_GET['action'];
}
if(isset($_GET['uid']))
{
$uid=$_GET['uid'];
}
$ref="";
if(isset($_GET['ref']))
{
$ref=$_GET['ref'];
}
if($ref=="")
{
$ref="index";
}

$callback='/jee/';
if($key!="")
{
$callback=$callback.$key;
if($action!="")
{
$callback=$callback.'/'.$action;
if($uid!="")
{
$callback=$callback.'/'.$uid;
if($ref!="")
{
$callback=$callback.'/'.$ref;
}

}
}

}


header('Location:/jee/login/'.$ref.'&callback='.$callback);
}
}

}

protected function checkAdmin()
{
if($session->isAdmin()==true)
{
return true;
}
else
{
return false;
}
}

function checkPro()
{
if($session->isPro()==true)
return true;

return false;
}

function checkStudent()
{
if($session->isUser()==true)
return true;

return false;
}


}

?>