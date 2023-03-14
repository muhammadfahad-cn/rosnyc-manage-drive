
<?php
/**
 * Convert file name to UTF-8 in Windows
 * @param string $filename
 * @return string
 */
function fm_convert_win($filename)
{
    if (FM_IS_WIN && function_exists('iconv')) {
        $filename = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $filename);
    }
    return $filename;
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
 * Get CSS classname for file
 * @param string $path
 * @return string
 */
function fm_get_file_icon_class($path)
{
    // get extension
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    switch ($ext) {
        case 'ico':
        case 'gif':
        case 'jpg':
        case 'jpeg':
        case 'jpc':
        case 'jp2':
        case 'jpx':
        case 'xbm':
        case 'wbmp':
        case 'png':
        case 'bmp':
        case 'tif':
        case 'tiff':
        case 'webp':
        case 'avif':
        case 'svg':
            $img = 'fa fa-picture-o';
            break;
        case 'passwd':
        case 'ftpquota':
        case 'sql':
        case 'js':
        case 'json':
        case 'sh':
        case 'config':
        case 'twig':
        case 'tpl':
        case 'md':
        case 'gitignore':
        case 'c':
        case 'cpp':
        case 'cs':
        case 'py':
        case 'rs':
        case 'map':
        case 'lock':
        case 'dtd':
            $img = 'fa fa-file-code-o';
            break;
        case 'txt':
        case 'ini':
        case 'conf':
        case 'log':
        case 'htaccess':
            $img = 'fa fa-file-text-o';
            break;
        case 'css':
        case 'less':
        case 'sass':
        case 'scss':
            $img = 'fa fa-css3';
            break;
        case 'bz2':
        case 'zip':
        case 'rar':
        case 'gz':
        case 'tar':
        case '7z':
        case 'xz':
            $img = 'fa fa-file-archive-o';
            break;
        case 'php':
        case 'php4':
        case 'php5':
        case 'phps':
        case 'phtml':
            $img = 'fa fa-code';
            break;
        case 'htm':
        case 'html':
        case 'shtml':
        case 'xhtml':
            $img = 'fa fa-html5';
            break;
        case 'xml':
        case 'xsl':
            $img = 'fa fa-file-excel-o';
            break;
        case 'wav':
        case 'mp3':
        case 'mp2':
        case 'm4a':
        case 'aac':
        case 'ogg':
        case 'oga':
        case 'wma':
        case 'mka':
        case 'flac':
        case 'ac3':
        case 'tds':
            $img = 'fa fa-music';
            break;
        case 'm3u':
        case 'm3u8':
        case 'pls':
        case 'cue':
        case 'xspf':
            $img = 'fa fa-headphones';
            break;
        case 'avi':
        case 'mpg':
        case 'mpeg':
        case 'mp4':
        case 'm4v':
        case 'flv':
        case 'f4v':
        case 'ogm':
        case 'ogv':
        case 'mov':
        case 'mkv':
        case '3gp':
        case 'asf':
        case 'wmv':
            $img = 'fa fa-file-video-o';
            break;
        case 'eml':
        case 'msg':
            $img = 'fa fa-envelope-o';
            break;
        case 'xls':
        case 'xlsx':
        case 'ods':
            $img = 'fa fa-file-excel-o';
            break;
        case 'csv':
            $img = 'fa fa-file-text-o';
            break;
        case 'bak':
        case 'swp':
            $img = 'fa fa-clipboard';
            break;
        case 'doc':
        case 'docx':
        case 'odt':
            $img = 'fa fa-file-word-o';
            break;
        case 'ppt':
        case 'pptx':
            $img = 'fa fa-file-powerpoint-o';
            break;
        case 'ttf':
        case 'ttc':
        case 'otf':
        case 'woff':
        case 'woff2':
        case 'eot':
        case 'fon':
            $img = 'fa fa-font';
            break;
        case 'pdf':
            $img = 'fa fa-file-pdf-o';
            break;
        case 'psd':
        case 'ai':
        case 'eps':
        case 'fla':
        case 'swf':
            $img = 'fa fa-file-image-o';
            break;
        case 'exe':
        case 'msi':
            $img = 'fa fa-file-o';
            break;
        case 'bat':
            $img = 'fa fa-terminal';
            break;
        default:
            $img = 'fa fa-info-circle';
    }

    return $img;
}
/**
 * @param $file
 * Recover all file sizes larger than > 2GB.
 * Works on php 32bits and 64bits and supports linux
 * @return int|string
 */
