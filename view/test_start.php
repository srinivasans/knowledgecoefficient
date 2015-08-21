<?php
include("controller/utility_class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $test_details['Name']; ?> - Knowledge Coefficient</title>
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
<div class="row-fluid">
<div class="span2"></div>
<div class="span8" id="wrapper">
<legend>Test - <?php echo $test_details['Name']; ?></legend>

<div class="description">
<?php echo $test_details['Description']; 
$time_lim=strtotime($test_details['TimeLimit'])-strtotime($test_details['Time']);
$time_lim=$time_lim/60;
?>
</div>

<table class="table table-striped table-bordered">
<tr><td>Opening Time</td><td><?php echo date('D M j G:i:s T Y',strtotime($test_details['Time'])); ?></td></tr>
<tr><td>Closing Time</td><td><?php echo date('D M j G:i:s T Y',strtotime($test_details['TimeEnd'])); ?></td></tr>
<tr><td>Time Limit</td><td><?php if($time_lim<5000000){ echo $time_lim." Minutes" ;}else{ echo 'No Time Limit'; } ?></td></tr>
</table>

<div class="label label-info">By Starting the test you agree to Honor the Code of Conduct</div>
<div style="padding-top:1%">
<?php
if(strtotime('now')>strtotime($test_details['Time']) && strtotime('now')<strtotime($test_details['TimeEnd']))
{
if($test_details['Access']=='private')
{
echo '(This is a private Test)Enter Passkey to Start Test: ';
echo '<form class="passkey" name="passkey" action="/jee/test/start_test/'.$test_details['ID'].'" method="POST">';
Utility::inputText("key","key","Pass Key",$class=NULL);
echo '<input type="submit" value="Start Test" class="btn btn-success"/>';
echo '</form>';
}
else
{
echo '<a href="/jee/test/start_test/'.$test_details['ID'].'" class="btn btn-success">';
if($test_details['Started']==0 && $test_details['Access']=='public')
{
echo 'Start Test';
}
else
{
echo 'Continue Test';
}
echo '</a>';
}
}
else if(strtotime('now')<strtotime($test_details['Time']))
{
echo '<button class="btn btn-invalid">The Test has not Started</button>';
}
else
{
echo '<div class="label label-important">The Test is Closed</div>';
}

?>
</div>

</div>
<div class="span2"></div>
</div>

</body>

</html>