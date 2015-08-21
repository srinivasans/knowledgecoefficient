<?php
include("controller/utility_class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $quiz_details['Name']; ?> - Knowledge Coefficient</title>
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
</script>

</head>

<body>
<?php
include("header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">
<div class="span2"></div>
<div class="span8" id="wrapper">
<legend>Quiz - <?php echo $quiz_details['Name']; ?></legend>

<div class="description">
<?php echo $quiz_details['Description']; 
$time_lim=strtotime($quiz_details['TimeLimit'])-strtotime($quiz_details['Time']);
$time_lim=$time_lim/60;
?>
</div>

<table class="table table-striped table-bordered">
<tr><td>Opening Time</td><td><?php echo date('D M j G:i:s T Y',strtotime($quiz_details['Time'])); ?></td></tr>
<tr><td>Closing Time</td><td><?php echo date('D M j G:i:s T Y',strtotime($quiz_details['TimeEnd'])); ?></td></tr>
<tr><td>Time Limit</td><td><?php if($time_lim<5000000){ echo $time_lim." Minutes"; }else{ echo 'No Time Limit'; } ?></td></tr>
</table>

<div class="label label-info">By Starting the quiz you agree to Honor the Code of Conduct</div>
<div style="padding-top:1%">
<?php
if(strtotime('now')>strtotime($quiz_details['Time']) && strtotime('now')<strtotime($quiz_details['TimeEnd']))
{
if($quiz_details['Access']=='private')
{
echo '(This is a private Quiz)Enter Passkey to Start Quiz: ';
echo '<form class="passkey" name="passkey" action="/jee/quiz/start_quiz/'.$quiz_details['ID'].'/'.$ref.'" method="POST">';
Utility::inputText("key","key","Pass Key",$class=NULL);
echo '<input type="submit" value="Start Quiz" class="btn btn-success"/>';
echo '</form>';
}
else
{
echo '<a href="/jee/quiz/start_quiz/'.$quiz_details['ID'].'/'.$ref.'" class="btn btn-success">';
if($quiz_details['Started']==0 && $quiz_details['Access']=='public')
{
echo 'Start Quiz';
}
else
{
echo 'Continue Quiz';
}
echo '</a>';
}
}
else if(strtotime('now')<strtotime($quiz_details['Time']))
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