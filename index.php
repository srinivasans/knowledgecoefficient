<?php
date_default_timezone_set('Asia/Calcutta');
include_once('model/pdofactory_class.php');
$strDSN='mysql:dbname=kc;host=localhost';
$objPDO=PDOfactory::GetPDO($strDSN,"root","",array());
include_once('model/session_class.php');

$session=new Session($objPDO);
if(isset($_GET['key']))
{
$view=strtolower($_GET['key']);
$check=true;
if(($view=='login' || $view=='signup' ) && $session->isLoggedIn()==true)
{
$check=false;
}
$controller=ucfirst($view).'Controller';
$controllerFile='controller/'.$view.'Controller.php';
if(file_exists($controllerFile))
{
if($check==true)
{
include_once($controllerFile);
$action=(isset($_GET['action']))?$_GET['action']:'index';
eval('$ctr = new '.$controller.'();');
if(method_exists($ctr,$action))
{
eval('$ctr->authenticate("'.$action.'");');
}
else
{
echo 'error';
}

}
else
{
echo '<meta http-equiv="Refresh" content="0;url=/jee">';
}
}
else
{
echo 'error';
}

}
else
{
include_once('controller/homeController.php');
$ctr=new HomeController();
var_dump($ctr);
$ctr->authenticate('index');
}
unset($objPDO);
?>