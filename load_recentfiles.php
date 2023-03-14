<?php
$file_name = 'gfg.html';

//Default Configuration
$CONFIG = '{"lang":"en","error_reporting":false,"show_hidden":false,"hide_Cols":false,"calc_folder":false,"theme":"light"}';
$root = __DIR__;
$root = str_replace("filemanager","",$root);
require_once($root."/constants.php");
$dbhost = DB_HOST;
$dbuser = DB_USER;
$dbpass = DB_PASS;
$dbname = DB_NAME;
$con = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

/**
 * H3K | Tiny File Manager V2.4.7
 * CCP Programmers | ccpprogrammers@gmail.com
 * https://tinyfilemanager.github.io
 */

//TFM version
define('VERSION', '2.4.7');

//Application Title
define('APP_TITLE', 'Tiny File Manager');

// --- EDIT BELOW CONFIGURATION CAREFULLY ---

// Auth with login/password
// set true/false to enable/disable it
// Is independent from IP white- and blacklisting
$use_auth = false;

// Login user name and password
// Users: array('Username' => 'Password', 'Username2' => 'Password2', ...)
// Generate secure password hash - https://tinyfilemanager.github.io/docs/pwd.html
$auth_users = array(
    'admin' => '$2y$10$/K.hjNr84lLNDt8fTXjoI.DBp6PpeyoJ.mGwrrLuCZfAwfSAGqhOW', //admin@123
    'user' => '$2y$10$Fg6Dz8oH9fPoZ2jJan5tZuv6Z4Kp7avtQ9bDfrdRntXtPeiMAZyGO' //12345
);

// Readonly users
// e.g. array('users', 'guest', ...)
$readonly_users = array(
    'user'
);

// Global readonly, including when auth is not being used
$global_readonly = false;

// user specific directories
// array('Username' => 'Directory path', 'Username2' => 'Directory path', ...)
$directories_users = array();

// Enable highlight.js (https://highlightjs.org/) on view's page
$use_highlightjs = true;

// highlight.js style
// for dark theme use 'ir-black'
$highlightjs_style = 'vs';

// Enable ace.js (https://ace.c9.io/) on view's page
$edit_files = true;

// Default timezone for date() and time()
// Doc - http://php.net/manual/en/timezones.php
$default_timezone = 'Etc/UTC'; // UTC

// Root path for file manager
// use absolute path of directory i.e: '/var/www/folder' or $_SERVER['DOCUMENT_ROOT'].'/folder'
$root_path = $_SERVER['DOCUMENT_ROOT'].'/admin/filemanager';

// Root url for links in file manager.Relative to $http_host. Variants: '', 'path/to/subfolder'
// Will not working if $root_path will be outside of server document root
$root_url = 'https://rosnyc.com/admin/index.php?action=filemanager';

// Server hostname. Can set manually if wrong
$http_host = $_SERVER['HTTP_HOST'];

// user specific directories
// array('Username' => 'Directory path', 'Username2' => 'Directory path', ...)
$directories_users = array();

// input encoding for iconv
$iconv_input_encoding = 'UTF-8';

// date() format for file modification date
// Doc - https://www.php.net/manual/en/function.date.php
$datetime_format = 'd.m.y H:i';

// Allowed file extensions for create and rename files
// e.g. 'txt,html,css,js'
$allowed_file_extensions = '';

// Allowed file extensions for upload files
// e.g. 'gif,png,jpg,html,txt'
$allowed_upload_extensions = '';

// Favicon path. This can be either a full url to an .PNG image, or a path based on the document root.
// full path, e.g http://example.com/favicon.png
// local path, e.g images/icons/favicon.png
$favicon_path = '';

// Files and folders to excluded from listing
// e.g. array('myfile.html', 'personal-folder', '*.php', ...)
$exclude_items = array();

// Online office Docs Viewer
// Availabe rules are 'google', 'microsoft' or false
// google => View documents using Google Docs Viewer
// microsoft => View documents using Microsoft Web Apps Viewer
// false => disable online doc viewer
$online_viewer = 'google';

// Sticky Nav bar
// true => enable sticky header
// false => disable sticky header
$sticky_navbar = true;

// Maximum file upload size
// Increase the following values in php.ini to work properly
// memory_limit, upload_max_filesize, post_max_size
$max_upload_size_bytes = 5000;

// Possible rules are 'OFF', 'AND' or 'OR'
// OFF => Don't check connection IP, defaults to OFF
// AND => Connection must be on the whitelist, and not on the blacklist
// OR => Connection must be on the whitelist, or not on the blacklist
$ip_ruleset = 'OFF';

// Should users be notified of their block?
$ip_silent = true;

// IP-addresses, both ipv4 and ipv6
$ip_whitelist = array(
    '127.0.0.1',    // local ipv4
    '::1'           // local ipv6
);

// IP-addresses, both ipv4 and ipv6
$ip_blacklist = array(
    '0.0.0.0',      // non-routable meta ipv4
    '::'            // non-routable meta ipv6
);

// if User has the customized config file, try to use it to override the default config above
$config_file = __DIR__.'/config.php';
if (is_readable($config_file)) {
    @include($config_file);
}

// --- EDIT BELOW CAREFULLY OR DO NOT EDIT AT ALL ---

// max upload file size
define('MAX_UPLOAD_SIZE', $max_upload_size_bytes);

// private key and session name to store to the session
if ( !defined( 'FM_SESSION_ID')) {
    define('FM_SESSION_ID', 'filemanager');
}

// Configuration
$cfg = new FM_Config();

// Default language
$lang = isset($cfg->data['lang']) ? $cfg->data['lang'] : 'en';

// Show or hide files and folders that starts with a dot
$show_hidden_files = isset($cfg->data['show_hidden']) ? $cfg->data['show_hidden'] : true;

// PHP error reporting - false = Turns off Errors, true = Turns on Errors
$report_errors = isset($cfg->data['error_reporting']) ? $cfg->data['error_reporting'] : true;

// Hide Permissions and Owner cols in file-listing
$hide_Cols = isset($cfg->data['hide_Cols']) ? $cfg->data['hide_Cols'] : true;

// Show directory size: true or speedup output: false
$calc_folder = isset($cfg->data['calc_folder']) ? $cfg->data['calc_folder'] : true;

// Theme
$theme = isset($cfg->data['theme']) ? $cfg->data['theme'] : 'light';

define('FM_THEME', $theme);

//available languages
$lang_list = array(
    'en' => 'English'
);

if ($report_errors == true) {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 1);
} else {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 0);
}

// if fm included
if (defined('FM_EMBED')) {
    $use_auth = false;
    $sticky_navbar = false;
} else {
    @set_time_limit(600);

    date_default_timezone_set($default_timezone);

    ini_set('default_charset', 'UTF-8');
    if (version_compare(PHP_VERSION, '5.6.0', '<') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
    }
    if (function_exists('mb_regex_encoding')) {
        mb_regex_encoding('UTF-8');
    }

    session_cache_limiter('');
    session_name(FM_SESSION_ID );
    function session_error_handling_function($code, $msg, $file, $line) {
        // Permission denied for default session, try to create a new one
        if ($code == 2) {
            session_abort();
            session_id(session_create_id());
            @session_start();
        }
    }
    set_error_handler('session_error_handling_function');
    session_start();
    restore_error_handler();
}
    // Create a New Directory For the New User.

    $path=dirname(__FILE__);
    $newpath=$path.'/'.base64_encode($_SESSION['userId']);
	$newpathTrash=$newpath.'/trash';
	$newpathShared=$newpath.'/shared';
	if (!file_exists($newpath)) {
		mkdir($newpath, 0777, true);
    }
    // Create trash Path
	if (!file_exists($newpathTrash)) {
		mkdir($newpathTrash, 0777, true);
    }

	// Create shared Path
	if (!file_exists($newpathShared)) {
		mkdir($newpathShared, 0777, true);
    }

 if (empty($auth_users)) {
    $use_auth = false;
 }

$is_https = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
    || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';

// update $root_url based on user specific directories

if (isset($_SESSION[FM_SESSION_ID]['logged']) && !empty($directories_users[$_SESSION[FM_SESSION_ID]['logged']])) {
    $wd = fm_clean_path(dirname($_SERVER['PHP_SELF']));
    $root_url =  $root_url.$wd.DIRECTORY_SEPARATOR.$directories_users[$_SESSION[FM_SESSION_ID]['logged']];
}
// clean $root_url
$root_url = fm_clean_path($root_url);

// abs path for site
defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
define('FM_IMAGE_URL', 'https://'.$http_host.'/admin/filemanager'.'/'.base64_encode($_SESSION['userId']).'/' );
defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);
// get current path

// Functions Used in this File
/**
 * HTTP Redirect
 * @param string $url
 * @param int $code
 */
function fm_redirect($url, $code = 302)
{
    header('Location: ' . $url, true, $code);
    exit;
}
//

// update root path

if ($use_auth && isset($_SESSION[FM_SESSION_ID]['logged'])) {
     $root_path = isset($directories_users[$_SESSION[FM_SESSION_ID]['logged']]) ? $directories_users[$_SESSION[FM_SESSION_ID]['logged']] : $root_path;
}

// clean and check $root_path
$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
    echo "<h1>".lng('Root path')." \"{$root_path}\" ".lng('not found!')." </h1>";
    exit;
}

defined('FM_SHOW_HIDDEN') || define('FM_SHOW_HIDDEN', $show_hidden_files);
defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path.'/'.base64_encode($_SESSION['userId']).'/');
defined('FM_LANG') || define('FM_LANG', $lang);
defined('FM_FILE_EXTENSION') || define('FM_FILE_EXTENSION', $allowed_file_extensions);
defined('FM_UPLOAD_EXTENSION') || define('FM_UPLOAD_EXTENSION', $allowed_upload_extensions);
defined('FM_EXCLUDE_ITEMS') || define('FM_EXCLUDE_ITEMS', (version_compare(PHP_VERSION, '7.0.0', '<') ? serialize($exclude_items) : $exclude_items));
defined('FM_DOC_VIEWER') || define('FM_DOC_VIEWER', $online_viewer);
define('FM_READONLY', $use_auth && !empty($readonly_users) && isset($_SESSION[FM_SESSION_ID]['logged']) && in_array($_SESSION[FM_SESSION_ID]['logged'], $readonly_users));
define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');

// always use ?action=filemanager&p=
//if (!isset($_GET['p']) && empty($_FILES)) {
    //fm_redirect(FM_SELF_URL . '?action=filemanager&p=');
//}

// get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

// clean path
  $p = fm_clean_path($p);


// for ajax request - save
$input = file_get_contents('php://input');
$_POST = (strpos($input, 'ajax') != FALSE && strpos($input, 'save') != FALSE) ? json_decode($input, true) : $_POST;

// instead globals vars
define('FM_PATH', $p);
define('FM_USE_AUTH', $use_auth);
define('FM_EDIT_FILE', $edit_files);
defined('FM_ICONV_INPUT_ENC') || define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);
defined('FM_USE_HIGHLIGHTJS') || define('FM_USE_HIGHLIGHTJS', $use_highlightjs);
defined('FM_HIGHLIGHTJS_STYLE') || define('FM_HIGHLIGHTJS_STYLE', $highlightjs_style);
defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);
unset($p, $use_auth, $iconv_input_encoding, $use_highlightjs, $highlightjs_style);

if(isset($_GET['sharedu'])){
	$path = $directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.$_GET['sharedu'].'/';

if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}
 echo "path===>".$path=$path;
