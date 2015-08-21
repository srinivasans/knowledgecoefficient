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


<div class="row-fluid" >
<div class="span2"></div>
<div class="span9">
<form class="form-horizontal" method="POST" action="/jee/test/edit_test">
<legend id="head"><div class="alert alert-success"><img src="/jee/img/loader.gif"/> <b> Quiz Over .. Responses Saved</b>  Redirecting to Evaluation Page...</div>
</legend>

<div>

</div>


</div>
</div>
</div>

</body>

</html>
