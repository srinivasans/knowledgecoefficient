<?php
include("controller/utility_class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $quiz['Name']; ?> - Knowledge Coefficient</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap.min.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/style.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap-responsive.min.css"/>
<script type="text/javascript" src="/jee/js/jquery.js"></script>
<script type="text/javascript" src="/jee/js/bootstrap.js"></script>
</head>

<body class="embed">
<?php
include("header_embed.php");
?>

<div class="container-fluid" id="container-wrap">

<table class="table table-striped table-bordered">
<?php
$html='';
$html.='<tr><td>Quiz Name</td><td>Your Marks</td><td>Total Marks</td><td>Percentage</td><td>Average</td><td>Highest</td><td>Analytics</td></tr>';
$html.='<tr class="success">';
if(isset($evaluation))
{
$html.='<td>'.$evaluation['Name'].'</td>';
$html.='<td>'.$evaluation['Marks'].'</td>';
$html.='<td>'.$evaluation['TotalMarks'].'</td>';
$html.='<td>'.$evaluation['Percentage'].'</td>';
$html.='<td>'.$evaluation['Average'].'</td>';
$html.='<td>'.$evaluation['Highest'].'</td>';
$html.='<td><a class="btn btn-warning" href="/jee/quiz/analytics/'.$quiz['ID'].'/embed">View Analytics</a></td>';
}
$html.='</tr>';
echo $html;
?>
</table>

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  The Answers Can be Viewed once the Quiz is Over ..
 
</div>


</body>
<?php
include("footer_mini.php");
?>

</html>