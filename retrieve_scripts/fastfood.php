<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	function getRandomvalue($array){
		$a = $array;
		$indice = rand(0, count($a)-1);
		return $a[$indice];
	}
	function sortarray($a, $b){
		return $b['percentage'] - $a['percentage'];
	}
	
	$result_r = array();
	
	//fisrt we get best recipe in each group.
	$grouprecipe = array();
	$sql = mysql_query("SELECT groupid FROM groups WHERE grouptype = 'Fast Foods'") or die ("Database query has failed: ".mysql_error());			
		
	while($row = mysql_fetch_array($sql)){			
		$sql2 = mysql_query("SELECT recipeid FROM recipes WHERE groupid = '{$row['groupid']}' AND approved = 'yes'") or die ("Database query failed: ".mysql_error());
		$recipes = array();
		while($row1 = mysql_fetch_array($sql2)){
			$sql3 = mysql_query("SELECT rankscore FROM rankings WHERE recipeid = '{$row1['recipeid']}'") or die ("Database query has failed: ".mysql_error());
			$total = 0;
			while($row2 = mysql_fetch_array($sql3)){
				$total = $total + $row2['rankscore'];				
			}
			$numrows = mysql_num_rows($sql3);
			
			$percentage = $numrows == 0 ? 0 : (($total / ( $numrows * 5 )) * 100); 
			//echo '</br>Percentage for recipe '.$row1['recipeid'].' is '.$percentage;
			if ($percentage != 0){
				$recipes[] = array('groupid' => $row['groupid'], 'recipeid' => $row1['recipeid'], 'percentage' => $percentage);
			}
		}	
		if (!empty($recipes)){
			usort($recipes , 'sortarray');
			$grouprecipe[] = array('groupid' => $recipes[0]['groupid'], 'recipeid' => $recipes[0]['recipeid'], 'percentage' => $recipes[0]['percentage']);
		}
	}	
	usort($grouprecipe , 'sortarray');	
	//check to see how much data was returned and determined what volume of it to send back to the client side 
	if (count($grouprecipe) > 10){
		$result_r = array_slice($grouprecipe, 0, 10, true);
	} else{
		$result_r = $grouprecipe;
	}
	
	$data1 = array();
	foreach ($result_r as $k => $val){
		$data1[] = $val['recipeid'];
	}
	$filteredarray1 = array();	
	$luckyno = '';
	
	if (!empty($filteredarray1)){
		$luckyno = getRandomvalue($filteredarray1);
	} else{
		$luckyno = getRandomvalue($data1);
	}
	
	
	function searcharray($array, $value, $key){
		foreach ($array as $k => $val){
			if ($val[$key] == $value){
				return $k;
			}
		}
	}
	$index = searcharray($result_r, $luckyno, 'recipeid');
	
	$sql4 = mysql_query("SELECT groupid, recipename, recipeimg FROM recipes WHERE recipeid ='{$result_r[$index]['recipeid']}' LIMIT 1") or die("Database query has failed: ".mysql_error()); 
	$row4 = mysql_fetch_array($sql4);
	$sql5 = mysql_query("SELECT groupname, groupimg FROM groups WHERE groupid = '{$row['groupid']}' LIMIT 1") or die ("Database query has failed: ".mysql_error());
	$row5 = mysql_fetch_array($sql5);
		
	$result['fastfoodname'] = $row4['recipename'];
	$result['fastfoodimg'] = 'uploaded_files/recipe_imgs/thumbs/'.$row4['recipeimg'];
	$result['fastfoodid'] = $result_r[$index]['recipeid'];
	
	
	echo json_encode($result);
	
?>
<?php mysql_close($connection); ?>