<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	$result_r = array();
	function sortarray($a, $b){
		return $b['percentage'] - $a['percentage'];
	}
	function getRandomvalue($array){
		$a = $array;
		$indice = rand(0, count($a)-1);
		return $a[$indice];
	}
	
	$requiredfields = array('Lunch', 'Breakfast', 'Dinner');
	$searchfield = getRandomvalue($requiredfields);
	//$searchfield = 'Fast Foods';
	//fisrt we get best recipe in each group.
	$grouprecipe = array();
	$sql = mysql_query("SELECT groupid FROM groups WHERE grouptype = '{$searchfield}'") or die ("Database query has failed: ".mysql_error());			
		
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
	if (count($grouprecipe) > 3){
		$result_r = array_slice($grouprecipe, 0, 3, true);
	} else{
		$result_r = $grouprecipe;
	}
		
	$result = array();
	foreach ($result_r as $k => $val){
		//get details of person who posted that recipe and the group to which that recipe belongs
		$sql = mysql_query("SELECT groupid, recipename, recipeimg FROM recipes WHERE recipeid ='{$val['recipeid']}' LIMIT 1") or die("Database query has failed: ".mysql_error()); 
		$row = mysql_fetch_array($sql);
		$sql2 = mysql_query("SELECT groupname, groupimg FROM groups WHERE groupid = '{$row['groupid']}' LIMIT 1") or die ("Database query has failed: ".mysql_error());
		$row2 = mysql_fetch_array($sql2);
		
		$sql3 = mysql_query("SELECT ingrident, recipe_procedure FROM ingredients WHERE recipeid = '{$val['recipeid']}'") or die ("Database query has failed: ".mysql_error());
		$recipeing = array();
		while($row3 = mysql_fetch_array($sql3)){
			$recipeing[] = $row3['ingrident'].': '.$row3['recipe_procedure'];
		}
		$recipeing1 = implode(', ',$recipeing);
		
		$result[] = array('type' => $searchfield, 'recipeid' => $val['recipeid'],'recipename' => $row['recipename'], 'recipeimg' => 'uploaded_files/recipe_imgs/thumbs/'.$row['recipeimg'], 'percentage' => $val['percentage'], 'ingredients' => $recipeing1);
	}
	
	echo json_encode($result);
	
?>
<?php mysql_close($connection); ?>