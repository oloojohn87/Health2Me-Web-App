<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/*echo "<script src='../../js/jquery.min.js'></script>
	  <script src='../../js/jquery-ui.min.js'></script>";
	  
echo "<script>
$.post('https://api.ihealthlabs.com:8443/OpenApiV2/OAuthv2/userauthorization/', {client_id:'d5e87e98e4b1469bb02cd3df359accf8', response_type:'code', redirect_uri:'https://health2.me'})
.done(function(data){
alert(data);
});
</script>";*/

echo '<form action="https://api.ihealthlabs.com:8443/OpenApiV2/OAuthv2/userauthorization/" method="get">
  <input type="hidden" name="client_id" value="d5e87e98e4b1469bb02cd3df359accf8"><br>
  <input type="hidden" name="response_type" value="code"><br>
  <input type="hidden" name="redirect_uri" value="https://health2.me"><br>
  <input type="hidden" name="APIName" value="Health2Me Devices"></br>
  <input type="submit" value="Submit">
</form>';
?>
