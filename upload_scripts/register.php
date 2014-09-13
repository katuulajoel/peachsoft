<?php require_once("../include/functions.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/session.php"); ?>
<?php

	if (isset($_POST)){
		//register user
		$username = mysql_prep($_POST['username']);
		$firstname = mysql_prep($_POST['firstname']);
		$lastname = mysql_prep($_POST['lastname']);
		$password = $_POST['password'];
		$email = mysql_prep($_POST['email']);
		$dob = mysql_prep($_POST['dob']);
		$gender = mysql_prep($_POST['gender']);
		$hashed_password = sha1($password);
		$profilepic = '';
		if ($gender == 'Male'){
			$profilepic = 'maledefault.jpg';
		} else if ($gender == 'Female'){
			$profilepic = 'femaledefault.jpg';
		}
		
		$query = "INSERT INTO members (
					firstname, othername, username, email, dob, hashed_password, gender, profilepic, regdate)
					VALUES ('{$firstname}','{$lastname}','{$username}','{$email}','{$dob}','{$hashed_password}','{$gender}','{$profilepic}', CURDATE())";
		
		$result = mysql_query($query, $connection);
		
		confirm_query($result);
		$userdetail = array();
		if($result){
			$_SESSION['user_id'] = mysql_insert_id();
			$_SESSION['username'] = $username;
			$_SESSION['email'] = $email;
			$_SESSION['userpf'] = $profilepic;
			$userdetail['userid'] = mysql_insert_id();
			$userdetail['username'] = $username;
			$userdetail['userpf'] = 'uploaded_files/profile_pictures/thumbs/'.$profilepic;
			$userdetail['reason'] = 'sucessful';	
			
			echo json_encode($userdetail);
		} else if (!result){
			$userdetail['reason'] = 'failure';
			echo json_encode($userdetail);
		}		
		
	}else{
		//didnt receive data
		//redirect_to('../testing.php');
	}

 ?>

<?php mysql_close($connection); ?>