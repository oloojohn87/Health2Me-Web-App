<?php

    require("../environment_detail.php");
    $dbhost = $env_var_db['dbhost'];
    $dbname = $env_var_db['dbname'];
    $dbuser = $env_var_db['dbuser'];
    $dbpass = $env_var_db['dbpass'];
    $domain = $env_var_db['hardcode'];
    $local = $env_var_db['local'];

    $tbl_name="messages"; // Table name

    $con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, 
                                                                                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    if (!$con)
    {
        die('Could not connect: ' . mysql_error());
    }	

    $enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
    $enc_result->execute();

    $row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);

    $pass = $row_enc['pass'];

    $doc = $_GET['doc_id'];
    $pat = $_GET['pat_id'];

    $doc_name = '';
    $pat_name = '';

    // GET DOCTOR NAME

    $doc_ = $con->prepare("SELECT CONCAT(name, ' ', surname) AS fullname FROM doctors WHERE id = ?");
    $doc_->bindValue(1, $doc, PDO::PARAM_INT);
    $doc_->execute();
    while($doc_row = $doc_->fetch(PDO::FETCH_ASSOC))
    {
        $doc_name = $doc_row['fullname'];
    }

    // GET PATIENT NAME

    $pat_ = $con->prepare("SELECT CONCAT(name, ' ', surname) AS fullname FROM usuarios WHERE Identif = ?");
    $pat_->bindValue(1, $pat, PDO::PARAM_INT);
    $pat_->execute();
    while($pat_row = $pat_->fetch(PDO::FETCH_ASSOC))
    {
        $pat_name = $pat_row['fullname'];
    }

    // GET PATIENT'S PHR

    $phr = '';
    $phr_ = $con->prepare("SELECT RawImage FROM lifepin WHERE IdUsu = ? AND orig_filename LIKE 'PHR_%' ORDER BY FechaInput DESC LIMIT 1");
    $phr_->bindValue(1, $pat, PDO::PARAM_INT);
    $phr_->execute();
    while($phr_row = $phr_->fetch(PDO::FETCH_ASSOC))
    {
        $phr = $phr_row['RawImage'];
    }

    if(strlen($phr) > 0)
    {
        $file = decrypt_files($phr, $doc, $pass);
    }
    
    function decrypt_files($rawimage, $queMed, $pass)
    {
        $ImageRaiz = substr($rawimage,0,strlen($rawimage)-4);
        $extensionR = strtolower(substr($rawimage,strlen($rawimage)-3,3));
        $filename = '../'.$local.'temp/'.$queMed.'/Packages_Encrypted/'.$ImageRaiz.'.'.$extensionR;
        if (!file_exists($filename))  
        {
            $out = shell_exec("echo '".$pass."' | openssl aes-256-cbc -pass stdin -d -in ../".$local."Packages_Encrypted/".$ImageRaiz.".".$extensionR." -out ../".$local."temp/".$queMed."/Packages_Encrypted/".$ImageRaiz.".".$extensionR);
        }

        return $filename;
    }
?>

