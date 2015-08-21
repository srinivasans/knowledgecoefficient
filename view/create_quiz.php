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


$('#create_quiz').ajaxForm({
target:'#error',
success:function(){$('#error').show();}
});
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
<div class="alert alert-error" id="error" style="display:none;text-align:center"></div>
<div class="row-fluid" >
<div class="span2"></div>
<div class="span9" id="create-form">
<form class="form-horizontal" id="create_quiz" method="POST" action="/jee/quiz/create_quiz">
<legend>Create Quiz</legend>
<?php
Utility::controlGroupText("quiz_name","quiz_name","text","Quiz Name","Quiz Name :");
Utility::controlGroupSelect("access","access","access","Access :",array("public"=>"Public","private"=>"Private","archive"=>"Archive"));
Utility::controlGroupSelect("test_type","test_type","test_type","View Type :",array("1"=>"All in One","2"=>"One-by-One"));
?>
<hr/>

<div class="control-group">
<?php
Utility::label("desc","Description",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::inputTextArea("desc","desc","Description");
?>
</div>
</div>

<div class="control-group">
<?php
Utility::label("credits","Credits",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::inputTextArea("credits","credits","Credits");
?>
</div>
</div>

<div class="control-group">
<?php
Utility::label("date","Date",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::datePicker("date","date");
?>
</div>

</div>

<div class="control-group">
<?php
Utility::label("time","Time",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::TimePicker("time","time");
?>
</div>

</div>
<div class="control-group">
<?php
Utility::label("time_end","Time End",NULL,"control-label");
?>
<div class="controls">
<?php
Utility::dateTimePicker("time_end","time_end");
?>
</div>

</div>
<?php
Utility::controlGroupText("time_limit","time_limit","text","Time Limit","Time Limit (in minutes):");
?>
<hr/>

<?php
Utility::controlGroupSubmit("create","create","submit","Create","Create Quiz",array("create-submit","btn-primary"));
?>

</form>
</div>
</div>
</div>

</body>

</html>