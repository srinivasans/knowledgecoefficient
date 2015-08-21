<?php
header('Content-Type: application/json');
date_default_timezone_set("Asia/Calcutta");
echo '{"dateString":"'.date("F d, Y H:i:s",strtotime('now')).'"}';
?>