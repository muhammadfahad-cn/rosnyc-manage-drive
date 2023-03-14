<?php
include('dbconnection.php');
$userId=$_SESSION['userId'];
/**
 * Get total size of directory tree.
 *
 * @param  string $directory Relative or absolute directory name.
 * @return int Total number of bytes.
 */
function fm_get_directorysize($directory) {
    $bytes = 0;
    $directory = realpath($directory);
    if ($directory !== false && $directory != '' && file_exists($directory)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)) as $file){
            $bytes += $file->getSize();
        }
    }
    return $bytes;
}

/**
 * Prevent XSS attacks
 * @param string $text
 * @return string
 */
function fm_isvalid_filename($text) {
    return (strpbrk($text, '/?%*:|"<>') === FALSE) ? true : false;
}

  $allowed_upload_extensions = '';
  defined('FM_UPLOAD_EXTENSION') || define('FM_UPLOAD_EXTENSION', $allowed_upload_extensions);
  
 
			
  // This is the code for the Upload Image in the Particular Directory			
			
   if (!empty($_FILES)) {
    $override_file_name = false;
    $chunkIndex = $_POST['dzchunkindex'];
    $chunkTotal = $_POST['dztotalchunkcount'];
	
	$FM_ROOT_PATH=$_POST['FM_ROOT_PATH'];
	$FM_PATH=$_POST['FM_PATH'];
	$upload_folder_name=$_POST['upload_folder_name'];
	
	if($upload_folder_name != ''){
		$path = $FM_ROOT_PATH.$upload_folder_name.'/';
	}
	else {
		
		$path = $FM_ROOT_PATH;
	}
	$f = $_FILES;
	$ds = DIRECTORY_SEPARATOR;
    if ($FM_PATH != '') {
        $path .= '/' . $FM_PATH;
    }
	//echo $path;
	$errors = 0;
    $uploads = 0;
    $allowed = (FM_UPLOAD_EXTENSION) ? explode(',', FM_UPLOAD_EXTENSION) : false;
    $response = array (
        'status' => 'error',
        'info'   => 'Oops! Try again'
    );

   if($upload_folder_name != ''){
	    $filename = $f['file']['name'];
		$tmp_name = $f['file']['tmp_name'];
		$file_size= $f['file']['size']; // size in bytes
		$file_error = $f['file']['error'];
	}
  else {
	  $filename = $f['files']['name'][0];
	  $tmp_name = $f['files']['tmp_name'][0];
	  $file_size= $f['files']['size'][0]; // size in bytes
	  $file_error = $f['files']['error'][0];
    }
	 $directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']).'/';
	 $directorysize=fm_get_directorysize($directory);
	 $totalfilesize = $file_size + $directorysize;   
            if($totalfilesize >=1000000000){ // 10000000    1000000000
				   $response = array (
							   'status' => 'error',
							   'info'   => 'Your Drive Limit is over Please upgrade.',
							);
					// Return the response
					echo json_encode($response); 
					exit();
			}
			
    $ext = pathinfo($filename, PATHINFO_FILENAME) != '' ? strtolower(pathinfo($filename, PATHINFO_EXTENSION)) : '';
    $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

    if(!fm_isvalid_filename($filename) && !fm_isvalid_filename($fullpath)) {
        $response = array (
            'status'    => 'error',
            'info'      => "Invalid File name!",
        );
        echo json_encode($response); exit();
    }
	$fullpath=$filename;
	$targetPath = $path;
	if ( is_writable($targetPath) ) {
		$fullPath = $path . '/' . basename($fullpath);
        $folder = substr($fullPath, 0, strrpos($fullPath, "/"));
		 
		if(file_exists ($fullPath) && !$override_file_name && !$chunks) {
            $ext_1 = $ext ? '.'.$ext : '';
            $fullPath = $path . '/' . basename($fullpath, $ext_1) .'_'. date('ymdHis'). $ext_1;
        }
		    // Code For The Rename the name of file  
			  $ext_1 = $ext ? '.'.$ext : '';
			  $temp= explode('.',$filename);
              $original_file_name=$temp[0];
               
			  $increment = 0; 
			  $pname = $original_file_name.$ext_1;
				  while(is_file($path.'/'.$pname)) {
					$increment++;
					$pname = $original_file_name.' '.'('.$increment.')'.$ext_1;
			  }
		//echo $pname;	  
		$fullPath=$path.'/'.$pname;
          
        if (!is_dir($folder)) {
            $old = umask(0);
            mkdir($folder, 0777, true);
            umask($old);
        }
       
       if (empty($file_error) && !empty($tmp_name) && $tmp_name != 'none' && $isFileAllowed) {
            if ($chunkTotal){
                $out = @fopen("{$fullPath}.part", $chunkIndex == 0 ? "wb" : "ab");
                if ($out) {
                    $in = @fopen($tmp_name, "rb");
                    if ($in) {
                        while ($buff = fread($in, 4096)) { fwrite($out, $buff); }
                    } else {
                        $response = array (
                        'status'    => 'error',
                        'info' => "failed to open output stream"
                        );
                    }
                    @fclose($in);
                    @fclose($out);
                    @unlink($tmp_name);

                    $response = array (
                        'status'    => 'success',
                        'info' => "file upload successful",
                        'fullPath' => $fullPath
                    );
                } else {
                    $response = array (
                        'status'    => 'error',
                        'info' => "failed to open output stream"
                        );
                }
                  if ($chunkIndex == $chunkTotal - 1) {
                    rename("{$fullPath}.part", $fullPath);
                }
               } else if (move_uploaded_file($tmp_name, $fullPath)) {
				// Be sure that the file has been uploaded
				
                if ( file_exists($fullPath) ) {
                    $response = array (
                        'status'    => 'success',
                        'info' => "file upload successful"
                    );
                } else {
                    $response = array (
                        'status' => 'error',
                        'info'   => 'Couldn\'t upload the requested file.'
                    );
                }
            } else {
                $response = array (
                    'status'    => 'error',
                    'info'      => "Error while uploading files. Uploaded files $uploads",
                );
            }
        }
    } else {
        $response = array (
            'status' => 'error',
            'info'   => 'The specified folder for upload isn\'t writeable.'
         );
      }
      
    // Return the response
	  if ($FM_PATH != '') {
           $FM_PATH;
      }
	  else {
		 $FM_PATH=''; 
	  }
	   $data=array('p'=>$FM_PATH, 'image_name'=>$pname);
	   echo json_encode($data);
	   exit();
           }
		
		
			
	
?>