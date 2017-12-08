<?php

require("../environment_detail.php");
require("../PasswordHash.php");
require("../NotificationClass.php");

$dbhost = $env_var_db["dbhost"];
$dbname = $env_var_db["dbname"];
$dbuser = $env_var_db["dbuser"];
$dbpass = $env_var_db["dbpass"];
$hardcode = $env_var_db['hardcode'];

// Connect to server and select databse.
$con = new PDO('mysql:host='.$dbhost.';dbname='.$dbname.';charset=utf8', ''.$dbuser.'', ''.$dbpass.'', array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if (!$con)
{
	die('Could not connect: ' . mysql_error());
}

$ai = json_decode($_POST['action_items']);

$tr = json_decode($_POST['trifold'], true);

$doc_id = $_POST['doc_id'];

$pat_id = $_POST['pat_id'];


$action_items = array("Everything is okay",
                      "Take steps to stop tobacco/nicotine use",
                      "Monitor your blood sugar as directed",
                      "Discuss abnormal cholesterol results with your doctor",
                      "Monitor your blood pressure as directed. Contact your doctor if > 140/90",
                      "Increase your physical activity",
                      "Lose weight to help improve (blood pressure, blood sugar, general health)",
                      "Start monthly breast self-exam",
                      "Schedule (Colonoscopy, Mammogram, Clinical breast exam, Pap)",
                      "Consider seeking professional help for alcohol moderation",
                      "Discuss abnormal liver tests with your doctor");


$trifold = array(
    "BLOOD PRESSURE" => 'The systolic (or top) value, which is also the higher of the two
                numbers, measures the pressure in the arteries when the
                heart beats (when the heart muscle contracts). The diastolic
                (or bottom) value, which is also the lower of the two numbers,
                measures the pressure in the arteries between heartbeats
                (when the heart muscle is resting between beats and refilling
                with blood).<br/><br/>
                Risk factors for hypertension include family history of high
                blood pressure, advanced age, lack of physical activity,
                poor diet including high salt consumption, obesity, excessive
                alcohol intake, tobacco use and stress.<br/><br/>
                Ways to improve your BP include losing weight, reducing
                salt intake, increasing exercise, cutting back on alcohol
                and following the DASH diet.',
    "LDL CHOLESTEROL" => 'LDL cholesterol is considered the <q>bad</q> cholesterol because
                it contributes to plaque, a thick, hard deposit that can clog
                arteries and make them less flexible. This condition is known
                as atherosclerosis. If a clot forms and blocks a narrowed
                artery, heart attack or stroke can result. In general, the lower
                your LDL cholesterol level is, the better. Ways to improve your
                LDL include cutting back on animal fats (replace red meats with
                fish, turkey or chicken; decrease cheese and dairy products),
                eating oatmeal five times a week, increasing the amount of fiber
                in your diet, and increasing your exercise.',
    "TOTAL CHOLESTEROL" => 'Total cholesterol is a measure of the cholesterol
                components LDL, HDL and a portion of your triglyceride
                level. A total cholesterol score of less than 200 mg/dL is
                considered normal.<br/><br/>
                Total cholesterol values may be elevated with
                higher HDL levels.<br/><br/>
                Ways to improve total cholesterol include cutting back on
                animal fats (replace red meats with fish, turkey or chicken,
                decrease cheese and dairy products), eating oatmeal
                five times a week, increasing the amount of fiber in your
                diet and increasing your exercise.',
    "GLUCOSE, HEMOGLOBIN A1C" => 'A blood glucose test measures the amount of a type of
                sugar called glucose, in your blood. Glucose comes from
                carbohydrates in foods. It is the main source of energy
                used by the body. Fasting blood sugar is often the first test
                done to check for prediabetes and diabetes.<br/><br/>
                Elevated blood glucose occurs when the body has too little
                insulin or when the body cannot use insulin properly.<br/><br/>
                Ways to improve blood glucose include cutting back or
                avoiding sugary foods and drinks, increasing exercise,
                decreasing carbohydrate intake (limiting white bread,
                pasta and rice), losing weight, managing stress, moderate
                alcohol consumption and avoiding tobacco use.<br/><br/>
                The Hemoglobin A1C reflects an average blood glucose
                level for the past 2 to 3 months.',
    "HDL CHOLESTEROL" => 'HDL is a type of lipoproteins often referred to as “good”
                cholesterol. They act as cholesterol scavengers removing
                the LDL (or bad) cholesterol, picking up excess cholesterol
                in the blood and taking it back to the liver where it is
                broken down. The higher your HDL level, the less “bad”
                cholesterol you will have in your blood.<br/><br/>
                Ways to improve your HDL cholesterol include cutting
                back or quitting smoking, increasing your exercise and
                increasing “good fats” in your diet. Foods that increase
                your HDL include oily fish (salmon, tuna, mackerel
                and trout), olive oil, avocado, almonds or walnuts.
                Eating 50 grams of dark chocolate (about 1.5 ounces)
                daily can improve the antioxidative action of HDL.
                Purple skinned fruits and juices (including red wine
                in moderation) will also help raise HDL. The use of 1 to 4
                grams of omega-3 fish oil, or flaxseed oil has proved to
                raise HDL levels.',
    "TRIGLYCERIDES" => 'Triglycerides are a type of fat (lipid) found in your blood. When
                you eat, your body converts any calories it does not need to use
                right away into triglycerides. Triglycerides are stored in fat cells.
                Later, hormones release triglycerides for energy between meals.
                If you regularly eat more calories than you need, particularly
                “easy” calories like carbohydrates and fats, you may have high
                triglycerides (hypertriglyceridemia).<br/><br/>
                High triglycerides may contribute to hardening of the arteries or
                thickening of the artery walls (atherosclerosis), which increases
                the risk of stroke, heart attack and heart disease. They are
                often a sign of other conditions including obesity and Metabolic
                Syndrome.<br/><br/>
                Sometimes high triglycerides are a sign of poorly controlled type
                2 diabetes, low levels of thyroid hormones (hypothyroidism),
                liver or kidney disease or rare genetic conditions that affect
                how your body converts fat to energy. High triglycerides could
                also be a side effect of taking certain medications, such as beta
                blockers, oral contraceptives, diuretics, steroids or tamoxifen.<br/><br/>
                Ways to improve triglyceride levels include losing weight (even
                5-10 lbs.), cutting back or avoiding sugary and refined foods
                (white bread, pasta, rice), choosing to eat “good fats”, limiting
                alcohol intake, increasing physical activity, and taking 1 to 4
                grams of omega-3 fish oil supplements.',
    "ABDOMINAL CIRCUMFERENCE" => 'Another way to assess your weight is to measure your
                abdominal circumference. Your waistline may be telling you
                that you are at high risk of developing obesity-related
                conditions. If you are a man whose abdominal circumference
                is more than 40 inches or a non-pregnant woman whose
                abdominal circumference is more than 35 inches, your risk for
                heart disease increases.<br/><br/>
                Excessive abdominal fat is serious because it places you at
                greater risk for developing obesity-related conditions, such as
                type 2 diabetes, high blood cholesterol, high triglycerides,
                high blood pressure, and coronary artery disease.',
    "METABOLIC SYNDROME" => 'Metabolic syndrome is a serious health condition. It is a
                group of risk factors that, in combination with one another,
                indicate that your body is more likely to develop a chronic
                health condition. People who are overweight, physically
                inactive or who have high blood sugar are at highest risk.
                Those with Metabolic Syndrome are FIVE times more
                likely to develop type 2 diabetes and TWICE as likely to
                develop heart disease. The good news is that for most
                people, the risks can be reversed.<br/><br/>
                If you have three or more of the Metabolic Syndrome risks,
                you are considered to have Metabolic Syndrome.
                Healthy lifestyle changes are the first options for treating
                Metabolic Syndrome, including losing weight, being
                physically active, eating a heart-healthy diet, and quitting
                smoking. Even small improvements in these areas can
                produce a meaningful change.<br/><br/>
                If lifestyle changes are not enough, your doctor may
                prescribe medications to control blood pressure, high blood
                sugar, high triglycerides and low HDL cholesterol.',
    "LIVER ENZYMES (AST, ALT)" => 'Elevated liver enzymes may indicate inflammation or damage
                to cells in the liver. Inflamed or injured liver cells leak higher than
                normal amounts of certain chemicals, including liver enzymes,
                into the bloodstream, which can result in elevated liver enzymes
                on blood tests.<br/><br/>
                The specific elevated liver enzymes most commonly found are
                Aspartate transaminase (AST) and Alanine transaminase (ALT).
                Elevated liver enzymes may be discovered during routine blood
                tests. In most cases, liver enzyme levels are only mildly and
                temporarily elevated. Most of the time, elevated liver enzymes
                do not signal a chronic, serious liver problem.',
    "BODY MASS INDEX" => 'Body mass index (BMI) is an estimate of body fat based on
                height and weight. It is used as a screening tool to identify
                possible weight problems in adults.<br/><br/>
                BMI does not take in to account muscle mass vs. body fat.
                Someone who is athletic with large muscle mass may fall into
                an at-risk category. To determine if excess weight is a health
                risk, a healthcare provider should consider other factors, such
                as exercise, diet, and personal and family health history.',
    "EXERCISE" => 'Physical activity is anything that gets your body moving.
                According to the Physical Activity Guidelines for Americans, you
                need to participate in two types of physical activity each week to
                improve your health–aerobic and muscle-strengthening.<br/><br/>
                Adults need at least 150 minutes of moderate-intensity aerobic
                (brisk walking) activity OR 75 minutes of vigorous-intensity
                aerobic activity (jogging or running) AND muscle strengthening
                activities two or more days a week. For even greater health
                benefits adults should increase their activity to 300 minutes
                of moderate-intensity aerobic (brisk walking) activity OR 150
                minutes of vigorous-intensity aerobic activity (jogging or running)
                AND muscle strengthening activities two or more days a week.<br/><br/>
                Benefits of exercise include lowering your risk for heart disease,
                keeping your weight down, improving your mood, decreasing
                your risk for certain types of cancer, reducing your risk for
                osteoporosis, increasing your energy and improving your sleep.',
    "SMOKING" => 'About 20% of all deaths from heart disease in the U.S. are
                directly related to cigarette smoking. That’s because smoking
                is a major cause of heart attack. A person’s risk of heart
                attack greatly increases with the number of cigarettes he or
                she smokes. Smokers continue to increase their risk of heart
                attack the longer they smoke. People who smoke a pack of
                cigarettes a day have more than TWICE the risk of heart attack
                than non-smokers.<br/><br/>
                Smoking decreases oxygen to the heart, increases blood
                pressure and heart rate, increases blood clotting and damages
                the cells that line the coronary arteries and other blood vessels.
                It also significantly increases the risk for cancer. Smoking is
                by far the most important preventable cause of cancer in the
                world. Quitting smoking is one of the most impactful things
                one can do to improve health.',
    "VACCINATIONS" => '<span style="color:red;">Why Are Vaccines Important?</span><br/><br/>
                First and foremost, vaccines save lives. With the introduction
                of more and more vaccinations you can be protected against
                more diseases than ever before. Some diseases that in the past
                were fatal have been eliminated completely. Others are close
                to extinction primarily because of safe and effective vaccines.
                Immunizations also protect others. By vaccinating yourself you
                help prevent the spread of disease to your friends and loved
                ones. Providing full childhood and adult immunizations
                is paving the way for future generations to eliminate
                diseases that disabled or killed generations before us.<br/><br/>
                Vaccinations are recommended throughout life
                to prevent vaccine-preventable diseases. Adult
                vaccination coverage, however, remains low for most
                routinely recommended vaccines and well below the
                Healthy People 2020 targets.',
    "CANCER SCREENING" => 'Cancer screening exams are medical tests done when you
                do not have any obvious signs of illness. They may help find
                cancer at an early stage, thus increasing the chances of a
                cure. Based on your gender and age, you should have the
                following screenings performed:<br/><br/>
                <span style="color:red;">Breast Cancer</span><br/><br/>
                <span style="color:red;">[ ]</span> Mammogram: Women ages 40 and over should obtain
                 a mammogram every 1-2 years or as recommended by
                 your healthcare provider<br/><br/>
                <span style="color:red;">[ ]</span> Clinical breast exam: Women ages 40 and over should
                 have a breast exam performed as recommended by
                 your healthcare provider.<br/><br/>
                <span style="color:red;">[ ]</span> Breast self-awareness: Women ages 20 and over should
                 know how their breasts normally look and feel and report
                 any change promptly to their healthcare provider.<br/><br/>
                <span style="color:red;">Cervical Cancer</span><br/><br/>
                <span style="color:red;">[ ]</span> Pap smear: Beginning at age 21 women should obtain
                 their first Pap test, repeating the test every three years.
                 Beginning at age 30, your healthcare provider may
                 recommend additional tests.<br/><br/>
                <span style="color:red;">Colorectal Cancer</span><br/><br/>
                <span style="color:red;">[ ]</span> Men and women ages 50 and over should follow
                 ONE of the guidelines below:<br/><br/>
                 &emsp;+ Obtain a colonoscopy once every 10 years<br/><br/>
                 &emsp;+ Obtain a virtual colonoscopy once every 5 years<br/><br/>
                 &emsp;+ Take a fecal occult blood test every year<br/><br/>
                If you have a family history of any of the above cancers or any
                other risk factors, you should discuss your cancer screening
                and prevention strategy with your healthcare provider.');


$html = '<html>
    <head>
        <style>
            #header{
                width: 93%;
                padding: 1%;
                padding-left: 6%;
                height: 30px;
                font-family: Calibri, sans-serif;
                font-size: 24px;
                color: #FFF;
                background-color: #EE3423;
                margin-bottom: 50px;
                border-radius: 4px;
            }
            
            #action_plan, .trifold_item{
                width: 500px;
                margin: auto;
                margin-top: 40px;
                margin-bottom: 40px;
            }
            
            #action_plan_header{
                width: 93%;
                padding: 1%;
                padding-left: 6%;
                height: 20px;
                font-family: Calibri, sans-serif;
                font-size: 18px;
                color: #FFF;
                background-color: #EE3423;
                border-radius: 4px;
            }
            
            #action_plan_body{
                width: 100%;
                margin: auto;
                margin-top: 10px;
            }
            
            .trifold_item_check{
                width: 40px;
                height: 40px;
                margin-top: -9px;
                margin-left: -12px;
                margin-right: 4px;
                float: left;
            }
            
            .trifold_item_header{
                width: 98%;
                padding: 1%;
                height: 20px;
                font-family: Calibri, sans-serif;
                font-size: 18px;
                color: #FFF;
                background-color: #EE3423;
                border-radius: 4px;
            }
            
            .trifold_item_body{
                width: 91%;
                padding: 1%;
                font-family: Calibri, sans-serif;
                font-size: 14px;
                color: #555;
                margin: auto;
                margin-top: 10px;
            }
            
            .trifold_item_note {
                margin-top: 30px;
            }
            
            h2{
                font-family: Calibri, sans-serif;
                font-size: 24px;
                color: #444;
                padding-left: 6%;
            }
            
            h4{
                margin-bottom: 5px;
            }
            
            ul{
                font-family: Calibri, sans-serif;
                font-size: 18px;
                color: #555;
                line-height: 150%;
            }
            hr {
                margin-bottom: 15px;
            }
            
            @page{ 
                @bottom-left{
                    content: flow(footer);
                }
            }
            
            #footer{
              width: 100%;
              padding:0;
              flow: static(footer);
            }
        </style>
    
    </head>
    <body>
        <div id="header">
            Personal Health Report
        </div>';

