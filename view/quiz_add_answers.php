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
$('document').ready(function(){
$('#elem').tooltip('show');
});

</script>
<?php
Utility::includeMCEScript();
?>
<script type="text/javascript">

function urldecode(url) {
  return decodeURIComponent(url.replace(/\+/g, ' '));
}

function setAnswer(elem)
{
clearModal();
var id=elem;
var pos=id.substring(5);
var ids=pos.split("-");
var quiz_id=ids[0];
var qno=ids[1];
$('#myModalLabel').html("Question "+qno);
$('#group_id').attr('value',quiz_id);
$('#qno').attr('value',qno);
$('#question').html('<img style="align:center" src="/jee/img/loader.gif"/>');
$('#question').load("/jee/quiz/question_answer/"+quiz_id+"/"+qno,function(){tinymce.get("solution").setContent($('#sol').attr('value'));});

$.ajax({
  url: "/jee/quiz/getquestion/"+quiz_id+"/"+qno,
}).done(function(data) {
if(data)
{
var quest=jQuery.parseJSON(data);
$('#type').attr('value',quest.type);
var i=1;

}
});

$('#myModal').modal();
}


function removeTag(tag)
{
$('#'+tag).remove();
}


function addTag(tag)
{
var id=tag.replace(/\s/g,'')
var html='<div id="'+id+'" class="tag-elem"><input type="hidden" name="tag[]" value="'+tag+'"/>'+tag+'<a onclick="removeTag(\''+id+'\')"> <i class="icon-remove"></i></a> </div>';
$('#tags').append(html);
}

function clearModal()
{
$('#report').hide();
$('#tags').html("");
$('#question').html("");
$('#add_question_form').resetForm();
$('#myModal').modal('hide');
}

function loadViewAdd()
{
var qno=$('#qno').attr('value');
var qid=$('#quiz_id').attr('value');
$('#qview_'+qno).html('<img src="/jee/img/loader.gif"/>');
$('#qview_'+qno).load("/jee/quiz/question_ans/"+qid+"/"+qno);
}

function loadView(qid,qno)
{
$.ajax({
  url: "/jee/quiz/question_ans/"+qid+"/"+qno,
  context: document.body
}).done(function(data) {
if(data)
{
$('#qview_'+qno).html('<img src="/jee/img/loader.gif"/>');
$('#qview_'+qno).html(data);
}
});
}


function deleteQuestion(elem)
{
var id=elem;
$('#deleteModal').modal();
$('#qid').attr('value',elem);
}


$('document').ready(function(){
$('#add_answer_form').submit(function(){
tinyMCE.triggerSave();
});

$('#add_answer_form').ajaxForm({
target:'#report',
success:function(){
$('#report').show();
var msg=$('#report').html();
if(msg.indexOf("Success")!=-1)
{
loadViewAdd();
clearModal();
}

}
});
});


$('document').ready(function(){
$('body').not('#tag_list').click(function(){
$('#tag_list').hide();
});
});

$('document').ready(function(){
$('#tag_select').keyup(function(){
var like=encodeURIComponent($('#tag_select').attr('value'));
$('#tag_list').load("http://localhost/jee/quiz/get_tags/"+like);
$('#tag_list').fadeIn();
});
});
</script>


</head>

<body>
<?php
include("header.php");
$script='<script type="text/javascript"> $("document").ready(function(){';
?>

<div class="container-fluid" id="full-min">

<div id="deleteModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4>Are You Sure ?</h4>
</div>
<div class="modal-body">
<form class="form-horizontal" id="delete_question_form" method="POST" action="/jee/quiz/deletequestion"> 
<?php
Utility::inputHidden("qid","qid",$quiz_id); 
?>
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="submit" class="btn btn-danger" >Delete</butto>
</form>
</div>
</div>

<!-- Modal -->
<div id="myModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel"></h3>
  </div>
  <div class="modal-body">
  <!--Modal Form-->
   <form class="form-horizontal" id="add_answer_form" method="POST" action="/jee/quiz/save_answers"> 
	<?php 
	Utility::inputHidden("quiz_id","quiz_id",$quiz_id); 
	Utility::inputHidden("qno","qno","");
	Utility::inputHidden("qid","qid","");
	?>
	<div id="question" class=""></div>	
	
	<?php
	Utility::inputTextAreaMCE("solution","solution","solution");
	?>
	
  <!--Modal Form-->
  </div>
  <div class="modal-footer">
	<div class="report" id="report"></div> 
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button type="submit" class="btn btn-primary" >Save Answer</button>
  </div>
  </form>
</div>

<!--Modal-->

<div class="row-fluid" >
<div class="span2"></div>
<div class="span9" id="create-form">

<legend><?php echo $quiz_name; ?> - Set Answers <div class="pull-right" style="margin-right:1%"><a href="/jee/quiz/setonboard/<?php echo $quiz_id; ?>" class="btn btn-success">Set On-Board</a></div></legend>

<?php
$qno=1;
$num_questions=count($question);
for($i=0;$i<$num_questions;$i++)
{
$script.="loadView('".$quiz_id."','".$qno."');";
?>
<div class="control-group">

<div class="qview" id="qview_<?php echo $qno; ?>">

<?php
Utility::label("question[".$quiz_id.']['.$i."]","Q".$qno.")",NULL);
?>

---- Question Not Set ----

</div>

</div>
<?php
$qno++;
}

$script.="}); </script>";
echo $script;

?>

</form>
</div>
</div>
</div>

</body>

</html>