<html>
    <head>
        <title>Catapult Telemedicine NP</title>
        
        <!-- HIDDEN INPUTS -->
        <input id="DOCNAME" type="hidden" value="<?php echo $doc_name; ?>" />
        <input id="PATNAME" type="hidden" value="<?php echo $pat_name; ?>" />
        <input id="PHR" type="hidden" value="<?php echo $phr; ?>" />
        
        <link rel="stylesheet" href="../css/jquery-ui-1.8.16.custom.css" media="screen"  />
        <link rel="stylesheet" href="../font-awesome/css/font-awesome.min.css">
        <link rel='stylesheet' type='text/css' href='../css/sweet-alert.css'>
        <!-- CSS -->
        <style>
            @-webkit-keyframes glow {
                from { opacity: 0.5;}
                50% { opacity: 0.99; }
                to { opacity: 0.5; }
            }
            #connection_toolbar{
                width: 80%;
                min-width: 1050px;
                height: 27PX;
                margin: auto;
                border: 1px solid #EE3423;
                border-radius: 5px;
                padding: 8px;
                font-family: Helvetica, sans-serif;
                color: #444;
            }
            #connection_description{
                height: 25px;
                margin-top: 3px;
                float: left;
                width: 36%;
            }
            #connection_status{
                width: 20%;
                height: 25px;
                margin-top: 3px;
                float: left;
            }
            #connection_time_elapsed{
                width: 17%;
                height: 25px;
                margin-top: 3px;
                color: #444;
                float: left;
            }
            #connection_close_consultation{
                width: 13%;
                height: 25px;
                float: right;
                margin-left: 8px;
            }
            #start_consultation_button{
                width: 13%;
                height: 25px;
                float: right;
                border: 1px solid #FFF;
                border-radius: 5px;
                outline: none;
                color: #EEA723;
                margin-top: -3px;
                background-color: #FFF;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 14px;
            }
            #close_consultation_button{
                width: 100%;
                height: 25px;
                float: left;
                border: 1px solid #EE3423;
                border-radius: 5px;
                outline: none;
                color: #FFF;
                background-color: #EE3423;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 14px;
            }
            #connection_window_buttons{
                width: 12%;
                height: 25px;
                float: right;
            }
            #next_appointment_warning{
                width: 80%;
                min-width: 1050px;
                height: 23px;
                margin: auto;
                margin-bottom: 7px;
                border: 1px solid #EEA723;
                background-color: #EEA723;
                border-radius: 5px;
                padding: 8px;
                padding-top: 12px;
                font-family: Helvetica, sans-serif;
                color: #FFF;
                -webkit-animation: glow 1.5s linear infinite;
                display: none;
            }
            #notes_window {
                font-family: Calibri, sans-serif;
            }
            #notes_text{
                width: 100%;
                height: 200px;
                margin-top: 15px;
                border: 1px solid #DDD;
                border-radius: 5px;
                outline: none;
                color: #666;
                padding: 10px;
                font-size: 12px;
                resize: none;
            }
            #notes_buttons{
                width: 420px;
                height: 30px;
                margin: auto;
            }
            .notes_button{
                width: 33%;
                height: 30px;
                border: 1px solid #EE3423;
                outline: none;
                color: #EE3423;
                background-color: #FFF;
                float: left;
                cursor: pointer;
            }
            .notes_button_selected{
                color: #FFF;
                background-color: #EE3423;
            }
            #report_frame{
                z-index: 0;
                cursor: default;
            }
            #report_editor{
                width: 100%; 
                height: 770px;
                cursor: none;
                position: absolute;
            }
            .connection_window_button{
                width: 25px;
                height: 25px;
                border-radius: 25px;
                outline: none;
                border: 1px solid #EE3423;
                background-color: #FFF;
                color: #EE3423;
                float: right;
                margin-left: 5px;
                cursor: pointer;
            }
            .connection_window_button:disabled{
                border: 1px solid #EEA39D;
                color: #EEA39D;
                cursor: default;
            }
            .connection_window_button_selected{
                background-color: #EE3423;
                color: #FFF;
            }
            .connection_window_button_alert{
                background-color: #EE3423;
                color: #FFF;
                -webkit-animation: glow 1.0s linear infinite;
            }
            .ui-dialog-titlebar {
                background-color: #EE3423;
                border: 0px solid #FFF;
                background-image: none;
                color: #000;
            }
            .window{
                background-color: #F8F8F8;
                box-shadow: 4px 4px 6px rgba(0, 0, 0, 0.05);
                color: #777;
            }
            #report_cursor{
                width: 12px;
                height: 12px;
                border-radius: 12px;
                background-color: #EE3423;
                opacity: 0.3;
                z-index: 1;
                position: absolute;
                display: none;
            }
            #report_toolbar{
                width: 100%;
                height: 30px;
                margin-top: 10px;
            }
            .report_toolbar_button{
                width: 30px;
                height: 30px;
                float: right;
                border: 1px solid #EE3423;
                border-radius: 30px;
                outline: none;
                color: #FFF;
                background-color: #EE645A;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 32px;
                float: left;
                margin-right: 7px;
            }
            
            .report_toolbar_button_alt{
                width: 30px;
                height: 30px;
                float: right;
                border: 1px solid #EE3423;
                border-radius: 30px;
                outline: none;
                color: #FFF;
                background-color: #EE3423;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 32px;
                float: left;
                margin-right: 7px;
            }
            
            .report_toolbar_button_off{
                background-color: #FFF;
                color:#EE3423;
            }
            .report_toolbar_button:hover{
                border: 1px solid #EE3423;
                background-color: #EE3423;
                color: #FFF;
            }
            
            #report_pagination{
                width: 200px;
                height: 25px;
                margin: auto;
                margin-bottom: 5px;
                color: #555;
            }
            
            .report_page_button{
                width: 25px;
                height: 25px;
                float: right;
                border: 1px solid #EE3423;
                border-radius: 25px;
                outline: none;
                color: #FFF;
                background-color: #EE645A;
                cursor: pointer;
                font-family: Helvetica, sans-serif;
                font-size: 12px;
                float: left;
            }
            .report_page_button:hover{
                border: 1px solid #EE3423;
                background-color: #EE3423;
                color: #FFF;
            }
            
            #report_pagination_text{
                width: 150px;
                height: 25px;
                text-align: center;
                float: left;
            }
            
            .chat_entry_remote{
                width: 80%;
                padding: 4px;
                float: left;
                background-color: #EE3423;
                color: #FFF;
                border: 1px solid #EE3423;
                border-radius: 5px;
                margin-top: 5px;
                margin-bottom: 5px;
                font-size: 12px;
            }
            .chat_entry_local{
                width: 80%;
                padding: 4px;
                float: right;
                background-color: #FFF;
                color: #777;
                border: 1px solid #D8D8D8;
                border-radius: 5px;
                margin-top: 5px;
                margin-bottom: 5px;
                font-size: 12px;
            }
            #chat_send_button{
                width: 25px; 
                height: 25px; 
                border-radius: 25px; 
                color: #FFF; 
                background-color: #54BC00; 
                outline: none; 
                border: 0px solid #FFF; 
                float: right;
                cursor: pointer;
            }
            #chat_text_box{
                width: 88%; 
                float: left; 
                font-size: 12px; 
                color: #777; 
                border-radius: 5px; 
                outline: none; 
                padding: 5px; 
                border: 1px solid #DDD;
            }
            
            .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
                color: #AAA;
            }
            .ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active {
                color: #EE3423;
            }
            #action_items {
                margin: 15px 0 0 30px;
            }
            .trifold_item{
                width: 500px;
                margin: auto;
                margin-top: 20px;
                margin-bottom: 20px;
            }
            
            .trifold_item_check{
                width: 30px;
                height: 30px;
                border-radius: 30px;
                background-color: #FFF;
                border: 3px solid #EE3423;
                margin-top: -8px;
                margin-left: -17px;
                margin-right: 8px;
                float: left;
            }
            .ui-accordion .ui-accordion-header {
                background: none;
            }
            
            .trifold_item_header, #accordion .ui-accordion-header{
                width: 98%;
                padding: 1%;
                height: 20px;
                font-family: Calibri, sans-serif;
                font-size: 18px;
                color: #FFF;
                background-color: #EE3423;
                border-radius: 4px;
                margin-top: 15px;
            }
            
            .trifold_item_body{
                width: 91%;
                padding: 1%;
                font-family: Calibri, sans-serif;
                font-size: 14px;
                color: #333;
                margin: auto;
                margin-top: 10px;
            }
            input[type=checkbox], span.ui-icon-triangle-1-e, span.ui-icon-triangle-1-s {
                display:none; 
                margin:10px;
            }
            
            .ui-accordion .ui-accordion-header .ui-icon-triangle-1-e, .ui-accordion .ui-accordion-header .ui-icon-triangle-1-s {
                position: relative;
                margin: -18px 18px 0 -25px;                
            }
            input[type=checkbox] + label, span.ui-icon-triangle-1-e, span.ui-icon-triangle-1-s {
                display:inline-block;
                
                width: 30px;
                height: 30px;
                border-radius: 30px;
                background-color: #FFF;
                border: 3px solid #EE3423;
                margin-top: -8px;
                margin-left: -17px;
                margin-right: 8px;
                float: left;
            }
            input[type=checkbox]:checked + label, span.ui-icon-triangle-1-s { 
                background: url('../images/icons/red_dot.png') center no-repeat;
                background-size:60%;
                background-color:#FFF;
                
                -webkit-box-shadow: inset 0px 0px 10px #000000;
                -moz-box-shadow: inset 0px 0px 10px #000000;
                box-shadow: inset 0px 0px 10px #000000;
            }
            #accordion {
                font-family: Calibri, sans-serif;
                font-size: 16px;
            }
                
        </style>
        <script src="../js/jquery.min.js"></script>
        <script src="../js/jquery-ui.min.js"></script>

        <!--<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>-->
    </head>
    
    <body>
        <!-- Video Modal Window -->
        <div id="video_window" style="display: none; width: 500px; height: 400px;">
            <div id="remoteVideos" style="background-color: #555; width: 100%; height: 100%; border-radius: 7px; overflow: hidden;">
                <video id="remoteVideo" style="width: 100%;" autoplay></video>
                
            </div>
            <div style="float:left; margin-left: 20px; margin-top: -68px;">
                <video id="localVideo" width="70" style="mask-image: url(small_video_mask.png);" autoplay></video>
            </div>
        </div>
        
        <!-- Notes Modal Window -->
        <div id="notes_window" style="display: none; width: 700px; min-height: 300px; overflow:scroll;">
            <div id="notes_buttons">
                <button id="notes_button_public" class="notes_button notes_button_selected" style="border-top-left-radius: 5px; border-bottom-left-radius: 5px;">Public</button>
                <button id="notes_button_private" class="notes_button">Private</button>
                <button id="notes_button_action_items" class="notes_button" style="border-top-right-radius: 5px; border-bottom-right-radius: 5px;">Action Items</button>
            </div>
            <textarea id="notes_text" style="display:none;"></textarea>
            <div id="accordion">
                <h3>BLOOD PRESSURE</h3>
                <div>
                    <p>
                        The systolic (or top) value, which is also the higher of the two numbers, measure the pressure in the arteries when the heart beats (when the heart muscle contracts). The diastolic (or button) value, which is also the lower of the two numbers, measures the pressure in the arteries between heartbeats (when the heart muscle is resting between beats and refilling with blood).
                        
                        Risk factors for hypertension includes family history of high blood pressure, advanced age, lack of physical activity, poor diet inclding high salt consumption, obesity, excessive alcohol intake, tobacco use and stress.
                        
                        Ways to improve your BP include losing weight, reducing salt intake, increasing exercise, cutting back on alcohol and following the DASH diet.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="BLOOD_PRESSURE"></textarea>
                    </span>
                  </div>
                <h3>GLUCOSE, HEMOGLOBIN H1C</h3>
                <div>
                    <p>
                        A blood glucose test measures the amount of a type of sugar called glucose, in your blood. Glucose comes from carbohydrates in foods. It is the main source of energy used by the body. Fasting blood sugar is often the first test done to check for prediabetes and diabetes.
                        
                        Elevated blood glucose occurs when the body has too little insulin or when the body cannot use insulin properly.
                        
                        Wasy to improve blood glucose include cutting back or avoiding sugary foods and drinks, increasing exercise, decreasing carbohydrate intake (limiting white bread, pasta and rice), losing weight, managing stress, moderate alcohol consumption and avoiding tobacoo use.
                        
                        The hemoglobin A1C reflects ana verage blood glucose level for thepast 2 to 3 months.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="GLUCOSE,_HEMOGLOBIN_A1C"></textarea>
                    </span>
                </div>
                <h3>TOTAL CHOLESTEROL</h3>
                <div>
                    <p>
                       Total cholesterol is a measure of the cholesterol components LDL, HDL and a portion of your triglyceride level. A total cholesterol score of less than 200 mg/dL is considered normal.
                        
                        Total cholesterol values may be elevated with higher HDL levels.
                        
                        Ways to improve total cholesterol include cutting back on animal fats (replace red meats with fish, turkey or chicken, decrease cheese and dairy products), eating oatmeal five times a week, increasing the amount of fiber in your diet and increasing your exercise.
                    </p> 
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="TOTAL_CHOLESTEROL"></textarea>
                    </span>
                </div>
                <h3>HDL CHOLESTEROL</h3>
                <div>
                    <p>
                            HDL is a type of lipoproteins often referred to as <q>good</q> cholesterol. They act as cholesterol scavengers removing the LDL (or bad) cholesterol, picking up excess cholesterol in the blood and taking it back to the liver where it is broken down. The higher your HDL level, the less <q>bad</q> cholesterol you will have in your blood.
                        
                        Ways to improve your HDL cholesterol include cutting back or quitting smoking, increasing your exercise and increasing <q>good fats</q> in your diet. Foods that increase your HDL include oily fish (salmon, tuna, mackerel and trout), olive oil, avocado, almonds or walnuts. Eating 50 grams of dark chocolate (about 1.5 ounces) daily can improve the antioxidative action of HDL. Purple skinned fruits and juices (including red wine in moderation) will also help raise HDL. The use of 1 to 4 grams of omega-3 fish oil, or flaxseed oil has proved to raise HDL levels.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="HDL_CHOLESTEROL"></textarea>
                    </span>
                </div>
                
                <h3>TRIGLYCERIDES</h3>
                <div>
                    <p>
                        Triglycerides are a type of fat (lipid) found in your blood. When you eat, your body converts any calories it does not need to use right away into triglycerides. Triglycerides are stored in fat cells. Later, hormones release triglycerides for energy between meals. If you regularly eat more calories than you need, particularly <q>easy</q> calories like carbohydrates and fats, you may have high triglycerides (hypertriglyceridemia).
