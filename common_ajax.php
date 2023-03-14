<?php
    $allowed_file_extensions = '';
    defined('FM_FILE_EXTENSION') || define('FM_FILE_EXTENSION', $allowed_file_extensions);    
	defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);
	 
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
	
	/**
 * Prevent XSS attacks
 * @param string $text
 * @return string
 */
function fm_isvalid_filename($text) {
    return (strpbrk($text, '/?%*:|"<>') === FALSE) ? true : false;
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

/**
 * Check the file extension which is allowed or not
 * @param string $filename
 * @return bool
 */
function fm_is_valid_ext($filename)
{
     $allowed = (FM_FILE_EXTENSION) ? explode(',', FM_FILE_EXTENSION) : false;
	 
	 $ext = pathinfo($filename, PATHINFO_EXTENSION);
	
     $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

    return true;
}
	
	/**
	* Safely rename
	* @param string $old
	* @param string $new
	* @return bool|null
	*/
	function fm_rename($old, $new)
	{
		
		if(!file_exists($new) && file_exists($old)){
			rename($old, $new);
			return true;
		}
		else {
			
			return false;
		}
	  //return (!file_exists($new) && file_exists($old)) ? rename($old, $new) : null;
	}
	
 // Rename
 
   $FM_ROOT_PATH=$_POST['FM_ROOT_PATH'];
   $FM_PATH=$_POST['FM_PATH'];
   
  if (isset($_POST['ren'], $_POST['to'])) {
    // old name
    $old = $_POST['ren'];
    $old = fm_clean_path($old);
    $old = str_replace('/', '', $old);
    // new name
    $new = $_POST['to'];
    $new = fm_clean_path(strip_tags($new));
    $new = str_replace('/', '', $new);
    // path
    $path = $FM_ROOT_PATH;
    if ($FM_PATH != '') {
        $path .= '/' . $FM_PATH;
    }
	
	
	// path redirect the response
	  if ($FM_PATH != '') {
           $p=$FM_PATH;
      }
	  else {
		 $p=''; 
	  }
	
	// rename
    if (fm_isvalid_filename($new) && $old != '' && $new != '') {
		
		$rename=fm_rename($path . '/' . $old, $path . '/' . $new);
		if ($rename == '1') {
            //fm_set_msg(sprintf(lng('Renamed from').' <b>%s</b> '. lng('to').' <b>%s</b>', fm_enc($old), fm_enc($new)));
            $message=sprintf('Renamed from'.' <b>%s</b> '. 'to'.' <b>%s</b>', fm_enc($old), fm_enc($new));
			echo json_encode(array('success' => true, 'message' => $message, 'p'=> $p));   echo $json; exit();
		} else {
            //fm_set_msg(sprintf(lng('Error while renaming from').' <b>%s</b> '. lng('to').' <b>%s</b>', fm_enc($old), fm_enc($new)), 'error');
            $message=sprintf('Error while renaming from'.' <b>%s</b> '. 'to'.' <b>%s</b>', fm_enc($old), fm_enc($new)); 
			echo json_encode(array('success' => false, 'message' => $message, 'p'=> $p));   echo $json; exit();
		}
    } else {
        //fm_set_msg(lng('Invalid characters in file name'), 'error');
		    $message='Invalid characters in file name'; 
		    echo json_encode(array('success' => false, 'message' => $message, 'p'=> $p));   echo $json; exit();
    }
	 //fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
	//$json=json_encode(array('success' => true, 'message' => $starred_data));   echo $json; exit();
}
?>