<?php
include("controller/utility_class.php");
?>

<!DOCTYPE html>
<html class="index" lang="en">
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

<body class="index">
<div class="container-fluid" id="container-wrap">

<?php
if(isset($_GET['data']) && $_GET['data']=='signin_success')
{
?>
<div class="alert alert-success alert-fixed" style="text-align:center">Signup successful ! You can login Now . <br/><span class="small">Verification mail sent to your mail address (Account will be deactivated if not verified for 2 months)</span></div>
<?php
}
?>

<?php
//include("login_header.php");
?>


<div class="container-fluid" id="login-container">

<div class="row-fluid">
<div class="span3"></div>
<div class="span6" id="main-title">Knowledge Coefficient</div>
<div class="span3"></div>
</div>

<div class="row-fluid">
<div class="span1"></div>
<div class="span10" id="sub-title">The Online-Examination Destination</div>
<div class="span1"></div>
</div>

<div class="row-fluid">
<!---->
<div class="span3"></div>

<div class="span6" id="main-wrap">
<div class="forms">
<div class="form_wrap">
<div class="tabs_wrap">
<div class="tab" id="login_tab">Login</div>
<div class="tab inactive_tab" id="signup_tab">SignUp</div>
</div>
<div class="login" id="login">
<table class="login_tab">
<form method="POST" action="/jee/login/vlogin&callback=<?php echo $callback; ?>">
<tr>
<td>
<input type="email" class="text_input" name="email" placeholder="E-mail"/>
</td>
</tr>
<tr>
<td>
<input type="password" class="text_input" name="password" placeholder="Password"/>
</td>
</tr>
<tr>
<td>

<a href="/jee/profile/forgot_password">
<div class="forgot_pass">
Forgot Password ?
</div>
</a>
<input type="submit" class="btn btn-primary" name="submit" value="Sign in" style="float:right;"/>
</td>
</tr>

</form>
</table>
</div>

<div class="signup" id="signup">
<table class="signup_tab">
<form method="POST" action="/jee/signup/vsignup" id="signup_form">
<tr>
<td>
<input type="text" name="firstname" class="text_input" placeholder="First Name"/>
</td>
</tr>
<tr>
<td>
<input type="text" name="lastname" class="text_input" placeholder="Last Name"/>
</td>
</tr>
<tr>
<td>
<input type="email" name="email" class="text_input" placeholder="E-mail"/>
</td>
</tr>
<tr>
<td>
<input type="password" name="password" class="text_input" placeholder="Password"/>
</td>
</tr>
<tr>
<td>
<input type="password" name="cpassword" class="text_input" placeholder="Confirm Password"/>
</td>
</tr>
<tr>
<td>
<input type="text" name="institute" class="text_input" placeholder="Institute"/>
</td>
</tr>
<tr>
<td>
<input type="submit" name="submit" class="btn btn-warning" value="Sign Up !"/>
</td>
</tr>
</form>
</table>
</div>

<div class="fb-login"><a href="/jee/?key=signup&action=facebook_login&callback=<?php echo $callback; ?>"><img class="connect-img" src="/jee/img/connect.png"/></a></div>

</div>
</div>
</div>

<div class="span3"></div>
</div>
<!--
<div class="span1"></div>

<div class="span5" id="login-box" style="background:rgba(255,255,255,0.8)">
<form class="form-horizontal" name="login_form" method="POST" action="login/vlogin">
<legend style="border-bottom: 1px solid #B8B8B8;">Login</legend>
  <?php
  Utility::controlGroupText("email","email","email","Email","Email");
  Utility::controlGroupText("password","password","password","Password","Password");
  Utility::controlGroupSubmit("submit","submit","submit","Submit","Submit",array("btn-success"));
  ?>
  
  
  
</form>
</div>

<div class="span5" id="login-box" style="background:rgba(255,255,255,0.8)">
<form class="form-horizontal" name="signup_form" method="POST" action="signup/vsignup">
  <legend>Sign Up</legend>
  <?php
  Utility::controlGroupText("firstname","firstname","text","First Name","First Name");
  Utility::controlGroupText("lastname","lastname","text","Last Name","Last Name");
  Utility::controlGroupText("email","email","email","Email","Email");
  Utility::controlGroupText("password","password","password","Password","Password");
  Utility::controlGroupText("cpassword","cpassword","password","Confirm Password","Confirm Password");
  Utility::controlGroupText("institute","institute","text","Institute","Institute");
  Utility::controlGroupSubmit("submit","submit","submit","Submit","Submit",array("btn-primary"));
  ?>
</form>
</div>

<div class="span1">
</div>
</div>
</div>-->

<?php
include("footer_mini.php");
?>

</div>



</body>
</html>