// check path
if (!is_dir($path)) {
    fm_redirect(FM_SELF_URL . '?action=filemanager&p=');
}

// get parent folder
$parent = fm_get_parent_path(FM_PATH);

$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
$current_path = array_slice(explode("/",$path), -1)[0];

if (is_array($objects) && fm_is_exclude_items($current_path)) {
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (!FM_SHOW_HIDDEN && substr($file, 0, 1) === '.') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (@is_file($new_path) && fm_is_exclude_items($file)) {
            //$files[] = $file;
			$files[$file] = filemtime($new_path);
        } elseif (@is_dir($new_path) && $file != '.' && $file != '..' && fm_is_exclude_items($file)) {
            //$folders[] = $file;
			 // sort array
		    //$folders=sort($folders);
			$folders[$file] = filemtime($new_path);
        }
     }
   }
}
else {
$path = FM_ROOT_PATH;
if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}
// check path
if(isset($_GET['sharedview'])){
//if (!is_dir($path)) {
    //fm_redirect(FM_SELF_URL . '?action=filemanager&p=');
//}
}
else {
	if (!is_dir($path)) {
       fm_redirect(FM_SELF_URL . '?action=filemanager&p=');
    }
}
// get parent folder
$parent = fm_get_parent_path(FM_PATH);

$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
$current_path = array_slice(explode("/",$path), -1)[0];

if (is_array($objects) && fm_is_exclude_items($current_path)) {
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (!FM_SHOW_HIDDEN && substr($file, 0, 1) === '.') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (@is_file($new_path) && fm_is_exclude_items($file)) {
            //$files[] = $file;
			$files[$file] = filemtime($new_path);
        } elseif (@is_dir($new_path) && $file != '.' && $file != '..' && fm_is_exclude_items($file)) {
			$folders[$file] = filemtime($new_path);
            //$folders[] = $file;
			// sort array
		}
    }
  }
}// Else Ends Here...

//echo "<pre>";
$sorting_order=0;
$sorting_files_order=0;
// Sort the Folders array.

  if ($sorting_order == SCANDIR_SORT_ASCENDING) {
    asort($folders, SORT_NUMERIC);
  }
  else {
   arsort($folders, SORT_NUMERIC);
  }

  $folders = array_keys($folders);

// Sort the Files array.
  if ($sorting_files_order == SCANDIR_SORT_ASCENDING) {
    asort($files, SORT_NUMERIC);
  }
  else {
   arsort($files, SORT_NUMERIC);
  }

  $files = array_keys($files);

//print_r($folders);

