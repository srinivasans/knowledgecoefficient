<?php
include("controller/utility_class.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $test['Name']; ?> - Knowledge Coefficient</title>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap.min.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/style.css"/>
<link rel="stylesheet" media="screen" href="/jee/css/bootstrap-responsive.min.css"/>
<script type="text/javascript" src="/jee/js/jquery.js"></script>
<script type="text/javascript" src="/jee/js/bootstrap.js"></script>
</head>

<body>
<?php
include("header.php");
?>

<div class="container-fluid" id="container-wrap">

<table class="table table-striped table-bordered">
<?php
$html='';
$html.='<tr><td>Test Name</td><td>Your Marks</td><td>Total Marks</td><td>Percentage</td><td>Average</td><td>Highest</td><td>Analytics</td></tr>';
$html.='<tr class="success">';
if(isset($evaluation))
{
$html.='<td>'.$evaluation['Name'].'</td>';
$html.='<td>'.$evaluation['Marks'].'</td>';
$html.='<td>'.$evaluation['TotalMarks'].'</td>';
$html.='<td>'.$evaluation['Percentage'].'</td>';
$html.='<td>'.$evaluation['Average'].'</td>';
$html.='<td>'.$evaluation['Highest'].'</td>';
$html.='<td><a class="btn btn-warning" href="/jee/test/analytics/'.$test['ID'].'">View Analytics</a></td>';
}
$html.='</tr>';
echo $html;
?>
</table>

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  
  <ul class="nav nav-tabs" id="tabs-left">
	<?php
	$html='';
	$j=0;
	foreach($sections as $key=>$value)
	{
	$html.='<li ';
	if($j==0)
	{
	$html.='class="active"';
	$j++;
	}
	
	$html.='><a href="#tab'.$key.'" data-toggle="tab">'.$value.'</a></li>';
	}
	echo $html;
	
	?>
  </ul>
  <div class="tab-content" id="tab-content">
  
	<?php
	$limit=$test['TimeLimit'];
	if(isset($register['TimeStarted']) && $register['TimeStarted'])
	{
	$time_started=strtotime($register['TimeStarted']);
	}
	else
	{
	$time_started=strtotime('now');
	}
	$limit=$time_started+strtotime($test['TimeLimit'])-strtotime($test['Time']);
	if($limit-strtotime($test['TimeEnd'])>0)
	{
	$limit=strtotime($test['TimeEnd']);
	}
	$limit=date('Y-m-d H:i:s',$limit);
	Utility::inputHidden("time_start","time_start",$test['Time']);
	Utility::inputHidden("time_limit","time_limit",$limit);
	Utility::inputHidden("test_id","test_id",$test['ID']);
	
	$html='';
	$i1=0;
	foreach($sections as $key=>$value)
	{
	if($i1==0)
	{
	$active="active";
	}
	else
	{
	$active="";
	}
	$i1++;
	
	
	echo '<div class="tab-pane '.$active.'" id="tab'.$key.'">';
			
			Utility::OpenQuestionEvaluation();
			Utility::AddSectionHead($value);
		foreach($section_groups[$key] as $k=>$gid)
		{
			if(isset($groups[$gid]) && isset($questions[$gid]))
			{
			Utility::OpenGroup($groups[$gid]['Name']);
			foreach($questions[$gid] as $no=>$question)
			{
			if(!isset($options[$question['ID']]))
			{
			$options[$question['ID']]=array();
			}
			Utility::AddQuestion($question['Qno'],$question['Question'],$options[$question['ID']]);
			
			
			$qnum=$question['Qno'];
			
			if($user_answers[$question['ID']]==$question['CorrectAnswer'])
			{
			$correct=1;
			}
			else if($user_answers[$question['ID']]==NULL || $user_answers[$question['ID']]=="")
			{
			$correct=2;
			}
			else
			{
			$correct=0;
			}
			
			if($question['Type']=="single"|| $question['Type']=="multiple")
			{
				Utility::StartOptionsTable();
				Utility::AddRowAns("ans","ans","Correct Answer",Utility::$Type[$question['Type']],NULL,NULL,explode(',',$question['CorrectAnswer']));
				Utility::AddRowAns("ans","ans","Your Answer",Utility::$Type[$question['Type']],NULL,NULL,explode(',',$user_answers[$question['ID']]),$correct);
			}
			else if($question['Type']=="numerical")
			{
				Utility::AddNumericalTableAns("ans","ans","Correct Answer",NULL,explode(',',$question['CorrectAnswer']));
				Utility::AddNumericalTableAns("ans","ans","Your Answer",NULL,explode(',',$user_answers[$question['ID']]),$correct);
			}
			else if($question['Type']=="match")
			{
			Utility::AddMatchTableAns("ans","ans","Correct Answer",NULL,NULL,NULL,NULL,explode(',',$question['CorrectAnswer']));
			Utility::AddMatchTableAns("ans","ans","Your Answer",NULL,NULL,NULL,NULL,explode(',',$user_answers[$question['ID']]),$correct);
			}
			
			echo '<div class="well">Solution : '.$user_solution[$question['ID']].'</div>';
			
			Utility::CloseTable();	
			
			echo '<hr/>';
			
			}				
			Utility::CloseGroup();			
			
			}
		}
			Utility::CloseQuestion();
			
	echo '</div>';
	echo '</div>';
	
	}
	echo $html;
	
	?>
	</div>


  
  
</div>

</div>



</body>
<?php
include("footer_mini.php");
?>

</html>