High triglycerides may contribute to hardening of the arteries or thickening of the artery walls (atherosclerosis), which increases the risk of stroke, heart attack and heart disease. They are often a sign of other conditions including obesity and Metabolic Syndrome.
Sometimes high triglycerides are a sign of poorly controlled type 2 diabetes, low levels of thyroid hormones (hypothyroidism), liver or kidney disease or rare genetic conditions that affect
how your body converts fat to energy. High triglycerides could also be a side effect of taking certain medications, such as beta blockers, oral contraceptives, diuretics, steroids or tamoxifen.
                        Ways to improve triglyceride levels include losing weight (even 5-10 lbs.), cutting back or avoiding sugary and refined foods (white bread, pasta, rice), choosing to eat <q>good fats</q>, limiting alcohol intake, increasing physical activity, and taking 1 to 4 grams of omega-3 fish oil supplements.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="TRIGLYCERIDES"></textarea>
                    </span>
                </div>
                
                <h3>ABDOMINAL CIRCUMFERENCE</h3>
                <div>
                    <p>
                        Another way to assess your weight is to measure your abdominal circumference. Your waistline may be telling you that you are at high risk of developing obesity-related conditions. If you are a man whose abdominal circumference is more than 40 inches or a non-pregnant woman whose abdominal circumference is more than 35 inches, your risk for heart disease increases.
