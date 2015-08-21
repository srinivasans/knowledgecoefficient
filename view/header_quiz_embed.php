<div class="navbar navbar-inverse" id="header-wrap-embed">
  <div class="navbar-inner" id="header-embed">
  <div class="container">

  
	<div class="nav-collapse collapse">
	<ul class="nav">
	  
	  <?php
	  
	  if(strtotime($quiz['TimeLimit'])-strtotime($quiz['Time']) > 5000000 || strtotime($quiz['TimeEnd'])-strtotime($quiz['Time']) > 5000000 )
	  {
	  echo '<li class="active" id="active-element"><a class="time embed-head">No Time Limit</a></li>';
	  }
	  else if(strtotime('now')-strtotime($quiz['TimeLimit']) > 1440 || strtotime('now')-strtotime($quiz['TimeEnd']) > 1440 )
	  {
	  echo '<li class="active" id="active-element"><a class="time embed-head">More than 1 day Left to complete</a></li>';
	  }
	  else
	  {
	  echo '<li class="active" id="active-element"><a class="time embed-head">Time Remaining : <span id="time"></span></a></li>';
	  }
	  ?>
	  
	  
	 </ul>
	<ul class="nav pull-right">
	
	
	<li class="embed-li" style="padding-right:10px"><button class="btn btn-success finish-btm embed-btn" id="finish-btm" onclick="finish()">Save</button></li>
	<li class="embed-li"><button onclick="finish_submit()" id="finish-head" class="btn btn-warning embed-btn">Finish Test</button></li>
	
  </ul>
  </div>
  
  </div>
  </div>
  
  
	  
	  
</div>