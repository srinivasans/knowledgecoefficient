<?php

abstract class Model
{
protected $ID;
protected $objPDO;
protected $strTableName;
protected $arRelationMap;
protected $blForDeletion;
protected $blisLoaded;
protected $arModifiedRelations;

abstract protected function defineTableName();
abstract protected function defineRelationMap();

public function __construct(PDO $objPDO,$id=NULL)
{
$this->strTableName=$this->defineTableName();
$this->arRelationMap=$this->defineRelationMap();
$this->objPDO=$objPDO;
$this->blisLoaded=false;
if(isset($id))
{
$this->ID=$id;
}
$this->arModifiedRelations=array();
}


public function load()
{
if(isset($this->ID))
{
$strQuery="SELECT ";
foreach($this->arRelationMap as $key => $value)
{
$strQuery.="`".$key."`,";
}
$strQuery=substr($strQuery,0,strlen($strQuery)-1);
$strQuery.=" FROM ".$this->strTableName." WHERE `id`=:eid ;";
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindParam(':eid',$this->ID,PDO::PARAM_INT);
$objStatement->execute();
$arRow=$objStatement->fetch(PDO::FETCH_ASSOC);
if($arRow)
{
foreach($arRow as $key=>$value)
{
$strMember=$this->arRelationMap[$key];
if(property_exists($this,$strMember))
{
if(is_numeric($value))
{
eval('$this->'.$strMember.' = ' .$value.';');
}
else
{
eval('$this->'.$strMember.' = "'.$value.'";');
}

}

}
}

}
$this->blisLoaded=true;
}

public function save()
{
if(isset($this->ID))
{
$strQuery="UPDATE `".$this->strTableName."` SET ";
foreach($this->arRelationMap as $key => $value)
{
eval('$actualVal = &$this->'.$value.';');
if(array_key_exists($value,$this->arModifiedRelations))
{
$strQuery.="`".$key."` = :".$value.", ";
}
}

$strQuery=substr($strQuery,0,strlen($strQuery)-2);
$strQuery.=" WHERE `id`= :eid";
unset($objStatement);

$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindValue(':eid',$this->ID,PDO::PARAM_INT);
foreach($this->arRelationMap as $key => $value)
{
eval('$actualVal = &$this->'.$value.';');
if(array_key_exists($value,$this->arModifiedRelations))
{
if(is_int($actualVal) || $actualVal==NULL)
{
$objStatement->bindValue(':'.$value,$actualVal,PDO::PARAM_INT);
}
else
{
$objStatement->bindValue(':'.$value,$actualVal,PDO::PARAM_STR);
}
}

}

$objStatement->execute();
}
else
{
$strValueList="";
$strQuery="INSERT INTO `".$this->strTableName."`(";
foreach($this->arRelationMap as $key => $value)
{
eval('$actualVal= &$this->'.$value.';');
if(isset($actualVal))
{
if(array_key_exists($value,$this->arModifiedRelations))
{
$strQuery.="`".$key."`, ";
$strValueList.=":".$value.", ";
}
}
}

$strQuery=substr($strQuery,0,strlen($strQuery)-2);
$strValueList=substr($strValueList,0,strlen($strValueList)-2);
$strQuery.=") VALUES (";
$strQuery.=$strValueList;
$strQuery.=")";
unset($objStatement);
$objStatement=$this->objPDO->prepare($strQuery);
foreach($this->arRelationMap as $key => $value)
{
eval('$actualVal = &$this->'.$value.';');
if(isset($actualVal))
{
if(array_key_exists($value,$this->arModifiedRelations))
{
if(is_int($actualVal) || $actualVal==NULL)
{
$objStatement->bindValue(':'.$value,$actualVal,PDO::PARAM_INT);
}
else
{
$objStatement->bindValue(':'.$value,$actualVal,PDO::PARAM_STR);
}

}
}

}
$objStatement->execute();
$this->ID=$this->objPDO->lastInsertId();
}

}


public function markForDeletion()
{
$this->blForDeletion=true;
}

public function __destruct()
{
if(isset($this->ID))
{
if($this->blForDeletion==true)
{
$strQuery="DELETE FROM `".$this->strTableName."` WHERE `id` = :eid";
$objStatement=$this->objPDO->prepare($strQuery);
$objStatement->bindValue(':eid',$this->ID,PDO::PARAM_INT);
$objStatement->execute();
}
else
{
$this->save();
}
}
}

public function __call($strFunction,$arArguements)
{
$strMethodType=substr($strFunction,0,3);
$strMember=substr($strFunction,3);
switch($strMethodType)
{
case "set":
return($this->setAccessor($strMember,$arArguements[0]));
break;
case "get":
return($this->getAccessor($strMember));
};
return false;
}

private function setAccessor($strMember,$strNewValue)
{
if(property_exists($this,$strMember))
{
if(is_numeric($strNewValue))
{
eval('$this->'.$strMember.' = '.$strNewValue.';');
}
else
{
eval('$this->'.$strMember.' = "'.$strNewValue.'";');
}
$this->arModifiedRelations[$strMember]="1";
}
else
{
return false;
}
}


private function getAccessor($strMember)
{
if($this->blisLoaded!=true)
{
$this->load();
}
if(property_exists($this,$strMember))
{
eval('$strVal = $this->'.$strMember.';');
return $strVal;
}
else
{
return false;
}
}

 public function getAsArray()
	 {
	 $arr=array();
	 foreach($this->arRelationMap as $key=>$value)
	 {
	 $stmt='$arr["'.$key.'"]=$this->get'.$value.'();';
	 eval($stmt);
	 }
	 return $arr;
	 }
	 
	
	


};

?>