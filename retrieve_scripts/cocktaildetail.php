<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php 
	$result_r = array();
	$recipeid = mysql_prep($_GET['recipeid']);
	$sql = mysql_query("SELECT ingrident, recipe_procedure FROM ingredients WHERE recipeid = '{$recipeid}'") or die ("Database query has failed: ".mysql_error());
	while($row = mysql_fetch_array($sql)){
		$result_r[] = array('ingredient' => $row['ingrident'], 'procedure' => $row['recipe_procedure']);
	}
	echo json_encode($result_r);
?>
<?php mysql_close($connection); ?>