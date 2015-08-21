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
<link rel="stylesheet" media="screen" href="/jee/css/blogstyle.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap-responsive.min.css"/>
<script type="text/javascript" src="/jee/js/jquery.js"></script>
<script type="text/javascript" src="/jee/js/bootstrap.js"></script>

<script type="text/javascript">
$('document').ready(function(){
$('#elem').tooltip('show');
});
</script>

<script type="text/javascript">
$('document').ready(function(){
$('#login_tab').click(function(){
$('#signup').hide();
$('#signup_tab').addClass('inactive_tab');
$('#login_tab').removeClass('inactive_tab');
$('#login').show();
});

$('#signup_tab').click(function(){
$('#login').hide();
$('#login_tab').addClass('inactive_tab');
$('#signup_tab').removeClass('inactive_tab');
$('#signup').show();
});
});
</script>

</head>

<body class="embed">

<div class="container-fluid" style="overflow-x:hidden;margin:0px;padding:0px">

<?php
include("header_embed.php");
?>


<div class="container-fluid">

<div class="row-fluid">
<div class="span3"></div>
<div class="span6">
<div id="main-title-embed">
Knowledge Coefficient
</div>
<div style="text-align:center;padding-top:10px" id="sub-title">The Online-Examination Destination</div>

<div style="text-align:center;padding-top:10px">Visit us at : <a href="/jee" target="_blank">www.knowledgecoefficient.com</a></div>
</div>

<div class="span3"></div>
</div>


</div>



</body>