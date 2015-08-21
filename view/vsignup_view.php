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

<script type="text/javascript">
$('document').ready(function(){
$('#elem').tooltip('show');
});


function validate_fname()
{
var fname=$('#firstname').attr('value');
if(fname.length>=3)
{
$('#firstname_validate').removeClass("validate_error");
$('#firstname_validate').addClass("validate_cleared");
$('#firstname_validate').html("First Name looks Great");

}
else
{
$('#firstname_validate').removeClass("validate_cleared");
$('#firstname_validate').addClass("validate_error");
$('#firstname_validate').html("Spell your First name Completely !");
}
}

function validate_lname()
{
var fname=$('#lastname').attr('value');
if(fname.length>=3)
{
$('#lastname_validate').removeClass("validate_error");
$('#lastname_validate').addClass("validate_cleared");
$('#lastname_validate').html("Last Name looks Great");
}
else
{
$('#lastname_validate').removeClass("validate_cleared");
$('#lastname_validate').addClass("validate_error");
$('#lastname_validate').html("Spell your Last name Completely !");
}
}

function validate_email()
{
var email=$('#email').attr('value');
var re = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if( re.test(email))
	{
	$('#email_validate').removeClass("validate_error");
	$('#email_validate').addClass("validate_cleared");
	$('#email_validate').html("We'll_Mail_you_a_Confirmation");
	}	
	else
	{
	$('#email_validate').removeClass("validate_cleared");
	$('#email_validate').addClass("validate_error");
	$('#email_validate').html("Invalid_Format");
	}
}

function validate_pwd()
{
var pwd=$('#password').attr('value');
if(pwd.length<=6)
{
$('#password_validate').removeClass("validate_cleared");
$('#password_validate').addClass("validate_error");
$('#password_validate').html("Password too Short");
}
else
{
var patt1=/[a-z]+/;
var patt2=/[A-Z]+/;
var patt5=/[a-zA-Z]+/;
var patt6=/[a-z0-9]+/;
var patt7=/[A-Z0-9]+/;
var patt3=/[0-9]+/;
var patt4=/\W+/;
var patt8=/[0-9a-zA-Z\W]+/;

var match1=patt1.test(pwd);
var match2=patt2.test(pwd);
var match3=patt3.test(pwd);
var match4=patt4.test(pwd);
var match5=patt5.test(pwd);
var match6=patt6.test(pwd);
var match7=patt7.test(pwd);
var match8=patt8.test(pwd);

if((match1&&match8&&match4) || (match2&&match8&&match4) && (match3&&match8&&match4) && (match4&&match8))
{
	$('#password_validate').removeClass("validate_error");
$('#password_validate').addClass("validate_cleared");
	$('#password_validate').html( "Password is Super Secure !!!");

}
else if(((match1&&match5) || (match2&&match5) || (match3&&match7) ||(match2&&match7) || (match3&&match6) || (match1&&match6)) || pwd.length>20)
{
	$('#password_validate').removeClass("validate_error");
$('#password_validate').addClass("validate_cleared");
	$('#password_validate').html( "Password is Perfect ! ");

}
else if((match2&&match5) || (match3&&match7) ||(match2&&match7) || (match3&&match6))
{
$('#password_validate').removeClass("validate_error");
$('#password_validate').addClass("validate_cleared");
	$('#password_validate').html( "Password is Okay");

}
else if(match1 || match2 || match3 ||match4)
{
$('#password_validate').removeClass("validate_error");
$('#password_validate').addClass("validate_cleared");
	$('#password_validate').html("Password can be More Secure ");
 
}
}
}

function validate_confirm_pwd()
{
var confirm_pwd=$('#cpassword').attr('value');
var pwd=$('#password').attr('value');
if(confirm_pwd==pwd)
{
$('#cpassword_validate').removeClass("validate_error");
$('#cpassword_validate').addClass("validate_cleared");
$('#cpassword_validate').html("Password Matched"); 
}
else
{
$('#cpassword_validate').removeClass("validate_cleared");
$('#cpassword_validate').addClass("validate_error");
	$('#cpassword_validate').html("Password doesn't Match");
}

}


$('document').ready(function(){
$('#firstname').keyup(validate_fname);
$('#lastname').keyup(validate_lname);
$('#email').keyup(validate_email);
$('#password').keyup(validate_pwd);
$('#cpassword').keyup(validate_confirm_pwd);
});
</script>
</head>

<body>





<div class="container-fluid" id="container-wrap">

<?php
if(isset($err))
{
?>
<div class="alert alert-error" style="text-align:center">
  <?php echo $err; ?>
</div>
<?php
}
?>

<?php
include("login_header.php");
?>
<div class="container-fluid" id="signup-container">
<div class="row-fluid">
<div class="span1"></div>


<div class="span10" id="login-box">
<form class="form-horizontal" name="signup_form" method="POST" action="../signup/vsignup">
  <legend>Sign Up</legend>
  <?php
  if(!isset($firstname) || !isset($lastname) || !isset($email) || !isset($institute))
  {
  $firstname="";
  $lastname="";
  $email="";
  $institute="";
  }
  Utility::controlGroupText("firstname","firstname","text","First Name","First Name",NULL,$firstname);
  Utility::controlGroupText("lastname","lastname","text","Last Name","Last Name",NULL,$lastname);
  Utility::controlGroupText("email","email","email","Email","Email",NULL,$email);
  Utility::controlGroupText("password","password","password","Password","Password");
  Utility::controlGroupText("cpassword","cpassword","password","Confirm Password","Confirm Password");
  Utility::controlGroupText("institute","institute","text","Institute","Institute",NULL,$institute);
  Utility::controlGroupSubmit("submit","submit","submit","Submit","Submit",array("btn-primary"));
  ?>
</form>
</div>

<div class="span1">
</div>
</div>
</div>

<?php
include("footer.php");
?>

</div>



</body>