Excessive abdominal fat is serious because it places you at greater risk for developing obesity-related conditions, such as type 2 diabetes, high blood cholesterol, high triglycerides, high blood pressure, and coronary artery disease.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="ABDOMINAL_CIRCUMFERENCE"></textarea>
                    </span>
                </div>
                
                <h3>METABOLIC SYNDROME</h3>
                <div>
                    <p>
                        Metabolic syndrome is a serious health condition. It is a group of risk factors that, in combination with one another, indicate that your body is more likely to develop a chronic health condition. People who are overweight, physically inactive or who have high blood sugar are at highest risk. Those with Metabolic Syndrome are FIVE times more likely to develop type 2 diabetes and TWICE as likely to develop heart disease. The good news is that for most people, the risks can be reversed.
If you have three or more of the Metabolic Syndrome risks, you are considered to have Metabolic Syndrome.
Healthy lifestyle changes are the first options for treating Metabolic Syndrome, including losing weight, being physically active, eating a heart-healthy diet, and quitting smoking. Even small improvements in these areas can produce a meaningful change.
If lifestyle changes are not enough, your doctor may prescribe medications to control blood pressure, high blood sugar, high triglycerides and low HDL cholesterol.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="METABOLIC_SYNDROME"></textarea>
                    </span>
                </div>
                
                <h3>LIVER ENZYMES (AST, ALT)</h3>
                <div>
                    <p>
                        Elevated liver enzymes may indicate inflammation or damage
