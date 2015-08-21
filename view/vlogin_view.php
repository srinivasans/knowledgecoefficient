<?php
include("controller/utility_class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Knowledge Coefficient</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" media="screen" href="http://localhost/jee/css/bootstrap.min.css"/>
<link rel="stylesheet" media="screen" href="http://localhost/jee/css/style.css"/>
<link rel="stylesheet" media="screen" href="http://localhost/jee/css/bootstrap-responsive.min.css"/>
<script type="text/javascript" src="http://localhost/jee/js/jquery.js"></script>
<script type="text/javascript" src="http://localhost/jee/js/bootstrap.js"></script>

</head>

<body>





<div class="container-fluid" id="container-wrap">

<?php
include("login_header.php");
?>
<div class="container-fluid" id="signup-container">
<div class="row-fluid">
<div class="span3"></div>


<div class="span6" id="login-box">
<form class="form-horizontal" name="login_form" method="POST" action="/jee/login/vlogin">
<legend>Login</legend>
  <?php
  Utility::controlGroupText("email","email","email","Email","Email");
  Utility::controlGroupText("password","password","password","Password","Password");
  Utility::controlGroupSubmit("submit","submit","submit","Submit","Submit",array("btn-success"));
  ?>
  
  
  
</form>
</div>

<div class="span3">
</div>


</div>
</div>

<?php
include("footer.php");
?>

</div>



</body>