<?php

require "push.php";

$id = $_GET['id'];
$msg = $_GET['msg'];
$title = $_GET['title'];

echo 'ID: '.$id.', Title: '.$title.', Message: '.$msg;

$push = new Push();

$push->send($id, $title, $msg);



?>