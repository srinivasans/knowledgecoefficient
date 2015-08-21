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
      <li <?php if($headMenu['active']=="test"){echo 'class="active" id="active-element"';} ?> ><a href="#"><?php echo $test['Name']; ?></a></li>
	  
	   <?php
	  $TimeLimit=$register['TimeStarted']+$test['TimeLimit']-$test['Time'];
	  if(strtotime($test['TimeLimit'])-strtotime($test['Time']) > 5000000 && strtotime($test['TimeEnd'])-strtotime($test['Time']) > 5000000 )
	  {
	  echo '<li class="active" id="active-element"><a class="time">No Time Limit</a></li>';
	  }
	  else if(strtotime($TimeLimit)-strtotime('now') > 86400 && strtotime($test['TimeEnd'])-strtotime('now') > 86400 )
	  {
	  echo '<li class="active" id="active-element"><a class="time">More than 1 day Left</a></li>';
	  }
	  else
	  {
	  echo '<li class="active" id="active-element"><a class="time">Time Remaining : <span id="time"></span></a></li>';
	  }
	  ?>
	  
	 </ul>
	<ul class="nav pull-right">
	
	
	<li style="padding-right:10px"><button class="btn btn-success finish-btm" id="finish-btm" onclick="finish()">Save</button></li>
	<li><button onclick="finish_submit()" id="finish-head" class="btn btn-warning">Finish Test</button></li>
	
  </ul>
  </div>
  
  </div>
  </div>
  
  
	  
	  
</div>