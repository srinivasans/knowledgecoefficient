<script type="text/javascript">
var loaded=0;
var $contentLoadTriggered=false;
var $maxLoaded=0;

$('document').ready(function(){
$('#notification').click(function(){
$('#loader').remove();
$('#notify').append('<li class="loader" id="loader"><img src="/jee/img/loader.gif"/></li>');
loadNotifClick();
});
loadPoints();

$('#points').click(function(){
$('#loader').remove();
$('#notify').append('<li class="loader" id="loader"><img src="/jee/img/loader.gif"/></li>');
loadPoints();
});

$('#notify').scroll(function(){
if(isScrollBottom() &&  $contentLoadTriggered == false)
{
$contentLoadTriggered = true;
$('#loader').remove();
$('#notify').append('<li class="loader" id="loader"><img src="/jee/img/loader.gif"/></li>');
loadNotif();
}
});


$('<audio id="chatAudio"><source src="notify.ogg" type="audio/ogg"><source src="/jee/sounds/notify.mp3" type="audio/mpeg"><source src="notify.wav" type="audio/wav"></audio>').appendTo('body');

});

function isScrollBottom() { 
  var elementHeight = $('#notify').outerHeight()-1 ; 
  var scrollPosition = $('#notify')[0].scrollHeight-$('#notify').scrollTop(); 
  return (elementHeight == scrollPosition); 
}

function loadNotif()
{
$.ajax({
  url: "/jee/home/notification",
  context: document.body,
  type:'POST',
  data:{uid:loaded},
}).done(function(data) {
  $('#loader').remove();
  $('#notify').append(data);
  loaded=$("#notify").children().length-1;
  $contentLoadTriggered == false;
}).fail(function(jqXHR, textStatus) {
  $('#notify').append(textStatus);
  $contentLoadTriggered == false;
});

}


function loadPoints()
{
$.ajax({
  url: "/jee/home/points",
  context: document.body,
  type:'POST',
  data:{uid:loaded},
}).done(function(data) {
  $('#loader').remove();
  $('#pts').html(data);
  loaded=$("#pts").children().length-1;
  $contentLoadTriggered == false;
}).fail(function(jqXHR, textStatus) {
  $('#pts').html(textStatus);
  $contentLoadTriggered == false;
});

}


function loadFrom(limit)
{
$.ajax({
  url: "/jee/home/notification",
  context: document.body,
  type:'POST',
  data:{uid:0,limit:limit},
}).done(function(data) {
  $('#loader').remove();
  var notif=$(data);
  notif.insertAfter('#notify li:eq(1)');
  loaded=$("#notify").children().length-1;
  $contentLoadTriggered == false;
}).fail(function(jqXHR, textStatus) {
  $('#notify').append(textStatus);
  $contentLoadTriggered == false;
});
}


function loadNotifClick()
{
$.ajax({
  url: "/jee/home/notification",
  context: document.body,
  type:'POST',
  data:{uid:loaded},
}).done(function(data) {
  $('#loader').remove();
  $('#notify').append(data);
  loaded=$("#notify").children().length-1;
  $contentLoadTriggered == false;
}).fail(function(jqXHR, textStatus) {
  $('#notify').append(textStatus);
  $contentLoadTriggered == false;
});

}


function readNotif(elem)
{
var id=elem;
if($('#'+id).hasClass('unread-notif'))
{
$.ajax({
  url: "/jee/home/read_notification",
  context: document.body,
  type:'POST',
  data:{uid:id},
}).done(function(data) {
  $('#'+id).removeClass('unread-notif');
});
}

}

</script>
<script type="text/javascript">

function IsNumeric(input)
{
    return (input - 0) == input && (input+'').replace(/^\s+|\s+$/g, "").length > 0;
}
var present_value=0;
var present_value_certis=0;
var first=0;

function getNotif()
{
if(typeof(EventSource)!="undefined")
  {
  var source=new EventSource("/jee/home/num_notifications");
  source.onmessage=function(event)
    {
	var notification=JSON.parse(event.data);
	if(notification.numNotif > present_value)
	{
	if(first==1)
	{
	$('#chatAudio')[0].play();
	}
	else
	{
	first=1;
	}
	$maxLoaded=notification.numNotif-present_value;
	loadFrom($maxLoaded);
	}
	present_value=notification.numNotif;
    $("#notification-label").html(notification.numNotif);
    };
  }
else
  {
  document.getElementById("notification-label").innerHTML="x";
  }
  
 }
 
 
getNotif();
</script>


<div class="navbar navbar-fixed-top navbar-inverse" id="header-wrap">
  <div class="navbar-inner" id="header">
  <div class="container">
  
  <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
  
    <a class="brand" id="title" href="/">Knowledge Coefficient</a>	
	<div class="nav-collapse collapse">
	<ul class="nav">
      <li <?php if($headMenu['active']=="dashboard"){echo 'class="active" id="active-element"';} ?> ><a href="/jee">Dashboard</a></li>
      <li <?php if($headMenu['active']=="test"){echo 'class="active" id="active-element"';} ?> ><a href="/jee/test">Tests</a></li>
      <li <?php if($headMenu['active']=="quiz"){echo 'class="active" id="active-element"';} ?> ><a href="/jee/quiz">Quizzes</a></li>
	 </ul>
	 
	 
	 <form class="navbar-search pull-left" method="POST" action="/jee/search/index/">
  <input type="text" name="query" class="search-query" placeholder="Search">
	</form>
	 
	<ul class="nav pull-right">
	
	<li class="dropdown" id="points">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-certificate icon-white"></i><div id="points-label" class="label label-warning notify-label"></div></a>
	<ul class="dropdown-menu" id="pts">
	 <li id="pts-header" class="pts-wrap-head-mock">Points</li> 
	 
	</ul>	
	</li>
	
	<li class="dropdown" id="notification">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-globe icon-white"></i><div id="notification-label" class="label label-warning notify-label"></div></a>
	<ul class="dropdown-menu" id="notify">
	 <li class="notify-wrap-head">Notifications</li> 
	 <li id="notif-header" class="notify-wrap-head-mock">Notifications</li> 
	 
	</ul>	
	</li>

	
	
	  <li class="dropdown">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown">
		<i class="icon-user icon-white"></i> 
		<?php
		echo $headMenu['username'];
		?>
		<b class="caret"></b>
		</a>
		<ul class="dropdown-menu">
			
			<li><a href="/jee/help">Help</a></li>
			<li><a href="/jee/profile/view">Profile</a></li>
			<li class="/jee/divider"></li>
			<li><a href="/jee/logout">Logout</a></li>
			
		</ul>
  </li>
  
  </ul>
  </div>
  
  </div>
  </div>
  
  
	  
	  
</div>