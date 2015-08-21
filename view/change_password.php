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
include("header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">
<div class="span1"></div>
<div class="span10" id="home-wrapper">
<h4 id="home-update-head">Change Password</h4>
<form name="profile_edit" action="/jee/profile/save_change_password" method="POST">
<table class="table table-bordered table-striped">
<tr><td>Old Password</td><td><input type="password" name="password" ></td></tr>
<tr><td>New Password</td><td><input type="password" name="new_password" ></td></tr>
<tr><td>Confirm New Password</td><td><input type="password" name="confirm_new_password"></td></tr>
<tr><td>Submit</td><td><input type="submit" name="submit" value="Save" class="btn btn-primary"></td></tr>
</table>
</form>
</div>
</div>
</div>

<?php
include("footer_static.php");
?>

</body>
</html>