to cells in the liver. Inflamed or injured liver cells leak higher than normal amounts of certain chemicals, including liver enzymes, into the bloodstream, which can result in elevated liver enzymes on blood tests.
The specific elevated liver enzymes most commonly found are Aspartate transaminase (AST) and Alanine transaminase (ALT).
Elevated liver enzymes may be discovered during routine blood tests. In most cases, liver enzyme levels are only mildly and temporarily elevated. Most of the time, elevated liver enzymes do not signal a chronic, serious liver problem.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="LIVER_ENZYMES_(AST,_ALT)"></textarea>
                    </span>
                </div>
                
                <h3>BODY MASS INDEX</h3>
                <div> 
                    <p>
                        Body mass index (BMI) is an estimate of body fat based on height and weight. It is used as a screening tool to identify possible weight problems in adults.
BMI does not take in to account muscle mass vs. body fat. Someone who is athletic with large muscle mass may fall into an at-risk category. To determine if excess weight is a health risk, a healthcare provider should consider other factors, such as exercise, diet, and personal and family health history.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="BODY_MASS_INDEX"></textarea>
                    </span>
                </div>
                
                <h3>EXERCISE</h3>
                <div>
                    <p>
                        Physical activity is anything that gets your body moving. According to the Physical Activity Guidelines for Americans, you need to participate in two types of physical activity each week to improve your health–aerobic and muscle-strengthening.
