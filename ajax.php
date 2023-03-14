<?php
    $root = __DIR__;
	$root = str_replace("filemanager","",$root);
	require_once($root."/constants.php");
	$dbhost = DB_HOST;
	$dbuser = DB_USER;
	$dbpass = DB_PASS;
	$dbname = DB_NAME;
	$con = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

	$userId=$_POST['userId'];
	$rowid=$_POST['rowid'];
	$pathid=$_POST['pathid'];

	$share_root_url = 'https://rosnyc.com/admin/index.php?action=filemanager';
	$root_path = $_SERVER['DOCUMENT_ROOT'].'/admin/filemanager';

	defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path.'/'.base64_encode($userId).'/');
	defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);

	//Check Connection
	$main_root_path = $_SERVER['DOCUMENT_ROOT'].'/admin/filemanager';
	if(mysqli_connect_errno())
	{
		print_f("Connection Failed: %s\n",mysqli_connect_error());
	}

	/**
 * Prevent XSS attacks
 * @param string $text
 * @return string
 */
function fm_isvalid_filename($text) {
    return (strpbrk($text, '/?%*:|"<>') === FALSE) ? true : false;
}

/**
 * Safely create folder
 * @param string $dir
 * @param bool $force
 * @return bool
 */
function fm_mkdir($dir, $force)
{
    if (file_exists($dir)) {
        if (is_dir($dir)) {
            return $dir;
        } elseif (!$force) {
            return false;
        }
        unlink($dir);
    }
    return mkdir($dir, 0777, true);
}

	/**
	* Encode html entities
	* @param string $text
	* @return string
	*/
	function fm_enc($text)
	{
	return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
	}

	function get_absolute_path($path) {
		$path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
		$parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
		$absolutes = array();
		foreach ($parts as $part) {
			if ('.' == $part) continue;
			if ('..' == $part) {
				array_pop($absolutes);
			} else {
				$absolutes[] = $part;
			}
		}
		return implode(DIRECTORY_SEPARATOR, $absolutes);
	}

	function fm_clean_path($path, $trim = true)
	{
		$path = $trim ? trim($path) : $path;
		$path = trim($path, '\\/');
		$path = str_replace(array('../', '..\\'), '', $path);
		$path =  get_absolute_path($path);
		if ($path == '..') {
			$path = '';
		}
		return str_replace('\\', '/', $path);
	}

	function custom_copy($src, $dst) {

    // open the source directory
    $dir = opendir($src);

    // Make the destination directory if not exist
    @mkdir($dst);

    // Loop through the files in source directory
    while( $file = readdir($dir) ) {

        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) )
            {

                // Recursively calling custom copy function
                // for sub directory
                custom_copy($src . '/' . $file, $dst . '/' . $file);

            }
            else {
                copy($src . '/' . $file, $dst . '/' . $file);
            }
        }
    }

    closedir($dir);
}

/**
 * Class to work with zip files (using ZipArchive)
 */
class FM_Zipper
{
    private $zip;

    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    /**
     * Create archive with name $filename and files $files (RELATIVE PATHS!)
     * @param string $filename
     * @param array|string $files
     * @return bool
     */
    public function create($filename, $files)
    {
        $res = $this->zip->open($filename, ZipArchive::CREATE);
        if ($res !== true) {
            return false;
        }
        if (is_array($files)) {
            foreach ($files as $f) {
                if (!$this->addFileOrDir($f)) {
                    $this->zip->close();
                    return false;
                }
            }
            $this->zip->close();
            return true;
        } else {
            if ($this->addFileOrDir($files)) {
                $this->zip->close();
                return true;
            }
            return false;
        }
    }

    /**
     * Extract archive $filename to folder $path (RELATIVE OR ABSOLUTE PATHS)
     * @param string $filename
     * @param string $path
     * @return bool
     */
    public function unzip($filename, $path)
    {
        $res = $this->zip->open($filename);
        if ($res !== true) {
            return false;
        }
        if ($this->zip->extractTo($path)) {
            $this->zip->close();
            return true;
        }
        return false;
    }

    /**
     * Add file/folder to archive
     * @param string $filename
     * @return bool
     */
    private function addFileOrDir($filename)
    {
        if (is_file($filename)) {
            return $this->zip->addFile($filename);
        } elseif (is_dir($filename)) {
            return $this->addDir($filename);
        }
        return false;
    }

