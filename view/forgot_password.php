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
</head>

<body>

<?php
// $name to be declared
include("login_header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">
<div class="span1"></div>
<div class="span10" id="home-wrapper">
<h4 id="home-update-head">Forgot Password</a></h4>

<table class="table table-bordered table-striped">
<form action="/jee/profile/send_forgot_mail" method="POST">
<tr><td>Email</td><td><input type="text" name="email" id="email" /></td></tr>
<tr><td>Captcha : <img src="/jee/img/captcha.php"/></td><td><input type="text" name="captcha" /></td></tr>
<tr><td>Submit</td><td><input type="submit" name="submit" class="btn btn-success" /></td></tr>
</form>
</table>

</div>
</div>
</div>

<?php
include("footer_static.php");
?>

</body>
</html>