$num_files = count($files);
$num_folders = count($folders);
$directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']).'/';


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
<div class="page-title-box d-sm-flex align-items-center justify-content-between">
        <div class="home-link font-size-14 d-flex align-items-center">
                <?php
                    $path_bread = fm_clean_path(FM_PATH);//.min.js
                    $root_url = "<a onclick=insidefolder('') href='javascript:void(0);'>Home</a>";
                    $sep = '<i class="fa fa-angle-right mx-2" aria-hidden="true"></i>';
                    if ($path_bread != '') {
                        $exploded = explode('/', $path_bread);
                        $count = count($exploded);
                        $array = array();
                        $parent = '';
                        for ($i = 0; $i < $count; $i++) {
                            $parent = trim($parent . '/' . $exploded[$i], '/');
                            $parent_enc = urlencode($parent);
                            $array[] = "<a onclick=insidefolder('{$parent_enc}') href='javascript:void(0);'>" . fm_enc(fm_convert_win($exploded[$i])) . "</a>";
                        }
                        $root_url .= $sep . implode($sep, $array);
                    }
                ?>
                <?php echo '<div class="home-link">' . $root_url . $editFile . '</div>'; ?>
            </div>
		</div>
		<div id="home_storage_data">
          <?php
					function get_list_image($dir)
					{
                    static $arr = Array();
					$supported_format = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif');

					if (!array_key_exists($dir,$arr)) {
							$arr[$dir] = 0;
					}

					foreach(glob("${dir}/*", GLOB_BRACE) as $fn) {
					 if(basename($fn) != 'trash' AND  basename($fn) != 'shared'){
						if (is_dir($fn)) {
							       get_list_image($fn);
							} else {
								 $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
								  if (in_array($ext, $supported_format))
									{
									$arr[$dir] += 1;
								    //print basename($fn) . "\n";
								}
							}
					    }
					}
					return $arr;
					}
					$a = get_list_image($directory);
					$sumimagefiles=0;
					foreach($a as $k => $v) {
					   //print "Number of files in ${k}: ${v} \n";
					     $sumimagefiles +=$v;
					   }
					?>
				<?php

				 function get_dir_image_size($dir){
					$supported_format = array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif');
					$count_size = 0;
					$count = 0;
					$dir_array = array_slice(scandir($dir),2);
				    foreach($dir_array as $key=>$filename){
						   $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
						   if (in_array($ext, $supported_format))
							 {
						  if($filename!= 'trash' AND filename != 'shared'){
						   if(is_dir($dir."/".$filename)){
							   $new_foldersize = get_dir_image_size($dir."/".$filename.'/');
							   $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
						       $count_size = $count_size+ $new_foldersize;
							}
							else if(is_file($dir."/".$filename)){
							    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
						        $count_size = $count_size + filesize($dir."/".$filename);
							    $count++;
							}
						   }
						  }
						}
					  return $count_size;
					}
					$totalimagesize=get_dir_image_size($directory);
					if($totalimagesize > 0){
						$totalimagesize= sizeFormat1(get_dir_image_size($directory));
					}
					else {
						$totalimagesize=0;
					}
				?>
    <a href="#" onclick="getStorage('images')">
	  <div class="file-image-wrap d-flex">
        <div class="files-detail">
            <h2>Images</h2>
            <span><?php echo $sumimagefiles;?>FIles</span>
            <div class="skewed"></div>
        </div>
        <div class="size-detail">
            <h3><?php echo $totalimagesize; ?></h3>
            <img src="https://rosnyc.com/admin/images/bg-img.png" class="img-fluid" alt="bg image">
        </div>
      </div>
	</a>
	<?php
	function get_list_doc($dir)
	{
	static $arr = Array();
	$supported_format = array(
			'txt', 'doc', 'docx', 'xml', 'xsl', 'csv', 'pdf'
		);

	if (!array_key_exists($dir,$arr)) {
			$arr[$dir] = 0;
	}

	foreach(glob("${dir}/*", GLOB_BRACE) as $fn) {
		if(basename($fn) != 'trash' AND basename($fn) != 'shared'){
		 if (is_dir($fn)) {
					get_list_doc($fn);
			} else {
				 $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
				  if (in_array($ext, $supported_format))
					{
					$arr[$dir] += 1;
				   // print basename($fn) . "\n";
				}
			}
		}
	}
	return $arr;
	}
	$a = get_list_doc($directory);
	$sumdocfiles=0;
	foreach($a as $k => $v) {
	//print "Number of files in ${k}: ${v} \n";
	$sumdocfiles +=$v;
	}
	?>
	<?php
	function get_dir_doc_size($dir){
	$count_size = 0;
	$count = 0;
	$dir_array = scandir($dir);
	  foreach($dir_array as $key=>$filename){
		$supported_format = array(
			'txt', 'doc', 'docx', 'xml', 'xsl', 'csv', 'pdf'
		);

		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		   if (in_array($ext, $supported_format))
			 {
			if($filename!= 'trash' AND $filename!= 'shared'){
			   if(is_dir($dir."/".$filename)){
				  $new_foldersize = get_dir_doc_size($dir."/".$filename);
				  $count_size = $count_size+ $new_foldersize;
				}else if(is_file($dir."/".$filename)){
				  $count_size = $count_size + filesize($dir."/".$filename);
				  $count++;
				}
		  }
	   }
	 }
	  return $count_size.'_'.$count;
	}

	$fordoc=get_dir_doc_size($directory);
	  if($fordoc > 0){
		$fordocArr=explode('_',  $fordoc);
			$totaldocsize=sizeFormat($fordocArr[0]);
			$totalidocsizeArr=explode('_', $totaldocsize);
			$totaldocfiles=$fordocArr[1];
			$totdocsize=$totalidocsizeArr[0];
		}
		else {
			$totdocsize = 0;
			$totaldocfiles = 0;
		}
	?>
    <div class="w-100 file-column d-sm-flex justify-content-between">
	  <div class="file-doc">
            <div class="d-flex w-100 align-items-center h-100">
                <i class="fa fa-file-text" aria-hidden="true"></i>
				 <a href="#" onclick="getStorage('docs')" class="file-doc-inner">
                  <div>
                    <h3>Documents</h3>
                    <span><?php echo $sumdocfiles; ?> Files</span>
                    <h4><?php echo $totdocsize; ?></h4>
                  </div>
				</a>
            </div>
        </div>
   <?php
	function get_list_media($dir)
	{
	static $arr = Array();
	$supported_format = array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv', 'wav', 'mp3', 'ogg', 'm4a');

	if (!array_key_exists($dir,$arr)) {
			$arr[$dir] = 0;
	}

	foreach(glob("${dir}/*", GLOB_BRACE) as $fn) {
		if(basename($fn) != 'trash' AND basename($fn) != 'shared'){
		 if (is_dir($fn)) {
					get_list_media($fn);
			} else {
				 $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
				  if (in_array($ext, $supported_format))
					{
					$arr[$dir] += 1;
				   // print basename($fn) . "\n";
				}
			}
		}
	}
	return $arr;
	}
	$a = get_list_media($directory);
	$summediafiles=0;
	foreach($a as $k => $v) {
	   //print "Number of files in ${k}: ${v} \n";
	   $summediafiles +=$v;
	}
	?>
	<?php
	function get_dir_media_size($dir){
	$count_size = 0;
	$count = 0;
	$dir_array = scandir($dir);
	  foreach($dir_array as $key=>$filename){
		$supported_format = array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv', 'wav', 'mp3', 'ogg', 'm4a');
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		   if (in_array($ext, $supported_format))
			 {
			if($filename!= 'trash' AND $filename!= 'shared'){
			   if(is_dir($dir."/".$filename)){
				  $new_foldersize = get_dir_media_size($dir."/".$filename);
				  $count_size = $count_size+ $new_foldersize;
				}else if(is_file($dir."/".$filename)){
				  $count_size = $count_size + filesize($dir."/".$filename);
				  $count++;
				}
		  }
	   }
	 }
	  return $count_size.'_'.$count;
	}

	$formedia=get_dir_media_size($directory);
	  if($formedia > 0){
		$formediaArr=explode('_',  $formedia);
			$totalmediasize=sizeFormat($formediaArr[0]);
			$totalimediasizeArr=explode('_', $totalmediasize);
			$totalmediafiles=$formediaArr[1];
			$totmediasize=$totalimediasizeArr[0];
		}
		else {
			$totmediasize = 0;
			$totalmediafiles = 0;
		}
	   ?>
	    <div class="file-media">
            <i class="fa fa-play-circle" aria-hidden="true"></i>
			<a href="#" onclick="getStorage('music')">
            <div class="file-media-inner d-flex justify-content-between align-items-center">
                <div>
                    <h3>Media</h3>
                    <span><?php echo $summediafiles?> Files</span>
                </div>
                <h4><?php echo $totmediasize?></h4>
            </div>
			</a>
        </div>
	<?php
	function get_list_other($dir)
	{
	static $arr = Array();
	$supported_format = array(
			'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'json', 'sh', 'config',
			'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'm3u', 'm3u8', 'pls', 'cue',
			'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py',
			'map',  'lock', 'dtd', 'svg', 'scss', 'asp', 'aspx', 'asx', 'asmx', 'ashx', 'jsx', 'jsp', 'jspx', 'cfm', 'cgi'
		);

	if (!array_key_exists($dir,$arr)) {
			$arr[$dir] = 0;
	}

	foreach(glob("${dir}/*", GLOB_BRACE) as $fn) {
		if(basename($fn) != 'trash' AND basename($fn) != 'shared'){
		 if (is_dir($fn)) {
					get_list_other($fn);
			} else {
				 $ext = strtolower(pathinfo($fn, PATHINFO_EXTENSION));
				  if (in_array($ext, $supported_format))
					{
					$arr[$dir] += 1;
				   // print basename($fn) . "\n";
				}
			}
		}
	}
	return $arr;
	}
	$a = get_list_other($directory);
	$sumotherfiles=0;
	foreach($a as $k => $v) {
	   //print "Number of files in ${k}: ${v} \n";
	   $sumotherfiles +=$v;
	}
	?>
	<?php
	function get_dir_other_size($dir){
	$count_size = 0;
	$count = 0;
	$dir_array = scandir($dir);
	  foreach($dir_array as $key=>$filename){
		$supported_format = array(
			'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'json', 'sh', 'config',
			'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'm3u', 'm3u8', 'pls', 'cue',
			'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py',
			'map',  'lock', 'dtd', 'svg', 'scss', 'asp', 'aspx', 'asx', 'asmx', 'ashx', 'jsx', 'jsp', 'jspx', 'cfm', 'cgi'
		);
		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		   if (in_array($ext, $supported_format))
			 {
			if($filename!= 'trash' AND $filename!= 'shared'){
			   if(is_dir($dir."/".$filename)){
				  $new_foldersize = get_dir_other_size($dir."/".$filename);
				  $count_size = $count_size+ $new_foldersize;
				}else if(is_file($dir."/".$filename)){
				  $count_size = $count_size + filesize($dir."/".$filename);
				  $count++;
				}
		   }
	    }
	  }
	  return $count_size.'_'.$count;
	}

	$forother=get_dir_other_size($directory);
	  if($forother > 0){
		$forotherArr=explode('_',  $forother);
			$totalothersize=sizeFormat($forotherArr[0]);
			$totaliothersizeArr=explode('_', $totalothersize);
			$totalotherfiles=$forotherArr[1];
			$totothersize=$totaliothersizeArr[0];
		}
		else {
			$totothersize = 0;
			$totalotherfiles = 0;
		}
	    ?>
		    <div class="file-other">
			   <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
				 <div class="file-other-inner">
					<h5>Other Files</h3>
					<h6><?php echo $totothersize; ?></h6>
				</div>
			</div>
		</div>
		</div>
		<style>
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
	<script>
    Dropzone.options.fileUploader1 = {
            chunking: true,
            chunkSize: 10000000,
            forceChunking: true,
            retryChunks: true,
            retryChunksLimit: 3,
			clickable: false,
            parallelUploads: 1000, // does not support more than 1!
            timeout: 3600000,
			dictDefaultMessage: "Create Folder",
			complete:function(file) {

            },
			totaluploadprogress: function(progress) {
			//console.log("progress==>"+progress);
			   $(".progress-track").show();
			   $(".progress-bar").width(progress + '%');
			   $(".progress-bar").text(progress.toFixed(2) + '%');
			},
			addedfile:function() {
                 console.log("A file has been added");
			},
			maxFilesize: 10000000000,
            acceptedFiles : "",
            init: function () {

				this.on("sending", function (file, xhr, formData) {

					var values = {};

				    if($("#fileUploader1").serializeArray()){
						console.log("createfolderfile==>"+$("#fileUploader1").serializeArray());
						$.each($("#fileUploader1").serializeArray(), function (i, field) {
							values[field.name] = field.value;
						});

						//Value Retrieval Function
						var getValue = function (valueName) {
						return values[valueName];
						};

						//Retrieve the Values
						var upload_type = getValue("upload_type");

					}

					console.log("upload_type_#fileUploader1==>"+upload_type);

					if(upload_type == 'createfolderfile'){


					let _path1 = (file.fullPath) ? file.fullPath : file.name;
					// Code For Upload Folder only
                    <?php
					 $ii = 3399;
                       foreach ($folders as $key => $value){
                            if ($value == 'trash') {  unset($folders[$key]); }
                            if ($value == 'shared') {  unset($folders[$key]); }
                        }
                      foreach ($folders as $f) {
					 ?>
					    if(document.getElementById("fullpath"+<?php echo $ii ?>)){
					       document.getElementById("fullpath"+<?php echo $ii ?>).value =_path1;
						}
					<?php
					  flush();
                      $ii++;
					}
					?>
					}
					if(upload_type == 'createfolder'){
						let _path = (file.fullPath) ? file.fullPath : file.name;
				 	   document.getElementById("fullpath1").value = _path;
					}

					if(upload_type == 'recentfile'){
						let _path = (file.fullPath) ? file.fullPath : file.name;
				 	   document.getElementById("fullpath2").value = _path;
					}

					xhr.ontimeout = (function() {
                        toast('Error: Server Timeout');
                    });
                }).on("success", function (res, responseText) {
					var jsonResponse = JSON.parse(res.xhr.response);
					if(res.status == 'success'){
						//toast('Upload Successfully');
						$("#toast-message").html('Upload Successfully');
						$("#basic-primary-trigger").trigger('click');
					   // Path Return Back to Same Foder Path after Drop Folder
					   let _pfolderpath= jsonResponse.p;

					   if(_pfolderpath != ''){
							  var scroll = $('.file-scroll').scrollTop();
							$("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+_pfolderpath,function(){
								   Dropzone.discover();
							$('.file-scroll').scrollTop(scroll);
							$('#home_storage_data').hide();
							})

							$("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php?p="+_pfolderpath,function(){
							  // Update Right Side Content
							  Dropzone.discover();
							});
					   }
					   else {
						var scroll = $('.file-scroll').scrollTop();
					    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
							Dropzone.discover();
							$('.file-scroll').scrollTop(scroll);
						})
						$("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php",function(){
						  // Update Right Side Content
						  Dropzone.discover();
						});
					  }
					  //let _response = JSON.parse(res.xhr.response);
					  //console.log('Upload Status >> ', res.status);
					}

					if(res.status == "error") {
                        toast(_response.info);
                    }
                 }).on("error", function(file, response) {
                    toast(response);
                });
            }
        }

		 Dropzone.options.fileUploader2 = {
            chunking: true,
            chunkSize: 10000000,
            forceChunking: true,
            retryChunks: true,
            retryChunksLimit: 3,
			clickable: false,
            parallelUploads: 1000, // does not support more than 1!
            timeout: 3600000,
			dictDefaultMessage: "Create Folder",
			complete:function(file) {

            },
			totaluploadprogress: function(progress) {
			//console.log("progress==>"+progress);
			   $(".progress-track").show();
			   $(".progress-bar").width(progress + '%');
			   $(".progress-bar").text(progress.toFixed(2) + '%');
			},
			addedfile:function() {
                 console.log("A file has been added");
			},
			maxFilesize: 10000000000,
            acceptedFiles : "",
            init: function () {

				this.on("sending", function (file, xhr, formData) {

					var values = {};

				    if($("#fileUploader2").serializeArray()){
						console.log("createfolder==>"+$("#fileUploader2").serializeArray());
						$.each($("#fileUploader2").serializeArray(), function (i, field) {
							values[field.name] = field.value;
						});

						//Value Retrieval Function
						var getValue = function (valueName) {
						return values[valueName];
						};

						//Retrieve the Values
						var upload_type = getValue("upload_type");

					}

					console.log("upload_type_#fileUploader2==>==>"+upload_type);

					if(upload_type == 'createfolderfile'){
					   let _path = (file.fullPath) ? file.fullPath : file.name;
					   document.getElementById("fullpath").value = _path;

					let _path1 = (file.fullPath) ? file.fullPath : file.name;
					// Code For Upload Folder only
                    <?php
					 $ii = 3399;
                       foreach ($folders as $key => $value){
                            if ($value == 'trash') {  unset($folders[$key]); }
                            if ($value == 'shared') {  unset($folders[$key]); }
                        }
                      foreach ($folders as $f) {
					 ?>
					    if(document.getElementById("fullpath"+<?php echo $ii ?>)){
					       document.getElementById("fullpath"+<?php echo $ii ?>).value =_path1;
						}
					<?php
					  flush();
                      $ii++;
					}
					?>
					}
					if(upload_type == 'createfolder'){
						let _path = (file.fullPath) ? file.fullPath : file.name;
				 	   document.getElementById("fullpath1").value = _path;
					}

					if(upload_type == 'recentfile'){
						let _path = (file.fullPath) ? file.fullPath : file.name;
				 	   document.getElementById("fullpath2").value = _path;
					}

					xhr.ontimeout = (function() {
                        toast('Error: Server Timeout');
                    });
                }).on("success", function (res, responseText) {
					var jsonResponse = JSON.parse(res.xhr.response);
					if(res.status == 'success'){
						//toast('Upload Successfully');
						$("#toast-message").html('Upload Successfully');
						$("#basic-primary-trigger").trigger('click');
					   // Path Return Back to Same Foder Path after Drop Folder
					   let _pfolderpath= jsonResponse.p;
					   if(_pfolderpath != ''){
						   var scroll = $('.file-scroll').scrollTop();
						$("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+_pfolderpath,function(){
		                       Dropzone.discover();
							   $('.file-scroll').scrollTop(scroll);
							   $('#home_storage_data').hide();
	                     })

						 $("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php?p="+_pfolderpath,function(){
						  // Update Right Side Content
						  Dropzone.discover();
						});
					   }
					   else {
						  var scroll = $('.file-scroll').scrollTop();
					       $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
							Dropzone.discover();
							$('.file-scroll').scrollTop(scroll);
						})
						$("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php",function(){
						  // Update Right Side Content
						  Dropzone.discover();
						});
					  }
					  //let _response = JSON.parse(res.xhr.response);
					  //console.log('Upload Status >> ', res.status);
					}

					if(res.status == "error") {
                        toast(_response.info);
                    }
                 }).on("error", function(file, response) {
                    toast(response);
                });
            }
        }

		var oldprogress = 0;
	      Dropzone.options.fileUploader3 = {
            chunking: true,
            chunkSize: 10000000,
            forceChunking: true,
            retryChunks: true,
            retryChunksLimit: 3,
			clickable: false,
			parallelUploads: 1000, // does not support more than 1!
            timeout: 3600000,
			dictDefaultMessage: "Create Folder",
			complete:function(file) {
            },
			totaluploadprogress: function(progress) {
			    $(".progress-track").show();
				$(".progress-bar").width(progress + '%');
				$(".progress-bar").text(progress.toFixed(2) + '%');
		    if (progress > oldprogress) {
			      oldprogress = progress;
			    }
			if(progress == 100){
			    $("#toast-message").html('Upload Successfully');
			    $("#basic-primary-trigger").trigger('click');
					//$(".progress-track").hide();
					//$("#loader-progress").hide();
			    }
			},
			addedfile:function() {
                 console.log("A file has been added");

			},
			maxFilesize: 10000000000,
            acceptedFiles : "",

            init: function () {
			   this.on("sending", function (file, xhr, formData) {
			   var upload = "";
				  var count = file.length;
				   if (count > 1) {
				     for (var i = 0; i < count; i++) {
				       var ext = file.name.split('.').pop();
					   alert(ext);
				       if (ext == "" ){
							// stop, don't upload anything
							// alert (stop)
							$("#toast-message").html('Upload Successfully');
					$("#basic-primary-trigger").trigger('click');
				        upload = "STOP"; // more than one file and there is a .mp4 or .mov

					}
				}
				}else {
				upload = "GO"; // only one file
				}


				if (upload != "STOP") {
				// Run the below code
				}

				$("#loader-progress").show();
			   	    var values = {};

				    if($("#fileUploader3").serializeArray()){
						console.log("createfolder==>"+$("#fileUploader3").serializeArray());
						$.each($("#fileUploader3").serializeArray(), function (i, field) {
							values[field.name] = field.value;
						});

						//Value Retrieval Function
						var getValue = function (valueName) {
						return values[valueName];
						};

						//Retrieve the Values
						var upload_type = getValue("upload_type");

					}

					console.log("upload_type_fileUploader3==>==>"+upload_type);

					if(upload_type == 'createfolderfile'){
					   let _path = (file.fullPath) ? file.fullPath : file.name;
					   document.getElementById("fullpath").value = _path;

					let _path1 = (file.fullPath) ? file.fullPath : file.name;
					// Code For Upload Folder only
                    <?php
					 $ii = 3399;
                       foreach ($folders as $key => $value){
                            if ($value == 'trash') {  unset($folders[$key]); }
                            if ($value == 'shared') {  unset($folders[$key]); }
                        }
                      foreach ($folders as $f) {
					 ?>
					    if(document.getElementById("fullpath"+<?php echo $ii ?>)){
					       document.getElementById("fullpath"+<?php echo $ii ?>).value =_path1;
						}
					<?php
					  flush();
                      $ii++;
					}
					?>
					}
					if(upload_type == 'createfolder'){
						let _path = (file.fullPath) ? file.fullPath : file.name;
				 	    document.getElementById("fullpath1").value = _path;
					}

					if(upload_type == 'recentfile'){
						let _path = (file.fullPath) ? file.fullPath : file.name;
				 	   document.getElementById("fullpath2").value = _path;
					}

					xhr.ontimeout = (function() {
                        toast('Error: Server Timeout');
                    });
                }).on("success", function (res, responseText) {

					//$(".progress-track").hide();
					var jsonResponse = JSON.parse(res.xhr.response);
					if(res.status == 'success'){
					   // Path Return Back to Same Folder Path after Drop Folder
					   let _pfolderpath= jsonResponse.p;
					   if(_pfolderpath != ''){
						   var scroll = $('.file-scroll').scrollTop();
						   $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+_pfolderpath,function(){
		                    Dropzone.discover();
							$('.file-scroll').scrollTop(scroll);
							$('#home_storage_data').hide();
                          })
						 $("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php?p="+_pfolderpath,function(){
						  // Update Right Side Content
						});
					   }
				    else {
					    var scroll = $('.file-scroll').scrollTop();
						$("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
						   Dropzone.discover();
						   $('.file-scroll').scrollTop(scroll);
						 })
						$("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php",function(){
						  // Update Right Side Content
						  Dropzone.discover();
						});
					  //let _response = JSON.parse(res.xhr.response);
					  //console.log('Upload Status >> ', res.status);
				    }
					  //toast('Upload Successfully');
				   }
				  if(res.status == "error") {
                       toast(_response.info);
                   }
				}).on("error", function(file, response) {
                    toast(response);
                });
			}
		}
  </script>
  <div class="file-scroll w-100">
    <div id="files_div">
	  <div class="my-file-inner">
        <div class="row mb-4">
            <div class="col-12 d-flex align-items-center justify-content-between">
                <h6>My Files</h6>
			    <a class="font-size-18" href="javascript: void(0);">View All</a>
            </div>
        </div>
        <div class="row mb-4">
        <?php
		    $ii = 3399;
                foreach ($folders as $key => $value){
                            if ($value == 'trash') {  unset($folders[$key]); }
                            if ($value == 'shared') {  unset($folders[$key]); }
                        }
                    foreach ($folders as $f) {
                        // Get No of files in the folder //
                        $cdirectory = $path . '/' . $f;
                        $cfilecount = 0;
                        $cfiles = array_slice(scandir($cdirectory),2);
                        if ($cfiles){
                              $cfilecount = count($cfiles);
                            }
                        $is_link = is_link($path . '/' . $f);
                        $img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
                        $modif_raw = filemtime($path . '/' . $f);
                        $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                        if ($calc_folder) {
                            $filesize_raw = fm_get_directorysize($path . '/' . $f);
                            $filesize = fm_get_filesize($filesize_raw);
                        }
                        else {
                            $filesize_raw = "";
                            $filesize = lng('Folder');
                        }
                        $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                        if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                            $owner = posix_getpwuid(fileowner($path . '/' . $f));
                            $group = posix_getgrgid(filegroup($path . '/' . $f));
                        } else {
                            $owner = array('name' => '?');
                            $group = array('name' => '?');
                        }
			   ?>
               <div class="col-sm-6 col-lg-4 col-xl-4 col-xxl-3" id="<?php echo $ii ?>">
			    <form action="<?php echo "https://".$http_host. '/admin/filemanager/ajax_upload_folder_file.php?p=' . fm_enc(FM_PATH) ?>" class="dropzone card-tabs-container dz-custom-file" id="fileUploader1" enctype="multipart/form-data">
                  <a onclick="insidefolder('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')" href="#">
				  <div class="card shadow-none mb-4 folder-shadow">
                    <div class="card-body d-flex align-items-start folder-card-shadow">
                        <div class="avatar-xs">
                            <div class="avatar-title bg-transparent rounded">
                                <i class="fa fa-folder"></i>
                            </div>
                           </div>
						    <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                            <input type="hidden" name="fullpath<?php echo $ii ?>" id="fullpath<?php echo $ii ?>" value="<?php echo fm_enc(FM_PATH) ?>">
							<input type="hidden" name="FM_ROOT_PATH" id="FM_ROOT_PATH" value="<?php echo FM_ROOT_PATH ?>">
							<input type="hidden" name="FM_PATH" id="FM_PATH" value="<?php echo FM_PATH ?>">
							<input type="hidden" name="upload_folder_name" id="FM_PATH" value="<?php echo fm_convert_win(fm_enc($f)) ?>">
							<input type="hidden" name="counter" id="counter" value="<?php echo $ii ?>">
							<input type="hidden" name="checkvalue" id="checkvalue" value="">
							<input type="hidden" name="upload_type" id="upload_type" value="createfolderfile">

							<div class="fallback">
                              <input name="file" type="file" multiple/>
							</div>
						   <div class="d-flex ps-4 pt-2">
                            <div class="overflow-hidden me-auto">
                                <h5 class="font-size-20 text-truncate mb-1"><?php echo fm_convert_win(fm_enc($f)) ?>
                                    <?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></h5>
                                <p class="text-truncate mb-0 text-300"><?php echo $cfilecount; ?> Files</p>
                            </div>
                            <!-- <div class="align-self-end ms-2">
                                <p class="text-muted mb-0 text-300"> <?php echo $filesize_raw; ?></p>
                            </div> -->
							</div>
							</a>
						</form>

						<div class="float-end ms-auto">
                            <div class="dropdown pt-2 ">
                                <a class="font-size-16 text-muted" style="position: relative; right: 12px; top: 26px; padding: 6px;" href="#" role="button" id="subDropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="subDropdownMenuLink">
                                    <li><a class="dropdown-item" title="<?php echo lng('Open') ?>" onclick="insidefolder('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')" href="javascript:void(0);">Open</a></li>
                                    <!--<li><a class="dropdown-item" href="#">Edit</a></li>-->
                                    <li><a class="dropdown-item" title="<?php echo lng('Rename')?>" href="javascript:void(0);" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename')?></a></li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li><a class="dropdown-item" title="<?php echo lng('Delete') ?>" onclick="sendtotrashfolder('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>', '<?php echo trim(FM_PATH) ?>')" href="javascript:void(0);"><?php echo lng('Delete') ?></a></li>
                                </ul>
                             </div>
                          </div>
                    </div>
                </div>
            </div>
            <?php
                flush();
                $ii++;
            }
            ?>
            <!-- end col -->
			<style>
			#the-progress-div{
			  color:#000!important;background-color:#9e9e9e!important;
			  width:0px; height:10px;
			}
			span.the-progress-text {
			float: left;
			width: 100%;
			text-align: center;
			background-color: #e3dbdb;
			padding: 2px;
			border-radius: 15px;
			}
           .dz-custom {
			margin: 0px !important;
			line-height: 0px !important;
			padding-left: 15px !important;
			font-size: 18px;
			}
			.progress-track {
			position: fixed;
			bottom: 0px;
			padding: 25px 15px;
			background: #fff;
			max-width: 51%;
			margin-left: 15px;
			z-index: 999;
			display: block;
			}

			</style>
			<?php
			    $fdirectory=$path;
				$userdir=$fdirectory;
				$new_dirctory_name="Untitled";
                $items = scandir($fdirectory);
                $countdir=1;
					foreach ($items as $item) {
                      if($item != $new_dirctory_name){
					    if(!is_dir($fdirectory.'/'.$new_dirctory_name)){
					     //mkdir($userdir.'/'.$new_dirctory_name, 0777, true);
                         $path_dir_name=$new_dirctory_name;
					    break;
				       }
				    }

					if($item == $new_dirctory_name){
					   if(!is_dir($fdirectory.'/'.$new_dirctory_name. '(1)')){
					  //mkdir($userdir.'/'.$new_dirctory_name.'1', 0777, true);
					   $path_dir_name=$new_dirctory_name. '(1)';
					   break;
					}
				}

				if (in_array($new_dirctory_name. '('.$countdir.')', $items))
				  {
                   $newcountdir=$countdir+1;
                    if(!is_dir($fdirectory.'/'.$new_dirctory_name. '('.$newcountdir.')')){
						 //mkdir($userdir.'/'.$new_dirctory_name.$newcountdir, 0777, true);
						  $path_dir_name=$new_dirctory_name. '('.$newcountdir.')';
						  break;
				        }
					$countdir++;
				   }
                }
           ?>
            <!--<div class="col-sm-6 col-lg-4 col-xl-4 col-xxl-3">
                <div class="card add-folder d-flex align-items-center justify-content-center">
                    <div class="d-flex w-100 align-items-center justify-content-center">
                      <div class="font-size-18">Create Folder</div>
					</div>
                </div>
            </div>-->

			<div class="col-sm-6 col-lg-4 col-xl-4 col-xxl-3">
			<input type="hidden" id="inc_val" value="0">
			  <form action="<?php echo "https://".$http_host. '/admin/filemanager/ajax_upload_folder.php?p=' . fm_enc(FM_PATH) ?>" class="dropzone card-tabs-container dz-custom card add-folder d-flex align-items-center justify-content-center" id="fileUploader2" enctype="multipart/form-data">
                    <input type="hidden" name="p" id="p" value="<?php echo fm_enc(FM_PATH) ?>">
					<input type="hidden" name="FM_PATH" id="FM_PATH" value="<?php echo fm_enc(FM_PATH) ?>">
                    <input type="hidden" name="fullpath1" id="fullpath1" value="<?php echo fm_enc(FM_PATH) ?>">
					<input type="hidden" name="path_dir_name" id="path_dir_name" value="<?php echo $path_dir_name ?>">
                    <input type="hidden" name="upload_type" id="upload_type" value="createfolder">
					<div class="fallback">
                        <input name="file" type="file" multiple/>
                    </div>
			  </form>
            </div>
			<!--<div id="the-progress-div" class="col-sm-12 col-lg-12 col-xl-12 col-xxl-12 progress" style="display:block;">
				  <span class="the-progress-text progress-bar">0 %</span>
		    </div>-->
			<p></p>

			<div class="progress-track" style="display:none;">
			   <div class="progress" style="height:15px; border-radius: 50px;">
			    <div class="progress-bar" role="progressbar" style="width: 0%; border-radius: 50px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
			  </div>
			</div>
		  </div>
        </div>
        <div class="d-flex flex-wrap">
           <h5 class="me-3">Recent Files</h5>
		    <div class="ms-auto">
              <a href="javascript: void(0);" class="font-size-18">View All</a>
            </div>
        </div>
		<div id="loader-progress" style="display:none;">
                <div class="loading-box" id="" style="/* display: none; */">
                   <div class="loader"></div>
                </div>
			</div>
		   <form action="<?php echo "https://".$http_host. '/admin/filemanager/ajax_upload_recent_file.php?p=' . fm_enc(FM_PATH) ?>" class="dropzone card-tabs-container dz-custom-file" id="fileUploader3" enctype="multipart/form-data">
			<input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
			<input type="hidden" name="fullpath2" id="fullpath2" value="<?php echo fm_enc(FM_PATH) ?>">
			<input type="hidden" name="FM_ROOT_PATH" id="FM_ROOT_PATH" value="<?php echo FM_ROOT_PATH ?>">
			<input type="hidden" name="FM_PATH" id="FM_PATH" value="<?php echo FM_PATH ?>">

			<input type="hidden" name="upload_type" id="upload_type" value="recentfile">
			<div class="fallback">
			  <input name="file" type="file" multiple/>
			</div>
           <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
             <input type="hidden" name="group" value="1">
              <div class="m-table-responsive">
				<div id="loader-storage" style="display:none;">
                  <div class="loading-box" id="" style="/* display: none; */">
                     <div class="loader"></div>
                  </div>
                </div>
				<div id="loader" style="display:none;">
                    <div class="loading-box" id="" style="/* display: none; */">
                        <div class="loader"></div>
                    </div>
                </div>
				  <div class="alert alert-danger response-error" style="display:none;" role="alert">
				     <div id="error-message"> </div>
				  </div>
				<div id="getAllStorageHTML" style="display:block;">
                    <table class="table table-hover table-sm <?php echo $tableTheme; ?>" id="main-table">
                        <thead class="thead-white">
                        <tr>
                            <?php if (!FM_READONLY): ?>
							<th style="width:3%" class="custom-checkbox-header">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" id="js-select-all-items" onclick="checkbox_toggle()">
									<label class="custom-control-label" for="js-select-all-items"></label>
								</div>
							</th><?php endif; ?>
                            <th><?php echo lng('Name') ?></th>
                            <th><?php echo lng('Size') ?></th>
                            <th><?php echo lng('Modified') ?></th>
                            <?php  if (!FM_IS_WIN && !$hide_Cols): ?>
                                <th><?php// echo lng('Perms') ?></th>
                                <th><?php// echo lng('Owner') ?></th><?php endif;  ?>
                            <th><?php echo lng('Actions') ?></th>
                        </tr>
                        </thead>
						<?php
                        // link to parent folder
                        if ($parent !== false) {
							$fm_path=FM_PATH;
							$arr=explode('/', $fm_path);
							$remove = array_pop($arr);
							$fmpath=implode("/", $arr);
                            ?>
                            <tr><?php if (!FM_READONLY): ?>
                                <td class="nosort"><a onclick="insidefolder('<?php echo urlencode(trim($fmpath)) ?>')" href="#"><i class="fa fa-chevron-circle-left go-back"></i></a></td><?php endif; ?>
                                <td data-sort></td>
                                <td data-order></td>
                                <td data-order></td>
                                <td></td>
                                <?php if (!FM_IS_WIN && !$hide_Cols) { ?>
                                    <td></td>
                                    <td></td>
                                <?php } ?>
                            </tr>
                         <?php
                        }
						?>
						<?php
                        $ii = 3399;
						foreach ($folders as $f) {
                            $is_link = is_link($path . '/' . $f);
                            $img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
                            $modif_raw = filemtime($path . '/' . $f);
                            $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                            if ($calc_folder) {
                                $filesize_raw = fm_get_directorysize($path . '/' . $f);
                                $filesize = fm_get_filesize($filesize_raw);
                            }
                            else {
                                $filesize_raw = "";
                                $filesize = lng('Folder');
                            }
                            $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                            if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
                                $owner = posix_getpwuid(fileowner($path . '/' . $f));
                                $group = posix_getgrgid(filegroup($path . '/' . $f));
                            } else {
                                $owner = array('name' => '?');
                                $group = array('name' => '?');
                            }
                            ?>
                            <tr id="<?php echo $ii ?>">
                                <?php if (!FM_READONLY): ?>
                                    <td class="custom-checkbox-td">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"  class="custom-control-input checkvalues" id="checkvalues" name="file[]" value="<?php echo fm_enc($f) ?>">
                                            <label class="custom-control-label" for="<?php echo $ii ?>"></label>
                                        </div>
                                    </td><?php endif; ?>
                                    <td data-sort=<?php echo fm_convert_win(fm_enc($f)) ?>>
                                        <div class="filename">
										<?php if(isset($_GET['sharedu'])){ ?>
										<a href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')).'&sharedu='.$_GET['sharedu'] ?>"><span class="thumb"><i class="<?php echo $img ?>"></i></span> <?php echo fm_convert_win(fm_enc($f)) ?>
                                            </a>
											<?php } else { ?>
										<a onclick="insidefolder('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')" href="#"><span class="thumb"><i class="<?php echo $img ?>"></i></span> <?php echo fm_convert_win(fm_enc($f)) ?>
                                            </a>
										<?php } ?>
									    <?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
									</td>
                                    <td data-order="a-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT);?>">
                                        <?php echo $filesize; ?>
                                    </td>
                                    <td data-order="a-<?php echo $modif_raw;?>"><?php echo $modif ?></td>
                                <?php  if (!FM_IS_WIN && !$hide_Cols): ?>
                                    <td>
                                    </td>
                                <td></td>
                                <?php endif;  ?>
                                <td class="inline-actions">
                                    <div class="dropdown">
									    <a class="font-size-16 text-muted" href="#" role="button" id="dropdownMenuLink"
                                                data-mdb-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i>
                                        </a>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                <!--<li> <a class="dropdown-item" title="<?php echo lng('DirectLink')?>" onclick="insidefolder('<?php echo fm_enc((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f . '/') ?>')" href="#"><?php echo lng('DirectLink')?></a><li>-->
                                            <?php if (!FM_READONLY): ?>
                                                <li><a class="dropdown-item" title="<?php echo lng('Rename')?>" href="javascript:void(0);" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename')?></a></li>
                                                <li><a class="dropdown-item" title="<?php echo lng('CopyTo')?>..." href="javascript:void(0);" onclick="open_iframe('<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><?php echo lng('CopyTo')?></a></li>
                                                <li><a class="dropdown-item" href="#" onclick="starred('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><span>Starred</span></a><!--<a class="dropdown-item" title="<?php echo lng('Starred')?>..." href="?action=filemanager&p=&amp;starred=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('Starred')?></a>--></li>
												<li><a class="dropdown-item" title="<?php echo lng('Share') ?>" href="javascript:void(0);" data-toggle="modal" data-target="#shareModal<?php echo $ii; ?>"><?php echo lng('Share') ?></a></li>
												<li><hr class="dropdown-divider" /></li>
                                                <li><a class="dropdown-item" href="javascript:void(0);" onclick="sendtotrashfolder('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>', '<?php echo trim(FM_PATH) ?>')"><span>Delete</span></a></li>
												<!--<li><a class="dropdown-item" title="<?php echo lng('Delete')?>" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="return confirm('<?php echo lng('Delete').' '.lng('Folder').'?'; ?>\n \n ( <?php echo urlencode($f) ?> )');"> <?php echo lng('Delete')?></a><li>-->
                                            <?php endif; ?>
                                            </ul>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            flush();
							?>