    /**
     * Add folder recursively
     * @param string $path
     * @return bool
     */
    private function addDir($path)
    {
        if (!$this->zip->addEmptyDir($path)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        if (!$this->addDir($path . '/' . $file)) {
                            return false;
                        }
                    } elseif (is_file($path . '/' . $file)) {
                        if (!$this->zip->addFile($path . '/' . $file)) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
}

/**
 * Class to work with Tar files (using PharData)
 */
class FM_Zipper_Tar
{
    private $tar;

    public function __construct()
    {
        $this->tar = null;
    }

    /**
     * Create archive with name $filename and files $files (RELATIVE PATHS!)
     * @param string $filename
     * @param array|string $files
     * @return bool
     */
    public function create($filename, $files)
    {
        $this->tar = new PharData($filename);
        if (is_array($files)) {
            foreach ($files as $f) {
                if (!$this->addFileOrDir($f)) {
                    return false;
                }
            }
            return true;
        } else {
            if ($this->addFileOrDir($files)) {
                return true;
            }
            return false;
        }
    }

    /**
     * Extract archive $filename to folder $path (RELATIVE OR ABSOLUTE PATHS)
     * @param string $filename
     * @param string $path
     * @return bool
     */
    public function unzip($filename, $path)
    {
        $res = $this->tar->open($filename);
        if ($res !== true) {
            return false;
        }
        if ($this->tar->extractTo($path)) {
            return true;
        }
        return false;
    }

    /**
     * Add file/folder to archive
     * @param string $filename
     * @return bool
     */
    private function addFileOrDir($filename)
    {
        if (is_file($filename)) {
            try {
                $this->tar->addFile($filename);
                return true;
            } catch (Exception $e) {
                return false;
            }
        } elseif (is_dir($filename)) {
            return $this->addDir($filename);
        }
        return false;
    }

    /**
     * Add folder recursively
     * @param string $path
     * @return bool
     */
    private function addDir($path)
    {
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        if (!$this->addDir($path . '/' . $file)) {
                            return false;
                        }
                    } elseif (is_file($path . '/' . $file)) {
                        try {
                            $this->tar->addFile($path . '/' . $file);
                        } catch (Exception $e) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
}

function deleteDir($path) {
	if (empty($path)) {
        return false;
    }
    return is_file($path) ?
            @unlink($path) :
            array_map(__FUNCTION__, glob($path.'/*')) == @rmdir($path);
}

function fm_get_file_mimes($extension)
{
    $fileTypes['swf'] = 'application/x-shockwave-flash';
    $fileTypes['pdf'] = 'application/pdf';
    $fileTypes['exe'] = 'application/octet-stream';
    $fileTypes['zip'] = 'application/zip';
    $fileTypes['doc'] = 'application/msword';
    $fileTypes['xls'] = 'application/vnd.ms-excel';
    $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
    $fileTypes['gif'] = 'image/gif';
    $fileTypes['png'] = 'image/png';
    $fileTypes['jpeg'] = 'image/jpg';
    $fileTypes['jpg'] = 'image/jpg';
    $fileTypes['webp'] = 'image/webp';
    $fileTypes['avif'] = 'image/avif';
    $fileTypes['rar'] = 'application/rar';

    $fileTypes['ra'] = 'audio/x-pn-realaudio';
    $fileTypes['ram'] = 'audio/x-pn-realaudio';
    $fileTypes['ogg'] = 'audio/x-pn-realaudio';

    $fileTypes['wav'] = 'video/x-msvideo';
    $fileTypes['wmv'] = 'video/x-msvideo';
    $fileTypes['avi'] = 'video/x-msvideo';
    $fileTypes['asf'] = 'video/x-msvideo';
    $fileTypes['divx'] = 'video/x-msvideo';

    $fileTypes['mp3'] = 'audio/mpeg';
    $fileTypes['mp4'] = 'audio/mpeg';
    $fileTypes['mpeg'] = 'video/mpeg';
    $fileTypes['mpg'] = 'video/mpeg';
    $fileTypes['mpe'] = 'video/mpeg';
    $fileTypes['mov'] = 'video/quicktime';
    $fileTypes['swf'] = 'video/quicktime';
    $fileTypes['3gp'] = 'video/quicktime';
    $fileTypes['m4a'] = 'video/quicktime';
    $fileTypes['aac'] = 'video/quicktime';
    $fileTypes['m3u'] = 'video/quicktime';

    $fileTypes['php'] = ['application/x-php'];
    $fileTypes['html'] = ['text/html'];
    $fileTypes['txt'] = ['text/plain'];
    //Unknown mime-types should be 'application/octet-stream'
    if(empty($fileTypes[$extension])) {
      $fileTypes[$extension] = ['application/octet-stream'];
    }
    return $fileTypes[$extension];
}




 // Code for the starred Data
   if(isset($_POST['starred_data'])){
      $starred_data=$_POST['starred_data'];
	  $starred_data = fm_clean_path(urldecode($starred_data));

	 // abs path from
     $from = urldecode(FM_ROOT_PATH.$starred_data);

	 $file_type=is_file($from);

	if($file_type == true){

		$ftype='file';
	}
	else {
		$ftype='folder';
	}

    $file_name=basename($from);

	$dirname = dirname($starred_data);

	if($dirname == '.' OR  $dirname == '..'){  $dirname=''; }

	// Check for already starrred
	$resultchk=$con->query("SELECT * from filemanager_starred WHERE userId=".$_SESSION['userId']." AND file_name='".$file_name."'");
	$row_cnt = $resultchk->num_rows;
	if( $row_cnt > 0){
	  $json=json_encode(array('success' => false, 'message' => 'Already starred'));
	  echo $json;
	  exit();
	}

	// insert starred data
    $result=$con->query("INSERT into filemanager_starred SET
										userId='".$userId."',
										root_path='".$dirname."',
										file_name='".$file_name."',
                                        file_type='".$ftype."'");

    $json=json_encode(array('success' => true, 'result' => $starred_data));
    echo $json;
	exit();
   }

// Code for the Trash Data Delete for folder

 if(isset($_POST['trash_data_folder'])){

	$trash_data=$_POST['trash_data_folder'];

	$trash_data = fm_clean_path(urldecode($trash_data));

	 // abs path from
     $from = urldecode(FM_ROOT_PATH.$trash_data);

	 $file_type=is_file($from);

	 $file_name=basename($trash_data);

	 $dirname = dirname($trash_data);

	 $source=urldecode(FM_ROOT_PATH.$trash_data);

	 $dest=urldecode(FM_ROOT_PATH.'trash'.'/'.$file_name);

	if($file_type == true){
		$ftype='file';

		if(file_exists($dest)){
			deleteDir($dest);
		    $result=$con->query("DELETE from filemanager_trash WHERE file_name='".$file_name."'");
		    rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);
		}
		else {
			rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);
	    }
    }
	else {
		$ftype='folder';

		if(file_exists($dest)){

			deleteDir($dest);
			//echo $dest;
			//echo "<br>";
			//echo "DELETE from filemanager_trash WHERE file_name='".$file_name."'";
		    $result=$con->query("DELETE from filemanager_trash WHERE file_name='".$file_name."'");

		    rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);


		}
		else {
			rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);
		}
	}

