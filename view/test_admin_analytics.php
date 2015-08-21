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
function gen_passkey(test_id)
{
if(confirm('Once set the universal passkey can be used by any user to attemp test. Are you sure?'))
{
$.ajax({
url:'/jee/test/setpasskey/'+test_id,
context: document.body
}).done(function(data){
$('#passkey_value').html(data);
});
}
}
</script>

</head>

<body>

<?php
// $name to be declared

include("header.php");
?>

<div class="container-fluid" id="full-min">
<div class="row-fluid">
<div class="span12">
<table class="table table-striped table-bordered">
<tr>
<th>ID</th>
<th>test Name</th>
<th>Access</th>
<th>View Type</th>
<th>Credits</th>
<th>Status</th>
<th>Generate/Refresh</th>
</tr>

<tr>
<td><?php echo $test_details['ID']; ?></td>
<td><?php echo $test_details['Name']; ?></td>
<td><?php echo $test_details['Access']; ?></td>
<td><?php echo $test_details['ViewType']; ?></td>
<td><?php echo $test_details['Credits']; ?></td>
<td><?php echo $test_details['Status']; ?></td>
<td><a href="/jee/test/generate_ranks/<?php echo $test_id; ?>" class="btn btn-primary">Generate Ranks</a> <a href="/jee/test/view/<?php echo $test_id; ?>" class="btn btn-warning">View Settings</a></td>
</tr>

</table>

<table class="table table-bordered table-striped">
<tr>
<th>Share Test</th>
<td><textarea name="share" id="share" placeholder="Share" style="resize:none;width:350px;" /><?php echo 'http://knowledgecoefficient.com/test/start/'.$test_details['ID'];?></textarea></td>
</tr>
</table>

<table class="table table-bordered table-striped">
<tr>
<th>Number of Users</th>
</tr>
<tr>
<td><?php echo $reg_count ?></td>
</tr>
</table>


<table class="table table-bordered table-striped">
<tr>
<td>View Analytics</td>
<td><a href="/jee/test/user_based_analytics/<?php echo $test_id; ?>" class="btn btn-success">User Analytics</a> <a href="/jee/test/question_analytics/<?php echo $test_id; ?>" class="btn btn-primary">Question-wise Analysis</a></td>
</tr>
</table>

<?php
if($test_details['Access']=='private')
{
?>
<table class="table table-bordered table-striped">
<tr>
<th colspan="2">Private test Access Settings</th>
</tr>
<tr>
<td><a onclick="gen_passkey(<?php echo $test_id; ?>)" class="btn btn-primary">Generate Universal Passkey</a><span class="passkey_value" id="passkey_value"><?php if($key){echo 'Passkey : '.$pass;} ?></span></td>
<td>
<code>Upload Passkeys (csv format with &lt;email&gt;,&lt;passkey&gt; columns):</code>
<form name="submit_passkey" id="passkey_submit" method="POST" action="/jee/test/import_passkeys/<?php echo $test_id; ?>" enctype="multipart/form-data">
<input type="file" name="passkey_list" />
<input type="submit" class="btn btn-success"/>
</form>
</td>
</tr>
</table>
<?php
}
$ar=array("Marks"=>"string","Highest"=>"number","Average"=>"number","Total Marks"=>"number");
drawGraph("Test Marks",$ar,array('Marks'=>array('Highest'=>$highest,'Average'=>$average,'TotalMarks'=>$exam_total)),array("Highest","Average","TotalMarks"),"test_marks",'BarChart');

?>

<table class="table table-bordered table-striped">
<tr>
<th>Marks Distribution</th>
<td>Total Marks : <?php echo $exam_total; ?></td>
<td>Average : <?php echo $average; ?></td>
<td>Highest : <?php echo $highest; ?></td>
</tr>
</table>

<div id="test_marks" class="graph"></div>
</div>
</div>

</div>

<?php
include("footer_static.php");
?>

</body>
</html>
