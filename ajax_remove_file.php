<?php
include('dbconnection.php');
$userId=$_SESSION['userId'];
    $file=$_POST['file'];
    $FM_ROOT_PATH=$_POST['FM_ROOT_PATH'];
	$FM_PATH=$_POST['FM_PATH'];
  
  if (!empty($file)) {
		$path = $FM_ROOT_PATH;
		$ds = DIRECTORY_SEPARATOR;
		
		if ($FM_PATH != '') {
			$path .= '/' . $FM_PATH;
		}
		
		$fullpath=$path.'/'.$file;
		
		if($fullpath !=''){
			unlink($fullpath);
		}
	}
exit();
?>