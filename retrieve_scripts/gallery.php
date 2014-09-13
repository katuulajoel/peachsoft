<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php
	if(isset($_GET)){
		$result_r = array();
		$recipearray = array();
		$grouparray = array();
		$data = isset($_GET['data']) ? json_decode($_GET['data'],true) : '';
		
		//generate the query from only fields that were altered
		$gene_query = "";
		$gene_query1 = "";
		if (!empty($data)){
			foreach($data as $k => $val){
				if ($val['type'] == 'group'){
					$gene_query = $gene_query." AND groupid <> ".$val['id'];				
				}
				if ($val['type'] == 'recipe'){
					$gene_query1 = $gene_query1." AND recipeid <> ".$val['id'];				
				}
			}
		}
		$sql = mysql_query("SELECT groupid, groupname, groupimg FROM groups WHERE co_id = '{$_SESSION['user_id']}'".$gene_query) or die ("Database query has failed: ".mysql_error());
		while($row = mysql_fetch_array($sql)){
			if ($row['groupimg'] != 'groupdefault.jpg'){				
				$groupid = $row['groupid'];
				$groupname = $row['groupname'];
				$image = 'uploaded_files/groupimgs/thumbs/'.$row['groupimg'];
				list($width, $height) = getimagesize('../'.$image);
				$grouparray[] = array('type' => 'group', 'id' => $groupid, 'name' => $groupname, 'image' => $image, 'width' => $width, 'height' => $height);
			}			
			$sql2 = mysql_query("SELECT recipeid, recipename, recipeimg FROM recipes WHERE groupid = '{$row['groupid']}' AND approved = 'yes' AND recipeimg <> 'default.jpg'".$gene_query1) or die ("Database query has failed: ".mysql_error());
			while($row1 = mysql_fetch_array($sql2)){
				$recipeid = $row1['recipeid'];
				$recipename = $row1['recipename'];
				$recipeimg = 'uploaded_files/recipe_imgs/thumbs/'.$row1['recipeimg'];
				list($width, $height) = getimagesize('../'.$recipeimg);
				$recipearray[] = array('type' => 'recipe', 'id' => $recipeid, 'name' => $recipename, 'image' => $recipeimg, 'width' => $width, 'height' => $height);
			}
		}
		$sql3 = mysql_query("SELECT groupid FROM groupmember WHERE memberid = '{$_SESSION['user_id']}'") or die ("Database query has failed: ".mysql_error());
		while($row = mysql_fetch_array($sql3)){
			$sql4 = mysql_query("SELECT recipeid, recipename, recipeimg FROM recipes WHERE groupid = '{$row['groupid']}' AND approved = 'yes' AND recipeimg <> 'default.jpg'".$gene_query1) or die ("Database query has failed: ".mysql_error());
			while($row1 = mysql_fetch_array($sql4)){
				$recipeid = $row1['recipeid'];
				$recipename = $row1['recipename'];
				$recipeimg = 'uploaded_files/recipe_imgs/thumbs/'.$row1['recipeimg'];
				list($width, $height) = getimagesize('../'.$recipeimg);
				$recipearray[] = array('type' => 'recipe', 'id' => $recipeid, 'name' => $recipename, 'image' => $recipeimg, 'width' => $width, 'height' => $height);
			}
			$sql5 = mysql_query("SELECT groupname, groupimg FROM groups WHERE groupid = '{$row['groupid']}'".$gene_query) or die ("Database query has failed: ".mysql_error());
			while($row1 = mysql_fetch_array($sql5)){
				if ($row1['groupimg'] != 'groupdefault.jpg'){				
					$groupid = $row['groupid'];
					$groupname = $row1['groupname'];
					$image = 'uploaded_files/groupimgs/thumbs/'.$row1['groupimg'];
					list($width, $height) = getimagesize('../'.$image);
					$grouparray[] = array('type' => 'group', 'id' => $groupid, 'name' => $groupname, 'image' => $image, 'width' => $width, 'height' => $height);
				}
			}
		}
		
		$result_r = array_merge($grouparray, $recipearray);
		echo json_encode($result_r);	
	}
?>
<?php mysql_close(); ?>