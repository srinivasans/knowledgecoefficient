<?php
include("controller/utility_class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Knowledge Coefficient</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap.min.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/style.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap-responsive.min.css"/>
<script type="text/javascript" src="/jee/js/jquery.js"></script>
<script type="text/javascript" src="/jee/js/bootstrap.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>

<script type="text/javascript">
$('document').ready(function(){
$('#elem').tooltip('show');
});

function deleteGroup(elem)
{
var id=elem;
$('#deleteModal').modal();
$('#tgid').attr('value',elem);
}
</script>
<?php
$scriptStart='<script type="text/javascript">
function addGroup(elem,name)
{
var id=elem;
$("#group-"+id).append("';
$scriptEnd=' <input type=\"hidden\" name=\"sections[]\" id=\"sections[]\" value=\""+name+"\"/>  <input type=\"hidden\" name=\"group_id[]\" id=\"group_id[]\" value=\"0\" /><hr/>");
}
</script>';

echo $scriptStart;
echo Utility::getcontrolGroupTextCode("group_name[]","group_name[]","text","Group Name","Group Name :");
echo Utility::getcontrolGroupTextCode("group_desc[]","group_desc[]","text","Group Description","Group Description :");
echo Utility::getcontrolGroupTextCode("num_questions[]","num_questions[]","text","Number of Questions","Number of Questions :");
echo $scriptEnd;
?>


</head>

<body>
<?php
include("header.php");
?>

<div class="container-fluid" id="full-min">

<div id="deleteModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4>Are You Sure ?</h4>
</div>
<div class="modal-body">
<form class="form-horizontal" id="delete_question_form" method="POST" action="/jee/test/deletegroup"> 
<?php
Utility::inputHidden("tgid","tgid",""); 
Utility::inputHidden("tid","tid",$test_id); 
?>
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="submit" class="btn btn-danger" >Delete</butto>
</form>
</div>
</div>

<div class="row-fluid" >
<div class="span2"></div>
<div class="span9" id="create-form">
<form class="form-horizontal" method="POST" action="/jee/test/save_add_settings">
<legend><?php echo $test_name; ?> - Additonal Settings <div class="pull-right" style="margin-right:1%"><a href="/jee/test/add_questions/<?php echo $test_id; ?>" class="btn btn-success">Questions</a></div></legend>

<?php
Utility::inputHidden("test_id","test_id",$test_id);
?>
<?php
foreach($sections as $section_id=>$section_name)
{
?>
<legend class="inner-legend"><?php echo $section_name; ?></legend>
<?php
if(isset($sec_questions[$section_id]))
{
Utility::controlGroupText("no_questions[".$section_id."]","no_questions[".$section_id."]","text","Number of Questions","No of Questions :",NULL,$sec_questions[$section_id]);
}
else
{
Utility::controlGroupText("no_questions[".$section_id."]","no_questions[".$section_id."]","text","Number of Questions","No of Questions :");
}
?>

<div class="add-group" id="group-<?php echo $section_name; ?>">
<hr/>
<?php
if(count($tgroups)>0)
{
foreach($tgroups as $key=>$value)
{
if($value['SectionId']==$section_id)
{
Utility::inputHidden("sections[]","sections[]",$value['SectionId']);
Utility::inputHidden("group_id[]","group_id[]",$value['ID']);
Utility::controlGroupText("group_name[]","group_name[]","text","Group Name","Group Name :",NULL,$value['Name']);
Utility::controlGroupText("group_desc[]","group_desc[]","text","Group Description","Group Description :",NULL,$value['Description']);
Utility::controlGroupText("num_questions[]","num_questions[]","text","Number of Questions","Number of Questions :",NULL,$value['NumQuestions']);
echo '<a onclick="deleteGroup(this.id)" id="'.$value['ID'].'" name="'.$test_id.'"><i class="icon-trash"></i>Delete</a>';
echo '<hr/>';
}
}
}
else
{
Utility::inputHidden("sections[]","sections[]",$section_id);
Utility::inputHidden("group_id[]","group_id[]",0);
Utility::controlGroupText("group_name[]","group_name[]","text","Group Name","Group Name :");
Utility::controlGroupText("group_desc[]","group_desc[]","text","Group Description","Group Description :");
Utility::controlGroupText("num_questions[]","num_questions[]","text","Number of Questions","Number of Questions :");
}
?>
<hr/>
</div>

<a style="cursor:pointer;padding-left:20%;" id="<?php echo $section_name; ?>" name="<?php echo $section_id; ?>" onclick="addGroup(this.id,this.name)">+Add Group</a>

<?php
}

Utility::controlGroupSubmit("proceed","proceed","submit","Proceed","Proceed",array("create-submit","btn-primary"));
?>
</form>
</div>
</div>
</div>

</body>

</html>