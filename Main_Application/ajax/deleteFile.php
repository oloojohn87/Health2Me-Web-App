<?php
sleep(10);
$files = explode("_", $_POST['file']);
for($i = 0; $i < count($files); $i++)
{
    unlink($files[$i]);
}
?>