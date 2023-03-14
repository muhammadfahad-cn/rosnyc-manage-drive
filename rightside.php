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
?>
<div class="card filemanager-sidebar rightside">
   <div class="card-body p-0">
        <?php
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
			 //$directory=$path.'/';
			 $directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']).'/';
			 function folderSize($dir){
				$count_size = 0;
				$count = 0;
				$dir_array = array_slice(scandir($dir),2);
			    foreach($dir_array as $key=>$filename){
				  if($filename!=".." && $filename!="."){
					if($filename!= 'trash' AND $filename!= 'shared'){
					   if(is_dir($dir."/".$filename)){
						  $new_foldersize = foldersize($dir."/".$filename);
						  $count_size = $count_size+ $new_foldersize;
						}else if(is_file($dir."/".$filename)){
						  $count_size = $count_size + filesize($dir."/".$filename);
						  $count++;
						}
					}
				   }
				 }
				return $count_size;
            }
			 $sizedir=sizeFormat(folderSize($directory));
				//echo "<pre>";print_r($sizedir);
				if($sizedir == '0 B'){
				    $sizeshow=0;
				    $sizesPershow=0;
				}
				else {
				  $sizedirArr=explode('_', $sizedir);
				  $sizeshow=$sizedirArr[0];
				     if($sizeshow > 0 ){ $sizeshow=$sizeshow;}
				         $sizesPershow=$sizedirArr[2];
				    if(!empty($sizesPershow) && $sizesPershow > 0 ){ $sizesPershow=$sizesPershow;}
				}
			 ?>
				<div class="storage-head d-flex align-items-center justify-content-between">
					<!--div class="dropdown notification-dropdown active">
						<a href="javascript:;" role="button" id="dropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false"> <i class="fa fa-bell" aria-hidden="true"></i></a>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
							<li><a class="dropdown-item" href="#">Action</a></li>
							<li><a class="dropdown-item" href="#">Another action</a></li>
							<li><a class="dropdown-item" href="#">Something else here</a></li>
						</ul>
					</div-->
					<h5 class="storage-title">Storage</h5>
					<!-- <div class="dropdown profile-dropdown">
						<a class="dropdown-toggle" href="javascript:;" role="button" id="dropdownMenuLinkProfile" data-mdb-toggle="dropdown" aria-expanded="false"> <img src="https://rosnyc.com/admin/images/avtar.png" class="img-fluid"> </a>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenuLinkProfile">
							<li><a class="dropdown-item" href="#">Action</a></li>
							<li><a class="dropdown-item" href="#">Another action</a></li>
							<li><a class="dropdown-item" href="#">Something else here</a></li>
						</ul>
					</div> -->
				</div>
				<div class="text-left" style="position: relative;">
					<div class="apex-charts" id="radial-chart"></div>
                    <p class="text-muted mt-4 text-center font-size-16"><?php echo $sizeshow; ?>  (<?php echo $sizesPershow ?>%) of 1GB used</p>
				    <p class="text-muted mt-4"></p>
				</div>

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

                <div class="storage-wrap">
                    <div class="card shadow-none mb-3">
                        <a href="#"  onclick="getStorage('images')" class="text-body">
                            <div>
                                <div class="storage-item d-flex align-items-center overflow-hidden">
                                    <div class="avatar-xs align-self-center me-3">
                                        <div class="avatar-title d-flex align-items-center">
												<img src="https://rosnyc.com/admin/images/bg-img.png" class="img-fluid" alt="bg image">
                                        </div>
										<div class="skewed"></div>
                                    </div>

                                    <div class="overflow-hidden me-auto">
                                        <h5 class="font-size-16 text-truncate ms-3 mb-0 text-300">Images</h5>
										<?php /**   <p class="text-muted text-truncate mb-0 text-300"><?php echo $sumimagefiles;?> Files</p> **/ ?>
                                    </div>

                                    <div class="ms-2">
                                        <p class="text-primary text-300 font-size-12 m-0"><?php echo $totalimagesize; ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
				<?php
			      function get_list_video($dir)
					{
                    static $arr = Array();
					$supported_format = array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv');

					if (!array_key_exists($dir,$arr)) {
							$arr[$dir] = 0;
					}

					foreach(glob("${dir}/*", GLOB_BRACE) as $fn) {
						if(basename($fn) != 'trash' AND basename($fn) != 'shared'){
						 if (is_dir($fn)) {
									get_list_video($fn);
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
					$a = get_list_video($directory);
					$sumvideofiles=0;
					foreach($a as $k => $v) {
					//print "Number of files in ${k}: ${v} \n";
					$sumvideofiles +=$v;
					}
					?>
				<?php
				 function get_dir_video_size($dir){
					$count_size = 0;
					$count = 0;
					$dir_array = scandir($dir);
					  foreach($dir_array as $key=>$filename){
						  $supported_format = array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv');
						  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
						   if (in_array($ext, $supported_format))
							 {
						  if($filename!= 'trash' AND $filename!= 'shared'){
						   if(is_dir($dir."/".$filename)){
							  $new_foldersize = get_dir_video_size($dir."/".$filename);
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

						$forvideo=get_dir_video_size($directory);

						if($forvideo > 0){
						$forvideoArr=explode('_',  $forvideo);
						    $totalvideosize=sizeFormat($forvideoArr[0]);
						    $totalivideosizeArr=explode('_', $totalvideosize);
						    $totalvideofiles=$forvideoArr[1];
							$totvideosize=$totalivideosizeArr[0];
						}
						else {
							$totvideosize = 0;
							$totalvideofiles = 0;
						}
			           ?>
                        <div class="card shadow-none mb-3">
                         <a href="#" onclick="getStorage('videos')" class="text-body">
                            <div>
                                <div class="storage-item d-flex align-items-center overflow-hidden video-item">
                                    <div class="avatar-xs align-self-center me-3">
                                        <div class="avatar-title rounded bg-transparent text-danger font-size-18 d-flex align-items-center">
										<i class="fa fa-play-circle" aria-hidden="true"></i>
                                        </div>
										<div class="skewed"></div>
                                    </div>

                                    <div class="overflow-hidden me-auto">
                                        <h5 class="font-size-16 text-truncate ms-3 mb-0 text-300">Video</h5>
										<?php /**  <p class="text-muted text-truncate mb-0 text-300"><?php echo $sumvideofiles;?> Files</p> **/ ?>
                                    </div>

                                    <div class="ms-2">
                                        <p class="text-primary text-300 font-size-12 m-0"><?php echo $totvideosize; ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
				<?php
				function get_list_music($dir)
					{
                    static $arr = Array();
					$supported_format = array('wav', 'mp3', 'ogg', 'm4a');

					if (!array_key_exists($dir,$arr)) {
							$arr[$dir] = 0;
					}

					foreach(glob("${dir}/*", GLOB_BRACE) as $fn) {
						if(basename($fn) != 'trash' AND basename($fn) != 'shared'){
						 if (is_dir($fn)) {
									get_list_music($fn);
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
					$a = get_list_music($directory);
					$summusicfiles=0;
					foreach($a as $k => $v) {
					//print "Number of files in ${k}: ${v} \n";
					$summusicfiles +=$v;
					}
					?>
				<?php
				 function get_dir_music_size($dir){
					$count_size = 0;
					$count = 0;
					$dir_array = scandir($dir);
					  foreach($dir_array as $key=>$filename){
						  $supported_format = array('wav', 'mp3', 'ogg', 'm4a');
						  $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
						   if (in_array($ext, $supported_format))
							 {
                           if($filename!= 'trash' AND $filename!= 'shared'){
						   if(is_dir($dir."/".$filename)){
							  $new_foldersize = get_dir_music_size($dir."/".$filename);
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

						$formusic=get_dir_music_size($directory);

						if($formusic > 0){
						$formusicArr=explode('_',  $formusic);
						    $totalmusicsize=sizeFormat($formusicArr[0]);
						    $totalimusicsizeArr=explode('_', $totalmusicsize);
						    $totalmusicfiles=$formusicArr[1];
							$totvideosize=$totalimusicsizeArr[0];
						}
						else {
							$totvideosize = 0;
							$totalmusicfiles = 0;
						}
			        ?>
					<div class="card shadow-none mb-3">
                        <a href="#" onclick="getStorage('music')" class="text-body">
                            <div>
                                <div class="storage-item d-flex align-items-center overflow-hidden music-item">
                                    <div class="avatar-xs align-self-center me-3">
                                        <div class="avatar-title d-flex align-items-center">
                                            <i class="fa fa-music"></i>
                                        </div>
										<div class="skewed"></div>
                                    </div>

                                    <div class="overflow-hidden me-auto">
                                        <h5 class="font-size-16 text-truncate ms-3 mb-0 text-300">Music</h5>
										<?php  /**  <p class="text-muted text-truncate mb-0 text-300"><?php echo $summusicfiles; ?> Files</p> */ ?>
                                    </div>

                                    <div class="ms-2">
                                        <p class="text-primary text-300 font-size-12 m-0"><?php echo $totvideosize; ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

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
		        <div class="card shadow-none mb-3">
                        <a href="#" onclick="getStorage('docs')" class="text-body">
                            <div>
                                <div class="storage-item d-flex align-items-center overflow-hidden document-item">
                                    <div class="avatar-xs align-self-center me-3">
                                        <div class="avatar-title d-flex align-items-center">
										<i class="fa fa-file-text" aria-hidden="true"></i>
                                        </div>
										<div class="skewed"></div>
                                    </div>

                                    <div class="overflow-hidden me-auto">
                                        <h5 class="font-size-16 text-truncate ms-3 mb-0 text-300">Document</h5>
										<?php  /**  <p class="text-muted text-truncate mb-0 text-300"><?php echo $sumdocfiles; ?> Files</p> **/ ?>
                                    </div>

                                    <div class="ms-2">
                                        <p class="text-primary text-300 font-size-12 m-0"><?php echo $totdocsize; ?></p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
	 <script>
		var options = {
        series: [<?php echo $sizesPershow ?>],
        chart: {
            height: 250,
            type: "radialBar",
            sparkline: {
                enabled: !0
            }
        },
        colors: ["#556ee6"],
        plotOptions: {
            radialBar: {
                startAngle: -90,
                endAngle: 90,
                track: {
                    background: "#e7e7e7",
                    strokeWidth: "97%",
                    margin: 5
                },
                hollow: {
                    size: "60%"
                },
                dataLabels: {
                    name: {
                        show: !1
                    },
                    value: {
                        offsetY: -2,
                        fontSize: "16px"
                    }
                }
            }
        },
        grid: {
            padding: {
                top: -10
            }
        },
        stroke: {
            dashArray: 3
        },
        labels: ["Storage"]
    },
    chart = new ApexCharts(document.querySelector("#radial-chart"), options);
    chart.render();

		</script>
		<script src="https://themesbrand.com/skote-django/layouts/assets/libs/apexcharts/apexcharts.min.js"></script>

                    <!--<div class="card border shadow-none">
                        <a href="javascript: void(0);" class="text-body">
                            <div class="p-2">
                                <div class="d-flex">
                                    <div class="avatar-xs align-self-center me-3">
                                        <div class="avatar-title rounded bg-transparent text-warning font-size-16">
                                            <i class="fa fa-folder"></i>
                                        </div>
                                    </div>

                                    <div class="overflow-hidden me-auto">
                                        <h5 class="font-size-16 text-truncate ms-3 mb-0 text-300">Others</h5>
                                        <p class="text-muted text-truncate mb-0 text-300">20 Files</p>
                                    </div>

                                    <div class="ms-2">
                                        <p class="text-primary text-300 font-size-12 m-0">1.4 GB</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>-->
				    <!--<div class="file-upload mt-3 mt-sm-5">
					   <input type="hidden" id="fileuploader-list-files" name="fileuploader-list-files" value="">
					   <input type="file" id="drivefile" name="files">
					</div>-->
					<!--<input type="file" id="drivefile" name="files">-->
					<style>
						#the-progress-div-right{
						color:#000!important;background-color:#9e9e9e!important;
						width:100%; height:20px; text-align:center; border-radius:50px;
						}

					.dz-rightside {
						display: block;
						padding: 55px 0 !important;
						border: 2px dashed #c2cdda !important;
						border-radius: 14px;
						text-align: center;
						}

					.dz-rightside h3 {
						margin: 0;
						margin-bottom: 8px;
						padding: 0;
						background: none;
						border: none;
						border-radius: 0;
						font-size: 18px;
						font-weight: bold;
						color: #5B5B7B;
						white-space: normal;
						box-shadow: none;
					}

					.dz-rightside p{
						margin: 0;
						padding: 0;
						color: #90a0bc;
						margin-bottom: 12px;
						font-family: Roboto,"Segoe UI","Helvetica Neue",Arial,sans-serif;
						font-weight: 400;
						font-size: 14px;
						line-height: normal;
						}
				    .dz-rightside button {
						line-height: initial;
						background: #1e156c;
						user-select: none;
						background-size: 140% auto;
						background-position: center;
						color: #fff;
						box-shadow: 0 4px 18px rgb(0 0 0 / 4%);
						display: inline-block;
						margin: 0;
						padding: 14px 22px;
						border: none;
						border-radius: 30px;
						outline: 0;
						font-weight: 700;
						font-size: 14px;
						cursor: pointer;
						box-shadow: 0 4px 18px rgb(0 0 0 / 4%);
						}
						.dz-rightside .fileuploader-input-button:hover {
						box-shadow: 0 8px 25px rgb(0 0 0 / 15%);
						transform: translateY(-2px);
						}

						.dz-preview, .dz-file-preview {
						display: none;
						}
					</style>
					<div class=" mt-3 mt-sm-5">
					 <form action="<?php echo "https://".$http_host. '/admin/filemanager/ajax_upload_recent_file.php?p=' . fm_enc(FM_PATH) ?>" class="dropzone card-tabs-container dz-custom dz-rightside file-upload" id="fileUploader4" enctype="multipart/form-data">
							<input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                            <input type="hidden" name="fullpath3" id="fullpath3" value="<?php echo fm_enc(FM_PATH) ?>">
							<input type="hidden" name="FM_ROOT_PATH" id="FM_ROOT_PATH" value="<?php echo FM_ROOT_PATH ?>">
							<input type="hidden" name="FM_PATH" id="FM_PATH" value="<?php echo FM_PATH ?>">
							<div class="fallback">
                              <input type="file" name="file" id="drivefile"  multiple/>
							</div>
			         </form>
				   </div>
				   <p></p>
				   <div class="progress-right" style="height:15px; border-radius: 50px; display:none;">
			          <div class="progress-bar bg-success" role="progressbar" style="width: 0%; border-radius: 50px;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
			       </div>
			<script>
		    Dropzone.options.fileUploader4 = {
            chunking: true,
            chunkSize: 10000000,
            forceChunking: true,
            retryChunks: true,
            retryChunksLimit: 3,
			clickable: true,
            parallelUploads: 1000, // does not support more than 1!
            timeout: 3600000,
			dictDefaultMessage: "<h3 class='fileuploader-input-caption'><span>Drag and drop files here</span></h3><p> or </p><button type='button' class='fileuploader-input-button'><span>Browse files</span></button>",

			totaluploadprogress: function(progress) {
			console.log("progress==>"+progress);
			   $(".progress-right").show();
			   $(".progress-bar").width(progress + '%');
			   $(".progress-bar").text(progress.toFixed(2) + '%');
			},
			maxFilesize: 10000000000,
            acceptedFiles : "",
            init: function () {

				this.on("sending", function (file, xhr, formData) {

				    let _path3 = (file.fullPath) ? file.fullPath : file.name;
					// Code For Upload Folder only
                    document.getElementById("fullpath3").value =_path3;

					xhr.ontimeout = (function() {
                        toast('Error: Server Timeout');
                    });
                }).on("success", function (res, responseText) {
					var jsonResponse = JSON.parse(res.xhr.response);

					  if(res.status == 'success'){

					  $("#toast-message").html('Upload Successfully');
					  $("#basic-primary-trigger").trigger('click');

					   // Path Return Back to Same Foder Path after Drop Folder
					   let _pfolderpath= jsonResponse.p;

					   console.log("_pfolderpath===>"+_pfolderpath);

					   $(".progress-right").hide();
					     var scroll = $('.file-scroll').scrollTop();
					   if(_pfolderpath != ''){
						$("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+_pfolderpath,function(){
		                      Dropzone.discover();
						   $('.file-scroll').scrollTop(scroll);
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
		</script>
		<script>
		   $('#upload_item').on('click', function() {
		       $(".fileuploader-input-button").get(0).click();
		   });
		</script>
	    <p></p>
					<!--<div id="upload_refresh_success" style="display:none;">
					     <a title="Reset this Document" href="javascript:void()" onclick='location.reload(true); return false;'>To see your uploaded images please refresh now</a></div>
					<style>
					  .fileuploader-action-success i:before {
                        content: "\2713 !important";
					   }
					</style>

					<script src="https://rosnyc.com/admin/filemanager/jquery.fileuploader.min.js"></script>
					<script type="text/javascript">
						$(document).ready(function() {
							let FM_ROOT_PATH='<?php echo FM_ROOT_PATH ?>';
							let FM_PATH ='<?php echo FM_PATH ?>'
							// enable fileuploader plugin
							$('#drivefile').fileuploader({
							  changeInput: '<div class="fileuploader-input">' +
										  '<div class="fileuploader-input-inner">' +
											  '<div class="fileuploader-icon-main"></div>' +
											  '<h3 class="fileuploader-input-caption"><span>${captions.feedback}</span></h3>' +
											  '<p>${captions.or}</p>' +
											  '<button type="button" class="fileuploader-input-button"><span>${captions.button}</span></button>' +
										  '</div>' +
									    '</div>',
						        theme: 'dragdrop',
								upload: {
						            url: 'https://rosnyc.com/admin/filemanager/ajax_upload_file.php',
						            data: { FM_ROOT_PATH:FM_ROOT_PATH, FM_PATH:FM_PATH },
						            type: 'POST',
						            enctype: 'multipart/form-data',
						            start: true,
						            synchron: true,
						            beforeSend: null,
						            onSuccess: function(result, item) {
										$('#home_storage_data').hide();
									    var jsonResponse=JSON.parse(result);
										item.name = result;
											console.log(jsonResponse.image_name);
											item.html.find('.column-title > div:first-child').text(jsonResponse.image_name).attr('title', result);

										item.html.find('.fileuploader-action-remove').addClass('fileuploader-action-success');

										// Path Return Back to Same Foder Path after Drop Folder
										let _pfolderpath= jsonResponse.p;

										if(_pfolderpath != ''){
										  $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+_pfolderpath,function(){
										   Dropzone.discover();
										   })
										}
										else {
                                         $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
										    Dropzone.discover();
										  })
										}

										var data = {};
										// get data
										if (result && result.files)
						                    data = result;
						                else
											data.hasWarnings = true;

										// if success

						                /*if (data.isSuccess && data.files[0]) {
						                    item.name = data.files[0].name;
											console.log(data.files[0].name);
											item.html.find('.column-title > div:first-child').text(data.files[0].name).attr('title', data.files[0].name);
						                }*/

										// if warnings
										if (data.hasWarnings) {
											for (var warning in data.warnings) {
												alert(data.warnings[warning]);
											}

											item.html.removeClass('upload-successful').addClass('upload-failed');
											// go out from success function by calling onError function
											// in this case we have a animation there
											// you can also response in PHP with 404
											return this.onError ? this.onError(item) : null;
										}
						                setTimeout(function() {
						                    item.html.find('.progress-bar2').fadeOut(400);
						                }, 400);


						            },
						            onError: function(item) {
										var progressBar = item.html.find('.progress-bar2');

										if(progressBar.length) {
											progressBar.find('span').html(0 + "%");
						                    progressBar.find('.fileuploader-progressbar .bar').width(0 + "%");
											item.html.find('.progress-bar2').fadeOut(400);
										}

						                item.upload.status != 'cancelled' && item.html.find('.fileuploader-action-retry').length == 0 ? item.html.find('.column-actions').prepend(
						                    '<button type="button" class="fileuploader-action fileuploader-action-retry" title="Retry"><i class="fileuploader-icon-retry"></i></button>'
						                ) : null;
						            },
						            onProgress: function(data, item) {
						                var progressBar = item.html.find('.progress-bar2');

						                if(progressBar.length > 0) {
						                    progressBar.show();
						                    progressBar.find('span').html(data.percentage + "%");
						                    progressBar.find('.fileuploader-progressbar .bar').width(data.percentage + "%");
						                }
						            },
						            onComplete: null,
						        },
								onRemove: function(item) {
									alert(item.name);
									let remove_image=1;
									$.post('https://rosnyc.com/admin/filemanager/ajax_remove_file.php', {
										file: item.name,
										FM_ROOT_PATH:FM_ROOT_PATH,
										FM_PATH:FM_PATH
									});
								},
								captions: $.extend(true, {}, $.fn.fileuploader.languages['en'], {
						            feedback: 'Drag and drop files here',
						            feedback2: 'Drag and drop files here',
						            drop: 'Drag and drop files here',
						            or: 'or',
						            button: 'Browse files',
									folderUpload: 'Folders are not allowed.',
						        }),
							});
						});
					</script>-->
				</div>
            </div>
        </div>
<?php
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
?>


