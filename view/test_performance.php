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

<body onload="startTime()">

<?php
include("header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">
<div class="span1"></div>
<div class="span10" id="home-wrapper">
<h4 id="home-update-head">Test Performances</h4>

<table class="table table-bordered table-striped">

<?php 
				if(count($performance)==0)
				{
				echo '<tr><td>-- Not attempted any --</td></tr>';
				}
				foreach($performance as $key=>$quiz)
				{
				echo '<tr><td><a href="/jee/test/analytics/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td><td>'.$quiz['Percentage'].'%</td></tr>';
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
