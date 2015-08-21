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
var id1=id;
id=id.substring(1);
if(id.indexOf('[]')!=-1)
{
id=id.substring(0,id.length-2);
}
id="head-"+id;
//document.write($(this).attr('id')+id);
if(chktest(this,id1))
{
$('#'+id).addClass('alert-success');
}
else
{
$('#'+id).removeClass('alert-success');
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


</script>

</head>

<body class="exam">
<?php
include("header_exam.php");
?>
<div class="container-fluid" id="container-wrap">


<div id="confirmModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4>Do you want to Finish Test ?</h4>
</div>
<div class="modal-body">
You will not be able to attempt once you finish the Test
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<a class="btn btn-warning" onclick="submit_direct()" >Finish Test</a>
</form>
</div>
</div>


<div class="alert alert-success" id="message-box">
  <button type="button" class="close" onclick="$('.alert').hide()">&times;</button>
  <div id="message"></div>
</div>


<form id="answer_form" name="answer_form" method="POST" action="/jee/test/finish/<?php if(isset($test['ID'])){echo $test['ID'];} ?>">
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
			
			Utility::OpenQuestion();
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
			$marks='<div class="marks">(Correct : '.$question['CorrectMarks'].', Wrong : '.$question['WrongMarks'].', Unanswered : '.$question['UnansweredMarks'].')</div>';
			
			Utility::AddQuestion($question['Qno'],$question['Question'],$options[$question['ID']],$marks);
			}				
			Utility::CloseGroup();
			
			}
		}
			Utility::CloseQuestion();
			//Test Options
			Utility::OpenOptionBox($key);
			$i=0;
			
		foreach($section_groups[$key] as $k=>$gid)
		{
		if(isset($groups[$gid]) && isset($questions[$gid]))
			{
			$class=array();
			if($i==0)
			{
			$class[]="in";
			//$i=1;
			}
			Utility::AddOptionsAccordion("accordion".$key,$gid,$groups[$gid]['Name'],$class);
			
			$tableOpen=false;
			foreach($questions[$gid] as $no=>$question)
			{
			if($question['Type']=="single"|| $question['Type']=="multiple")
			{
				if($tableOpen==false)
				{
				Utility::StartOptionsTable();
				$tableOpen=true;
				}
				Utility::AddRow("Q".$question['Qno'],"Q".$question['Qno'],$question['Qno'],Utility::$Type[$question['Type']],NULL,NULL,$question['Set']);
			}
			else if($question['Type']=="numerical")
			{
				if($tableOpen==true)
				{
				Utility::closeTable();
				$tableOpen=false;
				}
				Utility::AddNumericalTable("Q".$question['Qno'],"Q".$question['Qno'],$question['Qno'],NULL,$question['Set']);
			}
			else if($question['Type']=="match")
			{
			if($tableOpen==true)
			{
			Utility::closeTable();
			$tableOpen=false;
			}
			Utility::AddMatchTable("Q".$question['Qno'],"Q".$question['Qno'],$question['Qno'],NULL,NULL,NULL,NULL,$question['Set']);
				
			}
			
			}		
			Utility::CloseTable();	
			$tableOpen=false;
			Utility::CloseAccordion();
			}
			
			
			/*Utility::AddRow("Q1","Q1","1","checkbox");
			Utility::AddRow("Q2","Q2","2","checkbox");
			Utility::AddRow("Q3","Q3","3","checkbox");
			
			Utility::CloseAccordion();
			Utility::AddOptionsAccordion("accordion2","single_choice","Single Choice");
			Utility::StartOptionsTable();
			Utility::AddRow("Q1","Q1","1","radio");
			Utility::AddRow("Q2","Q2","2","radio");
			Utility::AddRow("Q3","Q3","3","radio");
			Utility::CloseTable();
			Utility::CloseAccordion();
			Utility::AddOptionsAccordion("accordion2","numerical","Numerical Answers");
			Utility::AddNumericalTable("Q1","Q1","1");
			Utility::AddNumericalTable("Q2","Q2","2");
			Utility::CloseAccordion();
			Utility::AddOptionsAccordion("accordion2","match","Match the Following");
			Utility::AddMatchTable("Q1","Q1","1");
			Utility::AddMatchTable("Q2","Q2","2");*/
			
			
		}
		
		Utility::CloseOptionBox();
		
		//Test Options
			
	echo '</div>';
	echo '</div>';
	
	}
	echo $html;
	
	?>
	

	<?php
		
	?>
        
    
	</div>
	</form>

  
  
</div>

</div>



</body>
<?php
include("footer_mini.php");
?>

</html>