<!-- Code for Iframe--->

<!--Modal: Name-->

<!--Modal Iframe Ends Here -->
<!----------- Modal ------------------------>
<script>
 function myFunction(sharedId) {
  /* Get the text field */

  var copyText = document.getElementById("copysharedlink"+sharedId);

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  //alert("Copied the text: " + copyText.value);
}
 </script>
  <div class="modal fade" id="shareModal<?php echo $ii; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
     <div class="modal-content">
	   <div class="modal-header">
	   <h5 class="modal-title" id="exampleModalLabel">Share <?php echo fm_convert_win(fm_enc($f)) ?></h5>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
			</button>
			  </div>
			  <div id="loader-model<?php echo $ii; ?>" style="display:none;"><img  style="z-index: 999;" src="https://rosnyc.com/admin/filemanager/load.webp" class="ajax-loader"></div>
	            <div class="alert alert-danger response-error-model<?php echo $ii; ?>" style="display:none;" role="alert">
		           <div id="error-message-model<?php echo $ii; ?>"> </div>
               </div>
			     <div class="modal-body">
					    <input class="form-control" type="text" id="share_emails<?php echo $ii?>" name="share_emails" value="" required>
						<input class="form-control" type="hidden" name="root_path" value="<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>">
						 <p></p>
					<p>Anyone on the Internet with the link can view</p>
					<?php
					$FM_SHARED_URL='https://'.$http_host.'/admin/index.php?action=filemanager&p=';
					$shared_u='&sharedu='.base64_encode($_SESSION['userId']);
					$FM_PATH=urlencode(trim(FM_PATH . '/' . $f, '/'));
					$sharedURLfolder = fm_enc($FM_SHARED_URL.($FM_PATH != '' ? '/' . $FM_PATH : '').$shared_u);
					?>
					<p>
					<input class="form-control" type="text" id="copysharedlink<?php echo $ii; ?>" name="shared_url" value="<?php echo trim($sharedURLfolder);?>" readonly/>
					</p>
						  </div>
							<div class="modal-footer">
							  <button type="button" class="btn btn-secondary" onclick="myFunction('<?php echo $ii; ?>')">Copy Link</button>
							  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							  <button type="button" onclick="sharedata('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>','<?php echo $ii; ?>')" class="btn btn-primary">Share</button>
					   </div>

				   </div>
			     </div>
		      </div>
				 <?php
				  $ii++;
				}// Folder Loop Ends Here...
				$ik = 6070;
				?>

				<?php
				   foreach ($files as $f) {
							$is_link = is_link($path . '/' . $f);
                            $img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
                            $modif_raw = filemtime($path . '/' . $f);
                            $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                            $filesize_raw = fm_get_size($path . '/' . $f);
                            $filesize = fm_get_filesize($filesize_raw);
                            $filelink = '?action=filemanager&p=' . urlencode(FM_PATH) . '&amp;view=' . urlencode($f);
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
                            ?>
                            <tr id="<?php echo $ik; ?>">
                                <?php if (!FM_READONLY): ?>
                                    <td class="custom-checkbox-td">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="<?php echo $ik ?>" name="file[]" value="<?php echo fm_enc($f) ?>">
                                        <label class="custom-control-label" for="<?php echo $ik ?>"></label>
                                    </div>
                                  </td>
								  <?php endif; ?>
                                <td data-sort=<?php echo fm_enc($f) ?>>
                                    <div class="filename">
									<?php if(isset($_GET['sharedu'])){
										$FM_SHARED_URL='https://'.$http_host.'/admin/index.php?action=filemanager&p=';
										$shared_file='&shared='.$_GET['sharedu'].'&sharedview='.$f;
										$sharedURL = fm_enc($FM_SHARED_URL.(FM_PATH != '' ? '/' . FM_PATH : '').$shared_file);
									    $sharedURLPreviewPath='https://'.$http_host.'/admin/filemanager/'.$_GET['sharedu'].'/'.(FM_PATH != '' ? '/' . FM_PATH : '').'/'.$f;
									 if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))): ?>
                                    <?php $imagePreview = $sharedURLPreviewPath; ?>
                                            <a href="<?php echo $sharedURL ?>"  data-preview-image="<?php echo $imagePreview ?>" title="<?php echo fm_enc($f) ?>">
                                    <?php else: ?>
                                            <a href="<?php echo $sharedURL ?>" title="<?php echo $f ?>">
                                    <?php endif; ?>
                                           <i class="<?php echo $img ?>"></i><?php echo fm_convert_win(fm_enc($f)) ?>
                                            </a>
									<?php } else { ?>
                                    <?php
                                    if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))): ?>
                                    <?php $imagePreview = fm_enc(FM_IMAGE_URL.(FM_PATH != '' ? '/' . FM_PATH : '').'/'.$f); ?>
                                            <a href="javascript:void(0)" onclick="files_preview_show('<?php echo $ik; ?>')" data-preview-image="<?php echo $imagePreview ?>" title="<?php echo fm_enc($f) ?>"><span onclick="files_preview_show('<?php echo $ik; ?>')"><img src="<?php echo $imagePreview ?>" alt="img"></span><?php  echo fm_convert_win(fm_enc($f))?>
                                    <?php else: ?>
                                            <a href="javascript:void(0)" onclick="files_preview_show('<?php echo $ik; ?>')"> <span onclick="files_preview_show('<?php echo $ik; ?>')" ><i class="<?php echo $img ?>" style="font-size: 48px; padding-left: 12px;"></i></span><?php echo fm_convert_win(fm_enc($f)) ?>
									<?php endif; ?>
                                            </span></a>
									<?php } ?>
										<?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?>
                                    </div>
                                </td>
                                <td data-order="b-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT); ?>"><span title="<?php printf('%s bytes', $filesize_raw) ?>">
                                    <?php echo $filesize; ?>
                                    </span></td>
                                <td data-order="b-<?php echo $modif_raw;?>"><?php echo $modif ?></td>
                                <?php  if (!FM_IS_WIN && !$hide_Cols): ?>
                                    <td>
                                    </td>
                                    <td></td>
                                <?php endif;  ?>
                                <td class="inline-actions">
								 <div class="dropdown">
                                    <a class="font-size-16 text-muted" href="#" role="button" id="secondDropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false" > <i class="fa fa-ellipsis-v"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="secondDropdownMenuLink">
									<li><a class="dropdown-item" title="<?php echo lng('Preview') ?>"  href="javascript:void(0);" onclick="files_preview_show('<?php echo $ik; ?>')"  data-title="<?php echo fm_convert_win(fm_enc($f)) ?>" data-max-width="100%" data-width="100%"><?php echo lng('Preview') ?></a></li>
									 <!--<li> <a class="dropdown-item" title="<?php echo lng('Preview') ?>" href="<?php echo $filelink.'&quickView=1'; ?>" data-toggle="lightbox" data-gallery="tiny-gallery" data-title="<?php echo fm_convert_win(fm_enc($f)) ?>" data-max-width="100%" data-width="100%"><?php echo lng('Preview') ?></a></li>-->
                                        <li> <a class="dropdown-item" title="<?php echo lng('DirectLink') ?>" href="<?php echo fm_enc(FM_IMAGE_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f) ?>" target="_blank"><?php echo lng('DirectLink') ?></a></li>
                                        <li><a class="dropdown-item" title="<?php echo lng('Download') ?>" onclick="downloadfile('<?php echo fm_enc((FM_PATH != '' ? '/' . FM_PATH : '')) ?>','<?php echo $f ?>')" href="javascript:void(0);"><?php echo lng('Download') ?></a></li>
                                        <?php if (!FM_READONLY): ?>
                                            <li><a class="dropdown-item" title="<?php echo lng('Rename') ?>" href="javascript:void(0);" onclick="rename('<?php echo fm_enc(addslashes(FM_PATH)) ?>', '<?php echo fm_enc(addslashes($f)) ?>');return false;"><?php echo lng('Rename') ?></a></li>
                                            <li><a class="dropdown-item" title="<?php echo lng('CopyTo') ?>..."
                                            href="javascript:void(0);" onclick="open_iframe('<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><?php echo lng('CopyTo') ?></a></li>
                                            <li><a class="dropdown-item" href="javascript:void(0);" onclick="starred('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')"><span>Starred</span></a>

											<!--<a class="dropdown-item" title="<?php echo lng('Starred') ?>..."
                                            href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;starred=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><?php echo lng('Starred') ?></a></li>-->
											<li><a class="dropdown-item" title="<?php echo lng('Share') ?>" href="javascript:void(0);" data-toggle="modal" data-target="#shareModal<?php echo $ik; ?>"><?php echo lng('Share') ?></a></li>
											<li><hr class="dropdown-divider" /></li>
                                            <!--<li> <a class="dropdown-item" title="<?php echo lng('Delete') ?>" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="return confirm('<?php echo lng('Delete').' '.lng('File').'?'; ?>\n \n ( <?php echo urlencode($f) ?> )');"> <?php echo lng('Delete') ?></a></li>-->
											<li><a class="dropdown-item" href="javascript:void(0);" onclick="sendtotrash('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>',<?php echo $ik; ?>)"><span>Delete</span></a>
                                        <?php endif; ?>
                                    </ul>
								  </div>
                                </td>
                            </tr>
                            <?php
                               flush();
                            ?>

