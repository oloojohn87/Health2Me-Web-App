<?php

$new_image_name = 'tobeparsed.jpg';

$resultado = move_uploaded_file($_FILES["file"]["tmp_name"], "Packages/".$new_image_name);


if ($resultado)
{
echo $new_image_name;
}
else
{
echo "***** ERROR *****";
}


?>