Adults need at least 150 minutes of moderate-intensity aerobic (brisk walking) activity OR 75 minutes of vigorous-intensity aerobic activity (jogging or running) AND muscle strengthening activities two or more days a week. For even greater health benefits adults should increase their activity to 300 minutes
of moderate-intensity aerobic (brisk walking) activity OR 150 minutes of vigorous-intensity aerobic activity (jogging or running) AND muscle strengthening activities two or more days a week.
Benefits of exercise include lowering your risk for heart disease, keeping your weight down, improving your mood, decreasing your risk for certain types of cancer, reducing your risk for osteoporosis, increasing your energy and improving your sleep.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="EXERCISE"></textarea>
                    </span>
                </div>
                
                <h3>SMOKING</h3>
                <div>
                    <p>
                        About 20% of all deaths from heart disease in the U.S. are directly related to cigarette smoking. That’s because smoking is a major cause of heart attack. A person’s risk of heart
attack greatly increases with the number of cigarettes he or she smokes. Smokers continue to increase their risk of heart attack the longer they smoke. People who smoke a pack of cigarettes a day have more than TWICE the risk of heart attack than non-smokers.
Smoking decreases oxygen to the heart, increases blood pressure and heart rate, increases blood clotting and damages the cells that line the coronary arteries and other blood vessels. It also significantly increases the risk for cancer. Smoking is
by far the most important preventable cause of cancer in the world. Quitting smoking is one of the most impactful things one can do to improve health.<br><br>
                        
                        <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="SMOKING"></textarea>
                    </span>
                    </p>
                    
                </div>
                
                <h3>VACCINATIONS</h3>
                <div>
                    <p>
                        <span style="color:red;">Why Are Vaccines Important?</span><br>
