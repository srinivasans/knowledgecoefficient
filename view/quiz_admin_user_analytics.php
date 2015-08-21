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
<?php
$arTags=array("Tag"=>"string","Your Marks"=>"number","Average"=>"number","Total Marks"=>"number");

$tags=array();
foreach($analytics['tag'] as $k=>$v)
{
$tags[$k]["Marks"]=$v['Marks'];
$tags[$k]["TotalMarks"]=$v['TotalMarks'];
$tags[$k]["Average"]=$v['Average'];
}
$num=count($tags);
drawGraph("Topic-wise Marks",$arTags,$tags,array("Marks","Average","TotalMarks"),"tag_marks",'BarChart',900,100+$num*100);

?>


</head>

<body>
<?php
include("header.php");
?>

<div class="container-fluid" style="margin-top:50px">

<div class="row-fluid" >
<div class="span1"></div>
<div class="span10" id="create-form">

<legend>Analytics - <?php echo $quiz_name." - ".$client_name; ?></legend>

<table class="table table-striped">
<?php
$html='';
$html.='<tr><td>Quiz Name</td><td>Your Marks</td><td>Total Marks</td><td>Percentage</td><td>Average</td><td>Highest</td><td>Rank</td><td>Percentile</td></tr>';
$html.='<tr>';
if(isset($evaluation))
{
$html.='<td>'.$quiz_name.'</td>';
$html.='<td>'.$evaluation['Marks'].'</td>';
$html.='<td>'.$evaluation['TotalMarks'].'</td>';
$html.='<td>'.$evaluation['Percentage'].'</td>';
$html.='<td>'.$evaluation['Average'].'</td>';
$html.='<td>'.$evaluation['Highest'].'</td>';
$html.='<td>'.$evaluation['Rank'].'</td>';
$html.='<td>'.$evaluation['Percentile'].'</td>';
}
$html.='</tr>';
echo $html;
?>
</table>

<div id="section_marks" class="graph"></div>
<div id="group_marks" class="graph"></div>
<?php
foreach($tags as $key=>$val)
{
echo '<div id="tag_marks" class="graph"></div>';
}
?>

</div>
</div>
</div>



