<?php
	
	function find_number_of_tokens($text)
	{
		$tokens = array();
		$tok = strtok($text, " ");
		while ($tok != false)
		{
			array_push($tokens,$tok);
			$tok = strtok(" ");
		} 
		return count($tokens);
	}
	
	
	function removeCommonWords($input)
	{
		$commonWords = array('a','able','about','above','abroad','according','accordingly','across','actually','adj','after','afterwards','again','against','ago','ahead','ain\'t','all','allow','allows','almost','alone','along','alongside','already','also','although','always','am','amid','amidst','among','amongst','an','and','another','any','anybody','anyhow','anyone','anything','anyway','anyways','anywhere','apart','appear','appreciate','appropriate','are','aren\'t','around','as','a\'s','aside','ask','asking','associated','at','available','away','awfully','b','back','backward','backwards','be','became','because','become','becomes','becoming','been','before','beforehand','begin','behind','being','believe','below','beside','besides','best','better','between','beyond','both','brief','but','by','c','came','can','cannot','cant','can\'t','caption','cause','causes','certain','certainly','changes','clearly','c\'mon','co','co.','com','come','comes','concerning','consequently','consider','considering','contain','containing','contains','corresponding','could','couldn\'t','course','c\'s','currently','d','dare','daren\'t','definitely','described','despite','did','didn\'t','different','directly','do','does','doesn\'t','doing','done','don\'t','down','downwards','during','e','each','edu','eg','eight','eighty','either','else','elsewhere','end','ending','enough','entirely','especially','et','etc','even','ever','evermore','every','everybody','everyone','everything','everywhere','ex','exactly','example','except','f','fairly','far','farther','few','fewer','fifth','first','five','followed','following','follows','for','forever','former','formerly','forth','forward','found','four','from','further','furthermore','g','get','gets','getting','given','gives','go','goes','going','gone','got','gotten','greetings','h','had','hadn\'t','half','happens','hardly','has','hasn\'t','have','haven\'t','having','he','he\'d','he\'ll','hello','help','hence','her','here','hereafter','hereby','herein','here\'s','hereupon','hers','herself','he\'s','hi','him','himself','his','hither','hopefully','how','howbeit','however','hundred','i','i\'d','ie','if','ignored','i\'ll','i\'m','immediate','in','inasmuch','inc','inc.','indeed','indicate','indicated','indicates','inner','inside','insofar','instead','into','inward','is','isn\'t','it','it\'d','it\'ll','its','it\'s','itself','i\'ve','j','just','k','keep','keeps','kept','know','known','knows','l','last','lately','later','latter','latterly','least','less','lest','let','let\'s','like','liked','likely','likewise','little','look','looking','looks','low','lower','ltd','m','made','mainly','make','makes','many','may','maybe','mayn\'t','me','mean','meantime','meanwhile','merely','might','mightn\'t','mine','minus','miss','more','moreover','most','mostly','mr','mrs','much','must','mustn\'t','my','myself','n','name','namely','nd','near','nearly','necessary','need','needn\'t','needs','neither','never','neverf','neverless','nevertheless','new','next','nine','ninety','no','nobody','non','none','nonetheless','noone','no-one','nor','normally','not','nothing','notwithstanding','novel','now','nowhere','o','obviously','of','off','often','oh','ok','okay','old','on','once','one','ones','one\'s','only','onto','opposite','or','other','others','otherwise','ought','oughtn\'t','our','ours','ourselves','out','outside','over','overall','own','p','particular','particularly','past','per','perhaps','placed','please','plus','possible','presumably','probably','provided','provides','q','que','quite','qv','r','rather','rd','re','really','reasonably','recent','recently','regarding','regardless','regards','relatively','respectively','right','round','s','said','same','saw','say','saying','says','second','secondly','see','seeing','seem','seemed','seeming','seems','seen','self','selves','sensible','sent','serious','seriously','seven','several','shall','shan\'t','she','she\'d','she\'ll','she\'s','should','shouldn\'t','since','six','so','some','somebody','someday','somehow','someone','something','sometime','sometimes','somewhat','somewhere','soon','sorry','specified','specify','specifying','still','sub','such','sup','sure','t','take','taken','taking','tell','tends','th','than','thank','thanks','thanx','that','that\'ll','thats','that\'s','that\'ve','the','their','theirs','them','themselves','then','thence','there','thereafter','thereby','there\'d','therefore','therein','there\'ll','there\'re','theres','there\'s','thereupon','there\'ve','these','they','they\'d','they\'ll','they\'re','they\'ve','thing','things','think','third','thirty','this','thorough','thoroughly','those','though','three','through','throughout','thru','thus','till','to','together','too','took','toward','towards','tried','tries','truly','try','trying','t\'s','twice','two','u','un','under','underneath','undoing','unfortunately','unless','unlike','unlikely','until','unto','up','upon','upwards','us','use','used','useful','uses','using','usually','v','value','various','versus','very','via','viz','vs','w','want','wants','was','wasn\'t','way','we','we\'d','welcome','well','we\'ll','went','were','we\'re','weren\'t','we\'ve','what','whatever','what\'ll','what\'s','what\'ve','when','whence','whenever','where','whereafter','whereas','whereby','wherein','where\'s','whereupon','wherever','whether','which','whichever','while','whilst','whither','who','who\'d','whoever','whole','who\'ll','whom','whomever','who\'s','whose','why','will','willing','wish','with','within','without','wonder','won\'t','would','wouldn\'t','x','y','yes','yet','you','you\'d','you\'ll','your','you\'re','yours','yourself','yourselves','you\'ve','z','zero',',');
		return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
	}

		
	
	
	$file_name=$_GET['name'];
	$idp = $_GET['idp'];
		
	require("environment_detail.php");
 $dbhost = $env_var_db['dbhost'];
 $dbname = $env_var_db['dbname'];
 $dbuser = $env_var_db['dbuser'];
 $dbpass = $env_var_db['dbpass'];

	// Check connection
	//$link = mysql_connect("$dbhost", "$dbuser", "$dbpass")or die("cannot connect");
	mysql_select_db("$dbname")or die("cannot select DB");
	//echo "Connection Established";
	
	//-----------------------------------------------------
	$suggesteddates = shell_exec('Lexer');
	$suggesteddates = chop($suggesteddates," ,");
	
	$dates = array();
	$cnt=0;
		$tok = strtok($suggesteddates, ",");
		while ($cnt < 3)
		{
			if($tok==false)
				break;
			//echo "<br>".$tok;
			$tok = str_replace(' ', '', $tok);
			if($cnt==0)	
			{
				array_push($dates,$tok);
				$cnt++;
			}
			else if($cnt==1)
			{
				if($tok!=$dates[0])
				{
					array_push($dates,$tok);
					$cnt++;
				}
			}
			else if($cnt==2)
			{
				//echo "Here";
				if($tok!=$dates[0] and $tok!=$dates[1])
				{
					//echo "There".$dates[0]."***".$dates[1]."****".$tok."***";
					array_push($dates,$tok);
					$cnt++;
				}
			}
			
			
			
			$tok=strtok(",");
			
		} 
		
		for($i=0;$i<$cnt;$i++)
		{
			$query = "update lifepin set suggesteddate".($i+1)."='".$dates[$i]."' where idpin=".$idp;
			echo "<br>".$query;
			mysql_query($query);
		}
	
	
	//-----------------------------------------------------
	
	
	
	
	//$query = "select creator_id,creator_type from test_file where fname='".$file_name."'";
	$query = "select creatortype,idcreator from lifepin where  idpin = ".$idp;
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	$creator_id = $row['idcreator'];
	$creator_type=$row['creatortype'];
	
	
	//$creator_id = $row['creator_id'];
	//$creator_type=$row['creator_type'];
	echo "<br><br>Suggested Names:<br>";
	if($creator_type == 1)
	{
		//echo "<br>Creator is a Doctor";
		
		
		//---------------------------Tokenize------------------------
		$filename = "c:/xampp/htdocs/ExtractedData.txt";
		$handle = fopen($filename, "r");
		$text = fread($handle, filesize($filename));
		fclose($handle);
		
		//echo "<br><br>Extracted Text : ".$text;
		$text = removeCommonWords($text);
		$query = "update lifepin set textorel='".$text."' where idpin = ".$idp;
		mysql_query($query);
		//echo "<br><br>Reduced Text : ".$text;
		$tokens = array();
		$token = strtok($text, " \n\t");
		
		while ($token != false)
		{
			array_push($tokens,$token);
			$token = strtok(" \n\t");
			if($token == '0')
				$token = strtok(" \n\t");
			//echo "<br>".$token;
		} 
		$name = array();
		$no_tokens = count($tokens);
		//echo "<br>".$no_tokens;
		echo "<br><br><br>";
		//---------------------------Process each Token----------------
		$flag=0;
		for($i=0;$i<$no_tokens;$i++)
		{
			if($flag==1)
			{
				break;
			}
			if(!strpos($tokens[$i],"'") or !strpos($tokens[$i],'"'))
			{
							
				$q1 = "select name,surname from usuarios where identif in (select distinct(u.idus) from doctorslinkusers u,lifepin l where l.idcreator = u.idmed and l.idcreator =".$creator_id." ) ";
				$query = $q1." and soundex(name) like soundex('".$tokens[$i]."') UNION ".$q1. "and levenshtein(name,'".$tokens[$i]."') in (0,1)" ;
						
					
				$result = mysql_query($query);
				$count=mysql_num_rows($result);
				if($count>0)
				{
					while ($row = mysql_fetch_array($result))
					{
						if($flag==1)
						{
						    break;
						}
						$words_in_name = $row['name']." ".$row['surname'];
						//echo "<br>".$words_in_name;
						$no_words = find_number_of_tokens($words_in_name);
						$newstring = '';
						for($j=0;$j<$no_words;$j++)
						{
							if( $no_tokens > ($i+$j))
							{
								$newstring = $newstring." ".$tokens[$i+$j]; 
							}
							else
							{
								break;
							}
						}
					    
						
						$q2 = "select identif as pid,concat(name,' ',surname) as suggestedName from usuarios where identif in (select distinct(u.idus) from doctorslinkusers u,lifepin l where l.idcreator = u.idmed and l.idcreator =".$creator_id." ) ";
						$query = $q2. " and soundex(concat(name,' ',surname)) like soundex('".$newstring."') UNION ".$q2. "and levenshtein(concat(name,' ',surname),'".$newstring."') in (0,1)" ;
								
						
						//echo "<br>".$query;
						$result1 = mysql_query($query);
						while($row1 = mysql_fetch_array($result1))
						{
							//echo "<br>".$row1['pid']."  --->  ".$row1['suggestedName'];
							//$name[$row1['pid']] = $row1['suggestedName'];
							$suggestedid = $row1['pid'];
							$flag=1;
							break;
						}
					
					
					}
					
				}
			}
		}
		
		/*if(!empty($name))
		{
			$suggestedid ='';

			foreach($name as $x=>$x_value)

			{

				$suggestedid = $suggestedid  .$x . " ,";

				echo "ID=" . $x . ", Name=" . $x_value;

				echo "<br>";

			}

			$suggestedid = chop($suggestedid,",");
		}
		else
		{
			$suggestedid = "No Suggestions !!!";
		}*/
		
		
		if($flag == 0)
		{
		   $suggestedid = "No Suggestions";
		}
	}
	else
	{
		//echo "<br>Creator is a Patient";
		if($creator_id)
			$suggestedid = $creator_id;
		else
			$suggestedid = "No Suggestions";
	}
	
	
  $query = "update lifepin set suggestedid='".$suggestedid."' where idpin = ".$idp; 
  mysql_query($query);
  echo "Updated ID in table";
  
	
?>