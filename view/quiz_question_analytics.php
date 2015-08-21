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
<script type="text/javascript">

</script>

</head>

<body class="exam">
<?php
include("header.php");
?>
<div class="container-fluid" id="container-wrap">

<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  
  <div class="tab-content" id="tab-content">
	<?php

	
	
	echo '<div class="tab-pane active" id="tab">';
			
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
			if(isset($analytics['question'][$question['ID']]['correct']))
			{
			echo '<table class="table table-striped table-bordered">';
			echo '<tr><th>'.($analytics['question'][$question['ID']]['correct']*100/$total_takers).'% answered correct</th><th>'.($analytics['question'][$question['ID']]['wrong']*100/$total_takers).'% answered wrong</th></tr>';
			echo '</table>';
			}
			Utility::CloseTable();	
			
			echo '<hr/>';
			
			
			}

			if(isset($analytics['tag']))
			{
			echo '<table class="table table-striped table-bordered">';
			echo '<th colspan="3">Topic-wise Analysis</th>';
			foreach($analytics['tag'] as $key=>$value)
			{
			echo '<tr><th>'.$key.'</td><td>'.($value['correct']*100/$total_takers).'% answered correct</th><td>'.($value['wrong']*100/$total_takers).'% answered wrong</td></tr>';
			}
			echo '</table>';
			}
			
			Utility::CloseQuestion();
			
			//Test Nav Pills
			//Test Nav Pills
			
			echo '<div class="span1" id="pills-wrap">';
			echo '<div class="nav nav-pills nav-stacked" id="pills">';
					foreach($questions as $no=>$question)
					{
					$selected="";
					if(isset($question['Set']) && count($question['Set'])>0 && $question['Set'][0]!="")
					{
					$selected="active";
					}
					echo '<li class="'.$selected.'" id="pill-'.$question['Qno'].'"><a href="#'.$question['Qno'].'">Q.'.$question['Qno'].'</a></li>';	
					}
			echo '</div>';
			echo '</div>';
			
			
			
			
	echo '</div>';
	echo '</div>';
	
	
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