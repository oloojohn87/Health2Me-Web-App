<html>
    <head>

    <?php
        session_start();
        require("environment_detail.php");
        $Usuario = $_GET['Usuario'];
        $MedID = $_SESSION['MEDID'];
        $pass = $_SESSION['decrypt'];

        $dbhost = $env_var_db['dbhost'];
        $dbname = $env_var_db['dbname'];
        $dbuser = $env_var_db['dbuser'];
        $dbpass = $env_var_db['dbpass'];
        $domain = $env_var_db['hardcode'];
        $local = $env_var_db['local'];

        $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        if (!$con)
        {
            die('Could not connect: ' . mysql_error());
        }
    ?>
        <link rel='stylesheet' href='<?php echo $domain; ?>/font-awesome/css/font-awesome.min.css'>
        <style>
            html{
                padding: 0px;
                margin: 0px;
            }
            body{
                padding: 0px;
                margin: 0px;
            }
        </style>
    </head>

    <body>
    <?php

    $patient = $con->prepare("SELECT CONCAT(Name, ' ', Surname) AS Name FROM usuarios WHERE Identif = ?");
    $patient->bindValue(1, $Usuario, PDO::PARAM_INT);
    $patient->execute();
    $patient_row = $patient->fetch(PDO::FETCH_ASSOC);

    $patient_name = $patient_row['Name'];
    $doctor_names = array();

    $doctors_files = array();
    $patient_files = array();


    $doctors = $con->prepare("SELECT DLD.IdMED2 AS ID, CONCAT(D.Name, ' ', D.Surname) AS Name, DLD.attachments AS Attachments FROM 
    (
        SELECT IdMED2, attachments FROM doctorslinkdoctors WHERE IdMED = ? AND IdPac = ?
    ) AS DLD 
        LEFT JOIN 
    doctors D 
        ON
            D.id = DLD.IdMED2 
        WHERE 
            D.Name IS NOT NULL AND D.Surname IS NOT NULL");
    $doctors->bindValue(1, $MedID, PDO::PARAM_INT);
    $doctors->bindValue(2, $Usuario, PDO::PARAM_INT);
    $doctors->execute();
    echo '<div id="sharestation" style="width: 100%; height: 30px; text-align: center; font-family: Helvetica, sans-serif; color: #888; margin-top: 15px;">Sharing with ';
    echo '<select id="user_select" style="width: 200px; height: 30px; padding: 15px; background-color: #FFF; border: 1px solid #DDD; font-size: 14px; color: #888;">';
    echo '<option value="'.$Usuario.'">'.$patient_row['Name'].'</option>';
    while($doctors_row = $doctors->fetch(PDO::FETCH_ASSOC))
    {
        $doctor_names[($doctors_row['ID'])] = $doctors_row['Name'];
        echo '<option value="'.$doctors_row['ID'].'">Dr. '.$doctors_row['Name'].'</option>';
        $doctors_files[($doctors_row['ID'])] = explode(' ', $doctors_row['Attachments']);
        for($i = count($doctors_files[($doctors_row['ID'])]) - 1; $i >= 0; $i--)
        {
            if(strlen($doctors_files[($doctors_row['ID'])][$i]) == 0)
                array_splice($doctors_files[($doctors_row['ID'])], $i, 1);
        }
        
    }
    echo '</select>';

    $reports = $con->prepare("SELECT * FROM (SELECT * FROM lifepin WHERE IdUsu = ? ORDER BY Fecha DESC) AS LP LEFT JOIN tipopin TP ON LP.Tipo = TP.Id");
    $reports->bindValue(1, $Usuario, PDO::PARAM_INT);
    $reports->execute();
    $num_reports = $reports->rowCount();
    
    echo '<span id="number_of_shared_files" style="margin-left: 8px;">0</span> of '.$num_reports.' reports';
    echo '<button id="save" style="background-color: #54BC00; width: 30px; height: 30px; margin-left: 10px; color: #FFF; border: 0px solid #FFF; outline: none; font-family: Helvetica, sans-serif; font-size: 18px; border-radius: 30px; cursor: pointer;"><i class="icon-lock" ></i></button>';
    echo '</div>';
    echo '<div id="reports_container" style="width: 100%; height: 300px; overflow-y: hidden; overflow-x: scroll; margin-top: 20px;"><div style="width: '.($num_reports * 172).'px; height: 300px; overflow-y: hidden; border-top: 9px solid #F2F2F2;">';
    echo '  <style>
                .report{
                    float: left; 
                    width: 150px; 
                    height: 250px; 
                    margin-right: 20px;
                    margin-top: 15px;
                    background-color: #FFF; 
                    -webkit-box-shadow: 1px 1px 8px rgba(25, 25, 25, 0.15); 
                    -moz-box-shadow:1px 1px 8px rgba(25, 25, 25, 0.15); 
                    box-shadow:1px 1px 8px rgba(25, 25, 25, 0.15);
                    border-radius: 5px;
                    border: 1px solid #DDD;
                    overflow: hidden;
                    
                }
                .report:hover{
                    -webkit-box-shadow: 1px 1px 8px rgba(25, 25, 25, 0.3); 
                    -moz-box-shadow:1px 1px 8px rgba(25, 25, 25, 0.3); 
                    box-shadow:1px 1px 8px rgba(25, 25, 25, 0.3);
                    border: 1px solid #C2C2C2;
                }
                .report_header{ 
                    width: 150px; 
                    height: 50px; 
                    background-color: #EEE; 
                    color: #999; 
                }
                .report_date{
                    float: right; 
                    
                    font-family: Helvetica, sans-serif; 
                    font-size: 12px; 
                    margin-right: 10px; 
                    margin-top: 10px;
                }
                .report_footer{
                    width: 100%; 
                    height: 20px; 
                    padding-top: 5px; 
                    opacity: 1,0;
                    margin-top: -25px; 
                    z-index: 2; 
                    position: relative; 
                    color: #FFF; 
                    text-align: center; 
                    font-family: Helvetica, sans-serif;
                }
                .select_button{
                    width: 30px; 
                    height: 30px; 
                    border-radius: 30px; 
                    background-color: #FFF; 
                    border: 2px solid #DDD; 
                    margin-top: 10px; 
                    /*margin-left: 10px; */
                    outline: none; 
                    cursor: pointer; 
                    font-size: 18px; 
                    color: #54BC00;
                }
                .select_button:disabled{
                    opacity: 0.6;
                }
                
                .patient_tag{
                    width: 130px;
                    height: 10px;
                    padding: 5px;
                    border-radius: 5px;
                    color: #FFF;
                    font-family: Helvetica, sans-serif;
                    background-color: #54BC00;
                    text-align: left;
                    margin-left: 5px;
                    margin-top: 3px;
                    margin-bottom: 3px;
                    font-size: 12px;
                    float: left;
                }
                .doctor_tag{
                    width: 130px;
                    height: 10px;
                    padding: 5px;
                    border-radius: 5px;
                    color: #FFF;
                    font-family: Helvetica, sans-serif;
                    background-color: #22AEFF;
                    text-align: left;
                    margin-left: 5px;
                    margin-top: 3px;
                    margin-bottom: 3px;
                    font-size: 12px;
                    float: left;
                }
                
                
                ::-webkit-scrollbar {
                    width: 12px;
                    height: 9px;
                }
                ::-webkit-scrollbar-track {
                    background-color: #F2F2F2;
                    border-radius: 2px;
                }
                ::-webkit-scrollbar-thumb {
                    border-radius: 2px;
                    background-color: #BBB;
                }
            </style>';

    while($reports_row = $reports->fetch(PDO::FETCH_ASSOC))
    {
        if($reports_row['hide_from_member'] == 0)
            array_push($patient_files, $reports_row['IdPin']);
        $file = decrypt_files($reports_row['RawImage'], $MedID, $pass);
        echo '<div class="report">';
        echo '<div class="report_header">';
        echo '<div class="report_date">'.date('M j, Y', strtotime($reports_row['Fecha'])).'</div>';
        if($reports_row['hide_from_member'] == 0)
            echo '<button id="select_'.$reports_row['IdPin'].'" class="select_button pin_button_selected" style="border: 2px solid #54BC00;" data-selected="1" disabled><i class="icon-ok" style="margin-left: -2px;"></i></button>';
        else
            echo '<button id="select_'.$reports_row['IdPin'].'" class="select_button pin_button_selected" data-selected="0"></button>';
        echo '</div>';
        echo '<img id="thumbnail_'.$reports_row['IdPin'].'" src="'.$file.'" height="200" width="150" style="z-index: 1; position: relative; cursor: pointer;" />';
        $type_label = $reports_row['NombreCorto'];
        if(strlen($type_label) == 0)
            $type_label = 'N/A';
        $type_label = str_replace(' ', '', $type_label);
        echo '<div class="report_footer" style="background-color: '.$reports_row['Color'].'" >'.$type_label.'</div>';
        echo '</div>';
    }
    echo '</div></div>';

    echo '<div id="tags_container" style="width: 100%; height: 100px; margin: 7px;">';
    echo '</div>';

    function decrypt_files($rawimage, $queMed, $pass)
    {
        $ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
        $extensionR = strtolower(substr($rawimage,strlen($rawimage)-3,3));
        if($extensionR=='jpg')
        {
            $extension='jpg';
        }elseif($extensionR == 'gif'){
            $extension = 'gif';
        }else{
            $extension='png';
        }
        $filename = $domain.'/temp/'.$queMed.'/PackagesTH_Encrypted/'.$ImageRaiz.'.'.$extension;	
        if (!file_exists($filename))  
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ".$local."PackagesTH_Encrypted/".$ImageRaiz.".".$extension." -out ".$local."temp/".$queMed."/PackagesTH_Encrypted/".$ImageRaiz.".".$extension);
        }

        return $filename;
    }

    ?>
        
    <script src="<?php echo $domain; ?>/js/jquery.min.js"></script> 
    <script>
        $(document).ready(function()
        {
            var doctor = <?php echo $MedID; ?>;
            var patient = <?php echo $Usuario; ?>;
            
            var doctor_names = JSON.parse('<?php echo json_encode($doctor_names); ?>');
            var patient_name = '<?php echo $patient_name; ?>';
            
            var patient_files = JSON.parse('<?php echo json_encode($patient_files); ?>');
            var patient_num_files = patient_files.length;
            var doctor_files = JSON.parse('<?php echo json_encode($doctors_files); ?>');
            var doctor_num_files = {};
            for (var property in doctor_files) 
            {
                if (doctor_files.hasOwnProperty(property)) 
                {
                    doctor_num_files[property] = doctor_files[property].length;
                }
            }
            
            var currently_selected_file = 0;
            $("#number_of_shared_files").text(patient_files.length);
            
            $("button[id^='select_']").on('click', function()
            {
                
                var id = $(this).attr("id").split('_')[1];
                if($(this).data("selected") == 0)
                {
                    $(this).data("selected", 1);
                    $(this).css('border', '2px solid #54BC00');
                    $(this).html('<i class="icon-ok" style="margin-left: -2px;" />');
                    if($("select#user_select option:selected").text().indexOf("Dr.") > -1)
                        doctor_files[$("#user_select").val()].push(id);
                    else
                        patient_files.push(id);
                }
                else
                {
                    $(this).data("selected", 0);
                    $(this).css('border', '2px solid #DDD');
                    $(this).html('');
                    if($("select#user_select option:selected").text().indexOf("Dr.") > -1)
                    {
                        var index = doctor_files[$("#user_select").val()].indexOf(id);
                        doctor_files[$("#user_select").val()].splice(index, 1);
                    }
                    else
                    {
                        var index = patient_files.indexOf(id);
                        patient_files.splice(index, 1);
                    }
                    
                }
                if($("select#user_select option:selected").text().indexOf("Dr.") > -1)
                    $("#number_of_shared_files").text(doctor_files[$("#user_select").val()].length);
                else
                    $("#number_of_shared_files").text(patient_files.length);
                
                update_tags();
            });
            
            $("#user_select").on('change', function()
            {
                $('.select_button').data("selected", 0);
                $('.select_button').css('border', '2px solid #DDD');
                $('.select_button').html('');
                $('.select_button').removeAttr('disabled');
                if($("select#user_select option:selected").text().indexOf("Dr.") > -1)
                {
                    for(var k = 0; k < doctor_files[$(this).val()].length; k++)
                    {
                        $("#select_"+doctor_files[$(this).val()][k]).data("selected", 1);
                        $("#select_"+doctor_files[$(this).val()][k]).css('border', '2px solid #54BC00');
                        $("#select_"+doctor_files[$(this).val()][k]).html('<i class="icon-ok" style="margin-left: -2px;" />');
                        if(doctor_num_files[$(this).val()] > k)
                            $("#select_"+doctor_files[$(this).val()][k]).attr('disabled', 'disabled');
                    }
                }
                else
                {
                    for(var k = 0; k < patient_files.length; k++)
                    {
                        $("#select_"+patient_files[k]).data("selected", 1);
                        $("#select_"+patient_files[k]).css('border', '2px solid #54BC00');
                        $("#select_"+patient_files[k]).html('<i class="icon-ok" style="margin-left: -2px;" />');
                        if(patient_num_files > k)
                            $("#select_"+patient_files[k]).attr('disabled', 'disabled');
                    }
                }
                if($("select#user_select option:selected").text().indexOf("Dr.") > -1)
                    $("#number_of_shared_files").text(doctor_files[$("#user_select").val()].length);
                else
                    $("#number_of_shared_files").text(patient_files.length);
            });
            
            function update_tags()
            {
                
                $("#tags_container").empty();
                if(patient_files.indexOf(currently_selected_file) > -1)
                    $("#tags_container").append('<div class="patient_tag">'+patient_name+'</div>');
                for (var property in doctor_files) 
                {
                    if (doctor_files.hasOwnProperty(property)) 
                    {
                        if(doctor_files[property].indexOf(currently_selected_file) > -1)
                            $("#tags_container").append('<div class="doctor_tag">Dr. '+doctor_names[property]+'</div>');
                    }
                }
            }
            
            $("img[id^='thumbnail_']").on('click', function()
            {
                var id = $(this).attr("id").split("_")[1];
                currently_selected_file = id;
                $('.report_header').css('background-color', '#EEE');
                $('.report_header').css('color', '#999');
                $(this).parent().children('.report_header').eq(0).css('background-color', '#54BC00');
                $(this).parent().children('.report_header').eq(0).css('color', '#FFF');
                update_tags();
            });
            
            $("#sharestation").on('click', 'button#save', function()
            {
                $("#reports_container").css('opacity', '0.5');
                $(this).html('<i class="icon-spinner icon-spin" ></i>');
                $.post("save_shared_reports.php", {doctor: doctor, patient: patient, doctor_files: JSON.stringify(doctor_files), patient_files: JSON.stringify(patient_files)}, function(data, status)
                {
                    $("#reports_container").css('opacity', '1.0');
                    $('#save').html('<i class="icon-lock" ></i>');
                    $("button[id^='select_']").each(function()
                    {
                        if($(this).data('selected') == 1)
                        {
                            $(this).attr('disabled', 'disabled');
                        }
                    });
                    
                    for (var property in doctor_files) 
                    {
                        if (doctor_files.hasOwnProperty(property)) 
                        {
                            doctor_num_files[property] = doctor_files[property].length;
                        }
                    }
                    patient_num_files = patient_files.length;
                });
            });
            
        });
    </script>

    </body>
</html>
