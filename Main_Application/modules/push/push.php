<?php

class Push{
    
    private $fp;
    private $socket = "/var/run/push_socket.socket";
    
    function __construct($socket = NULL) 
    {
        if($socket != NULL)
        {
            $this->socket = $socket;
        }
        $this->fp = fsockopen("unix://".$this->socket, 0, $errno, $errstr, 30);
        if (!$this->fp) 
        {
            echo "Push Error: $errstr ($errno)<br />\n";
        }
    }
    
    function __destruct() 
    {
       fclose($this->fp);
    }
    
    function send($id, $title, $message)
    {
        $obj = array("type" => "__msg", "id" => $id, "title" => $title, "data" => $message);
        fwrite($this->fp, json_encode($obj));
    }
    
    function broadcast($title, $message)
    {
        $obj = array("type" => "__bdc", "title" => $title, "data" => $message);
        fwrite($this->fp, json_encode($obj));
    }
    
    
};

?>