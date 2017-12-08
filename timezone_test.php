<?php


function ConvertLocalTimezoneToGMT($gmttime,$timezoneRequired)
{
    $system_timezone = date_default_timezone_get();
 
    $local_timezone = $timezoneRequired;
    date_default_timezone_set($local_timezone);
    $local = date("Y-m-d h:i:s A");
 
    date_default_timezone_set("GMT");
    $gmt = date("Y-m-d h:i:s A");
 
    date_default_timezone_set($system_timezone);
    $diff = (strtotime($gmt) - strtotime($local));
 
    $date = new DateTime($gmttime);
    $date->modify("+$diff seconds");
    $timestamp = $date->format("m-d-Y H:i:s");
    return $timestamp;
}

echo ConvertLocalTimezoneToGMT('2013-09-02 20:25:00','America/New_York');

?>