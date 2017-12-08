<?php
 require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

$tbl_name="lifepin"; // Table name

//KYLE$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
mysql_select_db("$dbname")or die("cannot select DB");	

//print_r($_FILES);

//echo "---Temp name:";
//echo $_FILES["file"]["name"];
//echo "---";

//echo "PARAM1:";
//echo $_POST['value1'];
//echo "*******";

//echo "PARAM2:";
//echo $_POST['value2'];
//echo "*******";

$new_image_name = 'eMLXX'.$_POST['value1'].md5(date('Ymdgisu')) .'.jpg';

//$resultado = move_uploaded_file($_FILES["file"]["tmp_name"], "PinImageSet/".$new_image_name);

// Move it
$resultado = move_uploaded_file($_FILES["file"]["tmp_name"], "Packages/".$new_image_name);

/*
// Move it
if(move_uploaded_file($_FILES["pic$index"]["tmp_name"] , "$dir/img-$index.$ext"))
// Resize it
GenerateThumbnail("$dir/img-$index.$ext","$dir/img-$index.$ext",600,800); 
*/

if ($resultado)
{
echo $new_image_name;
// Resize it
GenerateThumbnail("Packages/".$new_image_name,"PackagesTH/".$new_image_name,400,500); 

//echo "File Upload OK: ";
//echo "PinImageSet/".$new_image_name;
}
else
{
echo "***** ERROR *****";
}

$IdMed = $_POST['value2'];

// ACTUALIZACIÓN DE LA BASE DE DATOS
$Isql="INSERT INTO lifepin SET NeedACTION = 1, IdCreator='$IdMed', IdMed='$IdMed',  CreatorType = 1, FechaInput=NOW(), Fecha=NOW() , RawImage='$new_image_name' , CANAL=6, Location = 1, ValidationStatus=3, EvRuPunt= 2, Evento=99, Tipo=30 ";
$result = mysql_query($Isql);
$IdPin = mysql_insert_id();
						


function GenerateThumbnail($im_filename,$th_filename,$max_width,$max_height,$quality = 0.50)
{
// The original image must exist
if(is_file($im_filename))
{
    // Let's create the directory if needed
    $th_path = dirname($th_filename);
    if(!is_dir($th_path))
        mkdir($th_path, 0777, true);
    // If the thumb does not aleady exists
    if(!is_file($th_filename))
    {
        // Get Image size info
        list($width_orig, $height_orig, $image_type) = @getimagesize($im_filename);
        if(!$width_orig)
            return 2;
        switch($image_type)
        {
            case 1: $src_im = @imagecreatefromgif($im_filename);    break;
            case 2: $src_im = @imagecreatefromjpeg($im_filename);   break;
            case 3: $src_im = @imagecreatefrompng($im_filename);    break;
        }
        if(!$src_im)
            return 3;


        $aspect_ratio = (float) $height_orig / $width_orig;

        $thumb_height = $max_height;
        $thumb_width = round($thumb_height / $aspect_ratio);
        if($thumb_width > $max_width)
        {
            $thumb_width    = $max_width;
            $thumb_height   = round($thumb_width * $aspect_ratio);
        }

        $width = $thumb_width;
        $height = $thumb_height;

        $dst_img = @imagecreatetruecolor($width, $height);
        if(!$dst_img)
            return 4;
        $success = @imagecopyresampled($dst_img,$src_im,0,0,0,0,$width,$height,$width_orig,$height_orig);
        if(!$success)
            return 4;
        switch ($image_type) 
        {
            case 1: $success = @imagegif($dst_img,$th_filename); break;
            case 2: $success = @imagejpeg($dst_img,$th_filename,intval($quality*100));  break;
            case 3: $success = @imagepng($dst_img,$th_filename,intval($quality*9)); break;
        }
        if(!$success)
            return 4;
    }
    return 0;
}
return 1;
}
?>