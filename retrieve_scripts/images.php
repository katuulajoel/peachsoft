<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php	
	$data = $_GET['data'];
	$imagename = explode('/',$data);
	echo json_encode($imagename[0].'/'.$imagename[1].'/'.$imagename[3]);
?>