First and foremost, vaccines save lives. With the introduction
of more and more vaccinations you can be protected against more diseases than ever before. Some diseases that in the past were fatal have been eliminated completely. Others are close
to extinction primarily because of safe and effective vaccines. Immunizations also protect others. By vaccinating yourself you help prevent the spread of disease to your friends and loved ones. Providing full childhood and adult immunizations
is paving the way for future generations to eliminate
diseases that disabled or killed generations before us.
Vaccinations are recommended throughout life
to prevent vaccine-preventable diseases. Adult vaccination coverage, however, remains low for most routinely recommended vaccines and well below the Healthy People 2020 targets.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="VACCINATIONS"></textarea>
                    </span>
                </div>
                
                <h3>CANCER SCREENING</h3>
                <div>
                    <p>
                        Cancer screening exams are medical tests done when you do not have any obvious signs of illness. They may help find cancer at an early stage, thus increasing the chances of a cure. Based on your gender and age, you should have the following screenings performed:<br>
                        <span style="color:red;">Breast Cancer</span><br>
                        <span style="color:red;">[ ]</span> Mammogram: Women ages 40 and over should obtain a mammogram every 1-2 years or as recommended by your healthcare provider<br>
                        <span style="color:red;">[ ]</span> Clinical breast exam: Women ages 40 and over should have a breast exam performed as recommended by your healthcare provider.<br>
                        <span style="color:red;">[ ]</span> Breast self-awareness: Women ages 20 and over should know how their breasts normally look and feel and report any change promptly to their healthcare provider.<br>
                        <span style="color:red;">Cervical Cancer</span><br>
                        <span style="color:red;">[ ]</span> Pap smear: Beginning at age 21 women should obtain their first Pap test, repeating the test every three years. Beginning at age 30, your healthcare provider may recommend additional tests.<br>
                        <span style="color:red;">Colorectal Cancer</span><br>

                        <span style="color:red;">[ ]</span> Men and women ages 50 and over should follow ONE of the guidelines below:<br>
                        &emsp;+ Obtain a colonoscopy once every 10 years<br>
                        &emsp;+ Obtain a virtual colonoscopy once every 5 years<br>
                        &emsp;+ Take a fecal occult blood test every year<br><br>
