<?php
session_start();
$mySessionId = session_id();
echo "My Session ID".$mySessionId;
?>