<?php
require_once('model_class.php');
class User extends Model
{
protected $FirstName;
protected $LastName;
protected $Email;
protected $Class;
protected $Institute;
protected $City;
protected $State;
protected $Password;
protected $acctType;
protected $Verified;
protected $VerifyCode;

protected function defineTableName()
{
return('user');
}

protected function defineRelationMap()
{
return (array(
			"id"=>"ID",
			"firstname"=>"FirstName",
			"lastname"=>"LastName",
			"email"=>"Email",
			"password"=>"Password",
			"class"=>"Class",
			"institute"=>"Institute",
			"city"=>"City",
			"state"=>"State",
			"acct_type"=>"acctType",
			"verified"=>"Verified",
			"verify_code"=>"VerifyCode",
));
}

function checkAdmin()
{
if(!$this->blisLoaded)
$this->load();
if($this->acctType=='admin')
return true;

return false;
}

function checkPro()
{
if(!$this->blisLoaded)
$this->load();

if($this->acctType=='pro')
return true;

return false;
}

function checkUser()
{
if(!$this->blisLoaded)
$this->load();

if($this->acctType=='user')
return true;

return false;
}


public function loadByEmail($email)
{
$strQuery="SELECT * FROM `user` WHERE `email` = :email;"; 
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':email',$email,PDO::PARAM_STR);
$objStatement->execute();
$arRow=$objStatement->fetch(PDO::FETCH_ASSOC);
if(isset($arRow['id']))
{
$this->ID=$arRow['id'];
$this->load();
return true;
}
else
{
return false;
}
}


};

?>