<?php
include("../controller/utility_class.php");
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
$('document').ready(function(){
$('#elem').tooltip('show');
});
</script>
</head>

<body>
<?php
$headMenu=array("username"=>"Srinivasan","active"=>"test");
include("header.php");
?>
<div class="container-fluid" id="container-wrap">

<iframe src="http://localhost/jee/quiz/exam/3/embed" width="900px" height="550px" frameborder="0" id="iframe" />

</div>
	
</body>
	
	<?php
  include("footer_static.php");
  ?>