<!-- Code for Iframe--->

<!--Modal: Name-->

<!--Modal Iframe Ends Here -->

 <!------------- Modal ------------------------->
 <script>
 function myFunction(sharedId) {
  /* Get the text field */

  var copyText = document.getElementById("copysharedlink"+sharedId);

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

   /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);

  /* Alert the copied text */
  //alert("Copied the text: " + copyText.value);
}
 </script>
  <div class="modal fade share-modal" id="shareModal<?php echo $ik; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
     <div class="modal-content">
	   <div class="modal-header">
	   <h5 class="modal-title" id="exampleModalLabel">Share <?php echo fm_convert_win(fm_enc($f)) ?></h5>
		 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		   <span aria-hidden="true">&times;</span>
			</button>
			  </div>
			  <div id="loader-model<?php echo $ik; ?>" style="display:none;"><img  style="z-index: 999;" src="https://rosnyc.com/admin/filemanager/load.webp" class="ajax-loader"></div>
	            <div class="alert alert-danger response-error-model<?php echo $ik; ?>" style="display:none;" role="alert">
		           <div id="error-message-model<?php echo $ik; ?>"> </div>
               </div>
			     <div class="modal-body">
					<input class="form-control" type="text" id="share_emails<?php echo $ik?>" name="share_emails" value="" required>
					<input class="form-control" type="hidden" name="root_path" value="<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>">
					<p></p>
					<p class="mb-1">Anyone on the Internet with the link can view</p>
					<?php
					$FM_SHARED_URL='https://'.$http_host.'/admin/index.php?action=filemanager&p=';
					$shared_file='&shared='.base64_encode($_SESSION['userId']).'&sharedview='.$f;
					$sharedURL = fm_enc($FM_SHARED_URL.(FM_PATH != '' ? '/' . FM_PATH : '').$shared_file);
					?>
					<div class="d-flex copy-link">
					<input class="form-control" type="text" id="copysharedlink<?php echo $ik; ?>" name="shared_url" value="<?php echo trim($sharedURL);?>" readonly/>
                    <button type="button" class="btn btn-secondary" onclick="myFunction('<?php echo $ik; ?>')">Copy Link</button>
                    </div>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="sharedata('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>','<?php echo $ik; ?>')" class="btn btn-primary btn-rounded">Share</button>
					 </div>
					</div>
			     </div>
		      </div>
