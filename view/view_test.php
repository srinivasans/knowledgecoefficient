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
<script src="/jee/js/timepicker.js"></script>

<script type="text/javascript">
$('document').ready(function(){
$('#elem').tooltip('show');
});

function deleteTest()
{
$('#deleteModal').modal();
}

</script>
<?php
$scriptStart='<script type="text/javascript">
function addSection()
{
$("#sections").append(" ';
$scriptEnd=' ");
}
</script>';

echo $scriptStart;
echo Utility::getcontrolGroupTextCode("section[]","section[]","text","Section","Section :");
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
<form class="form-horizontal" id="delete_question_form" method="POST" action="/jee/test/delete"> 
<?php
Utility::inputHidden("tid","tid",$test_details['ID']); 
?>
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="submit" class="btn btn-danger" >Delete</butto>
</form>
</div>
</div>

<div class="row-fluid" >
<div class="span2"></div>
<div class="span9" id="create-form">
<form class="form-horizontal" method="POST" action="/jee/test/edit_test">
<legend>Test Details <div class="pull-right" style="margin-right:1%"><a href="/jee/test/setonboard/<?php echo $test_details['ID']; ?>" class="btn btn-warning">Set On-Board</a></div><div class="pull-right" style="margin-right:1%"><a href="/jee/test/add_settings/<?php echo $test_details['ID']; ?>" class="btn btn-info">Settings</a></div>
<div class="pull-right" style="margin-right:1%"><a href="/jee/test/add_questions/<?php echo $test_details['ID']; ?>" class="btn btn-success">Questions</a></div>
<div class="pull-right" style="margin-right:1%"><a onclick="deleteTest()" class="btn btn-danger">Delete</a></div>
</legend>
<?php
Utility::inputHidden("test_id","test_id",$test_details['ID']);
Utility::controlGroupTextEditable("test_name","test_name","text","Test Name","Test Name :",$test_details['Name']);
Utility::controlGroupSelectEditable("access","access","access","Access :",array("public"=>"Public","private"=>"Private","restricted"=>"Restricted","archive"=>"Archive"),$test_details['Access']);
Utility::controlGroupSelectEditable("test_type","test_type","test_type","View Type :",array("1"=>"OMR Sheet","2"=>"One-by-One"),$test_details['TestType']);
?>
<hr/>

<div class="control-group">
<?php
Utility::label("desc","Description",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::inputTextAreaEditable($test_details['Description'],"desc","desc","Description");
?>
</div>
</div>

<div class="control-group">
<?php
Utility::label("credits","Credits",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::inputTextAreaEditable($test_details['Credits'],"credits","credits","Credits");
?>
</div>
</div>

<div class="control-group">
<?php
Utility::label("date","Date",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::datePickerEditable($test_details['Date'],"date","date");
?>
</div>

</div>


<div class="control-group">
<?php
Utility::label("time","Time",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::TimePickerEditable(date('h:i',strtotime($test_details['Time'])),"time","time");
?>
</div>

</div>
<?php
$limit=$test_details['TimeEnd'];
?>
<div class="control-group">
<?php
Utility::label("time_end","Time End",NULL,"control-label");
?>
<div class="controls">
<?php
$limit=strtotime($test_details['TimeEnd'])-strtotime($test_details['Time']);
$minutes=round($limit/60);
if($minutes>5000000)
{
$test_details['TimeEnd']="";
}
Utility::DateTimePickerEditable($test_details['TimeEnd'],"time_end","time_end");
?>
</div>

</div>
<?php
$limit=strtotime($test_details['TimeLimit'])-strtotime($test_details['Time']);
$minutes=round($limit/60);
if($minutes>5000000)
{
$minutes="";
}
Utility::controlGroupTextEditable("time_limit","time_limit","text","Time Limit","Time Limit (in minutes):",$minutes);
?>



<hr/>

<div id="sections">
<?php
foreach($section as $key=>$value)
{
Utility::controlGroupText("section[".$key."]","section[".$key."]","text","Section","Section :",NULL,$value);
}
?>
</div>

<a style="cursor:pointer;padding-left:20%;" id="1" onclick="addSection()">+Add Section</a>

<?php
Utility::controlGroupSubmit("save","save","submit","Save","Save Test",array("create-submit","btn-primary"));
?>

</form>
</div>
</div>
</div>

</body>

</html>