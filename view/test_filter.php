<?php
include("controller/utility_class.php");
?>


<?php
foreach($tests_active as $k=>$test)
{
if($test['TimeLimit']>5000000)
{
$test['TimeLimit']="No Time Limit";
}
else
{
$test['TimeLimit']=$test['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td>';
echo '<td>'.$test['TimeLimit'].'</td>';
echo '<td><span class="label label-success">Active</span></td>';
echo '</tr>';
}

foreach($tests_upcoming as $k=>$test)
{
if($test['TimeLimit']>5000000)
{
$test['TimeLimit']="No Time Limit";
}
else
{
$test['TimeLimit']=$test['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td>';
echo '<td>'.$test['TimeLimit'].'</td>';
echo '<td><span class="label label-info">Upcoming</span></td>';
echo '</tr>';
}

foreach($tests_archived as $k=>$test)
{
if($test['TimeLimit']>5000000)
{
$test['TimeLimit']="No Time Limit";
}
else
{
$test['TimeLimit']=$test['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/test/start/'.$test['ID'].'">'.$test['Name'].'</a></td>';
echo '<td>'.$test['TimeLimit'].'</td>';
echo '<td><span class="label label-warning">Archived</span></td>';
echo '</tr>';
}
?>