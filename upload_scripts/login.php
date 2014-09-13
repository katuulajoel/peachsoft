<?php require_once("../include/functions.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/session.php"); ?>
<?php
		
	if (isset($_POST['submit'])){
		$password = $_POST['password'];
		$username = mysql_prep($_POST['username']);
		$hashed_password = sha1($password);
		
		$query = "SELECT id, username, email, profilepic FROM members WHERE hashed_password = '{$hashed_password }' AND username = '{$username}' LIMIT 1";
			
		$result = mysql_query($query, $connection);
		
		if (!$result){
			die('Failed to select data from database'.mysql_error());
		}else if ($result){
			$numberofrows = mysql_num_rows($result);
			$userdetail = array();
			if ($numberofrows != 0){
				$row = mysql_fetch_array($result);
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['userpf'] = $row['profilepic'];
				$userdetail['userid'] = $row['id'];
				$userdetail['username'] = $row['username'];
				$userdetail['userpf'] = "uploaded_files/profile_pictures/thumbs/".$row['profilepic'];
				$userdetail['reason'] = 'sucessful';
				
				echo json_encode($userdetail);
			}else{
				$userdetail['reason'] = 'failure';
				echo json_encode($userdetail);
			}			
		}
	}else{
		//didnt receive data
		redirect_to('../index.php');
	}

 ?>
 <?php mysql_close($connection); ?>