<?php require_once("../include/session.php"); ?>
<?php require_once("../include/connection.php"); ?>
<?php require_once("../include/functions.php"); ?>
<?php
 
/**Function to create thumbnails*/
function thumbs($orig_directory, $folder){
	
   //Full path to image folder /* change this path */
	$thumb_directory =  $orig_directory;    //Thumbnail folder
/* Opening the thumbnail directory and looping through all the thumbs: */
	$dir_handle = @opendir($orig_directory); //Open Full image dirrectory
	if ($dir_handle > 1){ //Check to make sure the folder opened
   
	$allowed_types=array('jpg','jpeg','gif','png');
	$file_parts=array();
	$ext='';
	$title='';
	$i=0;


while ($file = @readdir($dir_handle))
{

	/* Skipping the system files: */
	if($file=='.' || $file == '..') continue;

	$file_parts = explode('.',$file);    //This gets the file name of the images
	$ext = strtolower(array_pop($file_parts));

	/* Using the file name (withouth the extension) as a image title: */
	$title = implode('.',$file_parts);
	$title = htmlspecialchars($title);

	/* If the file extension is allowed: */
	if(in_array($ext,$allowed_types))
	{
		/* The code past here is the code at the start of the tutorial */
		/* Outputting each image: */
		list($width, $height) = getimagesize($orig_directory.'/'.$file);
		$scale = 1;
		if($width > 200 || $height > 200){
			$test = $width > $height ? $width : $height;
			$scale = ($test/200) != 0 ? ($test/200): 1;
		}
		
		$nw = $width/$scale;
		$nh = $height/$scale;
		$source = "{$orig_directory}/{$file}";
		$stype = explode(".", $source);
		$stype = $stype[count($stype)-1];
		$dir="{$thumb_directory}/thumbs";
		if (!is_dir($dir)) {
				mkdir($dir);        
		}
		$dest = "{$thumb_directory}/thumbs/{$file}";

		$size = getimagesize($source);
		$w = $size[0];
		$h = $size[1];

		switch($stype) {
			case 'gif':
				$simg = imagecreatefromgif($source);
				break;
			case 'jpg':
				$simg = imagecreatefromjpeg($source);
				break;
			case 'png':
				$simg = imagecreatefrompng($source);
				break;
		}

		$dimg = imagecreatetruecolor($nw, $nh);
		$wm = $w/$nw;
		$hm = $h/$nh;
		$h_height = $nh/2;
		$w_height = $nw/2;

		if($w> $h) {
			$adjusted_width = $w / $hm;
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $w_height;
			imagecopyresampled($dimg,$simg,-$int_width,0,0,0,$adjusted_width,$nh,$w,$h);
		} elseif(($w <$h) || ($w == $h)) {
			$adjusted_height = $h / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;

			imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);
		} else {
			imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
		}
			imagejpeg($dimg,$dest,100);
		}
}
  return true;
/* Closing the directory */
@closedir($dir_handle);

}
}
 
if (!empty($_FILES["myFile"])) {
	$folder = $_POST['folderlocation'];
	define("UPLOAD_DIR", "../uploaded_files/".$folder."/");
    
	// verify the file is a GIF, JPEG, or PNG
	$fileType = exif_imagetype($_FILES["myFile"]["tmp_name"]);
	$allowed = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);
	if (!in_array($fileType, $allowed)) {
		// file type is not permitted
		header('Location: index.php?message=invalid upload');
		exit;
	} else{
	
		$myFile = $_FILES["myFile"];
	 
		if ($myFile["error"] !== UPLOAD_ERR_OK) {
			echo "<p>An error occurred.</p>";
			exit;
		}
	 
		// ensure a safe filename
		$name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);
	 
		// don't overwrite an existing file
		$i = 0;
		$parts = pathinfo($name);
		while (file_exists(UPLOAD_DIR . $name)) {
			$i++;
			$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
		}
	 
		// preserve file from temporary directory
		$success = move_uploaded_file($myFile["tmp_name"], UPLOAD_DIR . $name);
		if (!$success) {
			echo "<p>Unable to save file.</p>";
			exit;
		}else{
			//create thumbnails for the images that have been uploaded
			thumbs('../uploaded_files/'.$folder, $folder);
		}		
		echo "<img id='image_dummy' src='uploaded_files/".$folder."/thumbs/".$name."'  name='".$name."'/>";
		// set proper permissions on the new file
		chmod(UPLOAD_DIR . $name, 0644);
		
		if($folder == 'profile_pictures'){		
			$result = mysql_query("UPDATE members SET profilepic = '{$name}' WHERE id = '{$_SESSION['user_id']}' ") or die ("Database query has failed: ".mysql_error());
			$_SESSION['userpf'] = $name;
		}
	}
}
?>
<?php 
	mysql_close();
?>