<!-- Button trigger modal -->
<!--<button type="button" class="btn btn-primary" data-mdb-toggle="modal" data-mdb-target="#exampleModal">
  Launch demo modal
</button>-->

<!-- Modal -->
<!--<div class="modal top fade" id="previewModal<?php echo $ik; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="false" data-mdb-keyboard="true">
  <div class="modal-dialog modal-xl ">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><?php echo $filelinkModel; ?></h5>
        <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">-->
  <div id="files_preview_div<?php echo $ik; ?>" style="display:none;">
  <?php //if (isset($_GET['view'])) {
    $file = $filelinkModel;
	$quickView =  true;
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file) || in_array($file, $GLOBALS['exclude_items'])) {
        fm_set_msg(lng('File not found'), 'error');
        //fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    if(!$quickView) {
        fm_show_header(); // HEADER
        fm_show_nav_path(FM_PATH); // current path
    }


	$file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);

	$file_url = FM_IMAGE_URL. fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' .$file);

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
	?>
    <div class="filemanager-wrap flex-wrap">
	  <div style="float:left;width:100%;"><a href="javascript:void(0)" style="float:left" onclick="files_preview_div_close('<?php echo $ik; ?>')">Close</a></div>
        <div class="row ">
            <div class="col-12">
                <div class="card card-lg">
                <?php
                    if($is_onlineViewer) {
                        if($online_viewer == 'google') {
                            echo '<iframe src="https://docs.google.com/viewer?embedded=true&hl=en&url=' . fm_enc($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                        } else if($online_viewer == 'microsoft') {
                            echo '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . fm_enc($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                        }
                    } elseif ($is_zip) {
                        // ZIP content
                        if ($filenames !== false) {
                            echo '<code class="maxheight">';
                            foreach ($filenames as $fn) {
                                if ($fn['folder']) {
                                    echo '<b>' . fm_enc($fn['name']) . '</b><br>';
                                } else {
                                    echo $fn['name'] . ' (' . fm_get_filesize($fn['filesize']) . ')<br>';
                                }
                            }
                            echo '</code>';
                        } else {
                            echo '<p>'.lng('Error while fetching archive info').'</p>';
                        }
                    } elseif ($is_image) {
                        // Image content
                        if (in_array($ext, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))) {
                            echo '<img src="' . fm_enc($file_url) . '" alt="" class="card-img-top preview-img">';
                        }
                    } elseif ($is_audio) {
                        // Audio content
                        echo '<p><audio src="' . fm_enc($file_url) . '" controls preload="metadata"></audio></p>';
                    } elseif ($is_video) {
                        // Video content
                        echo '<div class="preview-video"><video src="' . fm_enc($file_url) . '" width="640" height="360" controls preload="metadata"></video></div>';
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
                        echo $content;
                    }
                    ?>
                    <?php if(!$quickView) { ?>
                        <div class="card-body">
                            <h5 class="card-title mb-4"><?php echo $view_title ?> "<?php echo fm_enc(fm_convert_win($file)) ?>"</h5>
                            <p class="break-word d-none">
                                Full path: <?php echo fm_enc(fm_convert_win($file_path)) ?><br>
                                File size: <?php echo ($filesize_raw <= 1000) ? "$filesize_raw bytes" : $filesize; ?><br>
                                MIME-type: <?php echo $mime_type ?><br>
                                <?php
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
                                    ?>
                                    Files in archive: <?php echo $total_files ?><br>
                                    Total size: <?php echo fm_get_filesize($total_uncomp) ?><br>
                                    Size in archive: <?php echo fm_get_filesize($total_comp) ?><br>
                                    Compression: <?php echo round(($total_comp / $total_uncomp) * 100) ?>%<br>
                                    <?php
                                }
                                // Image info
                                if ($is_image) {
                                    $image_size = getimagesize($file_path);
                                    echo 'Image sizes: ' . (isset($image_size[0]) ? $image_size[0] : '0') . ' x ' . (isset($image_size[1]) ? $image_size[1] : '0') . '<br>';
                                }
                                // Text info
                                if ($is_text) {
                                    $is_utf8 = fm_is_utf8($content);
                                    if (function_exists('iconv')) {
                                        if (!$is_utf8) {
                                            $content = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $content);
                                        }
                                    }
                                    echo 'Charset: ' . ($is_utf8 ? 'utf-8' : '8 bit') . '<br>';
                                }
                                ?>
                            </p>
                            <p>
                                <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-chevron-circle-left go-back"></i> <?php echo lng('Back') ?></a>
                                <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($file) ?>"><i class="fa fa-download"></i> <?php echo lng('Download') ?></a>
                                <a class="card-link" href="<?php echo fm_enc($file_url) ?>" target="_blank"><i class="fa fa-link"></i> <?php echo lng('Open') ?></a>
                                <?php
                                // ZIP actions
                                if (!FM_READONLY && ($is_zip || $is_gzip) && $filenames !== false) {
                                    $zip_name = pathinfo($file_path, PATHINFO_FILENAME);
                                    ?>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>"><i class="fa fa-check-circle"></i> <?php echo lng('UnZip') ?></a>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>&amp;tofolder=1" title="UnZip to <?php echo fm_enc($zip_name) ?>"><i class="fa fa-check-circle"></i>
                                            <?php echo lng('UnZipToFolder') ?></a>
                                    <?php
                                }
                                if ($is_text && !FM_READONLY) {
                                    ?>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>" class="edit-file"><i class="fa fa-pencil-square"></i> <?php echo lng('Edit') ?>
                                        </a>
                                    <a class="card-link" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&env=ace"
                                        class="edit-file"><i class="fa fa-pencil-square-o"></i> <?php echo lng('AdvancedEditor') ?>
                                        </a>
                                <?php } ?>
                            </p>
                        </div>
                         <?php
                        }
					  ?>
                </div>
              </div>
            </div>
          </div>
		</div>
      <!--</div>
    </div>
  </div>
