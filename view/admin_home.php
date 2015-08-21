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
  now=new Date(data.dateString);
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

<body onload="startTime()">

<?php
// $name to be declared

include("header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">

<div class="span3" id="home-updates">
<div class="row-fluid" id="updates">
<div class="span12">
<h4 id="home-update-head">Updates<h4>

<p>
<ul class="unstyled">
<?php
foreach($updates as $k=>$update)
{
echo '<li class="update" align="justify">';
echo '<i class="icon-align-justify" style="vertical-align:top;padding:0px;"></i>';
echo '<a href="'.$update['Link'].'" style="vertical-align:top"><small>'.$update['Heading'].'</small></a>';
echo '</li>';
}
?>
</ul>
</p>

</div>

</div>

<!--
Trending Tests
-->

<div class="row-fluid" id="trending">
<div class="span12">
<h4 id="home-update-head"><span class="label label-warning">Trending Tests</span><h4>

<p>
<ul class="unstyled">
<?php
foreach($tests_trending as $k=>$trend)
{
echo '<li class="update" align="justify">';
echo '<a href="/jee/test/start/'.$trend['ID'].'" style="vertical-align:top"><small>'.$trend['Name'].'</small></a>';
echo '</li>';
}
?>
</ul>
</p>

</div>

</div>

<!-- Trending -->


<!--
Trending Tests
-->

<div class="row-fluid" id="trending">
<div class="span12">
<h4 id="home-update-head"><span class="label label-warning">Trending Quizzes</span><h4>

<p>
<ul class="unstyled">
<?php
foreach($quizs_trending as $k=>$trend)
{
echo '<li class="update" align="justify">';
echo '<a href="/jee/quiz/start/'.$trend['ID'].'" style="vertical-align:top"><small>'.$trend['Name'].'</small></a>';
echo '</li>';
}
?>
</ul>
</p>

</div>

</div>

<!-- Trending -->


</div>

<div class="span9" id="home-wrapper">
<h4 id="home-update-head">Welcome <?php echo $name; ?> ! <div class="pull-right" id="time"></div></h4>

<div class="row-fluid">

<div class="span5">
<h4 id="home-inner-head">Tests Created  <a href="test/create" class="btn-success small-btn"><i class="icon-plus-sign"></i>Create Test</a></h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">
                <?php 
				foreach($tests_created as $key=>$test)
				{
				echo '<tr><td><a href="/jee/test/view/'.$test['ID'].'">'.$test['Name'].'</a></td><td>'.$test['Date'].'</td><td>'.date("G:i T",strtotime($test['Time'])).'</td></tr>';
				}
				?>
				<div></div>
</table>
<div class="pull-right"><a href="/jee/test/created" > ... View All</a></div>

</div>

<div class="span1"></div>

<div class="span5">
<h4 id="home-inner-head">Created Tests Analytics</h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">

              <?php 
				if(count($tests_onboard)==0)
				{
				echo '<tr><td>-- No Quiz --</td></tr>';
				}
				foreach($tests_onboard as $key=>$test)
				{
				echo '<tr><td><a href="/jee/test/admin_analytics/'.$test['ID'].'">'.$test['Name'].'</a></tr>';
				}
				?>
</table>
<div class="pull-right"><a href="/jee/test/created_analytics"> ... View All</a></div>

</div>

</div>

<div class="row-fluid">
<div class="span5">
<h4 id="home-inner-head"> On-Board Tests</h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">

              <?php 
				if(count($tests_onboard)==0)
				{
				echo '<tr><td>-- No Test --</td></tr>';
				}
				foreach($tests_onboard as $key=>$test)
				{
				echo '<tr><td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td><td>'.$test['Date'].'</td><td>'.date("G:i T",strtotime($test['Time'])).'</td></tr>';
				}
				?>
</table>
<div class="pull-right"><a href="/jee/test/created_onboard"> ... View All</a></div>

</div>
</div>

<div class="row-fluid">

<div class="span5">
<h4 id="home-inner-head">Quizzes Created  <a href="quiz/create" class="btn-success small-btn"><i class="icon-plus-sign"></i>Create Quiz</a></h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">
                <?php 
				foreach($quizs_created as $key=>$quiz)
				{
				echo '<tr><td><a href="/jee/quiz/view/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td><td>'.$quiz['Date'].'</td><td>'.date("G:i T",strtotime($quiz['Time'])).'</td></tr>';
				}
				if(count($quizs_created)==0)
				{
				echo '<tr><td>-- No Quiz --</td></tr>';
				}
				?>
				<div></div>
</table>
<div class="pull-right"><a href="/jee/quiz/created"> ... View All</a></div>

</div>

<div class="span1"></div>

<div class="span5">
<h4 id="home-inner-head">Created Quizzes Analytics</h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">
	<?php 
				if(count($quizs_onboard)==0)
				{
				echo '<tr><td>-- No Quiz --</td></tr>';
				}
				foreach($quizs_onboard as $key=>$quiz)
				{
				echo '<tr><td><a href="/jee/quiz/admin_analytics/'.$quiz['ID'].'">'.$quiz['Name'].'</a></tr>';
				}
				?>
</table>
<div class="pull-right"><a href="/jee/quiz/created_analytics"> ... View All</a></div>

</div>

</div>

<div class="row-fluid">
<div class="span5">
<h4 id="home-inner-head"> On-Board Quizzes</h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">

              <?php 
				if(count($quizs_onboard)==0)
				{
				echo '<tr><td>-- No Quiz --</td></tr>';
				}
				foreach($quizs_onboard as $key=>$quiz)
				{
				echo '<tr><td><a href="/jee/quiz/start/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td><td>'.$quiz['Date'].'</td><td>'.date("G:i T",strtotime($quiz['Time'])).'</td></tr>';
				}
				?>
</table>
<div class="pull-right"><a href="/jee/quiz/created_onboard"> ... View All</a></div>

</div>
</div>



<div class="row-fluid">

<div class="span5">
<h4 id="home-inner-head"><img src="/jee/img/current.png" style="vertical-align:middle"/> Current Tests </h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">

              <?php 
				if(count($tests_active)==0)
				{
				echo '<tr><td>-- No Current Tests --</td></tr>';
				}
				foreach($tests_active as $key=>$test)
				{
				echo '<tr><td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td><td>'.$test['Date'].'</td><td>'.date("G:i T",strtotime($test['Time'])).'</td></tr>';
				}
			 ?>

</table>
<div class="pull-right"><a href="/jee/test/active"> ... More Tests</a></div>
</div>

<div class="span1"></div>

<div class="span5">
<h4 id="home-inner-head"><img src="/jee/img/analytics.png" style="vertical-align:middle"/>Test Performances</h4>

<table class="table table-striped table-bordered table-hover" id="table-margin-bottom-less">
			<?php 
				if(count($performance)==0)
				{
				echo '<tr><td>-- Not attempted any --</td></tr>';
				}
				foreach($performance as $key=>$test)
				{
				echo '<tr><td><a href="/jee/test/analytics/'.$test['ID'].'">'.$test['Name'].'</a></td><td>'.$test['Percentage'].'%</td></tr>';
				}
			 ?>
</table>
<div class="pull-right"><a href="/jee/test/performance"> ... View All</a></div>

</div>

</div>



<div class="row-fluid">

<div class="span5">
<h4 id="home-inner-head"><img src="/jee/img/current.png" style="vertical-align:middle"/> Current Quizzes </h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">

              <?php 
				if(count($quizs_active)==0)
				{
				echo '<tr><td>-- No Current Tests --</td></tr>';
				}
				foreach($quizs_active as $key=>$quiz)
				{
				echo '<tr><td><a href="/jee/quiz/start/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td><td>'.$quiz['Date'].'</td><td>'.date("G:i T",strtotime($quiz['Time'])).'</td></tr>';
				}
			 ?>

</table>
<div class="pull-right"><a href="/jee/quiz/active"> ... More Quizzes</a></div>
</div>

<div class="span1"></div>

<div class="span5">
<h4 id="home-inner-head"><img src="/jee/img/analytics.png" style="vertical-align:middle"/>Quiz Performances</h4>

<table class="table table-striped table-bordered table-hover" id="table-margin-bottom-less">
			<?php 
				if(count($quiz_performance)==0)
				{
				echo '<tr><td>-- Not attempted any --</td></tr>';
				}
				foreach($quiz_performance as $key=>$quiz)
				{
				echo '<tr><td><a href="/jee/quiz/analytics/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td><td>'.$quiz['Percentage'].'%</td></tr>';
				}
			 ?>
</table>
<div class="pull-right"><a href="/jee/quiz/performance"> ... View All</a></div>

</div>

</div>



<div class="row-fluid">
<div class="span5">
<h4 id="home-inner-head"><img src="/jee/img/upcoming.png" style="vertical-align:middle"/> Upcoming Tests </h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">

               <?php 
				if(count($tests_upcoming)==0)
				{
				echo '<tr><td>-- No Upcoming Tests --</td></tr>';
				}
				foreach($tests_upcoming as $key=>$test)
				{
				echo '<tr><td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td><td>'.$test['Date'].'</td><td>'.date("G:i T",strtotime($test['Time'])).'</td></tr>';
				}
			 ?>
</table>
<div class="pull-right"><a href="/jee/test/upcoming"> ... More Tests</a></div>

</div>

<div class="span1"></div>

<div class="span5">
<h4 id="home-inner-head"><img src="/jee/img/upcoming.png" style="vertical-align:middle"/> Upcoming Quizzes </h4>

<table id="table-margin-bottom-less" class="table table-striped table-bordered table-hover">

               <?php 
				if(count($quizs_upcoming)==0)
				{
				echo '<tr><td>-- No Upcoming Quizs --</td></tr>';
				}
				foreach($quizs_upcoming as $key=>$test)
				{
				echo '<tr><td><a href="/jee/quiz/start/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td><td>'.$quiz['Date'].'</td><td>'.date("G:i T",strtotime($quiz['Time'])).'</td></tr>';
				}
			 ?>
</table>
<div class="pull-right"><a href="/jee/quiz/upcoming"> ... More Quizzes</a></div>

</div>

</div>




</div>
</div>
</div>

<?php
include("footer_static.php");
?>

</body>
</html>