if(!empty($ai)) {  
    $html .= '<div id="action_plan">
                <div id="action_plan_header">
                Action Plan
              </div>
              <div id="action_plan_body">
                <ul>';
    //$testshow = var_export($tr, true);
    for($i = 0; $i < count($ai); $i++)
        $html .= "<li>".$action_items[$ai[$i]]."</li>";

    $html .= '</ul></div></div>';
}

foreach($tr as $key => $value) {
    $replaced_key = str_replace('_',' ',$key);
    $html .= '<div class="trifold_item"><div class="trifold_item_header"><div class="trifold_item_check"><img src="http://dev.health2.me/images/trifold_circle.png" style="width: 38px; height: 38px;" /></div>';
    $html .= $replaced_key;
    $html .= '</div><div class="trifold_item_body">';
    $html .= $trifold[$replaced_key];
    if(!empty($value)) {
        $html .= '<div class="trifold_item_note">';
        $html .= '<h4>Notes</h4><hr />';
        $html .= $value;
        $html .= '</div>';
    }
    $html .= '</div></div>';
}

$html .= '<div id="footer"><img src="http://dev.health2.me/images/CATAHealth.png" style="width: 240px; height: 80px;" /></div></body></html>';

$api_key = "lS3UYnxrtv9YWCn9upxm";
$url = "https://docraptor.com/docs?user_credentials=$api_key";