	 $status=1;

	 $modif = date ("Y-m-d h:i:s", filemtime($dest));
	 $modif=strtotime($modif);

	// Delete First from Starred Table
	$resultchk=$con->query("SELECT * from filemanager_starred WHERE userId=".$_SESSION['userId']." AND file_name='".$file_name."'");
	$row_cnt = $resultchk->num_rows;
	if( $row_cnt > 0){
		$rdata=mysqli_fetch_assoc($resultchk);
		$del_id=$rdata['Id'];
	    $result=$con->query("DELETE from filemanager_starred WHERE Id=".$del_id);
	}

	// Delete First from Share Table
	$resultchk1=$con->query("SELECT * from filemanager_share WHERE userId=".$_SESSION['userId']." AND file_name='".$file_name."'");
	$row_cnt1 = $resultchk1->num_rows;
	if( $row_cnt1 > 0){
		$rdata1=mysqli_fetch_assoc($resultchk1);
		$del_id1=$rdata1['id'];
	    $result1=$con->query("DELETE from filemanager_share WHERE Id=".$del_id1);
	}

	if($dirname == '.' OR  $dirname == '..'){  $dirname=''; }
	// insert starred data

	$result=$con->query("INSERT into filemanager_trash SET
										userId='".$userId."',
										root_path='".$dirname."',
										file_name='".$file_name."',
                                        file_type='".$ftype."',
										modif='".$modif."',
										fulltrashpath='".$fulltrashpath."',
										status='".$status."'");

    $json=json_encode(array('success' => true, 'result' => $dest, 'pathid' =>$pathid));
	echo $json;
  exit();
 }

// Code for the Trash Data

