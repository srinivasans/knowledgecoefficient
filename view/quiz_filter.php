<?php
include("controller/utility_class.php");
?>

<?php
foreach($quizs_active as $k=>$quiz)
{
if($quiz['TimeLimit']>5000000)
{
$quiz['TimeLimit']="No Time Limit";
}
else
{
$quiz['TimeLimit']=$quiz['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/quiz/start/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td>';
echo '<td>'.$quiz['TimeLimit'].'</td>';
echo '<td><span class="label label-success">Active</span></td>';
echo '</tr>';
}

foreach($quizs_upcoming as $k=>$quiz)
{
if($quiz['TimeLimit']>5000000)
{
$quiz['TimeLimit']="No Time Limit";
}
else
{
$quiz['TimeLimit']=$quiz['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/quiz/start/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td>';
echo '<td>'.$quiz['TimeLimit'].'</td>';
echo '<td><span class="label label-info">Upcoming</span></td>';
echo '</tr>';
}

foreach($quizs_archived as $k=>$quiz)
{
if($quiz['TimeLimit']>5000000)
{
$quiz['TimeLimit']="No Time Limit";
}
else
{
$quiz['TimeLimit']=$quiz['TimeLimit'].' Minutes.';
}
echo '<tr>';
echo '<td><a href="/jee/quiz/start/'.$quiz['ID'].'">'.$quiz['Name'].'</a></td>';
echo '<td>'.$quiz['TimeLimit'].'</td>';
echo '<td><span class="label label-warning">Archived</span></td>';
echo '</tr>';
}
?>