If you have a family history of any of the above cancers or any other risk factors, you should discuss your cancer screening and prevention strategy with your healthcare provider.
                    </p>
                    <span class="public_notes">
                        <h4>Notes</h4>
                        <textarea id="CANCER SCREENING"></textarea>
                    </span>
                </div>
                
            </div>
            <div id="action_items" style="display:none;">
                <div class="trifold_item_header">
                    <input type="checkbox" id="1" name="actionItems" value="1">
                    <label for="1">&nbsp;</label>
                    Take steps to stop tobacco/nicotine use
                </div>
                <div class="trifold_item_header">
                     <input type="checkbox" id="2" name="actionItems" value="2">
                     <label for="2">&nbsp;</label>
                     Monitor your blood sugar as directed
                </div>
                <div class="trifold_item_header">
                    <input type="checkbox" id="3" name="actionItems" value="3">
                    <label for="3">&nbsp;</label>
                    Discuss abnormal cholesterol results with your doctor
                </div>
                <div class="trifold_item_header">
                    <input type="checkbox" id="4" name="actionItems" value="4">
                    <label for="4">&nbsp;</label>
                    Monitor your blood pressure as directed. Contact your doctor if > 140/90
                </div>
                <div class="trifold_item_header">
                    <input type="checkbox" id="5" name="actionItems" value="5">
                    <label for="5">&nbsp;</label>
                    Increase your physical activity</div>
                <div class="trifold_item_header">
                    <input type="checkbox" id="6" name="actionItems" value="6">
                    <label for="6">&nbsp;</label>
                    Lose weight to help improve (blood pressure, blood sugar, general health)
                </div>
                <div class="trifold_item_header">
                    <input type="checkbox" id="7" name="actionItems" value="7">
                    <label for="7">&nbsp;</label>
                    Start monthly breast self-exam
                </div>
                <div class="trifold_item_header">
                    <input type="checkbox" id="8" name="actionItems" value="8">
                    <label for="8">&nbsp;</label>
                    Schedule (Colonoscopy, Mammogram, Clinical breast exam, Pap)
                </div>
                <div class="trifold_item_header">
                    <input type="checkbox" id="9" name="actionItems" value="9">
                    <label for="9">&nbsp;</label>
                    Consider seeking professional help for alcohol moderation
                </div>
                <div class="trifold_item_header" style="margin-bottom: 40px;">
                    <input type="checkbox" id="10" name="actionItems" value="10">
                    <label for="10">&nbsp;</label>
                    Discuss abnormal liver tests with your doctor
                </div>
                <hr />
                <div class="trifold_item_header" style="margin-top: 40px;">
                    <input type="checkbox" id="0" name="actionItems" value="0">
                    <label for="0">&nbsp;</label>
                    Or, Everything is good to go!
                </div>
            </div>

            
            
            
        </div>
        
        <!-- Report Modal Window -->
        <div id="report_window" style="display: none; width: 500px; height: 800px; position: relative;">
            <div id="report_pagination">
                <button class="report_page_button" id="report_page_prev"><i class="icon-chevron-left"></i></button>
                <div id="report_pagination_text"><span id="report_pagination_current">1</span> / <span id="report_pagination_total">1</span></div>
                <button class="report_page_button" id="report_page_next"><i class="icon-chevron-right"></i></button>
            </div>
            <div id="report_cursor"></div>
            <div id="report_container" style="width: 100%; height: 700px; overflow: scroll; background-color: #FFF; border-radius: 8px; border: 1px solid #BBB; z-index: 0;">
                <!--<canvas id="report_editor"></canvas>-->
                <canvas id="report_frame" width="550" height="700"></canvas>
            </div>
            <div id="report_toolbar">
                <button class="report_toolbar_button" id="zoom_in"><i class="icon-zoom-in"></i></button>
                <button class="report_toolbar_button" id="zoom_out"><i class="icon-zoom-out"></i></button>
                <button class="report_toolbar_button_alt" id="draw"><i class="icon-pencil"></i></button>
                <button class="report_toolbar_button" id="erase"><i class="icon-eraser"></i></button>
            </div>
        </div>
        
        <!-- Chat Modal Window -->
        <div id="chat_window" style="display: none; width: 300px; height: 400px;">
            <div id="chat_entries" style="width: 100%; height: 310px; overflow-y: scroll; overflow-x: hidden;">
            </div>
            <div style="width: 100%; height: 28px; margin-top: 11px;">
                <button id="chat_send_button"><i class="icon-chevron-right"></i></button>
                <input type="text" id="chat_text_box" placeholder="message..." />
            </div>
        </div>
        
        <!-- Next Appointment Warning -->
        <div id="next_appointment_warning">
            Your next patient <span id="patient_waiting">Jane Doe</span> is awaiting.
            <button id="start_consultation_button">Start Consultation</button>
        </div>
        
        <!-- Connection Toolbar -->
        <div id="connection_toolbar">
            <div id="connection_description">You are in a consultation with <span style="color: #EE3423"><?php echo $pat_name; ?>.</span></div>
            <div id="connection_status">
                Call Status: <span id="call_status" style="color: #AAA;">Not Connected</span>
            </div>
            <div id="connection_time_elapsed">Time Elapsed: <span id="time_elapsed_label" style="color: #EE3423;">00:00:00</span></div>
            <div id="connection_close_consultation">
                <button id="close_consultation_button">Close Consultation</button>
            </div>
            <div id="connection_window_buttons">
                <button class="connection_window_button" id="connection_window_chat_button"><i class="icon-comments"></i></button>
                <button class="connection_window_button" id="connection_window_notes_button"><i class="icon-pencil"></i></button>
                <button class="connection_window_button" id="connection_window_report_button"><i class="icon-folder-open"></i></button>
                <button class="connection_window_button" id="connection_window_video_button" disabled><i class="icon-facetime-video"></i></button>
            </div>
        </div>
        
        <script src="../PDFJS/pdf.js"></script>
        <script src="../PDFJS/compatibility.js"></script>
        <script src="../webrtc/draw.js"></script>
        <!--<script src="sizeof.compressed.js"></script>-->
        <script src="../js/moment-with-locales.js"></script>
        <script src="../js/socket.io-1.3.5.js"></script>
        <script src="../js/jquery.multi-open-accordion-1.5.3.min.js"></script>
        <script src="../push/push_client.js"></script>
        <script src="../js/sweet-alert.min.js"></script>
        <script src="../webrtc/webrtc.js"></script> 
        <script src="js/catapult_telemed_med.js"></script>
        
        
    
    </body>

</html>