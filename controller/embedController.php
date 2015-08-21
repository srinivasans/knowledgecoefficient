<?php
include('controller.php');
class EmbedController extends Controller
{
protected function index()
{
include($_SERVER['DOCUMENT_ROOT'].'/jee/view/embed_index.php');
}
}
?>