</div>-->
		   <?php
			   $ik++;
			}
		   ?>

		   <?php
				  if (empty($folders) && empty($files)) {
                            ?>
                            <tfoot>
                                <tr><?php if (!FM_READONLY): ?>
                                        <td></td><?php endif; ?>
                                    <td colspan="<?php echo (!FM_IS_WIN && !$hide_Cols) ? '6' : '4' ?>"><em><?php echo lng('Folder is empty') ?></em></td>
                                </tr>
                            </tfoot>
                            <?php
                        } else {
                            ?>
							<?php
							$directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']).'/';
							$directorysize=fm_get_directorysize($directory);
							$totalfreesize=1000000000 - $directorysize;
							$totalsizegb=sizeFormat($totalfreesize);
							$totalsizegbArr=explode('_', $totalsizegb);
							//fm_get_filesize(@disk_free_space($path))
                            ?>
                            <tfoot>
                                <tr><?php if (!FM_READONLY): ?>
                                    <td class="gray"></td><?php endif; ?>
                                    <td class="gray" colspan="<?php echo (!FM_IS_WIN && !$hide_Cols) ? '6' : '4' ?>">
                                        <?php echo lng('FullSize').': <span class="badge badge-light me-2">'.fm_get_filesize($all_files_size).'</span>' ?>
                                        <?php echo lng('File').': <span class="badge badge-light me-2">'.$num_files.'</span>' ?>
                                        <?php echo lng('Folder').': <span class="badge badge-light me-2">'.$num_folders.'</span>' ?>
                                        <?php echo lng('PartitionSize').': <span class="badge badge-light me-2">'.$totalsizegbArr[1].'GB</span> '.lng('FreeOf').' <span class="badge badge-light">1 GB</span>'; //fm_get_filesize(@disk_total_space($path)) ?>
                                    </td>
                                </tr>
                            </tfoot>
                            <?php
                           }
                        ?>
                    </table>
					</div>
				    <!--<div id="getStorageHTML"></div>-->
                </div>
                  <div class="row" id="files_preview_foot">
                    <?php if (!FM_READONLY): ?>
                      <div class="col-12">
                        <ul class="list-inline footer-action m-0">
                            <li class="list-inline-item"> <a href="#/select-all" class="btn btn-small btn-outline-primary btn-2" onclick="checkbox_toggle();return false;"><i class="fa fa-check-square"></i> <?php echo lng('SelectAll') ?> </a></li>
                            <li class="list-inline-item"><a href="#/unselect-all" class="btn btn-small btn-outline-primary btn-2" onclick="unselect_all();return false;"><i class="fa fa-window-close"></i> <?php echo lng('UnSelectAll') ?> </a></li>
                            <li class="list-inline-item"><a href="#/invert-all" class="btn btn-small btn-outline-primary btn-2" onclick="invert_all();return false;"><i class="fa fa-th-list"></i> <?php echo lng('InvertSelection') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="delete" id="a-delete" value="Delete" onclick="return confirm('<?php echo lng('Delete selected files and folders?'); ?>')">
                                <a href="javascript:void(0);" class="btn btn-small btn-outline-primary btn-2" onclick="massdelete('<?php echo FM_ROOT_PATH ?>', '<?php echo urlencode(FM_PATH) ?>')"><i class="fa fa-trash"></i> <?php echo lng('Delete') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="zip" id="a-zip" value="zip">
                                <a href="javascript:void(0);" class="btn btn-small btn-outline-primary btn-2" onclick="packfiles('<?php echo FM_ROOT_PATH ?>', '<?php echo urlencode(FM_PATH) ?>', 'zip')"><i class="fa fa-file-archive-o"></i> <?php echo lng('Zip') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="tar" id="a-tar" value="tar" onclick="return confirm('<?php echo lng('Create archive?'); ?>')">
                                <a href="javascript:void(0);" class="btn btn-small btn-outline-primary btn-2" onclick="packfiles('<?php echo FM_ROOT_PATH ?>', '<?php echo urlencode(FM_PATH) ?>', 'tar')"><i class="fa fa-file-archive-o"></i> <?php echo lng('Tar') ?> </a></li>
                            <li class="list-inline-item"><input type="submit" class="hidden" name="copy" id="a-copy" value="Copy">
                                <a href="javascript:void(0);" class="btn btn-small btn-outline-primary btn-2" onclick="masscopyiframe()"><i class="fa fa-files-o"></i> <?php echo lng('Copy') ?> </a></li>
                        </ul>
                    </div>

                    <?php else: ?>

                    <?php endif; ?>
                </div>
              </form>
			 </div>
		  </div>
<?php
/**
 * Save Configuration
 */
 class FM_Config
{
     var $data;

    function __construct()
    {
        global $root_path, $root_url, $CONFIG;
        $fm_url = $root_url.$_SERVER["PHP_SELF"];
        $this->data = array(
            'lang' => 'en',
            'error_reporting' => true,
            'show_hidden' => true
        );
        $data = false;
        if (strlen($CONFIG)) {
            $data = fm_object_to_array(json_decode($CONFIG));
        } else {
            $msg = 'Tiny File Manager<br>Error: Cannot load configuration';
            if (substr($fm_url, -1) == '/') {
                $fm_url = rtrim($fm_url, '/');
                $msg .= '<br>';
                $msg .= '<br>Seems like you have a trailing slash on the URL.';
                $msg .= '<br>Try this link: <a href="' . $fm_url . '">' . $fm_url . '</a>';
            }
            die($msg);
        }
        if (is_array($data) && count($data)) $this->data = $data;
        else $this->save();
    }

    function save()
    {
        $fm_file = __FILE__;
        $var_name = '$CONFIG';
        $var_value = var_export(json_encode($this->data), true);
        $config_string = "<?php" . chr(13) . chr(10) . "//Default Configuration".chr(13) . chr(10)."$var_name = $var_value;" . chr(13) . chr(10);
        if (is_writable($fm_file)) {
            $lines = file($fm_file);
            if ($fh = @fopen($fm_file, "w")) {
                @fputs($fh, $config_string, strlen($config_string));
                for ($x = 3; $x < count($lines); $x++) {
                    @fputs($fh, $lines[$x], strlen($lines[$x]));
                }
                @fclose($fh);
            }
        }
    }
}

/**
 * @param $obj
 * @return array
 */
