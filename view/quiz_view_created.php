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
  now=new Date(data.daquizring);
  nmon=now.getMonth();
  nday=now.getDate();
  }
});


});

function startTime()
{

var today=now;
var nmin=now.getMinutes();
var nhrs=now.getHours();
var nyrs=now.getYear();
nyrs+=1900;
var nsec=now.getSeconds();
nsec++;
now=new Date(nyrs,nmon,nday,nhrs,nmin,nsec);


//var today=new Date();
var h=today.getHours();
var m=today.getMinutes();
var s=today.getSeconds();

// add a zero in front of numbers<10
m=checkTime(m);
s=checkTime(s);
document.getElementById('time').innerHTML="Time : "+h+":"+m+":"+s;
t=setTimeout(function(){startTime()},1000);
}

function checkTime(i)
{
if (i<10)
  {
  i="0" + i;
  }
return i;
}

</script>
</head>

<body>

<?php
include("header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">
<div class="span1"></div>
<div class="span10" id="home-wrapper">
<h4 id="home-update-head">Quizzes</h4>

<table class="table table-bordered table-striped">

<?php
foreach($quizs as $k=>$quiz)
{
if($quiz['TimeLimit']>5000000)
{
$quiz['TimeLimit']="No Time Limit";
}
else
{
$quiz['TimeLimit']=$quiz['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/quiz/view/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td>';
echo '<td>'.$quiz['TimeLimit'].'</td>';

if($quiz['Status']=='active')
{
echo '<td><span class="label label-success">Active</span></td>';
}
else if($quiz['Status']=='upcoming')
{
echo '<td><span class="label label-warning">Upcoming</span></td>';
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