 if(isset($_POST['trash_data'])){

	$trash_data=$_POST['trash_data'];

	$trash_data = fm_clean_path(urldecode($trash_data));

	 // abs path from
     $from = urldecode(FM_ROOT_PATH.$trash_data);

	 $file_type=is_file($from);

	 $file_name=basename($trash_data);

	 $dirname = dirname($trash_data);

	 $source=urldecode(FM_ROOT_PATH.$trash_data);

	 $dest=urldecode(FM_ROOT_PATH.'trash'.'/'.$file_name);

	if($file_type == true){
		$ftype='file';

		if(file_exists($dest)){
			deleteDir($dest);
		    $result=$con->query("DELETE from filemanager_trash WHERE file_name='".$file_name."'");
		    rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);
		}
		else {
			rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);
	    }
    }
	else {
		$ftype='folder';

		if(file_exists($dest)){

			deleteDir($dest);
			//echo $dest;
			//echo "<br>";
			//echo "DELETE from filemanager_trash WHERE file_name='".$file_name."'";
		    $result=$con->query("DELETE from filemanager_trash WHERE file_name='".$file_name."'");

		    rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);


		}
		else {
			rename($source, $dest) ? "OK" : "ERROR";
			$fulltrashpath=$dest;
			//echo "Source===>".$source;
			unlink($source);
		}
	}

	 $status=1;

	 $modif = date ("Y-m-d h:i:s", filemtime($dest));
	 $modif=strtotime($modif);

	// Delete First from Starred Table
	$resultchk=$con->query("SELECT * from filemanager_starred WHERE userId=".$_SESSION['userId']." AND file_name='".$file_name."'");
	$row_cnt = $resultchk->num_rows;
	if( $row_cnt > 0){
		$rdata=mysqli_fetch_assoc($resultchk);
		$del_id=$rdata['Id'];
	    $result=$con->query("DELETE from filemanager_starred WHERE Id=".$del_id);
	}

	// Delete First from Share Table
	$resultchk1=$con->query("SELECT * from filemanager_share WHERE userId=".$_SESSION['userId']." AND file_name='".$file_name."'");
	$row_cnt1 = $resultchk1->num_rows;
	if( $row_cnt1 > 0){
		$rdata1=mysqli_fetch_assoc($resultchk1);
		$del_id1=$rdata1['Id'];
	    $result1=$con->query("DELETE from filemanager_share WHERE Id=".$del_id1);
	}

	if($dirname == '.' OR  $dirname == '..'){  $dirname=''; }
	// insert starred data

	$result=$con->query("INSERT into filemanager_trash SET
										userId='".$userId."',
										root_path='".$dirname."',
										file_name='".$file_name."',
                                        file_type='".$ftype."',
										modif='".$modif."',
										fulltrashpath='".$fulltrashpath."',
										status='".$status."'");

    $json=json_encode(array('success' => true, 'result' => $dest, 'rowid' =>$rowid));
	echo $json;
  exit();
 }


  if(isset($_POST['restore_data'])){

	$restore_data=$_POST['restore_data'];

	$restore_data = fm_clean_path(urldecode($restore_data));

	 // abs path from
     $from = urldecode(FM_ROOT_PATH.$restore_data);

	 $file_type=is_file($from);

	 $file_name=basename($restore_data);

	 $dirname = dirname($restore_data);

	 $source=urldecode(FM_ROOT_PATH.'trash'.'/'.$file_name);

	 $dest=urldecode(FM_ROOT_PATH.$restore_data);
	 if($file_type == true){
		$ftype='file';
		rename($source, $dest) ? "OK" : "ERROR";
    }
	else {
		$ftype='folder';
		rename($source, $dest) ? "OK" : "ERROR";
	}

	$status=1;

	if($dirname == '.' OR  $dirname == '..'){  $dirname=''; }
	// Delete row from trash table

	$result=$con->query("DELETE from filemanager_trash WHERE userId=".$userId." AND file_name='".$file_name."'");


	$json=json_encode(array('success' => true, 'result' => $dest, 'rowid' =>$rowid));
	echo $json;
  exit();
 }