$name = 'phr.pdf';
$post_array = array(
  'doc[name]' => $name,
  'doc[document_type]' => 'pdf',
  'doc[test]' => 'true',
  'doc[document_content]' => $html
);

$postdata = http_build_query($post_array);

$opts = array('http' =>
  array(
      'method'  => 'POST',
      'header'  => 'Content-type: application/x-www-form-urlencoded',
      'content' => $postdata
  )
);

$context = stream_context_create($opts);



//file_put_contents($name, $doc);




/*
 *  PROCESS FILE
 */

$mem_id = $pat_id;
$decode = file_get_contents($url, false, $context);
$hide_from_member = 0;
$queId = $mem_id;
$extension = 'pdf';

$result = $con->prepare("SELECT * FROM usuarios where Identif=?");
$result->bindValue(1, $queId, PDO::PARAM_INT);
$result->execute();

$row = $result->fetch(PDO::FETCH_ASSOC);

$doc_result = $con->prepare("SELECT * FROM doctors where id = ?");
$doc_result->bindValue(1, $doc_id, PDO::PARAM_INT);
$doc_result->execute();

$doc_row = $doc_result->fetch(PDO::FETCH_ASSOC);

$reportdate=date("Y-m-d");

$reporttype=1;

$notifications = new Notifications();

