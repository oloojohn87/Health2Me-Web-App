<?php

$doc = $_GET['doctor'];
$pat = $_GET['patient'];
$max = $_GET['max'];
$index = -1;
if(isset($_GET['index']))
    $index = $_GET['index'];
?>
<html>
    <head>
        <title>Timeline Test</title>
    
        <!--<link rel="stylesheet" href="css/tipped.css" >
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css" >
        <link rel="stylesheet" href="css/jquery-ui-1.8.16.custom.css" media="screen"  />-->
    </head>
    <body>
        
        
        <script src="js/jquery.min.js"></script>
        <script src="js/jquery.easing.1.3.js"></script>
        <script src="js/tipped.js"></script>
        <script src="js/imagesloaded.pkgd.min.js"></script>
        <script src="js/H2M_Timeline.js"></script>
        <script src="js/jquery-ui.min.js"></script>
        
        <div id="main_timeline<?php echo $pat; ?>" style="width: 100%; height: 150px;"></div>
        
        <script>
            $(document).ready(function()
            {
                $("#main_timeline<?php echo $pat; ?>").empty();
                 //$("#main_timeline<?php echo $pat; ?>").H2M_Timeline({doctor: doc['id'], patient: $("#USERID").val(), max: 24, minimized: 0, onReply: function(){get_personal_doctor_messages()}});
                var my_doc = <?php echo $doc; ?>;
                var my_pat = <?php echo $pat; ?>;
                var my_max = <?php echo $max; ?>;
                //console.log('--------------------------- Doc: '+<?php echo $doc ?>+' Pat: '+<?php echo $pat ?>+'  Max: '+<?php echo $max?>);
                console.log('--------------------------- Doc: '+my_doc+' Pat: '+my_pat+'  Max: '+my_max);
            
                $("#main_timeline<?php echo $pat; ?>").H2M_Timeline({doctor: my_doc, patient: my_pat, max: my_max, minimized: 0});
            //, onReply: function(){get_personal_doctor_messages()}
        
            });
        </script>
    </body>
</html>