function fm_get_size($file)
{
    static $iswin;
    static $isdarwin;
    if (!isset($iswin)) {
        $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
    }
    if (!isset($isdarwin)) {
        $isdarwin = (strtoupper(substr(PHP_OS, 0)) == "DARWIN");
    }

    static $exec_works;
    if (!isset($exec_works)) {
        $exec_works = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
    }

    // try a shell command
    if ($exec_works) {
        $arg = escapeshellarg($file);
        $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : ($isdarwin ? "stat -f%z $arg" : "stat -c%s $arg");
        @exec($cmd, $output);
        if (is_array($output) && ctype_digit($size = trim(implode("\n", $output)))) {
            return $size;
        }
    }

    // try the Windows COM interface
    if ($iswin && class_exists("COM")) {
        try {
            $fsobj = new COM('Scripting.FileSystemObject');
            $f = $fsobj->GetFile( realpath($file) );
            $size = $f->Size;
        } catch (Exception $e) {
            $size = null;
        }
        if (ctype_digit($size)) {
            return $size;
        }
    }

    // if all else fails
    return filesize($file);
}

function fm_get_filesize($size)
{
    $size = (float) $size;
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = ($size > 0) ? floor(log($size, 1024)) : 0;
    $power = ($power > (count($units) - 1)) ? (count($units) - 1) : $power;
    return sprintf('%s %s', round($size / pow(1024, $power), 2), $units[$power]);
}
/**
 * Check file is in exclude list
 * @param string $file
 * @return bool
 */
function fm_is_exclude_items($file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (isset($exclude_items) and sizeof($exclude_items)) {
        unset($exclude_items);
    }

    $exclude_items = FM_EXCLUDE_ITEMS;
    if (version_compare(PHP_VERSION, '7.0.0', '<')) {
        $exclude_items = unserialize($exclude_items);
    }
    if (!in_array($file, $exclude_items) && !in_array("*.$ext", $exclude_items)) {
        return true;
    }
    return false;
}

function get_list($dir, $storage)
	{
	static $arr = Array();
	static $folders1 = Array();
	
	if($storage == 'images'){
	   $supported_format = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif');
    }
	
	if($storage == 'videos'){
	   $supported_format = array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv');
    }
	
	if($storage == 'music'){
	   $supported_format = array('wav', 'mp3', 'ogg', 'm4a');
    }
	
	if($storage == 'docs'){
		$supported_format = array(
			'txt', 'doc', 'docx', 'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'json', 'sh', 'config',
			'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'xml', 'xsl', 'm3u', 'm3u8', 'pls', 'cue',
			'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py',
			'map', 'pdf', 'lock', 'dtd', 'svg', 'scss', 'asp', 'aspx', 'asx', 'asmx', 'ashx', 'jsx', 'jsp', 'jspx', 'cfm', 'cgi'
		);
    }
	if (!array_key_exists($dir,$arr)) {
			$arr[$dir] = 0;
	}
     foreach(glob("${dir}/*", GLOB_BRACE) as $fn) {
		 if(basename($fn) != 'trash' AND basename($fn) != 'shared'){
		   if (is_dir($fn) && $fn != '.' && $fn != '..' && fm_is_exclude_items($fn)) {
				get_list($fn,$storage);
			  } else {
				 $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
				  if (in_array($ext, $supported_format))
					{
					$arr[$dir] += 1;
				    //print ($fn) . "\n";
					$folders1[] = $fn;
				}
			  }
	       }	
	     }
	  return $folders1;
	}
