<?php
echo "<?xml version='1.0' encoding='UTF-8' ?>
<Response>
  <Dial>";
  if(isset($_GET['type'])){
  $pieces = explode("_", $_GET['type']);
  $conf_num = $pieces[1];
  }else{
  $conf_num = "ConferenceRoom";
  }
  if($pieces[0] == "patient"){
    echo "<Conference startConferenceOnEnter='false'>$conf_num</Conference>";
	}else{
	echo "<Conference record='record-from-start' startConferenceOnEnter='true' endConferenceOnExit='true'>$conf_num</Conference>";
	}
  echo "</Dial>
</Response>";
?>