function fm_object_to_array($obj)
{
    if (!is_object($obj) && !is_array($obj)) {
        return $obj;
    }
    if (is_object($obj)) {
        $obj = get_object_vars($obj);
    }
    return array_map('fm_object_to_array', $obj);
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
 * Path traversal prevention and clean the url
 * It replaces (consecutive) occurrences of / and \\ with whatever is in DIRECTORY_SEPARATOR, and processes /. and /.. fine.
 * @param $path
 * @return string
 */
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

/**
 * Get parent path
 * @param string $path
 * @return bool|string
 */
function fm_get_parent_path($path)
{
    $path = fm_clean_path($path);
    if ($path != '') {
        $array = explode('/', $path);
        if (count($array) > 1) {
            $array = array_slice($array, 0, -1);
            return implode('/', $array);
        }
        return '';
    }
    return false;
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
 * get language translations from json file
 * @param int $tr
 * @return array
 */
function fm_get_translations($tr) {
    try {
        $content = @file_get_contents('translation.json');
        if($content !== FALSE) {
            $lng = json_decode($content, TRUE);
            global $lang_list;
            foreach ($lng["language"] as $key => $value)
            {
                $code = $value["code"];
                $lang_list[$code] = $value["name"];
                if ($tr)
                    $tr[$code] = $value["translation"];
            }
            return $tr;
        }

    }
    catch (Exception $e) {
        echo $e;
    }
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
 * Get CSS classname for file
 * @param string $path
 * @return string
 */
function fm_get_file_icon_class($path)
{
    // get extension
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    switch (		$ext) {
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
/**
 * Get nice filesize
 * @param int $size
 * @return string
 */
function fm_get_filesize($size)
{
    $size = (float) $size;
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = ($size > 0) ? floor(log($size, 1024)) : 0;
    $power = ($power > (count($units) - 1)) ? (count($units) - 1) : $power;
    return sprintf('%s %s', round($size / pow(1024, $power), 2), $units[$power]);
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
 * Get info about zip archive
 * @param string $path
 * @return array|bool
 */
function fm_get_zif_info($path, $ext) {
    if ($ext == 'zip' && function_exists('zip_open')) {
        $arch = zip_open($path);
        if ($arch) {
            $filenames = array();
            while ($zip_entry = zip_read($arch)) {
                $zip_name = zip_entry_name($zip_entry);
                $zip_folder = substr($zip_name, -1) == '/';
                $filenames[] = array(
                    'name' => $zip_name,
                    'filesize' => zip_entry_filesize($zip_entry),
                    'compressed_size' => zip_entry_compressedsize($zip_entry),
                    'folder' => $zip_folder
                    //'compression_method' => zip_entry_compressionmethod($zip_entry),
                );
            }
            zip_close($arch);
            return $filenames;
        }
    } elseif($ext == 'tar' && class_exists('PharData')) {
        $archive = new PharData($path);
        $filenames = array();
        foreach(new RecursiveIteratorIterator($archive) as $file) {
            $parent_info = $file->getPathInfo();
            $zip_name = str_replace("phar://".$path, '', $file->getPathName());
            $zip_name = substr($zip_name, ($pos = strpos($zip_name, '/')) !== false ? $pos + 1 : 0);
            $zip_folder = $parent_info->getFileName();
            $zip_info = new SplFileInfo($file);
            $filenames[] = array(
                'name' => $zip_name,
                'filesize' => $zip_info->getSize(),
                'compressed_size' => $file->getCompressedSize(),
                'folder' => $zip_folder
            );
        }
        return $filenames;
    }
    return false;
}

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
function sizeFormat($bytes){
						$kb = 1024;
						$mb = $kb * 1024;
						$gb = $mb * 1024;
						$tb = $gb * 1024;

					if (($bytes >= 0) && ($bytes < $kb)) {
						  $bytes1=$bytes;
						  $gbd=$bytes1 / pow(1024, 3);
						  $totper=$gbd/1*100;
						return number_format((float)$bytes, 1, '.', '') . ' B' . '_'.$gbd . '_'.$totper;

					    } elseif (($bytes >= $kb) && ($bytes < $mb)) {
							$kb1=ceil($bytes / $kb);
							$gbd=$kb1*0.000001;
							$totper=$gbd/1*100;
						return number_format((float)$bytes / $kb, 1, '.', '') . ' KB' . '_'.$gbd . '_'.$totper;

						} elseif (($bytes >= $mb) && ($bytes < $gb)) {
							$mb1=ceil($bytes / $mb);
							// mb convert into gb
							$gbd=$mb1*0.001;
							$totper=$gbd/1*100;
						return number_format((float)$bytes / $mb, 1, '.', '') . ' MB' . '_'.$gbd . '_'.$totper;

                        } elseif (($bytes >= $gb) && ($bytes < $tb)) {
							$gb1=ceil($bytes / $gb);
							$gbd=$gb1;
							$totper=$gbd/1*100;
					   return number_format((float)$bytes / $gb, 1, '.', '') . ' GB';

						} elseif ($bytes >= $tb) {

						     return ceil($bytes / $tb) . ' TB';

						} else {

						return number_format((float)$bytes, 1, '.', '') . ' B';
					}
                }

	function sizeFormat1($bytes){
				$kb = 1024;
				$mb = $kb * 1024;
				$gb = $mb * 1024;
				$tb = $gb * 1024;
                  if (($bytes >= 0) && ($bytes < $kb)) {
						  $bytes1=$bytes;
						  $gbd=$bytes1 / pow(1024, 3);
						  $totper=$gbd/1*100;
						return $bytes . ' B';

					    } elseif (($bytes >= $kb) && ($bytes < $mb)) {
							$kb1=ceil($bytes / $kb);
							$gbd=$kb1*0.000001;
							$totper=$gbd/1*100;
						return ceil($bytes / $kb) . ' KB';

						} elseif (($bytes >= $mb) && ($bytes < $gb)) {
							$mb1=ceil($bytes / $mb);
							// mb convert into gb
							$gbd=$mb1*0.001;
							$totper=$gbd/1*100;
						return ceil($bytes / $mb) . ' MB';

                        } elseif (($bytes >= $gb) && ($bytes < $tb)) {
							$gb1=ceil($bytes / $gb);
							$gbd=$gb1;
							$totper=$gbd/1*100;
					   return ceil($bytes / $gb) . ' GB';

						} elseif ($bytes >= $tb) {

						     return ceil($bytes / $tb) . ' TB';

						} else {

						return $bytes . ' B';
					}
                }
?>
<?php
/**
 * Language Translation System
 * @param string $txt
 * @return string
 */
function lng($txt) {
    global $lang;

    // English Language
    $tr['en']['AppName']        = 'Tiny File Manager';      $tr['en']['AppTitle']           = 'File Manager';
    $tr['en']['Login']          = 'Sign in';                $tr['en']['Username']           = 'Username';
    $tr['en']['Password']       = 'Password';               $tr['en']['Logout']             = 'Sign Out';
    $tr['en']['Move']           = 'Move';                   $tr['en']['Copy']               = 'Copy';
    $tr['en']['Save']           = 'Save';                   $tr['en']['SelectAll']          = 'Select all';
    $tr['en']['UnSelectAll']    = 'Unselect all';           $tr['en']['File']               = 'File';
    $tr['en']['Back']           = 'Back';                   $tr['en']['Size']               = 'Size';
    $tr['en']['Perms']          = 'Perms';                  $tr['en']['Modified']           = 'Modified';
    $tr['en']['Owner']          = 'Owner';                  $tr['en']['Search']             = 'Search';
    $tr['en']['NewItem']        = 'New Item';               $tr['en']['Folder']             = 'Folder';
    $tr['en']['Delete']         = 'Delete';                 $tr['en']['Rename']             = 'Rename';
    $tr['en']['CopyTo']         = 'Copy to';                $tr['en']['DirectLink']         = 'Direct link';
    $tr['en']['UploadingFiles'] = 'Upload Files';           $tr['en']['ChangePermissions']  = 'Change Permissions';
    $tr['en']['Copying']        = 'Copying';                $tr['en']['CreateNewItem']      = 'New Folder';
    $tr['en']['Name']           = 'Name';                   $tr['en']['AdvancedEditor']     = 'Advanced Editor';
    $tr['en']['RememberMe']     = 'Remember Me';            $tr['en']['Actions']            = 'Actions';
    $tr['en']['Upload']         = 'Upload';                 $tr['en']['Cancel']             = 'Cancel';
    $tr['en']['InvertSelection']= 'Invert Selection';       $tr['en']['DestinationFolder']  = 'Destination Folder';
    $tr['en']['ItemType']       = 'Item Type';              $tr['en']['ItemName']           = 'Enter Folder Name';
    $tr['en']['CreateNow']      = 'Create Now';             $tr['en']['Download']           = 'Download';
    $tr['en']['Open']           = 'Open';                   $tr['en']['UnZip']              = 'UnZip';
    $tr['en']['UnZipToFolder']  = 'UnZip to folder';        $tr['en']['Edit']               = 'Edit';
    $tr['en']['NormalEditor']   = 'Normal Editor';          $tr['en']['BackUp']             = 'Back Up';
    $tr['en']['SourceFolder']   = 'Source Folder';          $tr['en']['Files']              = 'Files';
    $tr['en']['Move']           = 'Move';                   $tr['en']['Change']             = 'Change';
    $tr['en']['Settings']       = 'Settings';               $tr['en']['Language']           = 'Language';
    $tr['en']['Folder is empty']    = 'Folder is empty';    $tr['en']['PartitionSize']      = 'Partition size';
    $tr['en']['ErrorReporting'] = 'Error Reporting';        $tr['en']['ShowHiddenFiles']    = 'Show Hidden Files';
    $tr['en']['Full size']      = 'Full size';              $tr['en']['Help']               = 'Help';
    $tr['en']['Free of']        = 'Free of';                $tr['en']['Preview']            = 'Preview';
    $tr['en']['Help Documents'] = 'Help Documents';         $tr['en']['Report Issue']       = 'Report Issue';
    $tr['en']['Generate']       = 'Generate';               $tr['en']['FullSize']           = 'Full Size';
    $tr['en']['FreeOf']         = 'free of';                $tr['en']['CalculateFolderSize']= 'Calculate folder size';
    $tr['en']['ProcessID']      = 'Process ID';             $tr['en']['Created']    = 'Created';
    $tr['en']['HideColumns']    = 'Hide Perms/Owner columns';$tr['en']['You are logged in'] = 'You are logged in';
    $tr['en']['Check Latest Version'] = 'Check Latest Version';$tr['en']['Generate new password hash'] = 'Generate new password hash';
    $tr['en']['Login failed. Invalid username or password'] = 'Login failed. Invalid username or password';
    $tr['en']['password_hash not supported, Upgrade PHP version'] = 'password_hash not supported, Upgrade PHP version';

    // new - novos

    $tr['en']['Advanced Search']    = 'Advanced Search';    $tr['en']['Error while copying from']    = 'Error while copying from';
    $tr['en']['Nothing selected']   = 'Nothing selected';   $tr['en']['Paths must be not equal']    = 'Paths must be not equal';
    $tr['en']['Renamed from']       = 'Renamed from';       $tr['en']['Archive not unpacked']       = 'Archive not unpacked';
    $tr['en']['Deleted']            = 'Deleted';            $tr['en']['Archive not created']        = 'Archive not created';
    $tr['en']['Copied from']        = 'Copied from';        $tr['en']['Permissions changed']        = 'Permissions changed';
    $tr['en']['to']                 = 'to';                 $tr['en']['Saved Successfully']         = 'Saved Successfully';
    $tr['en']['not found!']         = 'not found!';         $tr['en']['File Saved Successfully']    = 'File Saved Successfully';
    $tr['en']['Archive']            = 'Archive';            $tr['en']['Permissions not changed']    = 'Permissions not changed';
    $tr['en']['Select folder']      = 'Select folder';      $tr['en']['Source path not defined']    = 'Source path not defined';
    $tr['en']['already exists']     = 'already exists';     $tr['en']['Error while moving from']    = 'Error while moving from';
    $tr['en']['Create archive?']    = 'Create archive?';    $tr['en']['Invalid file or folder name']    = 'Invalid file or folder name';
    $tr['en']['Archive unpacked']   = 'Archive unpacked';   $tr['en']['File extension is not allowed']  = 'File extension is not allowed';
    $tr['en']['Root path']          = 'Root path';          $tr['en']['Error while renaming from']  = 'Error while renaming from';
    $tr['en']['File not found']     = 'File not found';     $tr['en']['Error while deleting items'] = 'Error while deleting items';
    $tr['en']['Invalid characters in file name']                = 'Invalid characters in file name';
    $tr['en']['FILE EXTENSION HAS NOT SUPPORTED']               = 'FILE EXTENSION HAS NOT SUPPORTED';
    $tr['en']['Selected files and folder deleted']              = 'Selected files and folder deleted';
    $tr['en']['Error while fetching archive info']              = 'Error while fetching archive info';
    $tr['en']['Delete selected files and folders?']             = 'Delete selected files and folders?';
    $tr['en']['Search file in folder and subfolders...']        = 'Search file in folder and subfolders...';
    $tr['en']['Access denied. IP restriction applicable']       = 'Access denied. IP restriction applicable';
    $tr['en']['Invalid characters in file or folder name']      = 'Invalid characters in file or folder name';
    $tr['en']['Operations with archives are not available']     = 'Operations with archives are not available';
    $tr['en']['File or folder with this path already exists']   = 'File or folder with this path already exists';

    $tr['en']['Moved from']                 = 'Moved from';
	$tr['en']['Starred']= 'Starred';
	$tr['en']['Unstarred']='Unstarred';
	$tr['en']['Share']= 'Share';

	$i18n = fm_get_translations($tr);
    $tr = $i18n ? $i18n : $tr;

    if (!strlen($lang)) $lang = 'en';
    if (isset($tr[$lang][$txt])) return fm_enc($tr[$lang][$txt]);
    else if (isset($tr['en'][$txt])) return fm_enc($tr['en'][$txt]);
    else return "$txt";
}
?>
      <script>






 </script>
<script>
	function files_preview_show(div_id){
		$("#files_preview_div"+div_id).show();
		$("#main-table").hide();
		$("#files_preview_foot").hide();
	}

	function files_preview_div_close(div_id){
		$("#files_preview_div"+div_id).hide();
		$("#main-table").show();
		$("#files_preview_foot").show();
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