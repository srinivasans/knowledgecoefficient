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
<th colspan="4">User Analysis <a href="/jee/test/export_users/<?php echo $test_id; ?>" class="btn-primary small-btn">Export List</a></th>
</tr>
<tr>
<th>Name</th>
<th>Marks</th>
<th>Rank</th>
<th>Status</th>
</tr>
<?php
foreach($completed as $k=>$value)
{
echo '<tr>';
echo '<td><a href="/jee/profile/view_profile/'.$value['ID'].'">'.$value['Name'].'</a></td>';
echo '<td>'.$value['Marks'].'</td>';
echo '<td>'.$value['Rank'].'</td>';
echo '<td>Completed (<a href="/jee/test/user_report/'.$test_id.'/'.$value['ID'].'">View Report</a>)</td>';
echo '</tr>';
}

foreach($attempting as $k=>$value)
{
echo '<tr>';
echo '<td><a href="/jee/profile/view_profile/'.$value['ID'].'">'.$value['Name'].'</a></td>';
echo '<td></td>';
echo '<td></td>';
echo '<td>Attempting</td>';
echo '</tr>';
}
?>

</table>

</div>
</div>
</div>

<?php
include("footer_static.php");
?>

</body>
</html>
