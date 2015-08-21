<?php

class PDOFactory
{
public static function getPDO($strDSN,$strUser,$strPass,$strParams)
{
$strKey=md5(serialize(array($strDSN,$strUser,$strPass,$strParams)));
if(!isset($GLOBALS["PDOS"][$strKey]) || !($GLOBALS["PDOS"][$strKey] instanceof PDO))
{
$GLOBALS["PDOS"][$strKey]=new PDO($strDSN,$strUser,$strPass,$strParams);
};
return($GLOBALS["PDOS"][$strKey]);
}
};

?>