<!DOCTYPE html> 
<html> 
<head> 
	<title>Health2me Reports</title> 
	
	<meta name="viewport" content="width=device-width, initial-scale=1"> 

	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
	 <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
	 
	<script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<script type="text/javascript" src="PDFObject/pdfobject.js"></script>
	
    <style type="text/css" media="screen">
        .ui-page { -webkit-backface-visibility: hidden; }
		
		
		</style>
			<!-- insert in the document head -->

		<script type='text/javascript'>

		  function embedPDF(){

			var myPDF = new PDFObject({ 

			  url: 'http://dev.health2.me/mobapp/test.pdf'

			}).embed("div#my_pdf");  

		  }

		  window.onload = embedPDF; //Feel free to replace window.onload if needed.

		</script>
	
        <script type="text/javascript">
            
        $(document).bind("pageinit", function(){
            var nextId = 1;
			$("#add").click(function() {
				nextId++;
				var content = "<div data-role='collapsible' id='set" + nextId + "'><h3>Section " + nextId + "</h3><p>I am the collapsible content in a set so this feels like an accordion. I am hidden by default because I have the 'collapsed' state; you need to expand the header to see me.</p></div>";
				$("#set").append( content ).collapsibleset('refresh');
			});
			$("#expand").click(function() {
				$("#set").children(":last").collapsible( "expand" );
			});
			$("#collapse").click(function() {
				$("#set").children(":last").collapsible( "collapse" );
			});
					
			
			/*function getConnectedDoctor() {
			var cadenaGUD = '<?php echo $domain;?>/mobapp/getConnectedDoctors_mob.php?MEDID=<?php echo $MedID?>';
			//alert (cadenaGUD);
			$.ajax(
				   {
				   url: cadenaGUD,
				   dataType: "json",
				   async: false,
				   success: function(data)
				   {
				   //alert ('success');
				   doctor = data.items;
				   }
				   });
			}*/
			
            //apply overrides here
        });
    </script>
	<style>
	.map {
		width: 350px;
		height: 400px;
	  }
	</style>

</head> 
<body> 

<div data-role="page" data-theme="d" id="home">

	<div data-role="header" data-theme="a">
		<h1><img src="Icono_H2M.png" alt="" ></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="d" >	
	
		<!--<button type="button" data-icon="gear" data-theme="b" data-iconpos="right" data-mini="true" data-inline="true" id="add">Add</button>
		<button type="button" data-icon="plus" data-theme="b" data-iconpos="right" data-mini="true" data-inline="true" id="expand">Expand last</button>
		<button type="button" data-icon="minus" data-theme="b" data-iconpos="right" data-mini="true" data-inline="true" id="collapse">Collapse last</button>
		-->
		<div data-role="collapsible-set" data-content-theme="a">
			
			<div data-role="collapsible" data-theme="b" data-content-theme="a" data-collapsed="true">
				<h4>Clinical Summary Report</h4>
			<ul data-role="listview">
				<li><a href="#">List item 1</a></li>
			</ul>
			</div>
			<div data-role="collapsible" data-theme="b" data-content-theme="a" data-collapsed="true">
				<h4>Reports</h4>
			<div><ul data-role="listview" style="overflow:auto;height:240px">
				<li><input type="radio" name="radio-mini" id="report1" value="1" checked="checked" />
						<label for="report1">Report one</label></li>
				<li><input type="radio" name="radio-mini" id="report2" value="1" />
						<label for="report2">Report two</label></li>
				<li><input type="radio" name="radio-mini" id="report3" value="1" />
						<label for="report3">Report three</label></li>
				<li><input type="radio" name="radio-mini" id="report4" value="1" />
						<label for="report4">Report 4</label></li>
				<li><input type="radio" name="radio-mini" id="report5" value="1" />
						<label for="report5">Report 5</label></li>
				<li><input type="radio" name="radio-mini" id="report6" value="1" />
						<label for="report6">Report 6</label></li>
				<li><input type="radio" name="radio-mini" id="report7" value="1" />
						<label for="report7">Report 7</label></li>
				<li><input type="radio" name="radio-mini" id="report8" value="1" />
						<label for="report8">Report 8</label></li>
				<li><input type="radio" name="radio-mini" id="report9" value="1" />
						<label for="report9">Report 9</label></li>
				<li><input type="radio" name="radio-mini" id="report10" value="1" />
						<label for="report10">Report 10</label></li>
				<li><input type="radio" name="radio-mini" id="report11" value="1" />
						<label for="report11">Report 11</label></li>
				<li><input type="radio" name="radio-mini" id="report12" value="1" />
						<label for="report12">Report 12</label></li>
			</ul>
			</div><div>
			<ul data-role="listview" style="z-index:1;margin-top:4.5%">
				<li><a href="../ReportImages_mob.php" data-ajax="false">See All</a></li>
			</ul>
			</div>
			</div>
			
			
		</div>		
	</div>
	
	<div data-role="footer" data-id="foo1" data-position="fixed">
	<div data-role="navbar">
		<ul>
			<li><a href="dashboard_mob.php" data-ajax="false">Home</a></li>
			<li><a href="#">Capture</a></li>
			<li><a href="#" data-ajax="false">Reports</a></li>					
		</ul> 
			</div><!-- /navbar -->
		</div><!-- /footer -->
	</div><!-- /page -->
	
	<!-- Page to testing PDF-->
	<div data-role="page" id="PDFviewer" data-theme="d" tabindex="0" class="ui-page ui-body-a" >

	<div data-role="header" data-theme="a">
		<h1><img src="Icono_H2M.png" alt="" ></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="d">	
		
			
			<!--<div><iframe class='map' style='border:1px solid #666CCC; margin:0 auto; display:block;' src='test.pdf' frameborder='1' scrolling='auto'>
			</iframe>
			</div>-->
			
			<iframe src="http://docs.google.com/viewer?url=http://dev.health2.me/mobapp/test.pdf&embedded=true" style="width:auto; height:400px;" frameborder="0"></iframe>
			<!--<object data='http://dev.health2.me/mobapp/test.pdf#' 
					type='application/pdf' 
					width='100%' 
					height='100%'>

			<p>It appears your Web browser is not configured to display PDF files. 
			No worries, just <a href='http://dev.health2.me/mobapp/test.pdf'>click here to download the PDF file.</a></p>

			</object> -->
			<div id="my_pdf"></div>
		
	</div><!-- /content -->
	
	<div data-role="footer" data-id="foo1" data-position="fixed">
		<div data-role="navbar">
			<ul>
			<li><a href="Report_mob.php" data-ajax="false">Back</a></li>					
			</ul> 
		</div><!-- /navbar -->
	<!--</div><!-- /footer -->
	</div>
</body>
</html>

