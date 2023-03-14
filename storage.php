<?php
$exclude_items = array();
defined('FM_EXCLUDE_ITEMS') || define('FM_EXCLUDE_ITEMS', (version_compare(PHP_VERSION, '7.0.0', '<') ? serialize($exclude_items) : $exclude_items));
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

/**
 * Clean path
 * @param string $path
 * @return string
 */
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
 * Get mime type
 * @param string $file_path
 * @return mixed|string
 */
function fm_get_mime_type($file_path)
{
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_path);
        finfo_close($finfo);
        return $mime;
    } elseif (function_exists('mime_content_type')) {
        return mime_content_type($file_path);
    } elseif (!stristr(ini_get('disable_functions'), 'shell_exec')) {
        $file = escapeshellarg($file_path);
        $mime = shell_exec('file -bi ' . $file);
        return $mime;
    } else {
        return '--';
    }
}

/**
 * Get online docs viewer supported files extensions
 * @return array
 */
function fm_get_onlineViewer_exts()
{
    return array('doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx', 'ai', 'psd', 'dxf', 'xps', 'rar', 'odt', 'ods');
}

/**
 * Get image files extensions
 * @return array
 */
function fm_get_image_exts()
{
    return array('ico', 'gif', 'jpg', 'jpeg', 'jpc', 'jp2', 'jpx', 'xbm', 'wbmp', 'png', 'bmp', 'tif', 'tiff', 'psd', 'svg', 'webp', 'avif');
}

/**
 * Get file names of text files w/o extensions
 * @return array
 */
function fm_get_text_names()
{
    return array(
        'license',
        'readme',
        'authors',
        'contributors',
        'changelog',
    );
}

/**
 * Get audio files extensions
 * @return array
 */
function fm_get_audio_exts()
{
    return array('wav', 'mp3', 'ogg', 'm4a');
}

/**
 * Get video files extensions
 * @return array
 */
function fm_get_video_exts()
{
    return array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv');
}

/**
 * Get text file extensions
 * @return array
 */
function fm_get_text_exts()
{
    return array(
        'txt', 'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'json', 'sh', 'config',
        'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'xml', 'xsl', 'm3u', 'm3u8', 'pls', 'cue',
        'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py',
        'map', 'lock', 'dtd', 'svg', 'scss', 'asp', 'aspx', 'asx', 'asmx', 'ashx', 'jsx', 'jsp', 'jspx', 'cfm', 'cgi'
    );
}

/**
 * Check if string is in UTF-8
 * @param string $string
 * @return int
 */
