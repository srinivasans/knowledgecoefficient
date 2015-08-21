<div class="container-fluid">
<table class="table table-striped table-bordered">
<?php
$html='';
$html.='<tr><td>Quiz Name</td><td>User Marks</td><td>Total Marks</td><td>Percentage</td><td>Average</td><td>Highest</td></tr>';
$html.='<tr class="success">';
if(isset($evaluation))
{
$html.='<td>'.$evaluation['Name'].'</td>';
$html.='<td>'.$evaluation['Marks'].'</td>';
$html.='<td>'.$evaluation['TotalMarks'].'</td>';
$html.='<td>'.$evaluation['Percentage'].'</td>';
$html.='<td>'.$evaluation['Average'].'</td>';
$html.='<td>'.$evaluation['Highest'].'</td>';
}
$html.='</tr>';
echo $html;
?>
</table>

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  
 
  <div class="tab-content" id="full-tab-content">
  
	<?php
	$limit=$quiz['TimeLimit'];
	if(isset($register['TimeStarted']) && $register['TimeStarted'])
	{
	$time_started=strtotime($register['TimeStarted']);
	}
	else
	{
	$time_started=strtotime('now');
	}
	$limit=$time_started+strtotime($quiz['TimeLimit'])-strtotime($quiz['Time']);
	if($limit-strtotime($quiz['TimeEnd'])>0)
	{
	$limit=strtotime($quiz['TimeEnd']);
	}
	$limit=date('Y-m-d H:i:s',$limit);
	Utility::inputHidden("time_start","time_start",$quiz['Time']);
	Utility::inputHidden("time_limit","time_limit",$limit);
	Utility::inputHidden("quiz_id","quiz_id",$quiz['ID']);
	
	$html='';
	$i1=0;
	
	if($i1==0)
	{
	$active="active";
	}
	else
	{
	$active="";
	}
	$i1++;
	
	
	echo '<div class="tab-pane '.$active.'" id="tab">';
			
			Utility::OpenQuestionEvaluation();
			Utility::AddSectionHead($quiz['Name']);
		
			foreach($questions as $no=>$question)
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
				Utility::AddRowAns("ans","ans","User Answer",Utility::$Type[$question['Type']],NULL,NULL,explode(',',$user_answers[$question['ID']]),$correct);
			}
			else if($question['Type']=="numerical")
			{
				Utility::AddNumericalTableAns("ans","ans","Correct Answer",NULL,explode(',',$question['CorrectAnswer']));
				Utility::AddNumericalTableAns("ans","ans","User Answer",NULL,explode(',',$user_answers[$question['ID']]),$correct);
			}
			else if($question['Type']=="match")
			{
			Utility::AddMatchTableAns("ans","ans","Correct Answer",NULL,NULL,NULL,NULL,explode(',',$question['CorrectAnswer']));
			Utility::AddMatchTableAns("ans","ans","User Answer",NULL,NULL,NULL,NULL,explode(',',$user_answers[$question['ID']]),$correct);
			}
					
			Utility::CloseTable();	
			
			echo '<hr/>';
			
			}				
			Utility::CloseQuestion();
			
	echo '</div>';
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