// Delete From the Trash
if(isset($_POST['trash_del_data'])){

	$trash_del_data=$_POST['trash_del_data'];

	$trash_del_data = fm_clean_path(urldecode($trash_del_data));

	 // abs path from
     $from = urldecode(FM_ROOT_PATH.$trash_del_data);

	 $file_type=is_file($from);

	 $file_name=basename($trash_del_data);

	 $dirname = dirname($trash_del_data);

	 $source=urldecode(FM_ROOT_PATH.'trash'.'/'.$file_name);

	 $dest=urldecode(FM_ROOT_PATH.$trash_del_data);
	 if($file_type == true){
		$ftype='file';
		unlink($source);
    }
	else {
		$ftype='folder';
		unlink($source);
	}

	$status=1;

	if($dirname == '.' OR  $dirname == '..'){  $dirname=''; }

	// Delete row from trash table
	$result=$con->query("DELETE from filemanager_trash WHERE userId=".$userId." AND file_name='".$file_name."'");
    $json=json_encode(array('success' => true, 'result' => $source, 'rowid' =>$rowid));
	echo $json;
  exit();
 }

// Mass From the Trash
if(isset($_POST['masstrashdelete'])){

	$selectedvalues=$_POST['selectedvalues'];
	$userId=$_POST['userId'];

	$trash_del_data = fm_clean_path(urldecode($trash_del_data));

	 // abs path from

	 if(count($selectedvalues) > 0){

		 foreach($selectedvalues as $value){

		 $result=$con->query("SELECT * from filemanager_trash WHERE Id=".$value);
		 $data=mysqli_fetch_assoc($result);

         $file_type=$data['file_type'];
		 $source=$data['fulltrashpath'];

			 if($file_type == true){
				$ftype='file';
				unlink($source);
			}
			else {
				$ftype='folder';
				unlink($source);
			 }

		// Delete row from trash table
	    $result=$con->query("DELETE from filemanager_trash WHERE userId=".$userId." AND Id='".$value."'");
	    }
	$json=json_encode(array('success' => true, 'message' => 'successfully Delete'));
	echo $json;
    exit();
    }
    else {
      $json=json_encode(array('success' => false, 'message' =>'Nothing Selected!'));
	  echo $json;
      exit();
    }
 }

