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
<h4 id="home-update-head">Profile</h4>

<table class="table table-bordered table-striped">
<tr><th colspan="2"><?php echo $user_details['FirstName']." ".$user_details['LastName']; ?></th></tr>
<tr><td>User Type</td><td><?php echo $user_details['AcctType']; ?></td></tr>
<tr><td>Email</td><td><?php echo $user_details['Email']; ?></td></tr>
<tr><td>Class</td><td><?php echo $user_details['Class']; ?></td></tr>
<tr><td>Institute</td><td><?php echo $user_details['Institute']; ?></td></tr>
</table>

</div>
</div>
</div>

<?php
include("footer_static.php");
?>

</body>
</html>