$enc_result = $con->prepare("select pass from encryption_pass where id = (select max(id) from encryption_pass)");
$enc_result->execute();

$row_enc = $enc_result->fetch(PDO::FETCH_ASSOC);

$enc_pass = $row_enc['pass'];

if($extension == 'jpeg')
$extension = 'jpg';

$IdUsFIXED=0;
$confcode = $IdUsFIXED.md5(date('Ymdgisu'));
$new_image_name = 'eML'.$confcode.'.'.$extension;
$filepath="../Packages_Encrypted/".$new_image_name;

$extensions_list = array('png', 'jpg', 'tiff', 'gif', 'mov', 'pdf');

try { if(!in_array(strtolower($extension), $extensions_list)) throw new RuntimeException("ExtError"); }
catch (RuntimeException $e) { 
    echo 'ExtError Incorrect file extension only png, jpg, tiff, gif, mov, and pdf are allowed.';
    die();  
}

finally { $resultado = file_put_contents($filepath, $decode); }

$checksum=md5_file($filepath);

$Isql="INSERT INTO lifepin SET NeedACTION = 1, IdEmail='1', IdUsu=$mem_id, FechaInput=NOW(), Fecha='$reportdate' , FechaEmail = Now() , IdUsFIXED='$IdUsFIXED', IdUsFIXEDNAME='".$row['IdUsFIXEDNAME']."', IdMedEmail = NULL, CANAL=5, ValidationStatus=99, EvRuPunt= 2, Evento=99, Tipo=$reporttype, fs=1, checksum='$checksum', hide_from_member='$hide_from_member', idcreator = 1";
$q = $con->query($Isql);

