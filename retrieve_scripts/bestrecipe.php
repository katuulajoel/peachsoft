<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	$result_r = array();
	function sortarray($a, $b){
		return $b['percentage'] - $a['percentage'];
	}
	//fisrt we get best recipe in each group.
	$grouprecipe = array();
	$sql = mysql_query("SELECT groupid FROM groups") or die ("Database query has failed: ".mysql_error());			
		
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
			$percentage = $numrows == 0 ? 0 : (($total / ($numrows * 5)) * 100); 
			
			if ($percentage != 0){
				$recipes[] = array('groupid' => $row['groupid'], 'recipeid' => $row1['recipeid'], 'percentage' => $percentage);
			}			
		}	
		if (!empty($recipes)){
			usort($recipes , 'sortarray');
			$grouprecipe[] = array('groupid' => $recipes[0]['groupid'], 'recipeid' => $recipes[0]['recipeid'], 'percentage' => $recipes[0]['percentage']);
		}
	}
	// print_r($grouprecipe);	
	usort($grouprecipe , 'sortarray');
	
	//check to see how much data was returned and determined what volume of it to send back to the client side 
	if (count($grouprecipe) > 10){
		$result_r = array_slice($grouprecipe, 0, 10, true);
	} else{
		$result_r = $grouprecipe;
	}
	
	$result = array();
	foreach ($result_r as $k => $val){
		//get details of person who posted that recipe and the group to which that recipe belongs
		$sql = mysql_query("SELECT memberid, groupid, recipename, recipeimg FROM recipes WHERE recipeid ='{$val['recipeid']}' LIMIT 1") or die("Database query has failed: ".mysql_error()); 
		$row = mysql_fetch_array($sql);
		$sql1 = mysql_query("SELECT username, profilepic FROM members WHERE id = '{$row['memberid']}' LIMIT 1") or die ("Database query has  failed: ".mysql_error());
		$row1 = mysql_fetch_array($sql1);
		$sql2 = mysql_query("SELECT groupname, groupimg FROM groups WHERE groupid = '{$row['groupid']}' LIMIT 1") or die ("Database query has failed: ".mysql_error());
		$row2 = mysql_fetch_array($sql2);
		$result[] = array('recipename' => $row['recipename'], 'recipeimg' => 'uploaded_files/recipe_imgs/thumbs/'.$row['recipeimg'], 'groupname' => $row2['groupname'], 'groupimg' => 'uploaded_files/groupimgs/thumbs/'.$row2['groupimg'], 'username' => $row1['username'], 'userpf' => 'uploaded_files/profile_pictures/thumbs/'.$row1['profilepic'], 'percentage' => $val['percentage']);
	}
	
	echo json_encode($result);	
?>
<?php mysql_close($connection); ?>