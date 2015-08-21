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

function deleteTest()
{
$('#deleteModal').modal();
}

function confirmModal()
{
$('#confirmModal').modal();
}

</script>


</head>

<body>
<?php
include("header.php");
?>

<div class="container-fluid" id="full-min">

<div id="deleteModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4>Are You Sure ?</h4>
</div>
<div class="modal-body">
<form class="form-horizontal" id="delete_question_form" method="POST" action="/jee/quiz/delete"> 
<?php
Utility::inputHidden("qid","qid",$quiz_id); 
?>
<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
<button type="submit" class="btn btn-danger" >Delete</butto>
</form>
</div>
</div>

<div id="confirmModal" class="modal hide fade addquestion" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h4>Are You Sure ?</h4>
</div>
<div class="modal-body">

<div style="margin-right:1%"><button style="margin-right:1%" class="btn" data-dismiss="modal" aria-hidden="true">Close</button><a href="/jee/quiz/check_constraints/<?php echo $quiz_id; ?>" class="btn btn-info">Set On-Board</a></div>
</div>
</div>


<div class="row-fluid" >
<div class="span2"></div>
<div class="span9" id="create-form">
<form class="form-horizontal" method="POST" action="/jee/quiz/edit_quiz">
<legend>Set On-Board <div class="pull-right" style="margin-right:1%"></div>
<div class="pull-right" style="margin-right:1%"><a href="/jee/quiz/add_questions/<?php echo $quiz_id; ?>" class="btn btn-success">Questions</a></div>
<div class="pull-right" style="margin-right:1%"><a onclick="deleteTest()" class="btn btn-danger">Delete Test</a></div>
</legend>

<div style="margin-bottom:2%">
The Test will be Published only after setting On-Board. Setting On-Board tests for the Constraints set by you and consistancy of data. On constraint validity you will be redirected to the Confirmation page. Else you will be redirected to the page where there is constraint breach. Setting On-Board publishes the test on the main page. So, please make sure that you make changes wisely after setting on-board.
</div>

<div style="margin-right:1%"><a onclick="confirmModal()" class="btn btn-info">Set On-Board</a></div>

</div>
</div>
</div>

</body>

</html>