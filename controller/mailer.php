<?php
class Mail
{
public $To;
public $From;
public $Subject;
public $Message;

public function __construct()
{
$this->From='admin@knowledgecoefficient.com';
}

public function send()
{
$to      = $this->To;
$subject = $this->Subject;
$message = $this->Message;
$headers = 'From: '.$this->From.'' . "\r\n";
$sent=mail($to, $subject, $message, $headers);
return $sent;
}

}
?>