// Get All the List of the Images
   if (isset($_POST['storageID'])) {
        $directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']);
		$a = get_list($directory, $_POST['storageID']);
		$files=$a;
    }
	
	$datetime_format = 'd.m.y H:i';
	
	define('FM_READONLY', $use_auth && !empty($readonly_users) && isset($_SESSION[FM_SESSION_ID]['logged']) && in_array($_SESSION[FM_SESSION_ID]['logged'], $readonly_users));
	defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);
	$storage='';

	$storage .="<table class='table table-hover table-sm bg-white dataTable' id='main-table1'>
                  <thead class='thead-white'>
                     <tr>";
                             if (!FM_READONLY):
                                $storage .="<th style='width:3%' class='custom-checkbox-header'>
                                    <div class='custom-control custom-checkbox'>
                                        <input type='checkbox' class='custom-control-input' id='js-select-all-items' onclick='checkbox_toggle()'>
                                        <label class='custom-control-label' for='js-select-all-items'></label>
                                    </div>
                                </th>";
								  endif; 
                                $storage .="<th>Name</th>
                                           <th>Size</th>
                                           <th>Modified</th>";
                                if (!FM_IS_WIN && !$hide_Cols):
                                $storage .="<th></th>
                                           <th></th>";
								 endif;  
                                $storage .="<th>Actions</th>
                                             </tr>
                                           </thead>";
										   
								
                        // link to parent folder
                        if ($parent !== false) {
                            
                         $storage .="<tr>"; 
						    if (!FM_READONLY): 
                              $storage .="<td class='nosort'><a href='?action=filemanager&p=".urlencode($parent)."'>
							  <i class='fa fa-chevron-circle-left go-back'></i></a></td>";
							   endif; 
                               $storage .="<td data-sort></td>
                                <td data-order></td>
                                <td data-order></td>
                                <td></td>";
                                 if (!FM_IS_WIN && !$hide_Cols) { 
                                    $storage .="<td></td>
                                                  <td></td>";
                                                } 
                                    $storage .="</tr>";		   
						}
    
	// Main File Code Starts Here
	$ik = 2070;
	foreach ($files as $f) {
		 $dirname = dirname($f);
		 $parr=explode('public_html', $dirname);
		 $parr1=explode(base64_encode($_SESSION['userId']), $dirname);
		 $view_path=$parr1[1];
		 $path = dirname($f);
		 $f=basename($f);
	     $FM_IMAGE_URL='https://rosnyc.com/'.$parr[1];
			  
		$is_link = is_link($path . '/' . $f);
		$img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
		$modif_raw = filemtime($path . '/' . $f);
	    $modif = date(FM_DATETIME_FORMAT, $modif_raw);
		$filesize_raw = fm_get_size($path . '/' . $f);
		$filesize = fm_get_filesize($filesize_raw);
		$filelink = '?action=filemanager&p=' . urlencode($view_path) . '&amp;view=' . urlencode($f);
		$all_files_size += $filesize_raw;
		$perms = substr(decoct(fileperms($path . '/' . $f)), -4);
		if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
			$owner = posix_getpwuid(fileowner($path . '/' . $f));
			$group = posix_getgrgid(filegroup($path . '/' . $f));
		} else {
			$owner = array('name' => '?');
			$group = array('name' => '?');
		}
		
		$storage .="<tr id=".$ik.">";
			    if (!FM_READONLY): 
		$storage .="<td class='custom-checkbox-td'>
				<div class='custom-control custom-checkbox'>
					<input type='checkbox' class='custom-control-input' id=".$ik." name='file[]' value=".fm_enc($f).">
					<label class='custom-control-label' for=".$ik."></label>
				</div>
				</td>";
				 endif; 
		         $storage .="<td data-sort=".fm_enc($f).">
				      <div class='filename'>";
				  if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))):
						 $imagePreview = fm_enc($FM_IMAGE_URL.'/'.$f); 
				$storage .="<a style=color:#222222; href=".$filelink." data-preview-image=".$imagePreview." title=".fm_enc($f).">";
				       else:
				$storage .="<a style=color:#222222; href=".$filelink." title=".$f.">";
					  endif;
				$storage .="&nbsp;<i class='".$img."'></i><span style='padding-left: 4px;'>".fm_enc($f)."</span>
						</a>";
						 ($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : ''); 
						 
				$storage .="</div>
			</td>";
		   $storage .="<td data-order=b-".str_pad($filesize_raw, 18, '0', STR_PAD_LEFT)."><span title=".$filesize_raw."Bytes>".$filesize."</span></td>";
		   $storage .="<td data-order=".$modif_raw.">".$modif."</td>";
		   if(!FM_IS_WIN && !$hide_Cols): 
			 $storage .="<td></td>";
			 $storage .="<td></td>";
			endif; 

           $storage .="<td class='inline-actions'>
			<div class='dropdown'>
				<a class='font-size-16 text-muted' href='#' role='button' id='secondDropdownMenuLink' data-mdb-toggle='dropdown' aria-expanded='false' > <i class='fa fa-ellipsis-h'></i>
				</a>
				<ul class='dropdown-menu' aria-labelledby='secondDropdownMenuLink'>
				  <li><a class='dropdown-item' title='Preview' href='".$filelink."'&quickView=1' data-toggle='lightbox' data-gallery='tiny-gallery' data-title='".fm_convert_win(fm_enc($f))."' data-max-width='100%' data-width='100%'>Preview</a></li>
				  <li> <a class='dropdown-item' title='DirectLink' href='".fm_enc($FM_IMAGE_URL.'/'.$f)."' target='_blank'>DirectLink</a></li>
				  <li><a class='dropdown-item' title='Download' href='?action=filemanager&p=".urlencode((FM_PATH != '' ? '/' . FM_PATH : ''))."&amp;dl=.".urlencode($f)."'>Download</a></li>
				</ul>
				</div>
			  </td>";			
		   $storage .="</tr>";
		   flush();
		$ik++;
	 }
    $storage .="</table>";
	//echo $storage;
	$json=json_encode(array('success' => true, 'storageHtml' =>$storage)); 
	echo $json;
	exit();		
	
	