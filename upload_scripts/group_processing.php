<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	$result_r = array();
	if (isset($_GET['process']) && $_GET['process'] == 'joingroup'){
		$groupid = mysql_prep($_GET['_groupid']);
		$memberstatus = mysql_prep($_GET['_memberstatus']);		
		if ($memberstatus == 'member'){			
			$result_r[] = 'sucessful';//do nothing coz he is already a member
		}else{
			$query = "INSERT INTO groupmember (memberid, groupid, regdate) VALUES ('{$_SESSION['user_id']}', '{$groupid}', now())";
			$result = mysql_query($query, $connection);
			confirm_query($result);
			if($result){
				$result_r[] = 'sucessful';		
			}
		}		
	}else if ($_POST['process'] == 'newgroup'){
		$groupname = mysql_prep($_POST['groupname']);
		$groupdesc = mysql_prep($_POST['groupdesc']);
		$grouptype = mysql_prep($_POST['grouptype']);
		$image_r = explode('/',$_POST['image']);
		$image = $_POST['image'] != '' ? mysql_prep($image_r[3]) : 'groupdefault.jpg';
		$result = mysql_query("SELECT groupid FROM groups WHERE groupname = '{$groupname}'") or die ("Database query has failed: ".mysql_error());
			
		$result_r = array();
		if ( mysql_num_rows($result) != 0 ){
			//that dish is already registered
			$result_r['reason'] = false;
		}else{
			//go ahead and register that dish
			$result2 = mysql_query("INSERT INTO groups (co_id, groupname, grouptype, groupdesc, groupimg, regdate) VALUES ('{$_SESSION['user_id']}', '{$groupname}', '{$grouptype}', '{$groupdesc}', '{$image}', now())") or die ("Database query has failed: ".mysql_error());
			$groupid = mysql_insert_id();
			$reeipes[] = 'uploaded_files/recipe_imgs/thumbs/recipedefault.jpg';
			$result_r['reason'] = true;
			$result_r['groupid'] = $groupid;
			$result_r['groupname'] = $groupname;
			$result_r['grouprecipes'] = $reeipes;
			$result_r['groupimg'] = 'uploaded_files/groupimgs/thumbs/'.$image;
		}
	}	
	echo json_encode($result_r);
?>
<?php mysql_close($connection); ?>