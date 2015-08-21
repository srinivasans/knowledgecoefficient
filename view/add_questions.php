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

<script type="text/javascript" src="/jee/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<?php
$script='<script type="text/javascript">
tinyMCE.init({
        // General options
        mode : "textareas",
        theme : "advanced",
        plugins : "latex,jbimages,autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		remove_script_host : false,
		document_base_url : "http://localhost/jee/",
		
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselecttablecontrols,|,hr,|,sub,sup,|,emotions|,|,ltr,rtl,",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,link,unlink,anchor,image,help,code,jbimages,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage,latex",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : false,
		language:"en",

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)

        // Drop lists for link/image/media/template dialogs
        template_external_list_url : "js/template_list.js",
        external_link_list_url : "js/link_list.js",
        external_image_list_url : "js/image_list.js",
        media_external_list_url : "js/media_list.js",
		relative_urls:false,
		 setup : function(ed)
			{
			ed.onInit.add(function(ed)
			{
            ed.getDoc().body.style.fontSize = "12px";
			});
    },

});
</script>';
echo $script;
?>

<script type="text/javascript">
function addQuestion(elem)
{
var id=elem;
var pos=id.substring(4);
var ids=pos.split("-");
var section_id=ids[0];
var group_id=ids[1];
var qno=ids[2];
$('#myModalLabel').html("Question "+qno);
$('#section_id').attr('value',section_id);
$('#group_id').attr('value',group_id);
$('#qno').attr('value',qno);
$('#myModal').modal();
$('#report').hide();
$('#tags').html("");
tinymce.get("question").setContent(''); 
$('#add_question_form').clearForm();
}

function urldecode(url) {
  return decodeURIComponent(url.replace(/\+/g, ' '));
}


function editQuestion(elem)
{
clearModal();
var id=elem;
var pos=id.substring(5);
var ids=pos.split("-");
var group_id=ids[0];
var qno=ids[1];
$('#myModalLabel').html("Question "+qno);
$('#group_id').attr('value',group_id);
$('#qno').attr('value',qno);
$.ajax({
  url: "/jee/test/getquestion/"+group_id+"/"+qno,
}).done(function(data) {
if(data)
{
var quest=jQuery.parseJSON(data);
tinymce.get("question").setContent(urldecode(quest.question));
$('#type').attr('value',quest.type);
var i=1;
for(key in quest.options)
{
$('#option'+i).attr('value',quest.options[key]);
i++;
}

for(key in quest.tags)
{
addTag(quest.tags[key]);
}

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
var html='<div id="'+id+'" class="tag-elem label label-success"><input type="hidden" name="tag[]" value="'+tag+'"/>'+tag+'<a onclick="removeTag(\''+id+'\')"> <i class="icon-remove"></i></a> </div>';
$('#tags').append(html);
}

function clearModal()
{
$('#report').hide();
$('#tags').html("");
tinymce.get("question").setContent(''); 
$('#add_question_form').resetForm();
$('#myModal').modal('hide');
}

function loadViewAdd()
{
var qno=$('#qno').attr('value');
var tgid=$('#group_id').attr('value');
$('#qview_'+qno).html('<img src="/jee/img/loader.gif"/>');
$('#qview_'+qno).load("/jee/test/question/"+tgid+"/"+qno);
}

function loadView(tgid,qno)
{
$.ajax({
  url: "/jee/test/question/"+tgid+"/"+qno,
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
$('#add_question_form').submit(function(){
tinyMCE.triggerSave();
});

$('#add_question_form').ajaxForm({
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
$('#tag_list').load("http://localhost/jee/test/get_tags/"+like);
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
<form class="form-horizontal" id="delete_question_form" method="POST" action="/jee/test/deletequestion"> 
<?php
Utility::inputHidden("tid","tid",$test_id); 
Utility::inputHidden("qid","qid",""); 
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
   <form class="form-horizontal" id="add_question_form" method="POST" action="/jee/test/save_questions"> 
	<?php 
	Utility::inputHidden("test_id","test_id",$test_id); 
	Utility::inputHidden("section_id","section_id","");
	Utility::inputHidden("group_id","group_id","");
	Utility::inputHidden("qno","qno","");
	Utility::inputTextAreaMCE("question","question","question-editable");
	Utility::inputText("option1","option[]","Option A");
	Utility::inputText("option2","option[]","Option B");
	Utility::inputText("option3","option[]","Option C");
	Utility::inputText("option4","option[]","Option D");
	Utility::dropDownList("type","type","type");
	?>
	<div class="tag-select">
	<?php
		Utility::inputText("tag_select","tag_select","Select Tags");
	?>
	<ul class="dropdown-menu" id="tag_list">
	</ul>
	
	<div class="tags" id="tags"></div>
	</div>
	
	
  <!--Modal Form-->
  </div>
  <div class="modal-footer">
	<div class="report" id="report"></div> 
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button type="submit" class="btn btn-primary" >Save changes</button>
  </div>
  </form>
</div>

<!--Modal-->

<div class="row-fluid" >
<div class="span2"></div>
<div class="span9" id="create-form">

<legend><?php echo $test_name; ?> - Add Questions <div class="pull-right" style="margin-right:1%"><a href="/jee/test/add_settings/<?php echo $test_id; ?>" class="btn btn-info">Settings</a></div><div class="pull-right" style="margin-right:1%"><a href="/jee/test/setonboard/<?php echo $test_id; ?>" class="btn btn-success">Set On-Board</a></div><div class="pull-right" style="margin-right:1%"><a href="/jee/test/set_answers/<?php echo $test_id; ?>" class="btn btn-primary">Set Answers</a></div></legend>

<?php
$qno=1;

foreach($sections as $section_id=>$section_name)
{
?>
<legend class="inner-legend"><?php echo $section_name; ?></legend>

<?php
if(isset($groups[$section_id]))
{
foreach($groups[$section_id] as $group_id=>$group_details)
{
?>
<legend class="inner-inner-legend"><?php echo $group_details['name']; ?></legend>
<?php
$num_questions=$group_details['num_questions'];
for($i=0;$i<$num_questions;$i++)
{

$script.="loadView('".$group_id."','".$qno."');";
?>
<div class="control-group">

<div class="qview" id="qview_<?php echo $qno; ?>">

<?php
Utility::label("question[".$group_id.']['.$i."]","Q".$qno.")",NULL);
?>

<a href="#myModal" role="button" class="btn" onclick="addQuestion(this.id)" id="qno-<?php echo $section_id; ?>-<?php echo $group_id; ?>-<?php echo $qno ;?>"><i class="icon-plus-sign"></i> Add Question (<?php echo $qno; ?>)</a>

</div>

</div>
<?php
$qno++;
}


}
}
?>

<?php
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