// Share
 if(isset($_POST['share_emails'])){

	$share_emails=trim($_POST['share_emails']);

	$sharemailsarr=explode(',', $share_emails);

    foreach($sharemailsarr as $share_emails){

	$res=$con->query("SELECT * from users WHERE userEmail='".$share_emails."'");

    $row_cnt = $res->num_rows;

	if($row_cnt > 0){

		$userdata=mysqli_fetch_assoc($res);

		$userdestId=$userdata['userID'];

		$to_user=$userdestId;

		$db_root_path=$userdata['root_path'];

		// encrypt User ID
		 $userdestId=base64_encode($userdestId);
		 $destdir=$main_root_path.'/'.$userdestId.'/'.'shared/';

		  if(!is_dir($destdir)){
			 $json=json_encode(array('success' => false, 'message' =>'You cant Share to '.$userdata['userEmail'].' Because Thier is no shared folder.' ));
			 echo $json;
			 exit();
		  }
		  else {
			$file_path=$_POST['root_path'];

			$file_path = fm_clean_path(urldecode($file_path));

			// abs path from
			$file = urldecode(FM_ROOT_PATH.$file_path);

			$file_type=is_file($file);

			$file_name=basename($file_path);

			$dirname = dirname($file_path);

			if($dirname == '.' OR  $dirname == '..'){  $dirname=''; }

			     $source=urldecode(FM_ROOT_PATH.$file_path);

			     $dest=urldecode($destdir.$file_path);

			if($file_type == true){
			  $ftype='file';
			  // Copy the  File in the Other User shared folder
			  copy($source, $dest) ? "OK" : "ERROR";

			  $share_url=$share_root_url.'&p=' . urlencode('shared/'.$db_root_path) . '&amp;view=' . urlencode($file_name);
			}
			else {
			  $ftype='folder';
			  // Copy the Folder  in the Other User shared folder
			  custom_copy($source, $dest);

			  $share_url=$share_root_url.'&p=' . urlencode(trim(('shared/'.$data['root_path'] != '' ? '/' . 'shared/'.$data['root_path'] : '') . '/' . $data['file_name'], '/'));
			}
			$status=1;

	  //insert starred data
	  $result=$con->query("INSERT into filemanager_share SET
									userId='".$userId."',
									to_user='".$to_user."',
									share_emails='".$share_emails."',
									root_path='".$dirname."',
									file_name='".$file_name."',
									file_type='".$ftype."',
									status='".$status."'");

	$to  = $share_emails; // Note the comma


    // Subject
    $subject = 'Documents Shared With You';

    // Message
    $message = '
      <p>Your Share link of ".$ftype." is ".$share_url."</p>';

    // To send HTML mail, the Content-type header must be set
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

    $headers .= 'From: Shared Documents <info@rosnyc.com>' . "\r\n";


    // Mail it
    if(@mail($to, $subject, $message, $headers)){
		$str="Mail Sent Successfully";
	}
	else {

		$str="Mail Not Sent";;
	}

      $json=json_encode(array('success' => true, 'result' => $dest));
	  echo $json;
      exit();
	   }
	}
	else {
	   $json=json_encode(array('success' => false, 'message' =>'User '.$share_emails.' Does not exists our Database'));
	   echo $json;
	   exit();
	   }
	}// for each loop Ends Here
 }

// Delete from share...............

if(isset($_POST['deleteshare'])){

	$sharedel=$_POST['sharedel'];
	$userId=$_POST['userId'];

	if($sharedel > 0){
	   $result=$con->query("DELETE from filemanager_share WHERE id=".$sharedel);
	   $json=json_encode(array('success' => true, 'message' =>'Successfully Delete'));
	   echo $json;
	   exit();
	}
    else {
       $json=json_encode(array('success' => false, 'message' =>'Something Went Wrong!'));
	   echo $json;
	   exit();
    }
}

// Starred from share...............

if(isset($_POST['unstarred'])){

	$unstarredid=$_POST['unstarredid'];
	$userId=$_POST['userId'];

	if($unstarredid > 0){
	   $result=$con->query("DELETE from filemanager_starred WHERE id=".$unstarredid);
	   $json=json_encode(array('success' => true, 'message' =>'Successfully Unstarred'));
	   echo $json;
	   exit();
	}
    else {
       $json=json_encode(array('success' => false, 'message' =>'Something Went Wrong!'));
	   echo $json;
	   exit();
    }
}

// Mass Delete

if (isset($_POST['massdelete'])) {

	$root_path=$_POST['FM_ROOT_PATH'];
	$path = $_POST['FM_ROOT_PATH'];
	$FM_PATH = $_POST['FM_PATH'];
	$userId=$_POST['userId'];
    if ($FM_PATH != '') {
        $path .= '/' . $FM_PATH;
    }

    $errors = 0;
    $files = $_POST['selectedvalues'];

	if(is_array($files) && count($files)) {
          foreach ($files as $f) {

			if ($f != '') {

				$from = $path.'/'.$f;

				$file_type=is_file($from);

				$file_name=basename($f);
				if(FM_PATH != ''){
				  $dirname = dirname(FM_PATH.$f);
				}
				else {
				  $dirname = dirname($f);
				}

			    $source=urldecode($path.'/'.$f);

				$dest=urldecode($root_path.'trash'.'/'.$file_name);

				 if($file_type == true){
					    $ftype='file';

					    if(file_exists($dest)){
							deleteDir($dest);
							$result=$con->query("DELETE from filemanager_trash WHERE file_name='".$file_name."'");
							rename($source, $dest) ? "OK" : "ERROR";
							$fulltrashpath=$dest;
							//echo "Source===>".$source;
							deleteDir($source);
					    }
						else {
								rename($source, $dest) ? "OK" : "ERROR";
								$fulltrashpath=$dest;
								//echo "Source===>".$source;
								deleteDir($source);
						}
					}
					else {
						$ftype='folder';
							if(file_exists($dest)){
							  deleteDir($dest);
							  $result=$con->query("DELETE from filemanager_trash WHERE file_name='".$file_name."'");
							  rename($source, $dest) ? "OK" : "ERROR";
							  $fulltrashpath=$dest;
							//echo "Source===>".$source;
							 deleteDir($source);
							}
							else {
								rename($source, $dest) ? "OK" : "ERROR";
								$fulltrashpath=$dest;
								//echo "Source===>".$source;
								deleteDir($source);
					    }
					}

					$status=1;

					$modif = date ("Y-m-d h:i:s", filemtime($dest));

					$modif=strtotime($modif);

						// Delete from Starred Table
						$resultchk=$con->query("SELECT * from filemanager_starred WHERE userId=".$userId." AND file_name='".$file_name."'");
						$row_cnt = $resultchk->num_rows;
						if( $row_cnt > 0){
						$rdata=mysqli_fetch_assoc($resultchk);
						$del_id=$rdata['Id'];
						$result=$con->query("DELETE from filemanager_starred WHERE Id=".$del_id);
						}

						// Delete from Share Table
						$resultchk1=$con->query("SELECT * from filemanager_share WHERE userId=".$userId." AND file_name='".$file_name."'");
						$row_cnt1 = $resultchk1->num_rows;
						if( $row_cnt1 > 0){
						$rdata1=mysqli_fetch_assoc($resultchk1);
						$del_id1=$rdata1['Id'];
						$result1=$con->query("DELETE from filemanager_share WHERE Id=".$del_id1);
						}

						if($dirname == '.' OR  $dirname == '..'){  $dirname=''; }
						// insert starred data

						$result=$con->query("INSERT into filemanager_trash SET
										userId='".$userId."',
										root_path='".$dirname."',
										file_name='".$file_name."',
										file_type='".$ftype."',
										modif='".$modif."',
										fulltrashpath='".$fulltrashpath."',
										status='".$status."'");
			 }
        }
		// Return the response
		if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
	      echo json_encode(array('success' => true, 'message' => 'Selected files Or folder deleted', 'p'=> $FM_PATH));   echo $json; exit();
	    } else {
		// Return the response
		if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
        echo json_encode(array('success' => false, 'message' => 'Nothing Selected', 'p'=> $FM_PATH));   echo $json; exit();
    }
 }


 // Pack files
if(isset($_POST['packfiles'])){
    $path = $_POST['FM_ROOT_PATH'];
	$FM_PATH = $_POST['FM_PATH'];
	$userId=$_POST['userId'];
    $ext = 'zip';

	if ($FM_PATH != '') {
        $path .= '/' . $FM_PATH;
    }

    //set pack type
    $ext = $_POST['type'];


    if (($ext == "zip" && !class_exists('ZipArchive')) || ($ext == "tar" && !class_exists('PharData'))) {
        //fm_set_msg(lng('Operations with archives are not available'), 'error');


		$message='Operations with archives are not available';
		if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
		echo json_encode(array('success' => false, 'message' => $message, 'p'=> $FM_PATH));   echo $json; exit();


    }

    $files = $_POST['selectedvalues'];

	if (!empty($files)) {
		chdir($path);

       if (count($files) == 1) {
            $one_file = reset($files);
            $one_file = basename($one_file);
            $zipname = $one_file . '_' . date('ymd_His') . '.'.$ext;
        } else {
            $zipname = 'archive_' . date('ymd_His') . '.'.$ext;
        }

        if($ext == 'zip') {
            $zipper = new FM_Zipper();
            $res = $zipper->create($zipname, $files);
        } elseif ($ext == 'tar') {
            $tar = new FM_Zipper_Tar();
            $res = $tar->create($zipname, $files);
        }

        if ($res) {
            //fm_set_msg(sprintf(lng('Archive').' <b>%s</b> '.lng('Created'), fm_enc($zipname)));
			$message=sprintf('Archive'.' <b>%s</b> '.'Created', fm_enc($zipname));
			if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
			echo json_encode(array('success' => true, 'message' => $message, 'p'=> $FM_PATH));   echo $json; exit();

        } else {
            //fm_set_msg(lng('Archive not created'), 'error');
			$message='Archive not created';
			if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
			echo json_encode(array('success' => false, 'message' => $message, 'p'=> $FM_PATH));   echo $json; exit();
        }
    } else {
        //fm_set_msg(lng('Nothing selected'), 'alert');
		   $message='Nothing selected';
		   if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
		   echo json_encode(array('success' => false, 'message' => $message, 'p'=> $FM_PATH));   echo $json; exit();
    }

    //fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}

//  Mass share Delete
if(isset($_POST['masssharedelete'])){
    $selectedvalues=$_POST['selectedvalues'];
	$userId=$_POST['userId'];

	$path = $_POST['FM_ROOT_PATH'];
	$FM_PATH = $_POST['FM_PATH'];

	if(count($selectedvalues) > 0){

	   foreach($selectedvalues as $value){
	      $result=$con->query("DELETE from filemanager_share WHERE id=".$value);
	    }
	   if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
	   $json=json_encode(array('success' => true, 'message' =>'Successfully Delete', 'p'=> $FM_PATH));

	   echo $json;
	   exit();
	}
    else {
	   if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
       $json=json_encode(array('success' => false, 'message' =>'Something Went Wrong!', 'p'=> $FM_PATH));
	   echo $json;
	   exit();
    }
}

// Delete Mass  Starred Delete

if(isset($_POST['massstarreddelete'])){

   $selectedvalues=$_POST['selectedvalues'];
	$userId=$_POST['userId'];

	$path = $_POST['FM_ROOT_PATH'];
	$FM_PATH = $_POST['FM_PATH'];

	if(count($selectedvalues) > 0){

	   foreach($selectedvalues as $value){
	      $result=$con->query("DELETE from filemanager_starred WHERE Id=".$value);
	    }
	   if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
	   $json=json_encode(array('success' => true, 'message' =>'Successfully Delete', 'p'=> $FM_PATH));

	   echo $json;
	   exit();
	}
    else {
	   if ($FM_PATH != '') { $FM_PATH; } else { $FM_PATH=''; }
       $json=json_encode(array('success' => false, 'message' =>'Something Went Wrong!', 'p'=> $FM_PATH));
	   echo $json;
	   exit();
    }

}

// Create folder
if (isset($_POST['newname']) && isset($_POST['type'])) {
    $type = $_POST['type'];
	$FM_ROOT_PATH=$_POST['FM_ROOT_PATH'];
	$FM_PATH=$_POST['FM_PATH'];

    $new = str_replace( '/', '', fm_clean_path( strip_tags( $_POST['newname'] ) ) );
    if (fm_isvalid_filename($new) && $new != '' && $new != '..' && $new != '.') {
        $path = $FM_ROOT_PATH;
        if ($FM_PATH != '') {
            $path .= '/' . $FM_PATH;
        }
        if ($_POST['type'] == "file") {
            if (!file_exists($path . '/' . $new)) {
                if(fm_is_valid_ext($new)) {
                    @fopen($path . '/' . $new, 'w') or die('Cannot open file:  ' . $new);
                    fm_set_msg(sprintf(lng('File').' <b>%s</b> '.lng('Created'), fm_enc($new)));
                } else {
                    fm_set_msg(lng('File extension is not allowed'), 'error');
                }
            } else {
                fm_set_msg(sprintf(lng('File').' <b>%s</b> '.lng('already exists'), fm_enc($new)), 'alert');
            }
        } else {
            if (fm_mkdir($path . '/' . $new, false) === true) {
                //fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('Created'), $new));
				if ($FM_PATH != '') { $FM_PATH=$FM_PATH; } else { $FM_PATH=''; }
				$json=json_encode(array('success' => true, 'message' =>'Folder Created', 'p'=> $FM_PATH));
				echo $json;
				exit();


            } elseif (fm_mkdir($path . '/' . $new, false) === $path . '/' . $new) {
                //fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('already exists'), fm_enc($new)), 'alert');
				if ($FM_PATH != '') { $FM_PATH=$FM_PATH; } else { $FM_PATH=''; }
				 $json=json_encode(array('success' => false, 'message' =>'Folder Already Exist!', 'p'=> $FM_PATH));
				 echo $json;
				 exit();

            } else {
                //fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('not created'), fm_enc($new)), 'error');
				 $json=json_encode(array('success' => false, 'message' =>'Folder not created!', 'p'=> $FM_PATH));
				 echo $json;
				 exit();

            }
        }
    } else {
        //fm_set_msg(lng('Invalid characters in file or folder name'), 'error');
		 $json=json_encode(array('success' => false, 'message' =>'Invalid characters in file or folder name!', 'p'=> $FM_PATH));
			 echo $json;
			 exit();
    }
}

// Download
if (isset($_POST['dl'])) {
    $dl = $_POST['dl'];
	$userId=$_POST['userId'];
	$fm_path=$_POST['fm_path'];
    $dl = fm_clean_path($dl);
    $dl = str_replace('/', '', $dl);
    $path = FM_ROOT_PATH;

    if ($fm_path != '') {
        $path .= $fm_path;
    }


	if ($dl != '' && is_file($path.'/'.$dl)) {
     	$downloadpath="https://rosnyc.com/admin/filemanager/".base64_encode($userId)."";
		$toBeDownloaded=$downloadpath.$fm_path.'/'.$dl;

	    $json=json_encode(array('success' => true, 'toBeDownloaded'=>$toBeDownloaded, 'dl'=>$dl, 'message' =>'Successfully Download', 'p'=> $fm_path));
	    echo $json;
		exit();
    } else {
        //fm_set_msg(lng('File not found'), 'error');
        //fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }
}
?>