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
<script type="text/javascript" src="/jee/js/jquery.form.js"></script>
<script type="text/javascript" src="/jee/js/bootstrap.js"></script>
<script type="text/javascript">
var now=new Date();
var nmon=0;
var nday=0;

$('document').ready(function(){
$('#elem').tooltip('show');
  
  $.ajax({
  dataType: "json",
  url: '/jee/js/now.php',
  success: function(data){
  now=new Date(data.dateString);
  nmon=now.getMonth();
  nday=now.getDate();
  }
});
startTimer();

});

/*Uncheckable Radio*/
(function( $ ){

    $.fn.uncheckableRadio = function() {

        return this.each(function() {
            $(this).mousedown(function() {
                $(this).data('wasChecked', this.checked);
            });

            $(this).click(function() {
                if ($(this).data('wasChecked'))
                    this.checked = false;
            });
        });

    };

})( jQuery );
/*Uncheckable Radio*/


$('document').ready(function(){
$('input[type=radio]').uncheckableRadio();
$('.answer').each(function(){$(this).click(function(){
var id=$(this).attr('id');
var id3=id;
id=id.substring(1);
if(id.indexOf('[]')!=-1)
{
id=id.substring(0,id.length-2);
}
id1="pill-"+id;
id2="head-"+id;
//document.write($(this).attr('id')+id);
if(chktest(this,id3))
{
$('#'+id1).addClass('active');
$('#'+id2).addClass('alert-success');
}
else
{
$('#'+id1).removeClass('active');
$('#'+id2).removeClass('alert-success');
}

});
});
});


function chktest(elem,id){
//document.write(id);
   if(elem.checked == true || $('input[name="'+id+'"]:checked').length > 0)
   {
      return true;
   }
   return false;
}

function autoSubmit()
{
$('#message-box').fadeIn();
$('#message').html("<b>Autosaving...</b>");
$('#answer_form').submit();
t=setTimeout(function(){autoSubmit()},300000);
}

$('document').ready(function(){
$('#answer_form').ajaxForm({
target:"#message",
success:function(){
       $('#message-box').fadeIn();
 setTimeout(function(){
       $('#message-box').fadeOut();
    }, 5000);
}
});

t=setTimeout(function(){autoSubmit()},3000);
});

function finish()
{
$('#answer_form').submit();
}

function startTimer()
{
var time_limit=$('#time_limit').attr('value');
if(time_limit)
{
var year = time_limit.substring(0,4);
var month = time_limit.substring(5,7);
//Javascript Bug Correction for Date (0-11)
month=month-1;

var day = time_limit.substring(8,10);

var hour = time_limit.substring(11,13);
var min = time_limit.substring(14,16);
var sec=time_limit.substring(17,19);
}

var time_lim=new Date(year,month,day,hour,min,sec);
var today=now;
var nmin=now.getMinutes();
var nhrs=now.getHours();
var nyrs=now.getYear();
nyrs+=1900;
var nsec=now.getSeconds();
nsec++;
now=new Date(nyrs,nmon,nday,nhrs,nmin,nsec);

var time_left=(time_lim-today);
var s=Math.floor(time_left/1000);
var m=Math.floor(s/60);
var h=Math.floor(m/60);

if(h==0 && m<=10)
{
$('#finish-head').html("Auto-Finishing in "+m+" minutes...");
$('.finish-btm').each(
function(){$(this).html("Auto-Finishing in "+m+" minutes...");});
}
if(time_left<=0)
{
$('#answer_form').submit();
}
// add a zero in front of numbers<10
s=s%60;
m=m%60;
h=h;
h=checkTime(h);
m=checkTime(m);
s=checkTime(s);

//document.getElementById('time').innerHTML=h+":"+m+":"+s;
if(document.getElementById('time'))
{
document.getElementById('time').innerHTML=h+" : "+m+" : "+s;
}
//$('#time').append(nyrs+'--'+nmon+'--'+nday+'<br/>');

t=setTimeout(function(){startTimer();},1000);
}

function checkTime(i)
{
if (i<10 && i>=0)
  {
  i="0" + i;
  }
  if(i<0)
  {
  i="00";
  }
return i;
}


function finish_submit()
{
$('#confirmModal').modal();
}

function submit_direct()
{
$('#answer_form').ajaxForm({
target:"#message",
data:{finish:1,key:876},
type:'POST',
success:function(){
       $('#message-box').fadeIn();
 setTimeout(function(){
       $('#message-box').fadeOut();
    }, 5000);
}
}).submit();
}

function showQuestion(qno)
{
$('.quizobo-wrap').removeClass('obo-shown');
$('#question-'+qno).addClass('obo-shown');
$('.quiz-pills').removeClass('showing');
$('#pill-'+qno).addClass('showing');
$('#showing').attr('value',qno);
}

