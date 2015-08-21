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
        mode : "exact",
		elements:"';
foreach($groups as $sec_group)
{
foreach($sec_group as $group_id=>$group)
{
for($i=0;$i<$group['num_questions'];$i++)
{
$script.='question['.$group_id.']['.$i.'],';
}
}

}
$script=substr($script,0,-1);
$script.='",
        theme : "advanced",
        plugins : "jbimages,autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		remove_script_host : false,
		document_base_url : "http://localhost/jee/",
		
        // Theme options
        theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselecttablecontrols,|,hr,|,sub,sup,|,emotions|,|,ltr,rtl,",
        theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,link,unlink,anchor,image,help,code,jbimages,|,styleprops,spellchecker,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,blockquote,pagebreak,|,insertfile,insertimage",
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

});
</script>';
echo $script;
?>


</head>

<body>
<?php
include("header.php");
?>

<div class="container-fluid" id="full-min">

<!-- Modal -->
<div id="myModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Modal header</h3>
  </div>
  <div class="modal-body">
    <p>One fine body…</p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    <button class="btn btn-primary">Save changes</button>
  </div>
</div>

<!--Modal-->

<div class="row-fluid" >
<div class="span2"></div>
<div class="span9" id="create-form">
<form class="form-horizontal" method="POST" action="/jee/test/save_questions">
<legend><?php echo $test_name; ?> - Add Questions</legend>

<?php
Utility::inputHidden("test_id","test_id",$test_id);
$qno=1;
?>
<?php
foreach($sections as $section_id=>$section_name)
{
Utility::inputHidden("section_id[]","section_id[]",$section_id);
?>
<legend class="inner-legend"><?php echo $section_name; ?></legend>

<?php
foreach($groups[$section_id] as $group_id=>$group_details)
{
Utility::inputHidden("group_id[".$section_id."]","group_id[".$section_id."]",$group_id);
?>
<legend class="inner-inner-legend"><?php echo $group_details['name']; ?></legend>
<?php
$num_questions=$group_details['num_questions'];
for($i=0;$i<$num_questions;$i++)
{
?>
<div class="control-group">
<?php
Utility::label("question[".$group_id.']['.$i."]","Q".$qno.")",NULL);
Utility::inputHidden("qno[".$group_id.']['.$i."]","qno[".$group_id.']['.$i."]",$qno);
$qno++;
?>
<?php
/*
Utility::inputTextAreaMCE("question[".$group_id."][".$i."]","question[".$group_id."][".$i."]","question-editable");
Utility::inputText("options[".$group_id."][".$i."]","options[".$group_id."][".$i."]","Option A");
Utility::inputText("options[".$group_id."][".$i."]","options[".$group_id."][".$i."]","Option B");
Utility::inputText("options[".$group_id."][".$i."]","options[".$group_id."][".$i."]","Option C");
Utility::inputText("options[".$group_id."][".$i."]","options[".$group_id."][".$i."]","Option D");
Utility::dropDownList("type[".$group_id."][".$i."]","type[".$group_id."][".$i."]","type");
*/
?>
<a href="#myModal" role="button" class="btn" data-toggle="modal" id="qno[<?php echo $group_id; ?>][<?php echo $i ;?>]">Launch demo modal</a>
</div>
<?php
}


}
?>

<?php
}

Utility::controlGroupSubmit("save","save","submit","Save","Save",array("create-submit","btn-primary"));

?>

</form>
</div>
</div>
</div>

</body>

</html>