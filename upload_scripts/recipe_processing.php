<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	$result_r = array();
	if($_GET['requiredprocessing'] == 'uploadrecipe'){
		$array = $_GET['_details'];
		$recipename = mysql_prep($_GET['_recipename']);
		$recipeimg = isset($_GET['_recipeimg']) ? mysql_prep($_GET['_recipeimg']) : 'default.jpg';
		$groupid = mysql_prep($_GET['g_id']);		
		$sql = mysql_query("INSERT INTO recipes (memberid, groupid, recipename, recipeimg, approved, regdate) VALUES ('{$_SESSION['user_id']}', '{$groupid}', '{$recipename}', '{$recipeimg}', 'no', now())") or die ("Databse query has failed: ".mysql_error());
		$recipeid = mysql_insert_id();		
		for ($i = 0; $i < count($array); $i++){
			$ingrident = mysql_prep($array[$i]['name']);
			$procedure = mysql_prep($array[$i]['procedure']);
			$sql2 = mysql_query("INSERT INTO ingredients (recipeid, ingrident, recipe_procedure) VALUES ('{$recipeid}', '{$ingrident}', '{$procedure}')") or die ("Database query has failed: ".mysql_error());
		}
		$result_r['reason'] = 'uploaded';
	}else if($_GET['requiredprocessing'] == 'approverecipe'){
		$recipeid = mysql_prep($_GET['recipeid']);
		$groupid = mysql_prep($_GET['g_id']);
		//check to see if the person is the co_ordinator
		$sql = mysql_query("SELECT groupid FROM groups WHERE groupid = '{$groupid}' AND co_id = '{$_SESSION['user_id']}'") or die ("Database query has failed: ".mysql_error());
		
		if (mysql_num_rows($sql) != 0){
			$sql2 = mysql_query("SELECT recipeid FROM recipes WHERE recipeid = '{$recipeid}' AND approved = 'no'") or die ("Database query failed ".mysql_error());
			if (mysql_num_rows($sql2) != 0){
				$sql = mysql_query("UPDATE recipes SET approved = 'yes' WHERE recipeid = '{$recipeid}'") or die ("Database query failed ".mysql_query());
			}
		}
	}else if($_GET['requiredprocessing'] == 'comment'){
		$recipeid = mysql_prep($_GET['recipeid']);
		$comment = mysql_prep($_GET['comment']);
		
		//do some validation
		$sql = mysql_query("SELECT recipeid FROM recipes WHERE recipeid = '{$recipeid}' AND approved = 'yes'") or die ("Database query has failed: ".mysql_error());
		
		if (mysql_num_rows($sql) != 0){
			$sql1 = mysql_query("INSERT INTO comment (recipeid, memberid, comment, commentdate) VALUES ('{$recipeid}','{$_SESSION['user_id']}','{$comment}',now())") or die ("Database query has failed: ".mysql_error());
			confirm_query($sql1);
		}
	}else if($_GET['requiredprocessing'] == 'rank'){
		$recipeid = mysql_prep($_GET['recipeid']);
		$rank = mysql_prep($_GET['rank']);
		$groupid = mysql_prep($_GET['g_id']);
		//do some validation
		$sql = mysql_query("SELECT recipeid FROM recipes WHERE recipeid = '{$recipeid}' AND approved = 'yes'") or die ("Database query has failed: ".mysql_error());
		if (mysql_num_rows($sql) != 0){
			
			$sql2 = mysql_query("SELECT rankid FROM rankings WHERE memberid = '{$_SESSION['user_id']}' AND recipeid = '{$recipeid}'") or die ("Database query has failed: ".mysql_error());
			
			if (mysql_num_rows($sql2) != 0){
				$sql3 = mysql_query("UPDATE rankings SET rankscore = '{$rank}', dateranked = now() WHERE memberid = '{$_SESSION['user_id']}' AND recipeid = '{$recipeid}'") or die ("Database query has failed: ".mysql_error());
			}else{
				$sql1 = mysql_query("INSERT INTO rankings (recipeid, memberid, groupid, rankscore, dateranked) VALUES ('{$recipeid}','{$_SESSION['user_id']}','{$groupid}','{$rank}',now())") or die ("Database query has failed: ".mysql_error());
			}			
		}
		$result_r[] = true;
	}
	echo json_encode($result_r);
?>
<?php mysql_close($connection); ?>