$IdPin = $con->lastInsertId();

if($q){
    
    // notification
    $notifications->add('REPUPL', $doc_id, true, $mem_id, false, $IdPin);
    
    
    // text
    require_once "../Services/Twilio.php";		     
	$AccountSid = "AC109c7554cf28cdfe596e4811c03495bd";
	$AuthToken = "26b187fb3258d199a6d6edeb7256ecc1";
	
	// Instantiate a new Twilio Rest Client
	$client = new Services_Twilio($AccountSid, $AuthToken);
	
	/* Your Twilio Number or Outgoing Caller ID */
	$from = '+19034018888'; 
	$to= '+'.$row['telefono']; 
	
	//SMS BODY
	$body = "Your consultation has ended. Please login in to your account to view the NP's notes.";
	
	//TRY SENDING MESSAGE
	try{
	$client->account->sms_messages->create($from, $to, $body);
	}catch(Exception $e){
	echo "Twilio could not text message number.  Number is most likely incorrect.".$e;
	}
    
    // email
    
    require_once '../lib/swift_required.php';
    
    if(isset($row['email']) && $row['email'] != null)
    {
    
        $Content = file_get_contents('../templates/ConsultationEndedCatapult.html');
        $Content = str_replace("**Var4**",$doc_row['Name'].' '.$doc_row['Surname'],$Content);
        $Content = str_replace("**Var10**",$domain,$Content);

        $body = $Content;


        $subject = 'Consultation Ended';
        $from = 'Catapult Health';

        $transporter = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
        ->setUsername('dev.health2me@gmail.com')
        ->setPassword('health2me');

        $mailer = Swift_Mailer::newInstance($transporter);


        $message = Swift_Message::newInstance()
        ->setSubject($subject)
        ->setFrom(array('hub@inmers.us' => $from))
        ->setTo(array($row['email']))
        ->setBody($body, 'text/html')
        ;

        $result = $mailer->send($message);
    }
}

