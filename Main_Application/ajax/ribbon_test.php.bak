<script src="js/jquery.min.js"></script>
<script src="js/timezones.js" type="text/javascript"></script>  
<script>   
    var current_timezone = get_timezone_offset();
    url= "get_timezone.php?timezone="+current_timezone+"&email=lorigreensmith@gmail.com";
    var RecTipo=LanzaAjax(url);
    function LanzaAjax (DirURL)
		{
		var RecTipo = 'SIN MODIFICACIÓN';
		$.ajax(
           {
           url: DirURL,
           dataType: "html",
           async: false,
           complete: function(){ //alert('Completed');
                    },
           success: function(data) {
                    if (typeof data == "string") {
                                RecTipo = data;
                                }
                     }
            });
		return RecTipo;
		}   
    
    alert(RecTipo);
</script>

<?php
 require("environment_detail.php");
$dbhost = $env_var_db['dbhost'];
$dbname = $env_var_db['dbname'];
$dbuser = $env_var_db['dbuser'];
$dbpass = $env_var_db['dbpass'];


    $tbl_name="usuarios"; // Table name

    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 
                                                                                            
    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }		

    $DateTime = 0;
    date_default_timezone_set('America/Chicago');

    $sql=$con->prepare("SELECT DateTime FROM ".$dbname.".consults where Doctor=280 ORDER BY DateTime DESC LIMIT 1;");
    $sql->execute();
    $timeZone = date_default_timezone_get();

    $resultRow = $sql->fetch(PDO::FETCH_ASSOC);
    $lastDate = $resultRow['DateTime'];
    //echo 'DateTime : '.$lastDate.'<BR>';

    //for testing DEBUG
    $lastDate = '2014-08-20 15:50:13';
    $startTime = 0;


    //if you found a consultation
    if ($lastDate) {
            $startTime = time()-strtotime($lastDate);
            if ($startTime < 0) {
                $startTime = 0;   
            }    
         
    }    
    echo $startTime.'<BR>'; 

?>
<html lang="en" style="background: #F9F9F9;"><head>
<link href="css/style.css" rel="stylesheet">
<link href="css/bootstrap.css" rel="stylesheet">
<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">    
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="/js/timer.js"></script>
 <script src="js/jquery-easy-ticker-master/jquery.easy-ticker.js"></script> 

    
<style>
*{padding:0;margin:0}
ul li{list-style:none}
#tickerwrapper{margin:0px; padding:0px;width:300px;}
#tickerwrapper ul li{padding:5px;text-align:left}
#tickerwrapper .container{width:300px;height:60px;overflow:hidden; float:left;display:inline-block}    
    
                  div.H2MExBox{
                        width:330px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:10px;
                        background-color: rgb(34,174,255);
                        opacity: 0.1;
                    }
                    
                    div.H2MInText{
                        padding:0px; 
                        font-size:12px; 
                        font-family: "Arial";
                        text-align:left; 
                        color:grey; 
                        display:table-cell; 
                        vertical-align:middle;
                        padding-left:20px;
                        width:50%;  
                          
                        }
                    
                    div.H2MTray{
                        width:300px; 
                        height:50px; 
                        border:1px solid #cacaca; 
                        border-radius:5px; 
                        display:table;
                        margin:0px;
                        background-color: #F8F8F8;
                     }
                    
                    div.H2MTSCont{
                        width:300px;
                        overflow:hidden;
                    }
                    
                    div.H2MTrayScroll{
                        width:1000px;
                    }    
                        
</style>    
       
</head>
<body>    
 
<script type="text/javascript">
   //alert("hello");  
   
</script>    
<!-- timer -->             
<div style="align:center; margin:100px;">    
        <div class="timer">
            <span class="hour">00</span>:<span class="minute">00</span>:<span class="second">00</span>
        </div>
        <div class="control">
            <button style="display:none;" id="startButton" onClick="timer.start(1000);timer.reset(<?php echo $startTime; ?>);">Start</button> 
           <button  onClick="timer.stop()">Stop</button> 
            <button onClick="timer.reset(0)">Reset</button> 
            <button onClick="timer.mode(1)">Count up</button> 
            <button onClick="timer.mode(0)">Count down</button>
        </div>   
