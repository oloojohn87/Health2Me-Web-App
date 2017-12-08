<?php
$files = array();
$folders = array();
#$ext_list = array();
# regex and preg_match - http://stackoverflow.com/questions/8220180/php-regex-with-parentheses

$dir = "/var/www/vhost1/";

#exec("grep -R -ls index.php", $output);
exec("find *", $filesAndFolders);

/*foreach ($filesAndFolders as $check) {
	$file_parts = pathinfo($check);
	if(!in_array(strtolower($file_parts['extension']), $ext_list)){
		$ext_list[] = strtolower($file_parts['extension']);
	}
}
print "<pre>";
var_dump($ext_list);
echo"</pre>";*/

foreach ($filesAndFolders as $check) {
	if(is_dir($check)){
		$folders[] = $check;
	} else{
		$file_parts = pathinfo($check);
		$files[] = $check;
	}
}
unset($filesAndFolders);

//echo "files: " . count($files) . "<br>";
//print "<pre>";
//var_dump($files);
//echo"</pre>";

$numOfFiles = count($files);
for($x = 0; $x <= /*50*/ $numOfFiles; $x++){
	$currentFile = $files[$x];
	#echo $currentFile . "<br>";
	checkAllDirs($currentFile);
}

echo "folders: " . count($folders) . "<br>";
print "<pre>";
var_dump($folders);
echo"</pre>";

function checkAllDirs($file){
	//$countFolders = substr_count($file, "/");
	echo "<table cellpadding='5' width='100%' style='border-bottom: 1px solid #000;'>";
	for($x = 0; $x <= 1; $x++){
		if($x > 0){
			#change file to substring
			//$file = strstr($file, "/");
			//$file = substr($file, 1);
		}
		$fileList = array();
		exec("grep -R -ls $file", $fileList);
		$prefix = "";
		foreach($fileList as $fileToSearch){
			$files .= $prefix . $fileToSearch;
			$prefix = ", ";
		}
		echo "<tr>
		<td valign='top'>" . $file . "</td>
		<td valign='top'># files referencing:</td>
		<td valign='top'>" . count($fileList) . "</td>
		<td valign='top'>Subdirs:</td>
		<td valign='top'>". ($countFolders-$x) ."</td>
		<td valign='top'>" . $files ."</td></tr>";
//		if($x == $countFolders){
			#echo "<tr><td colspan='6'><br></td></tr></table>";
//		}
		$files = "";
	}
	echo "</table>";
}

?>