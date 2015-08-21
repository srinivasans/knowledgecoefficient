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
<script type="text/javascript" src="/jee/js/jquery.form.js"></script>

<script type="text/javascript">
var now=new Date();
var nmon=0;
var nday=0;

$('document').ready(function(){
$('#elem').tooltip('show');

  $.ajax({
  dataType: "json",
  url: '/jee/js/now.php',
  success: function(data){
  now=new Date(data.dateString);
  nmon=now.getMonth();
  nday=now.getDate();
  }

});

 $('#filter').ajaxForm({
target:'#test_list',
success:function(){
}
});

$('#filter_input').live('keyup',function(){$('#filter').submit();});


});

</script>
</head>

<body>

<?php
// $name to be declared
include("header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">
<div class="span1"></div>
<div class="span10" id="home-wrapper">
<h4 id="home-update-head">Search Results for "<?php echo $query; ?>"

</h4>

<table class="table table-bordered table-striped" id="test_list">

<?php
if(count($test_details))
{
echo '<tr><th colspan="3">Tests <a href="/jee/test/index&query='.$query.'" class="small pull-right">(View All)</a></th></tr>';
}
foreach($test_details as $k=>$test)
{
echo '<tr>';
echo '<td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a><div class="small">'.$test['Description'].'</div></td>';
echo '<td>'.$test['Access'].'</td>';
if($test['Status']==0)
{
echo '<td><span class="label label-info">Upcoming</span></td>';
}
else if($test['Status']==1)
{
echo '<td><span class="label label-success">Active</span></td>';
}
echo '</tr>';
}

if(count($quiz_details))
{
echo '<tr><th colspan="3">Quizzes <a href="/jee/quiz/index&query='.$query.'" class="small pull-right">(View All)</a></th></tr>';
}

foreach($quiz_details as $k=>$quiz)
{
echo '<tr>';
echo '<td><a href="/jee/quiz/start/'.$quiz['ID'].'">'.$quiz['Name'].'</a> <div class="small">'.$quiz['Description'].'</div></td>';
echo '<td>'.$quiz['Access'].'</td>';
if($quiz['Status']==0)
{
echo '<td><span class="label label-info">Upcoming</span></td>';
}
else if($quiz['Status']==1)
{
echo '<td><span class="label label-success">Active</span></td>';
}

echo '</tr>';
}

?>

</table>

</div>
</div>
</div>

<?php
include("footer_static.php");
?>

</body>
</html>
