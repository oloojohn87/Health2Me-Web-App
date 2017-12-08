<?php
session_start();
set_time_limit(180);
require("environment_detail.php");
require("push_server.php");

$urlpath=$_GET['path'];

?>
<html>
<head>
<style>
		body {
			margin: 0;
			padding: 0;
			background-color: #FAFAFA;
			font: 12pt "Tahoma";
		}
		* {
			box-sizing: border-box;
			-moz-box-sizing: border-box;
		}
		.page {
			width: 21cm;
			min-height: 29.7cm;
			padding: 2cm;
			margin: 1cm auto;
			border: 1px #D3D3D3 solid;
			border-radius: 5px;
			background: white;
			box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
		}
		.subpage {
			padding: 1cm;
			border: 5px red solid;
			height: 256mm;
			outline: 2cm #FFEAEA solid;
		}

		@page {
			size: A4;
			margin: 0;
		}
		@media print {
			.page {
				margin: 0;
				border: initial;
				border-radius: initial;
				width: initial;
				min-height: initial;
				box-shadow: initial;
				background: initial;
				page-break-after: always;
			}
		}
		
		
</style>
<script type="text/javascript">
function printpage() {
  document.getElementById('PrintImage').style.display = 'none';
  window.print();
  document.getElementById('PrintImage').style.display = 'block';
}
</script>
</head>
<body>
<div class="grid span3" style="width:90%;">
          <div class="grid-title a" style="height:60px;">
           <div class="pull-left a" id="AreaTipo" style="font-size:24px;"></div>
		   
		   <div class="pull-right">
               <div class="grid-title-label" ><button id="PrintImage" class="btn" style="margin-left:10px;" onclick="printpage()">Print</button></div>
           </div>		   
           <div class="pull-right">
               <div class="grid-title-label" id="AreaFecha" ><span class="label label-warning" ></span></div>
           </div>
		   
          <div class="clear"></div>  
           <div>
           <span class="ClClas" id="AreaClas" style="font-size:18px; color:grey;"></span>
           </div>
           <div class="clear"></div>   
          </div>
          
          <div class="grid-content" id="AreaConten" style="">
				
				<img id="ImagenN" src="<?php echo $urlpath;?>" alt="'+queId+'" style="border:1px solid #666CCC; margin:0 auto; display:block;">';
				<!--<img id="ImagenAmp" style="margin:0 auto;" src=""> -->
          </div>
		  <div id="media-active"></div>
        </div>


</body>
</html>