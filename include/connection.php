<?php 

	//Database constanst
	define("DB_SERVER","localhost");
	define("DB_USER", "root");
	define("DB_PASS", "");
	define("DB_NAME", "peach");
	
	//create a database connection
	$connection = mysql_connect(DB_SERVER,DB_USER,DB_PASS);
	if (!$connection){
			die ("Database connection failed: ". mysql_error());
	}
	
	//select the database to use
	$db_select = mysql_select_db(DB_NAME);
	if (!$db_select){
		die ("Failed to select database: ". mysql_error());
	}

?>