</div>
<!--            <div class="H2MTray">
        

                        <div class="H2MInText" style="width:270px;">
                            
                            <div id="tickerwrapper">
                            <div class="container">
                                <ul>
                                    <li>You are connected to 2 doctors</li>
                                    <li>You have 18 records available</li>
                                    <li>View your patient summary here</li>
                                    <li>You have 2 upcoming appointments</li>
                                    <li>View your medical history here</li>
                                    <li>View updates here</li>
                                </ul>
                            </div>
                                
                            </div>
                            </div>
                <div style="width:40%; margin-right: 20px; margin-top:20px; text-align:right;display:inline-block"><span class="icon-bullhorn icon-2x"></span></div>    
                        </div>-->
    
        
    <br><br>    
    
   <!-- <div id="TickerBC57768586" class="bc_ticker" style="width: 400px;height: 60px;overflow: auto;overflow-x: hidden;overflow-y: hidden;border-style: none;border-width: 0;border-color: #FFFFFF;background-color: #CDCDCD;">
<table width="100%">
	<tr>
		<td width="10" style="vertical-align: top;" style="color: #0000CC; font-size: 10pt; font-family: Arial,Helvetica;"><a id="TickerPrevBC57768586" href="javascript: ;" onclick="PrevTickerBC57768586();" class="bc_tickerarrow" style="text-decoration: none; color: #0000CC;">&laquo;</a></td>
		<td id="TickerContentBC57768586" class="bc_tickercontent" style="vertical-align: top;background-color: #CDCDCD;">
			<a id="TickerLinkBC57768586" href="javascript: ;" class="bc_tickerlink" style="text-decoration: none;" target="_top"><b><span id="TickerTitleBC57768586" class="bc_tickertitle" style="color: #0000CC; font-size: 10pt; font-family: Arial,Helvetica;"></span></b></a>			<span id="TickerSummaryBC57768586" class="bc_tickersummary" style="color: #000000; font-size: 10pt; font-family: Arial,Helvetica;"></span>
		</td>
		<td width="10" style="vertical-align: top;" style="color: #0000CC; font-size: 10pt; font-family: Arial,Helvetica;"><a id="TickerNextBC57768586" href="javascript: ;" onclick="NextTickerBC57768586();" class="bc_tickerarrow" style="text-decoration: none; color: #0000CC;">&raquo;</a></td>
	</tr>
</table>
<!-- DO NOT CHANGE OR REMOVE THE FOLLOWING NOSCRIPT SECTION OR THE BLASTCASTA NEWS TICKER WILL NOT FUNCTION PROPERLY. -->
<!--<noscript><a href="http://www.blastcasta.com/" title="News Ticker by BlastCasta"><img src="http://www.poweringnews.com/images/tp.gif" border="0" /></a></noscript>
</div>-->

<script id="scr57768586" type="text/javascript"></script>
<script type="text/javascript"> /* <![CDATA[ */ 
setTimeout('document.getElementById(\'scr57768586\').src = (document.location.protocol == \'https:\' ? \'https\' : \'http\') + \'://www.poweringnews.com/ticker-js.aspx?feedurl=http%3A//newsrss.bbc.co.uk/rss/newsonline_world_edition/health/rss.xml&changedelay=5&maxitems=-1&showsummary=0&objectid=57768586\'', 500);
 /* ]]> */ </script>
                       
<script>
    //alert(timezoneUTC.offset());
    //alert("hello");
    
    
    var containerheight = 0;
    var numbercount = 0;
    var liheight;
    var index = 1;
    function callticker() {
        $(".container ul").animate({
            "margin-top": (-1) * (liheight * index)
        }

        , 2500);
        if (index != numbercount - 1) {
            index = index + 1;
        }
        else {
            index = 0;
        }
        timer = setTimeout("callticker()", 3600);
    }       

        
		$( document ).ready(function() {
                numbercount = $(".container ul li").size();
                liheight = $(".container ul li").outerHeight();
                containerheight = $(".container ul  li").outerHeight() * numbercount;
                $(".container ul").css("height", containerheight);
                var timer = setTimeout("callticker()", 3600);  
            
            
                console.log( "timer running ..." );
                $('#startButton').trigger('click');
        });
    
        redTimer = 600000;
        startTimer = <?php echo $startTime; ?>*1000;
        if (startTimer > redTimer) {
            redTimer=0;   
        } else {
            redTimer = redTimer-startTimer;   
        }    
        
        setTimeout(function() {
        // Do something after 3 seconds
        // This can be direct code, or call to some other function
                    
            $( ".timer" ).css( "border", "3px solid red" );
            $( ".timer" ).css( "color", "red" );
        }, redTimer);
    
     
       
</script> 
</body>
</html>    
