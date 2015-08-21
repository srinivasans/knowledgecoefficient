<?php
			if(count($notifications)>0)
			{
			foreach($notifications as $k=>$notification)
			{
			$class="";
			$script="";
			if($notification['Read']==0)
			{
			$class="unread-notif";
			$script='onmouseout="readNotif(this.id)"';
			}
			echo '<li '.$script.'  id="'.$notification['ID'].'" time="'.$notification['Time'].'" class="notify-elem '.$class.'"><a href="'.$notification['Link'].'" id="notify-link"><img src="/jee/img/notification.png"/><div class="notification"><div class="notify-title">'.$notification['Title'].'</div><div class="notify-subtitle">'.$notification['SubTitle'].'</div></div></a></li>';
			}
			}
?>