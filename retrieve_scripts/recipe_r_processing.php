<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php
	$result_r = array();
	if($_GET['whattoget'] == 'comments'){
		$recipeid = mysql_prep($_GET['recipeid']);
		$result_r = array();
		$sql = mysql_query("SELECT commentid, memberid, comment, commentdate FROM comment WHERE recipeid = '{$recipeid}' ORDER BY commentid ASC") or die ("Database query has failed: ".mysql_error());
		while ($row = mysql_fetch_array($sql)){
			$sql2 = mysql_query("SELECT username, profilepic FROM members WHERE id = '{$row['memberid']}'") or die ("Database query has failed: ".mysql_error());
			$row2 = mysql_fetch_array($sql2);
			
			$username = $row2['username'];
			$userpic = 'uploaded_files/profile_pictures/'.$row2['profilepic'];
			$comment = $row['comment'];
			$commentid = $row['commentid'];
			$modificationdate = $row['commentdate'];
			
			$result_r[] = array('username' => $username, 'userpf' => $userpic, 'comment' => $comment, 'commentid' => $commentid, 'modificationdate' => $modificationdate);
		}		
	}else if($_GET['whattoget'] == 'ranks'){
		$groupid = mysql_prep($_GET['g_id']);		
		$sql = mysql_query("SELECT recipeid FROM recipes WHERE groupid = '{$groupid}' AND approved = 'yes'") or die ("Database query failed: ".mysql_error());
		while($row = mysql_fetch_array($sql)){
			$sql2 = mysql_query("SELECT rankscore FROM rankings WHERE recipeid = '{$row['recipeid']}'") or die ("Database query has failed: ".mysql_error());
			$total = 0;
			while($row2 = mysql_fetch_array($sql2)){
				$total = $total + $row2['rankscore'];
			}
			$numrows = mysql_num_rows($sql2);
			$percentage = $numrows == 0 ? 0 : ($total / ($numrows*5)) * 100; 
			
			$sql3 = mysql_query("SELECT recipename FROM recipes WHERE recipeid = '{$row['recipeid']}'") or die ("Database query has failed: ".mysql_error());
			$row3 = mysql_fetch_array($sql3);
			$recipename = $row3['recipename'];			
			$result_r[] = array('recipeid' => $row['recipeid'], 'recipename' => $recipename, 'percentage' => $percentage);
		}
	}
	echo json_encode($result_r);
?>
<?php mysql_close(); ?>