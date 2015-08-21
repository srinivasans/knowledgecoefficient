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

$('#filter').submit();
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
<h4 id="home-update-head">Tests
<form id="filter" action="/jee/test/get_filter" style="display:inline-block;float:right" method="POST">
<input type="text" name="filter" value="<?php if(isset($_GET['query'])){echo $_GET['query'];} ?>" id="filter_input" placeholder="Filter"/>
</form>

</h4>

<table class="table table-bordered table-striped" id="test_list">

<?php
foreach($tests_active as $k=>$test)
{
if($test['TimeLimit']>5000000)
{
$test['TimeLimit']="No Time Limit";
}
else
{
$test['TimeLimit']=$test['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td>';
echo '<td>'.$test['TimeLimit'].'</td>';
echo '<td><span class="label label-success">Active</span></td>';
echo '</tr>';
}

foreach($tests_upcoming as $k=>$test)
{
if($test['TimeLimit']>5000000)
{
$test['TimeLimit']="No Time Limit";
}
else
{
$test['TimeLimit']=$test['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td>';
echo '<td>'.$test['TimeLimit'].'</td>';
echo '<td><span class="label label-info">Upcoming</span></td>';
echo '</tr>';
}

foreach($tests_archived as $k=>$test)
{
if($test['TimeLimit']>5000000)
{
$test['TimeLimit']="No Time Limit";
}
else
{
$test['TimeLimit']=$test['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td>';
echo '<td>'.$test['TimeLimit'].'</td>';
echo '<td><span class="label label-warning">Archived</span></td>';
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