$locFile = "../Packages_Encrypted";
$ds = DIRECTORY_SEPARATOR; 

$locFileTH = "../PackagesTH_Encrypted";
$path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";

//Changes for handling jpg files
if($extension=="jpg") {
    $new_image_nameTH = 'eML'.$confcode.'.jpg';	
}elseif($extension=="gif") {
    $new_image_nameTH = 'eML'.$confcode.'.gif';	
}else {
    $new_image_nameTH = 'eML'.$confcode.'.png';
}

if($extension=="MOV") //for video use this command
{
    $path = 'ffmpeg';  //path of ffmpeg
    $cadenaConvert = 'ffmpeg -i '.$locFile.$ds.$new_image_name.' -f image2 -t 0.001 -ss 3 '. $locFileTH.$ds.$new_image_nameTH;

}
else   //use this command for other file types
{
    $path="/usr/lib/x86_64-linux-gnu/ImageMagick-6.7.7";  //path of ImageMagick
    $cadenaConvert = 'convert "'.$locFile.$ds.$new_image_name.'[0]" -colorspace RGB -geometry 200 "'.$locFileTH.$ds.$new_image_nameTH.'" 2>&1';
    //error_log($cadenaConvert, 3, "/var/log/apache2/error.log");
}
$output = shell_exec($cadenaConvert);  

shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ../PackagesTH_Encrypted/".$new_image_nameTH." -out ../temp/".$new_image_nameTH);
shell_exec("rm ../PackagesTH_Encrypted/".$new_image_nameTH);
shell_exec("cp ../temp/".$new_image_nameTH." ../PackagesTH_Encrypted/");
shell_exec("rm ../temp/".$new_image_nameTH);

shell_exec("echo '".$enc_pass."' | openssl aes-256-cbc -pass stdin -salt -in ../Packages_Encrypted/".$new_image_name." -out ../temp/".$new_image_name);
shell_exec("rm ../Packages_Encrypted/".$new_image_name);
shell_exec("cp ../temp/".$new_image_name." ../Packages_Encrypted/");
shell_exec("rm ../temp/".$new_image_name);        //Encrypt the report (ankit)
//file_put_contents("upload_test.txt",'Encrypt.bat Packages_Encrypted '.$new_image_name.' '.$enc_pass, FILE_APPEND);

//reación de un thumbnail desde el PDF  


$Isql="UPDATE lifepin SET IdEmail='1', RawImage='$new_image_name' , FechaInput=NOW(), ValidationStatus=8 , orig_filename ='phr.pdf',fs=1,idusu=".$queId.",idcreator=NULL, CreatorType = 0, IdMed = 0 WHERE IdPin='$IdPin'";
$q =$con->query($Isql);


/*
 *  END PROCESSING FILE
 */

// SET THE RESERVATION STATUS TO 'COMPLETED'

$today = date('Y-m-d');
$update = $con->prepare("UPDATE reservation SET status = 'COMPLETED' WHERE provider_id = ? AND patient_id = ? AND date BETWEEN ? AND ?");
$update->bindValue(1, $doc_id, PDO::PARAM_INT);
$update->bindValue(2, $pat_id, PDO::PARAM_INT);
$update->bindValue(3, $today.' 00:00:00', PDO::PARAM_STR);
$update->bindValue(4, $today.' 23:59:59', PDO::PARAM_STR);
$update->execute();
            

?>