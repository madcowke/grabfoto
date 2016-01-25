<?php
$tussenDir="/testing/grabfoto_github/";
function resize($width, $height){
  /* Get original image x y*/
  
  list($w, $h) = getimagesize($_FILES['imageFile']['tmp_name']);
  /* calculate new image size with ratio */
  $ratio = $w/$h;
  //echo "ratio: ".$ratio."<br>";
  
  if($ratio>1){
	$height=ceil($h*$width/$w);
	$y=$height;
	$x=0;
  }else{
	$width=ceil($w*$height/$h);
	$x=$width;
	$y=0;
  }
  
  
	
	$randomNummer=rand();
	$today = date("Y-m-d");
	if (!file_exists($_SERVER["DOCUMENT_ROOT"] .$GLOBALS['tussenDir']. 'images/fotos/'.$today)) {
		$oldmask = umask(0);
		mkdir($_SERVER["DOCUMENT_ROOT"] .$GLOBALS['tussenDir']. 'images/fotos/'.$today, 0777, true);
		//chmod($_SERVER["DOCUMENT_ROOT"] .$GLOBALS['tussenDir']. '/images/fotos/'.$today, 0777);
		umask($oldmask);
		
	}
  /* new file name */
  $path =$_SERVER["DOCUMENT_ROOT"] .$GLOBALS['tussenDir']. 'images/fotos/'.$today.'/'.$randomNummer.'_'.$width.'x'.$height.'_'.preg_replace("/[^a-zA-Z.]+/", "", $_FILES['imageFile']['name']);
  $pathToReturn=$GLOBALS['tussenDir']. 'images/fotos/'.$today.'/'.$randomNummer.'_'.$width.'x'.$height.'_'.preg_replace("/[^a-zA-Z.]+/", "", $_FILES['imageFile']['name']);
  //echo $path;
  
  /* read binary data from image file */
  $imgString = file_get_contents($_FILES['imageFile']['tmp_name']);
  /* origineel op de server zetten */
  move_uploaded_file($_FILES["imageFile"]["tmp_name"], $_SERVER["DOCUMENT_ROOT"] .$GLOBALS['tussenDir']. 'images/fotos/'.$today.'/'.$randomNummer.'_'.preg_replace("/[^a-zA-Z.]+/", "", $_FILES['imageFile']['name']));
  /* create image from string */
  $image = imagecreatefromstring($imgString);
  $tmp = imagecreatetruecolor($width, $height);
  imagecopyresampled($tmp, $image,
    0, 0,
    0, 0,
    $width, $height,
    $w, $h);
	/*
	echo "new x:".$x."<br>";
	echo "new y:".$y."<br>";
	echo "new width:".$width."<br>";	
	echo "new height:".$height."<br>";
	echo "original width:".$w."<br>";
	echo "original height:".$h."<br>";
	*/
  /* Save image */
  switch ($_FILES['imageFile']['type']) {
    case 'image/jpeg':
      imagejpeg($tmp, $path, 100);
	  //echo "jpeg";
      break;
    case 'image/pjpeg':
	  imagejpeg($tmp, $path, 100);
	  //echo "pjpeg";
      break;
	case 'image/png':
      imagepng($tmp, $path, 0);
	  //echo "png";
      break;
    case 'image/gif':
      imagegif($tmp, $path);
	  //echo "gif";
      break;
	case 'image/x-png':
	  imagepng($tmp, $path, 0);
	  //echo "x-png";
      break;	
    default:
      exit;
      break;
  }
  
  print '{"path":"'.$pathToReturn.'"}';
  
  /* cleanup memory */
  imagedestroy($image);
  imagedestroy($tmp);
}

if(file_exists($_FILES['imageFile']['tmp_name']) || is_uploaded_file($_FILES['imageFile']['tmp_name'])) {
	//echo "exists<br>";
	$file=$_FILES['imageFile']['tmp_name'];
	$fileError=True;
	$allowedExts = array("jpg","jpeg","png","gif","JPG","PNG","JPEG","GIF");
	$extension = end(explode(".", $_FILES["imageFile"]["name"]));
	
	if ((($_FILES["imageFile"]["type"] == "image/gif")
	|| ($_FILES["imageFile"]["type"] == "image/jpeg")
	|| ($_FILES["imageFile"]["type"] == "image/jpg")
	|| ($_FILES["imageFile"]["type"] == "image/pjpeg")
	|| ($_FILES["imageFile"]["type"] == "image/x-png")
	|| ($_FILES["imageFile"]["type"] == "image/png"))
	&& ($_FILES["imageFile"]["size"] < 5000000)
	&& in_array($extension, $allowedExts)) {
		$imageFile = addslashes(file_get_contents($_FILES['imageFile']['tmp_name']));
		
		$image_size = getimagesize($_FILES['imageFile']['tmp_name']);
		$tmp_path=resize(100,75);
		//echo "result:on";
		//print '{"result":"ok"}';
		//echo $tmp_path;
		/*
		if($image_size==FALSE){
			$filePath='';
			//echo "size is false<br>";
		}else{
			$filePath=$tmp_path;
			print '{"path":"'+$filePath+'"}';
			//echo "gelukt<br>";
		}
		*/
	}else{
		print '{"path":"Bestand is geen foto of is groter dan 5 MB"}';
		//echo "geen toegestane extensie<br>";
	}


}else{
	//echo "geen file<br>";
	print '{"path":"Het uploaden is mislukt"}';

}
?>
