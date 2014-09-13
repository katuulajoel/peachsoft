<?php require_once("include/functions.php"); ?>
<?php 
	//four steps for for closing a session
	//i.e. logging out
	
	//step1: find the session
	session_start();
	
	//step2: unset all session variables
	$_SESSION = array();
	
	//step3: Destroy the session cookie
	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(), '', time-42000, '/');
	}
	
	//Step4: Destroy the session
	session_destroy();
	
	//last send user back to index page
	redirect_to('index.php?logout=1');
?>