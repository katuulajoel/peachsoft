<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	$loginstatus = confirm_logged_in2();
	$result = array();
	
	if ($_GET['reason'] == 'refreshbrowser'){
		if (!$loginstatus){
			$result['status'] = true;
			$result['user_id'] = $_SESSION['user_id'];
			$result['username'] = $_SESSION['username'];
			$result['userpf'] = 'uploaded_files/profile_pictures/'.$_SESSION['userpf'];		
		}else{
			$result['status'] = false;
			$result['username'] = 'not yet';
			$result['userpf'] = 'not yet still';
		}
	}else if ($_GET['reason'] == 'openpage'){
		if (!$loginstatus){
			$result['status'] = true;	
		}else{
			$result['status'] = false;
		}
	}	
	echo json_encode($result);
?>