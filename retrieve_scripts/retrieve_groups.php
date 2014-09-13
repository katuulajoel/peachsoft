<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	$result_r = array();//result to be sent back to calling function
	
	function getrecipes($groupid){
		$recipes = array();
		$sql3 = mysql_query("SELECT recipeimg FROM recipes WHERE groupid = '{$groupid}' AND approved = 'yes' LIMIT 3") or die ("Database has query failed: ".mysql_error());
		if (mysql_num_rows($sql3) != 0){
			while($row2 = mysql_fetch_array($sql3)){
				$recipes[] = 'uploaded_files/recipe_imgs/'.$row2['recipeimg'];
			}
		}else{
			$recipes[] = 'uploaded_files/recipe_imgs/recipedefault.jpg';
		}
		return $recipes;
	}
	
	function no_members($groupid){//function that is used to count he naumber of member in group
		$sql2 = mysql_query("SELECT groupid, COUNT(memberid) FROM groupmember WHERE groupid = '{$groupid}'") or die("Database query has failed: ".mysql_error());
		$row1 = mysql_fetch_array($sql2);
		$sum = $row1['COUNT(memberid)'];
		return $sum;
	}
	
	function getpending($groupid){
		$sql2 = mysql_query("SELECT recipeid FROM recipes WHERE groupid = '{$groupid}' AND approved = 'no'") or die ("Database query has failed: ".mysql_error());
		$pending = mysql_num_rows($sql2);
		if ($pending == 0){
			return ''; 
		}else {
			return $pending;
		}
	}
	
	function memberstatus($groupid){//function that checks whether a person is theb co_ordinator, member or not member of theb group
		$status = '';
		$sql = mysql_query("SELECT co_id FROM groups WHERE groupid = '{$groupid}' AND co_id = '{$_SESSION['user_id']}' LIMIT 1") or die ("Database query has failed: ".mysql_error());
		if (mysql_num_rows($sql) != 0){
			$status = 'co_ordinator';
		}else{
			$sql1 = mysql_query("SELECT id FROM groupmember WHERE groupid = '{$groupid}' AND memberid = '{$_SESSION['user_id']}'") or die ("Database query has failed: ".mysql_query());
			if(mysql_num_rows($sql1) != 0){
				$status = 'member';
			}else{
				$status = 'join';
			}
		}		
		return $status;
	}
	
	if ($_GET['groupsneed'] == 'createdgroups'){	
		$data = isset($_GET['data']) ? json_decode($_GET['data'],true) : '';		
		//generate the query from only fields that were altered
		$gene_query = "";
		if (!empty($data)){
			foreach($data as $val){
				$gene_query = $gene_query." AND groupid <> ".$val;
				$newtotal = no_members($val);
				$memberstatus = memberstatus($val);
				$pending = getpending($val);
				$result_r[] = array('reason' => 'change', 'dataid' => $val, 'newvalue' => $newtotal, 'status' => $memberstatus, 'grouppending' => $pending);
			}
		}		
		$sql = mysql_query("SELECT groupid, groupname, groupimg FROM groups WHERE co_id = '{$_SESSION['user_id']}'".$gene_query) or die ("Database query has failed: ".mysql_error());
		if (mysql_num_rows($sql) != 0){
			while($row = mysql_fetch_array($sql)){	 
				$sum = no_members($row['groupid']);				
				$groupimg = 'uploaded_files/groupimgs/thumbs/'.$row['groupimg'];
				$groupname = $row['groupname'];
				$groupid = $row['groupid'];
				$recipes = array();
				$recipes = getrecipes($row['groupid']);					
				$pending = getpending($groupid);
				$result_r[] = array('reason' => 'add', 'groupid' => $groupid, 'groupname' => $groupname, 'groupimg' => $groupimg, 'grouprecipes' => $recipes, 'status' => 'co_ordinator', 'total' => $sum, 'grouppending' => $pending);		
			}		
		}
	} else if($_GET['groupsneed'] == 'recentgroups'){		
		$data = isset($_GET['data']) ? json_decode($_GET['data'],true) : '';	
		//generate the query from only fields that were altered
		function searcharray2($array,$val){
			foreach($array as $fieldname){
				if ($fieldname == $val){
					return true;
				}
			}
		}
		$sql = mysql_query("SELECT groupid, groupname, co_id, groupimg FROM groups ORDER BY groupid DESC LIMIT 4") or die ("Database query has failed: ".mysql_error());
		while($row = mysql_fetch_array($sql)){
			if (searcharray2($data,$row['groupid'])){
				$memberstatus = memberstatus($row['groupid']);
				$groupid = $row['groupid'];
				$newtotal = no_members($row['groupid']);
				$result_r[] = array('reason' => 'change', 'dataid' => $groupid, 'newvalue' => $newtotal, 'status' => $memberstatus);
			}else{
				$groupimg = 'uploaded_files/groupimgs/thumbs/'.$row['groupimg'];
				$groupname = $row['groupname'];
				$groupid = $row['groupid'];
				$status = '';
				if ($row['co_id'] == $_SESSION['user_id']){
					$status  = 'co_ordinator';
				} else{
					$status = memberstatus($row['groupid']);
				}
				$sum = no_members($row['groupid']);
				$recipes = array();
				$recipes = getrecipes($row['groupid']);
				$result_r[] = array('reason' => 'add', 'groupid' => $groupid, 'groupname' => $groupname, 'groupimg' => $groupimg, 'grouprecipes' => $recipes, 'status' => $status, 'total' => $sum, 'grouppending' => '');
			}					
		}
	}else if($_GET['groupsneed'] == 'populargroups'){		 
		$data = isset($_GET['data']) ? json_decode($_GET['data'],true) : '';
		//$data = json_decode('["24","12","7","6","8"]',true);	
		//generate the query from only fields that were altered
		$gene_query1 = "";
		if (!empty($data)){
			foreach($data as $val){
				$gene_query1 = $gene_query1." AND groupid <> ".$val;
			}
		}
		$result = mysql_query("SELECT groupid, COUNT(memberid) FROM groupmember GROUP BY groupid") or die("Database query has failed: ".mysql_error());
		// Print out result
		$numbers = array();		
		while($row = mysql_fetch_array($result)){
			$total = $row['COUNT(memberid)'];
			$groupid = $row['groupid'];
			$numbers[] = array('groupid' => $groupid , 'total' => $total);
		}
		//sort array
		function sortby($a, $b){
			return $b['total'] - $a['total'];
		}		
		usort($numbers, 'sortby');	
		$filteredarray = array_slice($numbers, 0, 4, true);	
		$filteredarray2 = array();
		for ($i = 0; $i < count($filteredarray); $i++){
			$filteredarray2[] = $filteredarray[$i]['groupid'];
		}
		$filteredarray3 = array_intersect($filteredarray2,$data);
		$filteredarray4 = array_diff($filteredarray2,$data);
		$filteredarray5 = array_diff($data,$filteredarray2);
		$filteredarray6 = array_merge($filteredarray3,$filteredarray4,$filteredarray5);
		//sort array2
		function sortarray($a, $b){
			return $b - $a;
		}
		usort($filteredarray6 , 'sortarray');
		$filteredarray7 = array_slice($filteredarray6, 0, 4, true);
		$gene_query = "";
		foreach($filteredarray7 as $fieldname){
			if($fieldname == $filteredarray7[0]){
				$gene_query = $gene_query." ".$fieldname."";
			} else {
				$gene_query = $gene_query.", ".$fieldname."";
			}
		}
		//find data that is supposed to be removed from most popular
		$filteredarray8 = array_diff($data,$filteredarray7);
		//Search array
		function searcharray($value, $key, $array, $key_r){
			foreach($array as $k => $val){
				if($val[$key] == $value){
					return ''.$val[$key_r];
				}
			}
			return null;
		}
		//check for any changes on this data and send back results
		$filteredarray9 = array_intersect($data,$filteredarray7);
		foreach($filteredarray9 as $fieldname){
			$newtotal = searcharray($fieldname, 'groupid', $numbers, 'total');
			$memberstatus = memberstatus($fieldname);
			$result_r[] = array('reason' => 'change', 'dataid' => $fieldname, 'newvalue' => $newtotal, 'status' => $memberstatus);
		}
		if (!empty($gene_query)){
			$query2 = mysql_query("SELECT groupid, groupname, co_id, groupimg FROM groups WHERE groupid IN( ".$gene_query." ) ".$gene_query1." ORDER BY groupid DESC") or die ("Database query has failed1: ".mysql_error());
			while($row = mysql_fetch_array($query2)){
				$groupimg = 'uploaded_files/groupimgs/thumbs/'.$row['groupimg'];
				$groupname = $row['groupname'];
				$groupid = $row['groupid'];
				if($row['co_id'] == $_SESSION['user_id']){
					$status = 'co_ordinator';
				}else{
					$status = memberstatus($row['groupid']);
				}			
				$no_members = searcharray($groupid, 'groupid', $filteredarray, 'total');				
				$recipes = array();
				$recipes = getrecipes($row['groupid']);
				$result_r[] = array('reason' => 'add', 'groupid' => $groupid, 'groupname' => $groupname, 'groupimg' => $groupimg, 'grouprecipes' => $recipes, 'status' => $status, 'total' => $no_members, 'grouppending' => '');	
			}
		}
		foreach($filteredarray8 as $fieldname){
			$result_r[] = array('reason' => 'remove', 'dataid' => $fieldname);
		}
	}else if($_GET['groupsneed'] == 'joinedgroups'){
		$data = isset($_GET['data']) ? json_decode($_GET['data'],true) : '';		
		//generate the query from only fields that were altered
		$gene_query1 = "";
		if (!empty($data)){
			foreach($data as $val){
				$gene_query1 = $gene_query1." AND groupid <> ".$val;
				$newtotal = no_members($val);
				$memberstatus = memberstatus($val);
				$result_r[] = array('reason' => 'change', 'dataid' => $val, 'newvalue' => $newtotal, 'status' => $memberstatus);
			}
		}
		$result3 = mysql_query("SELECT groupid FROM groupmember WHERE memberid = '{$_SESSION['user_id']}'") or die ("Database query has failed: ".mysql_error());
		$group_id = array();
		while($row = mysql_fetch_array($result3)){
			$group_id[] = $row['groupid'];
		}
		//generate the query from only fields that were altered
		$gene_query = "";
		foreach($group_id as $fieldname){
			if($fieldname == $group_id[0]){
				$gene_query = $gene_query." ".$fieldname."";
			} else {
				$gene_query = $gene_query.", ".$fieldname."";
			}
		}

		if (!empty($gene_query)){
			$sql1 = mysql_query("SELECT groupid, groupname, groupimg FROM groups WHERE groupid IN( ".$gene_query." )".$gene_query1) or die ("Database query has failed: ".mysql_error());
			while($row = mysql_fetch_array($sql1)){
				$groupimg = 'uploaded_files/groupimgs/thumbs/'.$row['groupimg'];
				$groupname = $row['groupname'];
				$groupid = $row['groupid'];
				$sum = no_members($row['groupid']);
				$recipes = array();
				$recipes = getrecipes($row['groupid']);
				$result_r[] = array('reason' => 'add', 'groupid' => $groupid, 'groupname' => $groupname, 'groupimg' => $groupimg, 'grouprecipes' => $recipes, 'status' => 'member', 'total' => $sum, 'grouppending' => '');		
			}
		}	
	}else if($_GET['groupsneed'] == 'searchgroups'){
		$term = mysql_prep($_GET['term']);		
		$sql = mysql_query("SELECT groupid, groupname, co_id, groupimg FROM groups WHERE groupname like '%".$term."%' order by groupname ") or die ("Database query has failed: ".mysql_error());
		while($row = mysql_fetch_array($sql)){
			$groupimg = 'uploaded_files/groupimgs/thumbs/'.$row['groupimg'];
			$groupname = $row['groupname'];
			$groupid = $row['groupid'];
			$co_id = $row['co_id'];		
			$status = '';		
			if($co_id == $_SESSION['user_id']){
				$status = 'co_ordinator';
			}else{
				$status = memberstatus($row['groupid']);
			}			
			$sum = no_members($row['groupid']);			
			$recipes = array();
			$recipes = getrecipes($row['groupid']);
			$result_r[] = array('groupid' => $groupid, 'groupname' => $groupname, 'groupimg' => $groupimg, 'grouprecipes' => $recipes, 'status' => $status, 'total' => $sum, 'grouppending' => '');		
		}
	}else if($_GET['groupsneed'] == 'groupdetails'){
		$groupid = mysql_prep($_GET['g_id']);
		$sql = mysql_query("SELECT co_id, groupdesc, regdate FROM groups WHERE groupid = '{$groupid}'") or die ("Database query has failed: ".mysql_error());
		$row = mysql_fetch_array($sql);
		$result_r['groupdesc'] = $row['groupdesc'];
		$result_r['regdate'] = $row['regdate'];
		$sql1 = mysql_query("SELECT username FROM members WHERE id = '{$row['co_id']}' LIMIT 1") or die ("Database query has failed: ".mysql_error());
		$row1 = mysql_fetch_array($sql1);
		$result_r['username'] = $row1['username']; 
	}else if($_GET['groupsneed'] == 'autocomplete'){
		$term = mysql_prep($_GET["term"]);	 
		$sql=mysql_query("SELECT groupid, groupname, groupimg FROM groups where groupname like '%".$term."%' order by groupname ") or die ("Database query has failed: ".mysql_error());
		if ($sql) {		 
			while($result=mysql_fetch_array($sql)){
				$value = $result["groupname"];
				$label = $result["groupname"];
				$url = '#';
				$icon = 'uploaded_files/groupimgs/thumbs/'.$result["groupimg"];
				$receiverid = $result["groupid"];				 
				$result_r[]=array('value'=> $value,'label'=> $label,'groupid'=> $receiverid,'icon'=> $icon);
			}
		}
	}	
	echo json_encode($result_r);	
?>
<?php mysql_close($connection); ?>