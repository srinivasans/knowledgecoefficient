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
<script type="text/javascript" src="/jee/js/jquery.form.js"></script>
<script type="text/javascript" src="/jee/js/bootstrap.js"></script>

</head>

<body class="exam">
<?php
include("header.php");
?>
<div class="container-fluid" id="container-wrap">

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
			if(isset($analytics['question'][$question['ID']]['correct']))
			{
			echo '<table class="table table-striped table-bordered">';
			echo '<tr><th>'.($analytics['question'][$question['ID']]['correct']*100/$total_takers).'% answered correct</th><th>'.($analytics['question'][$question['ID']]['wrong']*100/$total_takers).'% answered wrong</th></tr>';
			echo '</table>';
			}
					
			Utility::CloseTable();	
			
			echo '<hr/>';
			
			
			}				
			Utility::CloseGroup();
			
			}
		}
		if(isset($analytics['tag']))
			{
			echo '<table class="table table-striped table-bordered">';
			echo '<th colspan="3">Topic-wise Analysis</th>';
			foreach($analytics['tag'] as $k=>$value)
			{
			echo '<tr><th>'.$k.'</td><td>'.($value['correct']*100/$total_takers).'% answered correct</th><td>'.($value['wrong']*100/$total_takers).'% answered wrong</td></tr>';
			}
			echo '</table>';
			}
			
			Utility::CloseQuestion();
			
			//Test Nav Pills
			//Test Nav Pills
			
			echo '<div class="span1" id="pills-wrap">';
			echo '<div class="nav nav-pills nav-stacked" id="pills">';
			foreach($section_groups[$key] as $k=>$gid)
			{
				if(isset($groups[$gid]) && isset($questions[$gid]))
				{
					foreach($questions[$gid] as $no=>$question)
					{
					$selected="";
					if(isset($question['Set']) && count($question['Set'])>0 && $question['Set'][0]!="")
					{
					$selected="active";
					}
					echo '<li class="'.$selected.'" id="pill-'.$question['Qno'].'"><a href="#'.$question['Qno'].'">Q.'.$question['Qno'].'</a></li>';	
					}
				}
			}
			echo '</div>';
			echo '</div>';
			
			
			
			
	echo '</div>';
	echo '</div>';
	
	}
	echo $html;
	
	?>
	

	<?php
		
	?>
        
    
	</div>

  
  
</div>

</div>



</body>
<?php
include("footer_mini.php");
?>

</html>