function fm_is_utf8($string)
{
    return preg_match('//u', $string);
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
	
	
	// Download
if (isset($_GET['dl'])) {
    $dl = $_GET['dl'];
    $dl = fm_clean_path($dl);
    $dl = str_replace('/', '', $dl);
    $path = FM_ROOT_PATH;
	
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
	if ($dl != '' && is_file($path. $dl)) {
		fm_download_file($path.$dl, $dl, 1024);
    } else {
        fm_set_msg(lng('File not found'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }
}
?>

<style>
.dz-custom {
			margin: 0px !important;
			line-height: 0px !important;
			padding-left: 15px !important;
			font-size: 18px;
			}
			.dz-custom-file {
			border: none !important;
			padding: 0px !important;
			margin: 0px !important;
			}
			.dz-custom-file .dz-message {
			display: none;
			}
			.h5, h5 {
              font-size: 14px !important;
            }
			.custom-checkbox1{
			display: revert; !important;
		}
		.filemanager-wrap .my-file-inner .card .card-body { padding: 0px; }
		.folder-card-shadow a {
    float: left;
    width: 100%;
    display: flex;
    padding: 30px 20px;
}

.dz-drag-hover {
    background: rgb(251, 244, 251) !important;
    background: -moz-linear-gradient(90deg, rgba(251, 244, 251, 1) 0%, rgba(232, 241, 248, 1) 100%) !important;
    background: -webkit-linear-gradient(90deg, rgba(251, 244, 251, 1) 0%, rgba(232, 241, 248, 1) 100%) !important;
    background: linear-gradient(90deg, rgba(251, 244, 251, 1) 0%, rgba(232, 241, 248, 1) 100%) !important;
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#fbf4fb", endColorstr="#e8f1f8", GradientType=1);
}
		</style>

<?php

   // Get All the List of the Images
   if (isset($_GET['storageID'])) {
        $directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']);
		$a = get_list($directory, $_GET['storageID']);
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
                              $storage .="<td class='nosort'><a onclick=insidefolder('') href='javascript:void(0);'>
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
		$filelinkModel = $f;
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
				$storage .="<a href='#' onclick=storage_preview_show(".$ik.") data-preview-image='".$imagePreview."' title=".fm_convert_win(fm_enc($f))."><span onclick=storage_preview_show(".$ik.")><img src='".$imagePreview."' alt=img></span>".fm_convert_win(fm_enc($f))."";
				       else:
				$storage .="<a href='#' onclick=storage_preview_show(".$ik.") title=".fm_convert_win(fm_enc($f))."><span onclick=storage_preview_show(".$ik.")><i class='".$img."' style='font-size: 48px; padding-left:12px;'></i></span>".fm_convert_win(fm_enc($f))."";
					  endif;
				//$storage .="&nbsp;<span><img src='".$imagePreview."'/></span>".fm_convert_win(fm_enc($f))."
				$storage .="</span></a>";
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
				<a class='font-size-16 text-muted' href='#' role='button' id='secondDropdownMenuLink' data-mdb-toggle='dropdown' aria-expanded='false' > <i class='fa fa-ellipsis-v'></i>
				</a>
				<ul class='dropdown-menu' aria-labelledby='secondDropdownMenuLink'>
				  <li><a class='dropdown-item' title='Preview' href='#' onclick=storage_preview_show(".$ik.") data-toggle='lightbox' data-gallery='tiny-gallery' data-title='".fm_convert_win(fm_enc($f))."' data-max-width='100%' data-width='100%'>Preview</a></li>
				  <li> <a class='dropdown-item' title='DirectLink' href='".fm_enc($FM_IMAGE_URL.'/'.$f)."' target='_blank'>DirectLink</a></li>
				  <li><a class='dropdown-item' title='Download' href='?action=filemanager&p=".urlencode((FM_PATH != '' ? '/' . FM_PATH : ''))."&amp;dl=.".urlencode($f)."'>Download</a></li>
				</ul>
				</div>
			  </td>";			
		   $storage .="</tr>";
	//defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));	   
	define('FM_IMAGE_URL', 'https://'.$http_host.'/admin/filemanager'.'/'.base64_encode($_SESSION['userId']).'/' );	   
   $storage .="<div id=storage_preview_show".$ik." style='display:none;'>";
  
    $file = $filelinkModel;
	$quickView =  true;
    //$file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    //if ($file == '' || !is_file($path . '/' . $file) || in_array($file, $GLOBALS['exclude_items'])) {
        //fm_set_msg(lng('File not found'), 'error');
        
    //}

    if(!$quickView) {
        fm_show_header(); // HEADER
        fm_show_nav_path(FM_PATH); // current path
    }
    
	
	$file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);

	$file_url = $FM_IMAGE_URL.'/'.$f;

	$file_path = $path . '/' . $file;

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
	
    $mime_type = fm_get_mime_type($file_path);
    $filesize_raw = fm_get_size($file_path);
    $filesize = fm_get_filesize($filesize_raw);

    $is_zip = false;
    $is_gzip = false;
    $is_image = false;
    $is_audio = false;
    $is_video = false;
    $is_text = false;
    $is_onlineViewer = false;

    $view_title = 'File';
    $filenames = false; // for zip
    $content = ''; // for text
    $online_viewer = strtolower(FM_DOC_VIEWER);

    if($online_viewer && $online_viewer !== 'false' && in_array($ext, fm_get_onlineViewer_exts())){
        $is_onlineViewer = true;
    }
    elseif ($ext == 'zip' || $ext == 'tar') {
        $is_zip = true;
        $view_title = 'Archive';
        $filenames = fm_get_zif_info($file_path, $ext);
    } elseif (in_array($ext, fm_get_image_exts())) {
        $is_image = true;
        $view_title = 'Image';
    } elseif (in_array($ext, fm_get_audio_exts())) {
        $is_audio = true;
        $view_title = 'Audio';
    } elseif (in_array($ext, fm_get_video_exts())) {
        $is_video = true;
        $view_title = 'Video';
    } elseif (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }
	
	$storage .="<div class='filemanager-wrap flex-wrap'>
	  <div><a href='#' style='float:left' onclick=storage_preview_div_close(".$ik.")>Close</a></div>
        <div class=row>
            <div class=col-12>
                <div class='card card-lg'>";
				
                 if($is_onlineViewer) {
                        if($online_viewer == 'google') {
                             $storage .= '<iframe src="https://docs.google.com/viewer?embedded=true&hl=en&url=' . fm_enc($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                        } else if($online_viewer == 'microsoft') {
                             $storage .= '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . fm_enc($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                        }
                    } elseif ($is_zip) {
                        // ZIP content
                        if ($filenames !== false) {
                             $storage .= '<code class="maxheight">';
                            foreach ($filenames as $fn) {
                                if ($fn['folder']) {
                                     $storage .= '<b>' . fm_enc($fn['name']) . '</b><br>';
                                } else {
                                     $storage .= $fn['name'] . ' (' . fm_get_filesize($fn['filesize']) . ')<br>';
                                }
                            }
                             $storage .= '</code>';
                        } else {
                             $storage .= '<p>'.lng('Error while fetching archive info').'</p>';
                        }
                    } elseif ($is_image) {
                        // Image content
                        if (in_array($ext, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))) {
                            $storage .='<img src="' . fm_enc($file_url) . '" alt="" class="card-img-top preview-img">';
                        }
                    } elseif ($is_audio) {
                        // Audio content
                        $storage .='<p><audio src="' . fm_enc($file_url) . '" controls preload="metadata"></audio></p>';
                    } elseif ($is_video) {
                        // Video content
                        $storage .='<div class="preview-video"><video src="' . fm_enc($file_url) . '" width="640" height="360" controls preload="metadata"></video></div>';
                    } elseif ($is_text) {
                        if (FM_USE_HIGHLIGHTJS) {
                            // highlight
                            $hljs_classes = array(
                                'shtml' => 'xml',
                                'htaccess' => 'apache',
                                'phtml' => 'php',
                                'lock' => 'json',
                                'svg' => 'xml',
                            );
                            $hljs_class = isset($hljs_classes[$ext]) ? 'lang-' . $hljs_classes[$ext] : 'lang-' . $ext;
                            if (empty($ext) || in_array(strtolower($file), fm_get_text_names()) || preg_match('#\.min\.(css|js)$#i', $file)) {
                                $hljs_class = 'nohighlight';
                            }
                            $content = '<pre class="with-hljs"><code class="' . $hljs_class . '">' . fm_enc($content) . '</code></pre>';
                        } elseif (in_array($ext, array('php', 'php4', 'php5', 'phtml', 'phps'))) {
                            // php highlight
                            $content = highlight_string($content, true);
                        } else {
                            $content = '<pre>' . fm_enc($content) . '</pre>';
                        }
                         $storage .=$content;
                    }

                    if(!$quickView) { 
					   
                          $storage .="<div class='card-body'>";
                           $storage .="<h5 class='card-title mb-4'>".$view_title." ".fm_enc(fm_convert_win($file))."</h5>";
                            $storage .="<p class=break-word d-none>";
                             $storage .="   Full path: ".fm_enc(fm_convert_win($file_path))."<br>";
                              //$storage .="   File size: ".($filesize_raw <= 1000) ? "$filesize_raw bytes" : $filesize; <br>
                              $storage .="   MIME-type:".$mime_type." <br>";
                                
                                // ZIP info
                                if (($is_zip || $is_gzip) && $filenames !== false) {
                                    $total_files = 0;
                                    $total_comp = 0;
                                    $total_uncomp = 0;
                                    foreach ($filenames as $fn) {
                                        if (!$fn['folder']) {
                                            $total_files++;
                                        }
                                        $total_comp += $fn['compressed_size'];
                                        $total_uncomp += $fn['filesize'];
                                    }
                                    
									$storage .="    Files in archive: ". $total_files." <br>";
									$storage .="   Total size: ".fm_get_filesize($total_uncomp)."<br>";
									$storage .="    Size in archive: ".fm_get_filesize($total_comp)."<br>";
									$storage .="   Compression: ".round(($total_comp / $total_uncomp) * 100)."%<br>";
                                }
                                // Image info
                                if ($is_image) {
                                    $image_size = getimagesize($file_path);
                                     $storage .= 'Image sizes: ' . (isset($image_size[0]) ? $image_size[0] : '0') . ' x ' . (isset($image_size[1]) ? $image_size[1] : '0') . '<br>';
                                }
                                // Text info
                                if ($is_text) {
									$is_utf8 = fm_is_utf8($content);
									if (function_exists('iconv')) {
                                        if (!$is_utf8) {
                                            $content = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $content);
                                        }
                                    }
                                     $storage .= 'Charset: ' . ($is_utf8 ? 'utf-8' : '8 bit') . '<br>';
                                }
                            $storage .="</p>";
                            $storage .="<p>";
							$storage .="<a class=card-link href='?action=filemanager&p=".urlencode(FM_PATH)."><i class='fa fa-chevron-circle-left go-back'></i>Back</a>";
							$storage .="<a class=card-link href='?action=filemanager&p=".urlencode(FM_PATH)."&amp;dl=".urlencode($file)."><i class='fa fa-download'></i>Download</a>";
                            $storage .="<a class='card-link' href=".fm_enc($file_url)." target='_blank'><i class='fa fa-link'></i>Open</a>";
                            // ZIP actions
                                if (!FM_READONLY && ($is_zip || $is_gzip) && $filenames !== false) {
                                    $zip_name = pathinfo($file_path, PATHINFO_FILENAME);
                                   
                             $storage .="<a class='card-link' href='?action=filemanager&p=".urlencode(FM_PATH)."&amp;unzip=".urlencode($file)."><i class='fa fa-check-circle'></i>Unzip</a>";
                             $storage .="<a class='card-link' href='?action=filemanager&p=".urlencode(FM_PATH)."&amp;unzip=".urlencode($file)."&amp;tofolder=1 title=UnZip to ".fm_enc($zip_name)."><i class='fa fa-check-circle'></i>
                                            UnZipToFolder</a>";
                               }
                            if ($is_text && !FM_READONLY) {
                                    
                             $storage .="<a class='card-link' href='?action=filemanager&p=".urlencode(trim(FM_PATH))."&amp;edit=".urlencode($file)." class=edit-file><i class=fa fa-pencil-square></i>Edit</a>";
                             $storage .="<a class='card-link' href='?action=filemanager&p=".urlencode(trim(FM_PATH))."&amp;edit=".urlencode($file)."&env=ace
                                        class=edit-file><i class=fa fa-pencil-square-o></i>AdvancedEditor</a>";
                                 } 
                            $storage .="</p>
                        </div>";
                         
                        }					
					
                $storage .="</div>
              </div>
            </div>
          </div>
		</div>";
		   flush();
		$ik++;
	 }
    $storage .="</table>";
	echo $storage;
	//$json=json_encode(array('success' => true, 'storageHtml' =>$storage)); 
	//echo $json;
	//exit();		
	
	?>
	<script>
		function storage_preview_show(div_id){
			$("#storage_preview_show"+div_id).show();
			$("#main-table1").hide();
			$("#storage_preview_foot").hide();
		}

		function storage_preview_div_close(div_id){
			$("#storage_preview_show"+div_id).hide();
			$("#main-table1").show();
			$("#storage_preview_foot").show();
		}
		
		// Dom Ready Event
    
	    //load config
        fm_get_config();
        //dataTable init
		var $table = $('#main-table'),
            tableLng = $table.find('th').length,
            _targets = (tableLng && tableLng == 7 ) ? [0, 4,5,6] : tableLng == 5 ? [0,4] : [3],
            emptyType = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);
            mainTable = $('#main-table').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets, orderable: false}, {type: emptyType, targets: '_all',},]
        });
		
		// Code for the shared
		var $table1 = $('#main-table1'),
            tableLng1 = $table1.find('th').length,
            _targets1 = (tableLng1 && tableLng1 == 7 ) ? [0, 4,5,6] : tableLng1 == 5 ? [0,4] : [3],
            emptyType1 = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);
            mainTable1 = $('#main-table1').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets1, orderable: false}, {type: emptyType1, targets: '_all',},]
        });
		
		// Code for the starred
		var $table2 = $('#main-table2'),
            tableLng2 = $table2.find('th').length,
            _targets2 = (tableLng2 && tableLng1 == 7 ) ? [0, 4,5,6] : tableLng1 == 5 ? [0,4] : [3],
            emptyType2 = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);
            mainTable2 = $('#main-table2').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets2, orderable: false}, {type: emptyType2, targets: '_all',},]
        });
		
		// Code for the trash
		var $table3 = $('#main-table3'),
            tableLng3 = $table3.find('th').length,
            _targets3 = (tableLng3 && tableLng3 == 7 ) ? [0, 4,5,6] : tableLng3 == 5 ? [0,4] : [3],
            emptyType3 = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);
            mainTable3 = $('#main-table3').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets3, orderable: false}, {type: emptyType3, targets: '_all',},]
        });
		
        //search
        $('#search-addon').on( 'keyup', function () {
			
			mainTable.search( this.value ).draw();
			mainTable1.search( this.value ).draw();
			mainTable2.search( this.value ).draw();
			mainTable3.search( this.value ).draw();
        });
		
        $("input#advanced-search").on('keyup', function (e) {
            if (e.keyCode === 13) { fm_search(); }
        });
        $('#search-addon3').on( 'click', function () { fm_search(); });
        //upload nav tabs
        $(".fm-upload-wrapper .card-header-tabs").on("click", 'a', function(e){
            e.preventDefault();let target=$(this).data('target');
            $(".fm-upload-wrapper .card-header-tabs a").removeClass('active');$(this).addClass('active');
            $(".fm-upload-wrapper .card-tabs-container").addClass('hidden');$(target).removeClass('hidden');
        });
	</script>