function showPrev()
{
var curr=$('#showing').attr('value');
var prev=parseInt(curr)-1;
elem=document.getElementById('question-'+prev);
if(elem)
{
showQuestion(prev);
}

}

function showNext()
{
var curr=$('#showing').attr('value');
var next=parseInt(curr)+1;
elem=document.getElementById('question-'+next);
if(elem)
{
showQuestion(next);
}

}


</script>

</head>

<body class="exam">
<?php
include("header_quiz.php");
?>
<div class="container-fluid" id="container-wrap">


<div id="confirmModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4>Do you want to Finish Quiz ?</h4>
</div>
<div class="modal-body">
You will not be able to attempt once you finish the Quiz
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<a class="btn btn-warning" onclick="submit_direct()" >Finish Quiz</a>
</form>
</div>
</div>


<div class="alert alert-success" id="message-box">
  <button type="button" class="close" onclick="$('.alert').hide()">&times;</button>
  <div id="message"></div>
</div>


<form id="answer_form" name="answer_form" method="POST" action="/jee/quiz/finish/<?php if(isset($quiz['ID'])){echo $quiz['ID'];} ?>">
<div class="span12">
<div class="tabbable tabs-left"> <!-- Only required for left/right tabs -->
  
  <div class="tab-content" id="tab-content">
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
	$l=$limit;
	}
	$limit=date('Y-m-d H:i:s',$limit);
	Utility::inputHidden("time_start","time_start",$quiz['Time']);
	if($l<2880)
	{
	Utility::inputHidden("time_limit","time_limit",$limit);
	}
	
	
	Utility::inputHidden("quiz_id","quiz_id",$quiz['ID']);
	
	$html='';
	$i1=0;

	
	
	echo '<div class="tab-pane active" id="tab">';
			
			Utility::OpenQuestionEvaluation();
			Utility::AddSectionHead($quiz['Name']);
			echo '<input type="hidden" name="showing" id="showing" value="1"/>';
			echo '<div class="nav nav-pills quiz-nav-pills" style="overflow-y:auto">';
					$showing="showing";
					foreach($questions as $no=>$question)
					{
					$selected="";
					if(isset($question['Set']) && count($question['Set'])>0 && $question['Set'][0]!="")
					{
					$selected="active";
					}
					echo '<li onclick="showQuestion('.$question['Qno'].')" class="'.$selected.' '.$showing.' quiz-pills" id="pill-'.$question['Qno'].'"><a href="#'.$question['Qno'].'">'.$question['Qno'].'</a></li>';	
					$showing="";
					}
			echo '</div>';
			$active="obo-shown";
			foreach($questions as $no=>$question)
			{
			echo '<div class="quizobo-wrap '.$active.'" id="question-'.$question['Qno'].'">';
			$active="";
			
			if(!isset($options[$question['ID']]))
			{
			$options[$question['ID']]=array();
			}
			
			
			$marks='<div class="marks">(Correct : '.$question['CorrectMarks'].', Wrong : '.$question['WrongMarks'].', Unanswered : '.$question['UnansweredMarks'].')</div>';
			
			Utility::AddQuestion($question['Qno'],$question['Question'],$options[$question['ID']],$marks);
			$qnum=$question['Qno'];
			if($question['Type']=="single"|| $question['Type']=="multiple")
			{
			Utility::StartOptionsTable();
			Utility::AddRow("Q".$question['Qno'],"Q".$question['Qno'],$qnum,Utility::$Type[$question['Type']],NULL,NULL,$question['Set']);
			}
			else if($question['Type']=="numerical")
			{
			Utility::AddNumericalTable("Q".$question['Qno'],"Q".$question['Qno'],$qnum,NULL,$question['Set']);
			}
			else if($question['Type']=="match")
			{
			Utility::AddMatchTable("Q".$question['Qno'],"Q".$question['Qno'],$qnum,NULL,NULL,NULL,NULL,$question['Set']);
			}
					
			Utility::CloseTable();	
			
			echo '<hr/>';
			echo '</div>';
			
			}		
			echo '<div class="nav nav-pills quiz-pn-pills" style="overflow-y:auto">';
					echo '<li onclick="showPrev()" id="prev" class="quiz-pn-pill"><a>Previous</a></li>';	
					echo '<li onclick="showNext()" id="next" class="quiz-pn-pill"><a>Next</a></li>';	
			echo '</div>';			
			Utility::CloseQuestion();
			
			//Test Nav Pills
			//Test Nav Pills
			
			
			
			
			
			
	echo '</div>';
	echo '</div>';
	
	echo $html;
	
	?>
	

	<?php
		
	?>
        
    
	</div>
	</div>
	</form>

  
  
</div>

</div>



</body>
<?php
include("footer_mini.php");
?>

</html>