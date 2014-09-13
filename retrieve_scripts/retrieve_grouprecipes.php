<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php	
	$groupid = mysql_prep($_GET['g_id']);
	$member = mysql_prep($_GET['member']);
	$result_r = array();
	$data = isset($_GET['data']) ? json_decode($_GET['data'],true) : '';
	//generate the query from only fields that were altered
	$gene_query = "";
	if (!empty($data)){
		foreach($data as $fieldname){
			$gene_query = $gene_query." AND recipeid <> ".$fieldname;
		}
	}
	$recipeids = array();
	if ($member == 'member'){
		$sql = mysql_query("SELECT recipeid, memberid, recipename, recipeimg, regdate FROM recipes WHERE groupid = '{$groupid}' AND approved = 'yes'".$gene_query) or die ("database query failed error1: ".mysql_error());
		if (mysql_num_rows($sql) != 0) {					
			while($row = mysql_fetch_array($sql)){
				$sql2 = mysql_query("SELECT username, profilepic FROM members WHERE id = '{$row['memberid']}' LIMIT 1") or die ("Database query has failed error6: ". mysql_error());
				$row2 = mysql_fetch_array($sql2);
				$username = $row2['username'];
				$userpic = 'uploaded_files/profile_pictures/'.$row2['profilepic'];
				$recipename = $row['recipename'];
				$recipeimg = 'uploaded_files/recipe_imgs/'.$row['recipeimg'];
				$recipeid = $row['recipeid'];
				$array = array();
				$sql4 = mysql_query("SELECT ingrident FROM ingredients WHERE recipeid = '{$recipeid}'")or die ("Database query has failed error8: ". mysql_error());
				while($row3 = mysql_fetch_array($sql4)){
					$array[] = $row3['ingrident'];
				}
				$ingrident = implode(', ', $array);
				$modificationdate = $row['regdate'];
				$result_r[] = array('approved' => 'yes', 'ingrident' => $ingrident, 'username' => $username, 'userpic' => $userpic, 'recipename' => $recipename, 'recipeimg' => $recipeimg, 'recipeid' => $recipeid, 'modificationdate' => $modificationdate);
			}					
		}
		else if ($gene_query == ''){
			//no recipe display current coz non is approved
			$result_r[] = array('approved' => 'yes', 'ingrident' => 'not yet set', 'username' => 'Person who posted', 'userpic' => '#', 'recipename' => 'Recipename', 'recipeimg' => '#', 'recipeid' => 0, 'modificationdate' => null);
		}		
	
	}else if($member == 'co_ordinator'){
		$sql = mysql_query("SELECT recipeid, memberid, recipename, recipeimg, approved, regdate FROM recipes WHERE groupid = '{$groupid}'".$gene_query) or die ("Database query has failed error5: ". mysql_error());
		
		if ($sql){
			while($row = mysql_fetch_array($sql)){
				$sql2 = mysql_query("SELECT username, profilepic FROM members WHERE id = '{$row['memberid']}' LIMIT 1") or die ("Database query has failed error6: ". mysql_error());
				$row2 = mysql_fetch_array($sql2);
				$username = $row2['username'];
				$userpic = 'uploaded_files/profile_pictures/'.$row2['profilepic'];
				$recipename = $row['recipename'];
				$recipeimg = 'uploaded_files/recipe_imgs/'.$row['recipeimg'];
				$recipeid = $row['recipeid'];
				$approved = $row['approved'];
				$array = array();
				$sql4 = mysql_query("SELECT ingrident FROM ingredients WHERE recipeid = '{$recipeid}'")or die ("Database query has failed error8: ". mysql_error());
				while($row3 = mysql_fetch_array($sql4)){
					$array[] = $row3['ingrident'];
				}
				$ingrident = implode(', ', $array);
				$modificationdate = $row['regdate'];
				$result_r[] = array('approved' => $approved, 'ingrident' => $ingrident, 'username' => $username, 'userpic' => $userpic, 'recipename' => $recipename, 'recipeimg' => $recipeimg, 'recipeid' => $recipeid, 'approved' => $approved, 'modificationdate' => $modificationdate);
			}			
			if (mysql_num_rows($sql) == 0 && $gene_query == ''){
				$result_r[] = array('approved' => 'yes', 'ingrident' => 'not yet set', 'username' => 'Person who posted', 'userpic' => '#', 'recipename' => 'Recipename', 'recipeimg' => '#', 'recipeid' => 0, 'modificationdate' => null);
			}
		}
	}	
	echo json_encode($result_r)
?>