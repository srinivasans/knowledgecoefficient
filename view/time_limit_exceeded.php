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
<legend>Test - <?php echo $test_details['Name']; ?></legend>

<div class="description">
Time Limit Exceeded
</div>

<div class="description">
<a href="/jee/test/evaluate/<?php echo $test_details['ID'] ?>" class="btn btn-success">View Evaluation</a>
</div>

</div>
<div class="span2"></div>
</div>
</div>

</body>

</html>