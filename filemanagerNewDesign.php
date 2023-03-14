<script>
    // Body class for dark theme
    // document.body.className += " dark-theme dark-filemanager";
</script>
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

// Create Directory from the Email ID
	//echo "<pre>";
	//print_r($_SESSION);
	//echo "uemail===>".$str=base64_encode($_SESSION['userEmail']);
	//echo "<br>";
	//$str = strtr(base64_encode($str), '+/=', '._-');
	//echo $str;
	//exit();

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

// Insert folder name into the DB
    $userpath=$con->query("SELECT folder_path from users WHERE userId=".$_SESSION['userId']."");
	$userfolderdata=mysqli_fetch_assoc($result);

	if(empty($userfolderdata['folder_path'])){

		$userpathset=$con->query("UPDATE users SET folder_path='".$newpath."' WHERE userId=".$_SESSION['userId']."");
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

// logout
if (isset($_GET['logout'])) {
    unset($_SESSION[FM_SESSION_ID]['logged']);
    fm_redirect(FM_SELF_URL);
}

// Validate connection IP
if ($ip_ruleset != 'OFF') {
    function getClientIP() {
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            return  $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        return '';
    }

    $clientIp = getClientIP();

    $proceed = false;

    $whitelisted = in_array($clientIp, $ip_whitelist);
    $blacklisted = in_array($clientIp, $ip_blacklist);

    if($ip_ruleset == 'AND'){
        if($whitelisted == true && $blacklisted == false){
            $proceed = true;
        }
    } else
    if($ip_ruleset == 'OR'){
         if($whitelisted == true || $blacklisted == false){
            $proceed = true;
        }
    }

    if($proceed == false){
        trigger_error('User connection denied from: ' . $clientIp, E_USER_WARNING);

        if($ip_silent == false){
            fm_set_msg(lng('Access denied. IP restriction applicable'), 'error');
            fm_show_header_login();
            fm_show_message();
        }

        exit();
    }
}

// Auth
if ($use_auth) {
    if (isset($_SESSION[FM_SESSION_ID]['logged'], $auth_users[$_SESSION[FM_SESSION_ID]['logged']])) {
        // Logged
    } elseif (isset($_POST['fm_usr'], $_POST['fm_pwd'])) {
        // Logging In
        sleep(1);
        if(function_exists('password_verify')) {
            if (isset($auth_users[$_POST['fm_usr']]) && isset($_POST['fm_pwd']) && password_verify($_POST['fm_pwd'], $auth_users[$_POST['fm_usr']])) {
                $_SESSION[FM_SESSION_ID]['logged'] = $_POST['fm_usr'];
                fm_set_msg(lng('You are logged in'));
                fm_redirect(FM_SELF_URL . '?action=filemanager&p=');
            } else {
                unset($_SESSION[FM_SESSION_ID]['logged']);
                fm_set_msg(lng('Login failed. Invalid username or password'), 'error');
                fm_redirect(FM_SELF_URL);
            }
        } else {
            fm_set_msg(lng('password_hash not supported, Upgrade PHP version'), 'error');;
        }
    } else {
        // Form
        unset($_SESSION[FM_SESSION_ID]['logged']);
        fm_show_header_login();
        ?>
        <section class="h-100">
            <div class="container h-100">
                <div class="row justify-content-md-center h-100">
                    <div class="card-wrapper">
                        <div class="card fat <?php echo fm_get_theme(); ?>">
                            <div class="card-body">
                                <form class="form-signin" action="" method="post" autocomplete="off">
                                    <div class="form-group">
                                       <div class="brand">
                                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" M1008 width="100%" height="80px" viewBox="0 0 238.000000 140.000000" aria-label="H3K Tiny File Manager">
                                                <g transform="translate(0.000000,140.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                                    <path d="M160 700 l0 -600 110 0 110 0 0 260 0 260 70 0 70 0 0 -260 0 -260 110 0 110 0 0 600 0 600 -110 0 -110 0 0 -260 0 -260 -70 0 -70 0 0 260 0 260 -110 0 -110 0 0 -600z"/>
                                                    <path fill="#003500" d="M1008 1227 l-108 -72 0 -117 0 -118 110 0 110 0 0 110 0 110 70 0 70 0 0 -180 0 -180 -125 0 c-69 0 -125 -3 -125 -6 0 -3 23 -39 52 -80 l52 -74 73 0 73 0 0 -185 0 -185 -70 0 -70 0 0 115 0 115 -110 0 -110 0 0 -190 0 -190 181 0 181 0 109 73 108 72 1 181 0 181 -69 48 -68 49 68 50 69 49 0 249 0 248 -182 -1 -183 0 -107 -72z"/>
                                                    <path d="M1640 700 l0 -600 110 0 110 0 0 208 0 208 35 34 35 34 35 -34 35 -34 0 -208 0 -208 110 0 110 0 0 212 0 213 -87 87 -88 88 88 88 87 87 0 213 0 212 -110 0 -110 0 0 -208 0 -208 -70 -69 -70 -69 0 277 0 277 -110 0 -110 0 0 -600z"/></g>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <h1 class="card-title"><?php echo APP_TITLE; ?></h1>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="form-group">
                                        <label for="fm_usr"><?php echo lng('Username'); ?></label>
                                        <input type="text" class="form-control" id="fm_usr" name="fm_usr" required autofocus>
                                    </div>

                                    <div class="form-group">
                                        <label for="fm_pwd"><?php echo lng('Password'); ?></label>
                                        <input type="password" class="form-control" id="fm_pwd" name="fm_pwd" required>
                                    </div>

                                    <div class="form-group">
                                        <?php fm_show_message(); ?>
                                    </div>

                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-block mt-4" role="button">
                                            <?php echo lng('Login'); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="footer text-center">
                            &mdash;&mdash; &copy;
                            <a href="https://tinyfilemanager.github.io/" target="_blank" class="text-muted" data-version="<?php echo VERSION; ?>">CCP Programmers</a> &mdash;&mdash;
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
        fm_show_footer_login();
        exit;
    }
}

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

/*************************** ACTIONS ***************************/

// AJAX Request
if (isset($_POST['ajax']) && !FM_READONLY) {

    // save
    if (isset($_POST['type']) && $_POST['type'] == "save") {
        // get current path
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        // check path
        if (!is_dir($path)) {
            fm_redirect(FM_SELF_URL . '?action=filemanager&p=');
        }
        $file = $_GET['edit'];
        $file = fm_clean_path($file);
        $file = str_replace('/', '', $file);
        if ($file == '' || !is_file($path . '/' . $file)) {
            fm_set_msg(lng('File not found'), 'error');
            fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
        }
        header('X-XSS-Protection:0');
        $file_path = $path . '/' . $file;

        $writedata = $_POST['content'];
        $fd = fopen($file_path, "w");
        $write_results = @fwrite($fd, $writedata);
        fclose($fd);
        if ($write_results === false){
            header("HTTP/1.1 500 Internal Server Error");
            die("Could Not Write File! - Check Permissions / Ownership");
        }
        die(true);
    }

    //search : get list of files from the current folder
    if(isset($_POST['type']) && $_POST['type']=="search") {
        $dir = FM_ROOT_PATH;
        $response = scan(fm_clean_path($_POST['path']), $_POST['content']);
        echo json_encode($response);
        exit();
    }

    // backup files
    if (isset($_POST['type']) && $_POST['type'] == "backup" && !empty($_POST['file'])) {
        $fileName = $_POST['file'];
        $fullPath = FM_ROOT_PATH . '/';
        if (!empty($_POST['path'])) {
            $relativeDirPath = fm_clean_path($_POST['path']);
            $fullPath .= "{$relativeDirPath}/";
        }
        $date = date("dMy-His");
        $newFileName = "{$fileName}-{$date}.bak";
        $fullyQualifiedFileName = $fullPath . $fileName;
        try {
            if (!file_exists($fullyQualifiedFileName)) {
                throw new Exception("File {$fileName} not found");
            }
            if (copy($fullyQualifiedFileName, $fullPath . $newFileName)) {
                echo "Backup {$newFileName} created";
            } else {
                throw new Exception("Could not copy file {$fileName}");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // Save Config
    if (isset($_POST['type']) && $_POST['type'] == "settings") {
        global $cfg, $lang, $report_errors, $show_hidden_files, $lang_list, $hide_Cols, $calc_folder, $theme;
        $newLng = $_POST['js-language'];
        fm_get_translations([]);
        if (!array_key_exists($newLng, $lang_list)) {
            $newLng = 'en';
        }

        $erp = isset($_POST['js-error-report']) && $_POST['js-error-report'] == "true" ? true : false;
        $shf = isset($_POST['js-show-hidden']) && $_POST['js-show-hidden'] == "true" ? true : false;
        $hco = isset($_POST['js-hide-cols']) && $_POST['js-hide-cols'] == "true" ? true : false;
        $caf = isset($_POST['js-calc-folder']) && $_POST['js-calc-folder'] == "true" ? true : false;
        $te3 = $_POST['js-theme-3'];

        if ($cfg->data['lang'] != $newLng) {
            $cfg->data['lang'] = $newLng;
            $lang = $newLng;
        }
        if ($cfg->data['error_reporting'] != $erp) {
            $cfg->data['error_reporting'] = $erp;
            $report_errors = $erp;
        }
        if ($cfg->data['show_hidden'] != $shf) {
            $cfg->data['show_hidden'] = $shf;
            $show_hidden_files = $shf;
        }
        if ($cfg->data['show_hidden'] != $shf) {
            $cfg->data['show_hidden'] = $shf;
            $show_hidden_files = $shf;
        }
        if ($cfg->data['hide_Cols'] != $hco) {
            $cfg->data['hide_Cols'] = $hco;
            $hide_Cols = $hco;
        }
        if ($cfg->data['calc_folder'] != $caf) {
            $cfg->data['calc_folder'] = $caf;
            $calc_folder = $caf;
        }
        if ($cfg->data['theme'] != $te3) {
            $cfg->data['theme'] = $te3;
            $theme = $te3;
        }
        $cfg->save();
        echo true;
    }

    // new password hash
    if (isset($_POST['type']) && $_POST['type'] == "pwdhash") {
        $res = isset($_POST['inputPassword2']) && !empty($_POST['inputPassword2']) ? password_hash($_POST['inputPassword2'], PASSWORD_DEFAULT) : '';
        echo $res;
    }

    //upload using url
    if(isset($_POST['type']) && $_POST['type'] == "upload" && !empty($_REQUEST["uploadurl"])) {
        $path = FM_ROOT_PATH;


		if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }



         function event_callback ($message) {
            global $callback;
            echo json_encode($message);
        }

        function get_file_path () {
            global $path, $fileinfo, $temp_file;
			$ext = strtolower(pathinfo($fileinfo->name, PATHINFO_EXTENSION));

			 $temp= explode('.',$fileinfo->name);
             $original_file_name=$temp[0];
               $increment = 0;
			   $pname = $original_file_name.'.'.$ext;
			      while(is_file($path.'/'.$pname)) {
			   $increment++;
			   $pname = $original_file_name.'('.$increment.')'.'.'.$ext;
			}
			return $path.$pname;
		}

        $url = !empty($_REQUEST["uploadurl"]) && preg_match("|^http(s)?://.+$|", stripslashes($_REQUEST["uploadurl"])) ? stripslashes($_REQUEST["uploadurl"]) : null;

        //prevent 127.* domain and known ports
        $domain = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $knownPorts = [22, 23, 25, 3306];

        if (preg_match("/^localhost$|^127(?:\.[0-9]+){0,2}\.[0-9]+$|^(?:0*\:)*?:?0*1$/i", $domain) || in_array($port, $knownPorts)) {
            $err = array("message" => "URL is not allowed");
            event_callback(array("fail" => $err));
            exit();
        }

        $use_curl = false;
        $temp_file = tempnam(sys_get_temp_dir(), "upload-");
        $fileinfo = new stdClass();
        $fileinfo->name = trim(basename($url), ".\x00..\x20");

        $allowed = (FM_UPLOAD_EXTENSION) ? explode(',', FM_UPLOAD_EXTENSION) : false;
        $ext = strtolower(pathinfo($fileinfo->name, PATHINFO_EXTENSION));
        $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

        $err = false;

        if(!$isFileAllowed) {
            $err = array("message" => "File extension is not allowed");
            event_callback(array("fail" => $err));
            exit();
        }

        if (!$url) {
            $success = false;
        } else if ($use_curl) {
            @$fp = fopen($temp_file, "w");
            @$ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOPROGRESS, false );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            @$success = curl_exec($ch);
            $curl_info = curl_getinfo($ch);
            if (!$success) {
                $err = array("message" => curl_error($ch));
            }
            @curl_close($ch);
            fclose($fp);
            $fileinfo->size = $curl_info["size_download"];
            $fileinfo->type = $curl_info["content_type"];
        } else {
            $ctx = stream_context_create();
            @$success = copy($url, $temp_file, $ctx);
            if (!$success) {
                $err = error_get_last();
            }
        }

        if ($success) {
			global $path, $fileinfo, $temp_file;
			  $ext = strtolower(pathinfo($fileinfo->name, PATHINFO_EXTENSION));
			  $temp= explode('.',$fileinfo->name);
              $original_file_name=$temp[0];

			   $increment = 0;
			   $pname = $original_file_name.'.'.$ext;
			      while(is_file($path.'/'.$pname)) {
			        $increment++;
			        $pname = $original_file_name.' '.'('.$increment.')'.'.'.$ext;
			    }
			$success = rename($temp_file, $path.'/'.$pname);
			chmod($path.'/'.$pname, 0644);
        }

        if ($success) {
            event_callback(array("done" => $fileinfo));
        } else {
            unlink($temp_file);
            if (!$err) {
                $err = array("message" => "Invalid url parameter");
            }
            event_callback(array("fail" => $err));
        }
    }

  exit();
}

// Delete file / folder
// if (isset($_GET['del']) && !FM_READONLY) {
//     $del = str_replace( '/', '', fm_clean_path( $_GET['del'] ) );
//     if ($del != '' && $del != '..' && $del != '.') {
//         $path = FM_ROOT_PATH;
//         if (FM_PATH != '') {
//             $path .= '/' . FM_PATH;
//         }
//         $is_dir = is_dir($path . '/' . $del);
//         if (fm_rdelete($path . '/' . $del)) {
//             $msg = $is_dir ? lng('Folder').' <b>%s</b> '.lng('Deleted') : lng('File').' <b>%s</b> '.lng('Deleted');
//             fm_set_msg(sprintf($msg, fm_enc($del)));
//         } else {
//             $msg = $is_dir ? lng('Folder').' <b>%s</b> '.lng('not deleted') : lng('File').' <b>%s</b> '.lng('not deleted');
//             fm_set_msg(sprintf($msg, fm_enc($del)), 'error');
//         }
//     } else {
//         fm_set_msg(lng('Invalid file or folder name'), 'error');
//     }
//     fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
// }

// Create folder
if (isset($_GET['new']) && isset($_GET['type']) && !FM_READONLY) {
    $type = $_GET['type'];
    $new = str_replace( '/', '', fm_clean_path( strip_tags( $_GET['new'] ) ) );
    if (fm_isvalid_filename($new) && $new != '' && $new != '..' && $new != '.') {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        if ($_GET['type'] == "file") {
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
                fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('Created'), $new));
            } elseif (fm_mkdir($path . '/' . $new, false) === $path . '/' . $new) {
                fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('already exists'), fm_enc($new)), 'alert');
            } else {
                fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('not created'), fm_enc($new)), 'error');
            }
        }
    } else {
        fm_set_msg(lng('Invalid characters in file or folder name'), 'error');
    }
    fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}
?>
   <div id="mydiv">
    <!--<iframe id="frame" src="" width="100%" height="300">
    </iframe>-->
    <div class="modal fade" id="modalfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-lg" role="document">
    <!--Content-->
    <div class="modal-content">
      <!--Body-->
      <div class="modal-body mb-0 p-0">
        <div class="embed-responsive embed-responsive-16by9 z-depth-1-half">
		 <script>
		  $(document).ready(function () {
			$('iframe').on('load', function() {
				    $("iframe").contents().find("content-wrapper").addClass("iframeclass");
					$("iframe").contents().find(".left-sidebar").css("display", "none");
					$("iframe").contents().find("nav#ml-menu").css("display", "none");
					$("iframe").contents().find("content-wrapper").css("width", "100%");
					$("iframe").contents().find("content-wrapper").css("padding", "1em");
					$("iframe").contents().find("content-wrapper").css("margin-left", "0px !important");
			    });
			    $("#ifram-close").on('click', function(){
			      $( '#iframe-id-file' ).attr( 'src', function ( i, val ) { return val; });
				   $('#modalfile').modal('hide');
				  //$("#modalfile .close").click();
				});
		      });
	     </script>
		 <iframe class="embed-responsive-item" id="iframe-id-file" src="" allowfullscreen></iframe>
        </div>
      </div>
      <!--Footer-->
      <div class="modal-footer justify-content-center">
       <button type="button" class="btn btn-outline-primary btn-rounded btn-md ml-4" id="ifram-close" data-dismiss="modal">Close</button>
      </div>
	 </div>
    <!--/.Content-->
   </div>
</div>
</div>
<?php
// Copy folder / file
if (isset($_GET['copy'], $_GET['finish']) && !FM_READONLY) {
    // from
    $copy = $_GET['copy'];
    $copy = fm_clean_path($copy);

	// empty path
    if ($copy == '') {
        fm_set_msg(lng('Source path not defined'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }
    // abs path from
    $from = FM_ROOT_PATH . '/' . $copy;
    // abs path to
    $dest = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $dest .= '/' . FM_PATH;
    }
    $dest .= '/' . basename($from);
    // move?
    $move = isset($_GET['move']);
    // copy/move/duplicate
    if ($from != $dest) {
        $msg_from = trim(FM_PATH . '/' . basename($from), '/');
        if ($move) { // Move and to != from so just perform move
            $rename = fm_rename($from, $dest);
            if ($rename) {
                fm_set_msg(sprintf(lng('Moved from').' <b>%s</b> '.lng('to').' <b>%s</b>', fm_enc($copy), fm_enc($msg_from)));
            } elseif ($rename === null) {
                fm_set_msg(lng('File or folder with this path already exists'), 'alert');
            } else {
                fm_set_msg(sprintf(lng('Error while moving from').' <b>%s</b> '.lng('to').' <b>%s</b>', fm_enc($copy), fm_enc($msg_from)), 'error');
            }
        } else { // Not move and to != from so copy with original name
            if (fm_rcopy($from, $dest)) {
                fm_set_msg(sprintf(lng('Copied from').' <b>%s</b> '.lng('to').' <b>%s</b>', fm_enc($copy), fm_enc($msg_from)));
            } else {
                fm_set_msg(sprintf(lng('Error while copying from').' <b>%s</b> '.lng('to').' <b>%s</b>', fm_enc($copy), fm_enc($msg_from)), 'error');
            }
        }
    } else {
       if (!$move){ //Not move and to = from so duplicate
            $msg_from = trim(FM_PATH . '/' . basename($from), '/');
            $fn_parts = pathinfo($from);
            $extension_suffix = '';
            if(!is_dir($from)){
               $extension_suffix = '.'.$fn_parts['extension'];
            }
            //Create new name for duplicate
            $fn_duplicate = $fn_parts['dirname'].'/'.$fn_parts['filename'].'-'.date('YmdHis').$extension_suffix;
            $loop_count = 0;
            $max_loop = 1000;
            // Check if a file with the duplicate name already exists, if so, make new name (edge case...)
            while(file_exists($fn_duplicate) & $loop_count < $max_loop){
               $fn_parts = pathinfo($fn_duplicate);
               $fn_duplicate = $fn_parts['dirname'].'/'.$fn_parts['filename'].'-copy'.$extension_suffix;
               $loop_count++;
            }
            if (fm_rcopy($from, $fn_duplicate, False)) {
                fm_set_msg(sprintf('Copyied from <b>%s</b> to <b>%s</b>', fm_enc($copy), fm_enc($fn_duplicate)));
            } else {
                fm_set_msg(sprintf('Error while copying from <b>%s</b> to <b>%s</b>', fm_enc($copy), fm_enc($fn_duplicate)), 'error');
            }
       }
       else{
           fm_set_msg(lng('Paths must be not equal'), 'alert');
       }
    }

	echo "<span class='copy_success' style='text-align:center;width: 100%;
    color: green;
    float: left;
    position: relative;
    font-size: 20px !important;
    margin-top: 15px;'>Successfull Copyied</span>";
	?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<script>
	 $(document).ready(function () {

		  parent.$('#modalfile').modal('hide');
		  $("#ifram-close").trigger('click');
		  $('#iframe-id-file', window.parent.document).remove();
	});
	</script>
  <?php
	exit();
    //fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}
?>

<?php
// Mass copy files/ folders
if (isset($_POST['file'], $_POST['copy_to'], $_POST['finish']) && !FM_READONLY) {
    // from
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // to
    $copy_to_path = FM_ROOT_PATH;
    $copy_to = fm_clean_path($_POST['copy_to']);
    if ($copy_to != '') {
        $copy_to_path .= '/' . $copy_to;
    }
    if ($path == $copy_to_path) {
        fm_set_msg(lng('Paths must be not equal'), 'alert');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }
    if (!is_dir($copy_to_path)) {
        if (!fm_mkdir($copy_to_path, true)) {
            fm_set_msg('Unable to create destination folder', 'error');
            fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
        }
    }
    // move?
    $move = isset($_POST['move']);
    // copy/move
    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                // abs path from
                $from = $path . '/' . $f;
                // abs path to
                $dest = $copy_to_path . '/' . $f;
                // do
                if ($move) {
                    $rename = fm_rename($from, $dest);
                    if ($rename === false) {
                        $errors++;
                    }
                } else {
                    if (!fm_rcopy($from, $dest)) {
                        $errors++;
                    }
                }
            }
        }
        if ($errors == 0) {
            $msg = $move ? 'Selected files and folders moved' : 'Selected files and folders copied';
            fm_set_msg($msg);
        } else {
            $msg = $move ? 'Error while moving items' : 'Error while copying items';
            fm_set_msg($msg, 'error');
        }
    } else {
        fm_set_msg(lng('Nothing selected'), 'alert');
    }
    fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}

// Rename
if (isset($_GET['ren'], $_GET['to']) && !FM_READONLY) {
    // old name
    $old = $_GET['ren'];
    $old = fm_clean_path($old);
    $old = str_replace('/', '', $old);
    // new name
    $new = $_GET['to'];
    $new = fm_clean_path(strip_tags($new));
    $new = str_replace('/', '', $new);
    // path
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // rename
    if (fm_isvalid_filename($new) && $old != '' && $new != '') {
        if (fm_rename($path . '/' . $old, $path . '/' . $new)) {
            fm_set_msg(sprintf(lng('Renamed from').' <b>%s</b> '. lng('to').' <b>%s</b>', fm_enc($old), fm_enc($new)));
        } else {
            fm_set_msg(sprintf(lng('Error while renaming from').' <b>%s</b> '. lng('to').' <b>%s</b>', fm_enc($old), fm_enc($new)), 'error');
        }
    } else {
        fm_set_msg(lng('Invalid characters in file name'), 'error');
    }
    fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}
if (isset($_GET['shareddl'])) {
	$shared=$_GET['shared'];
    $dl = $_GET['shareddl'];
    $dl = fm_clean_path($dl);
    $dl = str_replace('/', '', $dl);
	$FM_DOWNLOAD_PATH=$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.$shared.'/';
    $path = $FM_DOWNLOAD_PATH;

    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
	//$path. $dl;
	//exit();
	if ($dl != '' && is_file($path. $dl)) {
		fm_download_file($path.$dl, $dl, 1024);
    } else {
        fm_set_msg(lng('File not found'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }
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

// Upload
if (!empty($_FILES) && !FM_READONLY) {
    $override_file_name = false;
    $chunkIndex = $_POST['dzchunkindex'];
    //$chunkTotal = $_POST['dztotalchunkcount'];
    //echo "Fullpath=>".$_REQUEST['fullpath'];
    $f = $_FILES;
    $path = FM_ROOT_PATH;
    $ds = DIRECTORY_SEPARATOR;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $uploads = 0;
    $allowed = (FM_UPLOAD_EXTENSION) ? explode(',', FM_UPLOAD_EXTENSION) : false;
    $response = array (
        'status' => 'error',
        'info'   => 'Oops! Try again'
    );

	 $filename = $f['file']['name'];
	 $tmp_name = $f['file']['tmp_name'];
	 $file_size= $f['file']['size']; // size in bytes
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

    if(!fm_isvalid_filename($filename) && !fm_isvalid_filename($_REQUEST['fullpath'])) {
        $response = array (
            'status'    => 'error',
            'info'      => "Invalid File name!",
        );
        echo json_encode($response); exit();
    }

	$targetPath = $path.'/';
	if ( is_writable($targetPath) ) {
		$fullPath = $path . '/' . basename($_REQUEST['fullpath']);
        $folder = substr($fullPath, 0, strrpos($fullPath, "/"));

		if(file_exists ($fullPath) && !$override_file_name && !$chunks) {
            $ext_1 = $ext ? '.'.$ext : '';
            $fullPath = $path . '/' . basename($_REQUEST['fullpath'], $ext_1) .'_'. date('ymdHis'). $ext_1;
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
			$fullPath=$path.'/'.$pname;

        if (!is_dir($folder)) {
            $old = umask(0);
            mkdir($folder, 0777, true);
            umask($old);
        }

       if (empty($f['file']['error']) && !empty($tmp_name) && $tmp_name != 'none' && $isFileAllowed) {

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
    echo json_encode($response);
    exit();
}

// Mass deleting
if (isset($_POST['group'], $_POST['delete']) && !FM_READONLY) {

	 $sucessmsgbulkdelete='';
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) && count($files)) {

		  foreach ($files as $f) {

			if ($f != '') {

				$from = $path.$f;
				$file_type=is_file($from);
				$file_name=basename($f);
				if(FM_PATH != ''){
				  $dirname = dirname(FM_PATH.$f);
				}
				else {
				  $dirname = dirname($f);
				}

			    $source=urldecode($path.$f);

				$dest=urldecode($path.'trash'.'/'.$file_name);

				 if($file_type == true){
					    $ftype='file';
					    rename($source, $dest) ? "OK" : "ERROR";
					    $fulltrashpath=$dest;
					}
					else {
					    $ftype='folder';
					    rename($source, $dest) ? "OK" : "ERROR";
					    $fulltrashpath=$dest;
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
										userId='".$_SESSION['userId']."',
										root_path='".$dirname."',
										file_name='".$file_name."',
										file_type='".$ftype."',
										modif='".$modif."',
										fulltrashpath='".$fulltrashpath."',
										status='".$status."'");

				//echo "<hr>";

				//$new_path = $path . '/' . $f;
                //if (!fm_rdelete($new_path)) {
                    //$errors++;
                //}
            }
        }

        if ($errors == 0) {
            fm_set_msg(lng('Selected files and folder deleted'));

        } else {
            fm_set_msg(lng('Error while deleting items'), 'error');
        }
    } else {
        fm_set_msg(lng('Nothing selected'), 'alert');
    }
 $sucessmsgbulkdelete='bulk_delete';


	//fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}

// Pack files
if (isset($_POST['group']) && (isset($_POST['zip']) || isset($_POST['tar'])) && !FM_READONLY) {
    $path = FM_ROOT_PATH;
    $ext = 'zip';
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    //set pack type
    $ext = isset($_POST['tar']) ? 'tar' : 'zip';


    if (($ext == "zip" && !class_exists('ZipArchive')) || ($ext == "tar" && !class_exists('PharData'))) {
        fm_set_msg(lng('Operations with archives are not available'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    $files = $_POST['file'];
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
            fm_set_msg(sprintf(lng('Archive').' <b>%s</b> '.lng('Created'), fm_enc($zipname)));
        } else {
            fm_set_msg(lng('Archive not created'), 'error');
        }
    } else {
        fm_set_msg(lng('Nothing selected'), 'alert');
    }

    fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}

// Unpack
if (isset($_GET['unzip']) && !FM_READONLY) {
    $unzip = $_GET['unzip'];
    $unzip = fm_clean_path($unzip);
    $unzip = str_replace('/', '', $unzip);
    $isValid = false;

    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    if ($unzip != '' && is_file($path . '/' . $unzip)) {
        $zip_path = $path . '/' . $unzip;
        $ext = pathinfo($zip_path, PATHINFO_EXTENSION);
        $isValid = true;
    } else {
        fm_set_msg(lng('File not found'), 'error');
    }


    if (($ext == "zip" && !class_exists('ZipArchive')) || ($ext == "tar" && !class_exists('PharData'))) {
        fm_set_msg(lng('Operations with archives are not available'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    if ($isValid) {
        //to folder
        $tofolder = '';
        if (isset($_GET['tofolder'])) {
            $tofolder = pathinfo($zip_path, PATHINFO_FILENAME);
            if (fm_mkdir($path . '/' . $tofolder, true)) {
                $path .= '/' . $tofolder;
            }
        }

        if($ext == "zip") {
            $zipper = new FM_Zipper();
            $res = $zipper->unzip($zip_path, $path);
        } elseif ($ext == "tar") {
            try {
                $gzipper = new PharData($zip_path);
                if (@$gzipper->extractTo($path,null, true)) {
                    $res = true;
                } else {
                    $res = false;
                }
            } catch (Exception $e) {
                //TODO:: need to handle the error
                $res = true;
            }
        }

        if ($res) {
            fm_set_msg(lng('Archive unpacked'));
        } else {
            fm_set_msg(lng('Archive not unpacked'), 'error');
        }

    } else {
        fm_set_msg(lng('File not found'), 'error');
    }
    fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
}

// Change Perms (not for Windows)
if (isset($_POST['chmod']) && !FM_READONLY && !FM_IS_WIN) {
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $file = $_POST['chmod'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || (!is_file($path . '/' . $file) && !is_dir($path . '/' . $file))) {
        fm_set_msg(lng('File not found'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    $mode = 0;
    if (!empty($_POST['ur'])) {
        $mode |= 0400;
    }
    if (!empty($_POST['uw'])) {
        $mode |= 0200;
    }
    if (!empty($_POST['ux'])) {
        $mode |= 0100;
    }
    if (!empty($_POST['gr'])) {
        $mode |= 0040;
    }
    if (!empty($_POST['gw'])) {
        $mode |= 0020;
    }
    if (!empty($_POST['gx'])) {
        $mode |= 0010;
    }
    if (!empty($_POST['or'])) {
        $mode |= 0004;
    }
    if (!empty($_POST['ow'])) {
        $mode |= 0002;
    }
    if (!empty($_POST['ox'])) {
        $mode |= 0001;
    }

    if (@chmod($path . '/' . $file, $mode)) {
        fm_set_msg(lng('Permissions changed'));
    } else {
        fm_set_msg(lng('Permissions not changed'), 'error');
    }

    fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
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

/*************************** /ACTIONS ***************************/

// Listing of the Recent Files


// get current path

if(isset($_GET['sharedu'])){
	$path = $directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.$_GET['sharedu'].'/';

if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}

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
            $files[] = $file;
        } elseif (@is_dir($new_path) && $file != '.' && $file != '..' && fm_is_exclude_items($file)) {
            $folders[] = $file;
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
            $files[] = $file;
        } elseif (@is_dir($new_path) && $file != '.' && $file != '..' && fm_is_exclude_items($file)) {
            $folders[] = $file;
        }
    }
  }
}// Else Ends Here...

if (!empty($files)) {
    natcasesort($files);
}
if (!empty($folders)) {
    natcasesort($folders);
}




// copy form POST
if (isset($_GET['masscopy'])) {
	$copy_files = isset($_POST['file']) ? $_POST['file'] : null;
    /*if (!is_array($copy_files) || empty($copy_files)) {
        fm_set_msg(lng('Nothing selected'), 'alert');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }*/

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    ?>
	<div class="path">
        <div class="card <?php echo fm_get_theme(); ?>">
            <div class="card-header">
                <h6><?php echo lng('Copying') ?></h6>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                    <input type="hidden" name="finish" value="1">
                    <?php
                    foreach ($copy_files as $cf) {
                        echo '<input type="hidden" name="file[]" value="' . fm_enc($cf) . '">' . PHP_EOL;
                    }
                    ?>
                    <p class="break-word"><?php echo lng('Files') ?>: <b><?php echo implode('</b>, <b>', $copy_files) ?></b></p>
                    <p class="break-word"><?php echo lng('SourceFolder') ?>: <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?><br>
                        <label for="inp_copy_to"><?php echo lng('DestinationFolder') ?>:</label>
                        <?php echo FM_ROOT_PATH ?>/<input type="text" name="copy_to" id="inp_copy_to" value="<?php echo fm_enc(FM_PATH) ?>">
                    </p>
                    <p class="custom-checkbox custom-control"><input type="checkbox" name="move" value="1" id="js-move-files" class="custom-control-input"><label for="js-move-files" class="custom-control-label" style="vertical-align: sub"> <?php echo lng('Move') ?></label></p>
                    <p>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo lng('Copy') ?></button> &nbsp;
                        <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>" class="btn btn-outline-primary"><i class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></a></b>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
    fm_show_footer();
    exit;
}

// copy form
if (isset($_GET['copy']) && !isset($_GET['finish']) && !FM_READONLY) {
    $copy = $_GET['copy'];
    $copy = fm_clean_path($copy);
    if ($copy == '' || !file_exists(FM_ROOT_PATH . '/' . $copy)) {
        fm_set_msg(lng('File not found'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    ?>
	<style>
		.wrapper>.content-wrapper {
		margin-left: 0px !important;
		width: 100% !important;
		padding: 1em;
		}
		.wrapper.left-sidebar {
		display: none !important;
		}
		.copy_success {
		width: 100%;
		color: green;
		float: left;
		position: relative;
		font-size: 20px !important;
		margin-top: 15px;
		}
	</style>
    <div class="path">
        <p><b>Copying</b></p>
        <!--<p class="break-word">
            Source path: <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . $copy)) ?><br>
            Destination folder: <?php echo fm_enc(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?>
        </p>-->
        <p>
            <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1"><i class="fa fa-check-circle"></i> Copy</a></b> &nbsp;
            <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1&amp;move=1"><i class="fa fa-check-circle"></i> Move</a></b> &nbsp;
            <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-times-circle"></i> Cancel</a></b>
        </p>
        <p><i><?php echo lng('Select folder') ?></i></p>
        <ul class="folders break-word">
            <?php
            if ($parent !== false) {
                ?>
                <li><a href="?action=filemanager&p=<?php echo urlencode($parent) ?>&amp;copy=<?php echo urlencode($copy) ?>"><i class="fa fa-chevron-circle-left"></i> ..</a></li>
                <?php
            }
            foreach ($folders as $f) {
                ?>
                <li>
                    <a href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>&amp;copy=<?php echo urlencode($copy) ?>"><i class="fa fa-folder-o"></i> <?php echo fm_convert_win($f) ?></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
    fm_show_footer();
    exit;
}

if (isset($_GET['settings']) && !FM_READONLY) {
    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    global $cfg, $lang, $lang_list;
?>
 <div class="col-md-8 offset-md-2 filemanager-settings">
        <div class="card mb-2 <?php echo fm_get_theme(); ?>">
            <h6 class="card-header">
                <i class="fa fa-cog"></i>  <?php echo lng('Settings') ?>
                <a href="?action=filemanager&p=<?php echo FM_PATH ?>" class="float-right"><i class="fa fa-window-close"></i> <?php echo lng('Cancel')?></a>
            </h6>
            <div class="card-body">
                <form id="js-settings-form" action="" method="post" data-type="ajax" onsubmit="return save_settings(this)">
                    <input type="hidden" name="type" value="settings" aria-label="hidden" aria-hidden="true">
                    <div class="form-group row mb-3">
                        <label for="js-language" class="col-sm-3 col-form-label"><?php echo lng('Language') ?></label>
                        <div class="col-sm-5">
                            <select class="form-control" id="js-language" name="js-language">
                                <?php
                                function getSelected($l) {
                                    global $lang;
                                    return ($lang == $l) ? 'selected' : '';
                                }
                                foreach ($lang_list as $k => $v) {
                                    echo "<option value='$k' ".getSelected($k).">$v</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <?php
                    //get ON/OFF and active class
                    function getChecked($conf, $val, $txt) {
                        if($conf== 1 && $val ==1) {
                            return $txt;
                        } else if($conf == '' && $val == '') {
                            return $txt;
                        } else {
                            return '';
                        }
                    }
                    ?>
                    <div class="form-group row mb-3">
                        <label for="js-err-rpt-1" class="col-sm-3 col-form-label"><?php echo lng('ErrorReporting') ?></label>
                        <div class="col-sm-9">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary <?php echo getChecked($report_errors, 1, 'active') ?>">
                                    <input type="radio" name="js-error-report" id="js-err-rpt-1" autocomplete="off" value="true" <?php echo getChecked($report_errors, 1, 'checked') ?> > ON
                                </label>
                                <label class="btn btn-secondary <?php echo getChecked($report_errors, '', 'active') ?>">
                                    <input type="radio" name="js-error-report" id="js-err-rpt-0" autocomplete="off" value="false" <?php echo getChecked($report_errors, '', 'checked') ?> > OFF
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="js-hdn-1" class="col-sm-3 col-form-label"><?php echo lng('ShowHiddenFiles') ?></label>
                        <div class="col-sm-9">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary <?php echo getChecked($show_hidden_files, 1, 'active') ?>">
                                    <input type="radio" name="js-show-hidden" id="js-hdn-1" autocomplete="off" value="true" <?php echo getChecked($show_hidden_files, 1, 'checked') ?> > ON
                                </label>
                                <label class="btn btn-secondary <?php echo getChecked($show_hidden_files, '', 'active') ?>">
                                    <input type="radio" name="js-show-hidden" id="js-hdn-0" autocomplete="off" value="false" <?php echo getChecked($show_hidden_files, '', 'checked') ?> > OFF
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="js-hid-1" class="col-sm-3 col-form-label"><?php echo lng('HideColumns') ?></label>
                        <div class="col-sm-9">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary <?php echo getChecked($hide_Cols, 1, 'active') ?>">
                                    <input type="radio" name="js-hide-cols" id="js-hid-1" autocomplete="off" value="true" <?php echo getChecked($hide_Cols, 1, 'checked') ?> > ON
                                </label>
                                <label class="btn btn-secondary <?php echo getChecked($hide_Cols, '', 'active') ?>">
                                    <input type="radio" name="js-hide-cols" id="js-hid-0" autocomplete="off" value="false" <?php echo getChecked($hide_Cols, '', 'checked') ?> > OFF
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="js-dir-1" class="col-sm-3 col-form-label"><?php echo lng('CalculateFolderSize') ?></label>
                        <div class="col-sm-9">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary <?php echo getChecked($calc_folder, 1, 'active') ?>">
                                    <input type="radio" name="js-calc-folder" id="js-dir-1" autocomplete="off" value="true" <?php echo getChecked($calc_folder, 1, 'checked') ?> > ON
                                </label>
                                <label class="btn btn-secondary <?php echo getChecked($calc_folder, '', 'active') ?>">
                                    <input type="radio" name="js-calc-folder" id="js-dir-0" autocomplete="off" value="false" <?php echo getChecked($calc_folder, '', 'checked') ?> > OFF
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <label for="js-3-1" class="col-sm-3 col-form-label"><?php echo lng('Theme') ?></label>
                        <div class="col-sm-5">
							<select class="form-control" id="js-3-0" name="js-theme-3" style="width:165px;">
							  <option value='light' <?php if($theme == "light"){echo "selected";} ?>><?php echo lng('light') ?></option>
							  <option value='dark' <?php if($theme == "dark"){echo "selected";} ?>><?php echo lng('dark') ?></option>
							</select>
                        </div>
                    </div>

                    <div class="form-group row mb-3">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check-circle"></i> <?php echo lng('Save'); ?></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <?php
    fm_show_footer();
    exit;
}

if (isset($_GET['help'])) {
    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    global $cfg, $lang;
    ?>

    <div class="col-md-8 offset-md-2 pt-3">
        <div class="card mb-2 <?php echo fm_get_theme(); ?>">
            <h6 class="card-header">
                <i class="fa fa-exclamation-circle"></i> <?php echo lng('Help') ?>
                <a href="?action=filemanager&p=<?php echo FM_PATH ?>" class="float-right"><i class="fa fa-window-close"></i> <?php echo lng('Cancel')?></a>
            </h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <p><h3><a href="https://github.com/prasathmani/tinyfilemanager" target="_blank" class="app-v-title"> Tiny File Manager <?php echo VERSION; ?></a></h3></p>
                        <p>Author: Prasath Mani</p>
                        <p>Mail Us: <a href="mailto:ccpprogrammers@gmail.com">ccpprogrammers[at]gmail.com</a> </p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><a href="https://github.com/prasathmani/tinyfilemanager/wiki" target="_blank"><i class="fa fa-question-circle"></i> <?php echo lng('Help Documents') ?> </a> </li>
                                <li class="list-group-item"><a href="https://github.com/prasathmani/tinyfilemanager/issues" target="_blank"><i class="fa fa-bug"></i> <?php echo lng('Report Issue') ?></a></li>
                                <li class="list-group-item"><a href="javascript:latest_release_info('<?php echo VERSION; ?>');"><i class="fa fa-link"> </i> <?php echo lng('Check Latest Version') ?></a></li>
                                <?php if(!FM_READONLY) { ?>
                                <li class="list-group-item"><a href="javascript:show_new_pwd();"><i class="fa fa-lock"></i> <?php echo lng('Generate new password hash') ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row js-new-pwd hidden mt-2">
                    <div class="col-12">
                        <form class="form-inline" onsubmit="return new_password_hash(this)" method="POST" action="">
                            <input type="hidden" name="type" value="pwdhash" aria-label="hidden" aria-hidden="true">
                            <div class="form-group mb-2">
                                <label for="staticEmail2"><?php echo lng('Generate new password hash') ?></label>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="inputPassword2" class="sr-only"><?php echo lng('Password') ?></label>
                                <input type="text" class="form-control btn-sm" id="inputPassword2" name="inputPassword2" placeholder="Password" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm mb-2"><?php echo lng('Generate') ?></button>
                        </form>
                        <textarea class="form-control" rows="2" readonly id="js-pwd-result"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    fm_show_footer();
    exit;
}

// This is code for the Direct Shared Viewer

// file viewer
if (isset($_GET['sharedview'])) {
    $file = $_GET['sharedview'];
	$shared=$_GET['shared'];

	$quickView = (isset($_GET['quickView']) && $_GET['quickView'] == 1) ? true : false;
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    //if ($file == '' || !is_file($path . '/' . $file) || in_array($file, $GLOBALS['exclude_items'])) {
        //fm_set_msg(lng('File not found'), 'error');
        //fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    //}

    if(!$quickView) {
        fm_show_header(); // HEADER
        fm_show_nav_path(FM_PATH); // current path
    }

	$FM_SHARED_URL='https://'.$http_host.'/admin/filemanager/'.$shared.'/';
    $file_url = $FM_SHARED_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);


	//$file_url = FM_IMAGE_URL.$file;

	$directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.$shared.'/';

	$file_path = $directory . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);

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
        <div class="row">
            <div class="col-12">
                <?php if(!$quickView) { ?>
                    <p class="break-word"><b><?php echo $view_title ?> "<?php echo fm_enc(fm_convert_win($file)) ?>"</b></p>
                    <p class="break-word">
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
                        <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;shareddl=<?php echo urlencode($file)?>&amp;shared=<?php echo $shared ?>"><i class="fa fa-download"></i> <?php echo lng('Download') ?></a></b> &nbsp;
                        <b><a href="<?php echo fm_enc($file_url) ?>" target="_blank"><i class="fa fa-link"></i> <?php echo lng('Open') ?></a></b>
                        &nbsp;
                        <?php
                        // ZIP actions
                        if (!FM_READONLY && ($is_zip || $is_gzip) && $filenames !== false) {
                            $zip_name = pathinfo($file_path, PATHINFO_FILENAME);
                            ?>
                            <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>"><i class="fa fa-check-circle"></i> <?php echo lng('UnZip') ?></a></b> &nbsp;
                            <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>&amp;tofolder=1" title="UnZip to <?php echo fm_enc($zip_name) ?>"><i class="fa fa-check-circle"></i>
                                    <?php echo lng('UnZipToFolder') ?></a></b> &nbsp;
                            <?php
                        }
                        if ($is_text && !FM_READONLY) {
                            ?>
                            <b><a href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>" class="edit-file"><i class="fa fa-pencil-square"></i> <?php echo lng('Edit') ?>
                                </a></b> &nbsp;
                            <b><a href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&env=ace"
                                class="edit-file"><i class="fa fa-pencil-square-o"></i> <?php echo lng('AdvancedEditor') ?>
                                </a></b> &nbsp;
                        <?php } ?>
                        <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-chevron-circle-left go-back"></i> <?php echo lng('Back') ?></a></b>
                    </p>
                    <?php
                }
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
                        echo '<p class="text-center m-0"><img src="' . fm_enc($file_url) . '" alt="" class="preview-img"></p>';
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
            </div>
        </div>
    </div>
    <?php
    if(!$quickView) {
        fm_show_footer();
    }
    exit;
}

// file viewer
if (isset($_GET['view'])) {
    $file = $_GET['view'];

	$quickView = (isset($_GET['quickView']) && $_GET['quickView'] == 1) ? true : false;
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file) || in_array($file, $GLOBALS['exclude_items'])) {
        fm_set_msg(lng('File not found'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    if(!$quickView) {
        fm_show_header(); // HEADER
        fm_show_nav_path(FM_PATH); // current path
    }


	$file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);

	$file_url = FM_IMAGE_URL.$file;

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
                    } ?>
                </div>
            </div>
        </div>
    </div>
    <?php
    if(!$quickView) {
        fm_show_footer();
    }
    exit;
}

// file editor
if (isset($_GET['edit'])) {
    $file = $_GET['edit'];
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file)) {
        fm_set_msg(lng('File not found'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }
    $editFile = ' : <i><b>'. $file. '</b></i>';
    header('X-XSS-Protection:0');
    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
    $file_path = $path . '/' . $file;

    // normal editer
    $isNormalEditor = true;
    if (isset($_GET['env'])) {
        if ($_GET['env'] == "ace") {
            $isNormalEditor = false;
        }
    }

    // Save File
    if (isset($_POST['savedata'])) {
        $writedata = $_POST['savedata'];
        $fd = fopen($file_path, "w");
        @fwrite($fd, $writedata);
        fclose($fd);
        fm_set_msg(lng('File Saved Successfully'));
    }

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = fm_get_mime_type($file_path);
    $filesize = filesize($file_path);
    $is_text = false;
    $content = ''; // for text

    if (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }

    ?>
    <div class="path">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-lg-6 pt-1">
                <div class="btn-toolbar" role="toolbar">
                    <?php if (!$isNormalEditor) { ?>
                        <div class="btn-group js-ace-toolbar">
                            <button data-cmd="none" data-option="fullscreen" class="btn btn-sm btn-outline-secondary" id="js-ace-fullscreen" title="Fullscreen"><i class="fa fa-expand" title="Fullscreen"></i></button>
                            <button data-cmd="find" class="btn btn-sm btn-outline-secondary" id="js-ace-search" title="Search"><i class="fa fa-search" title="Search"></i></button>
                            <button data-cmd="undo" class="btn btn-sm btn-outline-secondary" id="js-ace-undo" title="Undo"><i class="fa fa-undo" title="Undo"></i></button>
                            <button data-cmd="redo" class="btn btn-sm btn-outline-secondary" id="js-ace-redo" title="Redo"><i class="fa fa-repeat" title="Redo"></i></button>
                            <button data-cmd="none" data-option="wrap" class="btn btn-sm btn-outline-secondary" id="js-ace-wordWrap" title="Word Wrap"><i class="fa fa-text-width" title="Word Wrap"></i></button>
                            <button data-cmd="none" data-option="help" class="btn btn-sm btn-outline-secondary" id="js-ace-goLine" title="Help"><i class="fa fa-question" title="Help"></i></button>
                            <select id="js-ace-mode" data-type="mode" title="Select Document Type" class="btn-outline-secondary border-left-0 d-none d-md-block"><option>-- Select Mode --</option></select>
                            <select id="js-ace-theme" data-type="theme" title="Select Theme" class="btn-outline-secondary border-left-0 d-none d-lg-block"><option>-- Select Theme --</option></select>
                            <select id="js-ace-fontSize" data-type="fontSize" title="Select Font Size" class="btn-outline-secondary border-left-0 d-none d-lg-block"><option>-- Select Font Size --</option></select>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="edit-file-actions col-xs-12 col-sm-7 col-lg-6 text-right pt-1">
                <a title="<?php echo lng('Back') ?>" class="btn btn-sm btn-outline-primary" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;view=<?php echo urlencode($file) ?>"><i class="fa fa-reply-all"></i> <?php echo lng('Back') ?></a>
                <a title="<?php echo lng('BackUp') ?>" class="btn btn-sm btn-outline-primary" href="javascript:void(0);" onclick="backup('<?php echo urlencode(trim(FM_PATH)) ?>','<?php echo urlencode($file) ?>')"><i class="fa fa-database"></i> <?php echo lng('BackUp') ?></a>
                <?php if ($is_text) { ?>
                    <?php if ($isNormalEditor) { ?>
                        <a title="Advanced" class="btn btn-sm btn-outline-primary" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&amp;env=ace"><i class="fa fa-pencil-square-o"></i> <?php echo lng('AdvancedEditor') ?></a>
                        <button type="button" class="btn btn-sm btn-outline-primary" name="Save" data-url="<?php echo fm_enc($file_url) ?>" onclick="edit_save(this,'nrl')"><i class="fa fa-floppy-o"></i> Save
                        </button>
                    <?php } else { ?>
                        <a title="Plain Editor" class="btn btn-sm btn-outline-primary" href="?action=filemanager&p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>"><i class="fa fa-text-height"></i> <?php echo lng('NormalEditor') ?></a>
                        <button type="button" class="btn btn-sm btn-outline-primary" name="Save" data-url="<?php echo fm_enc($file_url) ?>" onclick="edit_save(this,'ace')"><i class="fa fa-floppy-o"></i> <?php echo lng('Save') ?>
                        </button>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php
        if ($is_text && $isNormalEditor) {
            echo '<textarea class="mt-2" id="normal-editor" rows="33" cols="120" style="width: 99.5%;">' . htmlspecialchars($content) . '</textarea>';
        } elseif ($is_text) {
            echo '<div id="editor" contenteditable="true">' . htmlspecialchars($content) . '</div>';
        } else {
            fm_set_msg(lng('FILE EXTENSION HAS NOT SUPPORTED'), 'error');
        }
        ?>
    </div>
    <?php
    fm_show_footer();
    exit;
}

// chmod (not for Windows)
if (isset($_GET['chmod']) && !FM_READONLY && !FM_IS_WIN) {
    $file = $_GET['chmod'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || (!is_file($path . '/' . $file) && !is_dir($path . '/' . $file))) {
        fm_set_msg(lng('File not found'), 'error');
        fm_redirect(FM_SELF_URL . '?action=filemanager&p=' . urlencode(FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file;
    $file_path = $path . '/' . $file;

    $mode = fileperms($path . '/' . $file);

    ?>
    <div class="path">
        <div class="card mb-2 <?php echo fm_get_theme(); ?>">
            <h6 class="card-header">
                <?php echo lng('ChangePermissions') ?>
            </h6>
            <div class="card-body">
                <p class="card-text">
                    Full path: <?php echo $file_path ?><br>
                </p>
                <form action="" method="post">
                    <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                    <input type="hidden" name="chmod" value="<?php echo fm_enc($file) ?>">

                    <table class="table compact-table <?php echo fm_get_theme(); ?>">
                        <tr>
                            <td></td>
                            <td><b><?php echo lng('Owner') ?></b></td>
                            <td><b><?php echo lng('Group') ?></b></td>
                            <td><b><?php echo lng('Other') ?></b></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><b><?php echo lng('Read') ?></b></td>
                            <td><label><input type="checkbox" name="ur" value="1"<?php echo ($mode & 00400) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="gr" value="1"<?php echo ($mode & 00040) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="or" value="1"<?php echo ($mode & 00004) ? ' checked' : '' ?>></label></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><b><?php echo lng('Write') ?></b></td>
                            <td><label><input type="checkbox" name="uw" value="1"<?php echo ($mode & 00200) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="gw" value="1"<?php echo ($mode & 00020) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="ow" value="1"<?php echo ($mode & 00002) ? ' checked' : '' ?>></label></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><b><?php echo lng('Execute') ?></b></td>
                            <td><label><input type="checkbox" name="ux" value="1"<?php echo ($mode & 00100) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="gx" value="1"<?php echo ($mode & 00010) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="ox" value="1"<?php echo ($mode & 00001) ? ' checked' : '' ?>></label></td>
                        </tr>
                    </table>

                    <p>
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo lng('Change') ?></button> &nbsp;
                        <b><a href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>" class="btn btn-outline-primary"><i class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></a></b>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
    fm_show_footer();
    exit;
}
 //--- FILEMANAGER MAIN
fm_show_header(); // HEADER
fm_show_nav_path(FM_PATH); // current path
// messages
fm_show_message();

$num_files = count($files);
$num_folders = count($folders);
$all_files_size = 0;
$tableTheme = (FM_THEME == "dark") ? "text-white bg-dark table-dark" : "bg-white";
?>
<div class="filemanager-wrap flex-wrap">
    <div class="row w-100 d-none">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="home-link d-flex align-items-center">
                        <?php
                            $path_bread = fm_clean_path(FM_PATH);//.min.js
                            $root_url = "<a href='?action=filemanager&p='><i class='fa fa-home' aria-hidden='true' title='" . FM_ROOT_PATH . "'></i></a>";
                            $sep = '<i class="bread-crumb"> / </i>';
                            if ($path_bread != '') {
                                $exploded = explode('/', $path_bread);
                                $count = count($exploded);
                                $array = array();
                                $parent = '';
                                for ($i = 0; $i < $count; $i++) {
                                    $parent = trim($parent . '/' . $exploded[$i], '/');
                                    $parent_enc = urlencode($parent);
                                    $array[] = "<a href='?action=filemanager&p={$parent_enc}'>" . fm_enc(fm_convert_win($exploded[$i])) . "</a>";
                                }
                                $root_url .= $sep . implode($sep, $array);
                            }

                            ?>

                        <?php echo '<div class="home-link">' . $root_url . $editFile . '</div> '; ?>

                </div>
            </div>
        </div>
    </div>
    <div class="card filemanager-sidebar left-bar">
        <a class="sidebar-close" href="javascript:;">✕</a>
        <div class="card-body p-0">
            <div class="d-flex flex-column h-100">
                <div class="mb-5">
                    <h1>Cloudy Drive</h1>
                    <div class="mb-3 d-none">
                        <div class="dropdown">
                            <button class="btn btn-light w-100" type="button" id="CreateDropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-plus me-1 font-size-10 mb-1"></i> Create New
                            </button>
                            <ul class="dropdown-menu">
								<?php
								$directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']).'/';
								  sizeFormat1(fm_get_directorysize($directory));
								    fm_get_directorysize($directory);
								     if(fm_get_directorysize($directory) >=1000000000){
								        //echo "<span style='color:red;'>Your Drive Limit Is over Please upgrade</span>";
								   ?>
								   <li><a class="dropdown-item" href="#"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</a><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</a></a></li>
								   <?php } else { ?>
								   <li><a class="dropdown-item" href="index.php?action=filemanager&p=<?php echo FM_PATH;?>&upload"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</a></li>
								   <?php } ?>
                                <li><a class="dropdown-item" href="#createNewItem" data-toggle="modal" data-target="#createNewItem"><i class="fa fa-plus-square"></i> Folder</a></li>
                            </ul>
                        </div>
                    </div>
                    <ul class="list-unstyled categories-list lefttabs">
                      <li class="active" id="test1">
                        <span class="shape"></span>
                            <div class="custom-accordion">
                                <a href="javascript:;" id="collapseOneLink" class="fw-medium d-flex align-items-center active tab-1">
                                    <i class="fa fa-folder font-size-20 me-3"></i> Files
                                </a>
							    <a href="javascript:;" id="collapseOneLink1">
								  <i class="fa fa-chevron-down accor-down-icon font-size-12 ms-auto"></i>
                                </a>
								<div class="w-100 mb-1" id="collapseOne" style="display:none;">
                                    <div class="card border-0 shadow-none ps-3 mb-0">
                                        <ul class="list-unstyled m-0">
					   <?php
			            $ii = 3399;

						foreach ($folders as $key => $value){
							   if ($value == 'trash') {  unset($folders[$key]); }
							   if ($value == 'shared') {  unset($folders[$key]); }
							}
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
                              <li><a onclick="insidefolder('<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>')" href="javascript:void(0)"><i class="fa fa-folder font-size-17"></i><?php echo fm_convert_win(fm_enc($f)) ?>
                                  </a><?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></a></li>
										<?php
								flush();
							  $ii++;
							}
							?>
                            <!--<li><a href="javascript:;"><i class="fa fa-folder font-size-17"></i>Create Folder</a></li>-->
                              </ul>
                             </div>
                            </div>
                          </div>
                        </li>
                       <!--<li>
                            <a href="javascript: void(0);" class="d-flex align-items-center">
                                <i class="fa fa-google font-size-14 text-muted me-2"></i> <span
                                    class="me-auto">Google Drive</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript: void(0);" class="d-flex align-items-center">
                                <i class="fa fa-dropbox font-size-14 me-2 text-primary"></i> <span
                                    class="me-auto">Dropbox</span>
                            </a>
                        </li>-->
                        <li>
                        <span class="shape"></span>
                            <a href="javascript: void(0);" class="fw-medium d-flex align-items-center tab-2">
                                <i class="fa fa-share-alt font-size-20 me-3"></i> <span class="me-auto">Shared</span>
                            </a>
						</li>
                        <li>
                        <span class="shape"></span>
                            <a href="javascript: void(0);" class="d-flex align-items-center tab-3">
                                <i class="fa fa-star-o font-size-20 me-3"></i><span
                                    class="me-auto">Starred</span></a>
                            </a>
                        </li>
                        <li>
                        <span class="shape"></span>
                            <a href="javascript: void(0);" class="d-flex align-items-center tab-4">
                                <i class="fa fa-trash font-size-20 me-3"></i> <span
                                    class="me-auto">Trash</span>
                            </a>
                        </li>
                        <li>
                        <span class="shape"></span>
                            <a href="javascript: void(0);" class="d-flex align-items-center">
                               <i class="fa fa-cog font-size-20 me-3"></i> <span
                                    class="me-auto">Setting</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="upgrade-card w-100">
                        <div class="upgrade-img">
                        <img src="https://rosnyc.com/admin/images/g2.png" class="img-fluid" alt="bg image">
                        </div>
                        <div class="upgrade-content">
                            <h5>Upgrade Plan</h5>
                            <p>Cum sociis natoque penatibus et</p>
                            <a href="javascript: void(0);" class="btn">Upgrade</a>
                        </div>
                </div>
            </div>
        </div>
    </div>
	<style>
		.ajax-loader {
		position: relative;
		left: 50%;
		top: 50%;
		margin-left: -32px; /* -1 * image width / 2 */
		margin-top: -32px; /* -1 * image height / 2 */
		width:100px;
		}

		div#loading-image {
		position: absolute;
		z-index: 999;
		top: 25%;
		width: 45%;
		float: left;
		}
		div#loading-image img {
		width: 100%;
		max-width: 200px;
		display: table;
		margin: 0 auto;
		z-index: 999;
		}
	</style>

    <div class="my-file">
	<!-- Toasts -->
   <button style="display:none;" type="button" class="btn btn-primary" id="basic-primary-trigger">Primary</button>
   <button style="display:none;" type="button" class="btn btn-danger" id="basic-danger-trigger">Danger</button>
   <button style="display:none;" type="button" class="btn btn-danger" id="basic-primary-trash-trigger">Danger</button>
   <button style="display:none;" type="button" class="btn btn-danger" id="basic-primary-unstarred-trigger">Danger</button>
   <button style="display:none;" type="button" class="btn btn-danger" id="basic-primary-restore-trigger">Danger</button>
   <button style="display:none;" type="button" class="btn btn-danger" id="basic-primary-trash-delete-trigger">Danger</button>
   <button style="display:none;" type="button" class="btn btn-danger" id="basic-primary-delete-shared-trigger">Danger</button>
   <button style="display:none;" type="button" class="btn btn-danger" id="basic-primary-bulk-delete-trigger">Danger</button>
    <div class="filemanager-search d-flex justify-content-between">
        <div class="d-flex search-wrap">
            <div class="form-outline w-100">
                <input type="text" class="form-control" aria-label="<?php echo lng('Search') ?>" aria-describedby="v-addon2" id="search-addon" placeholder="<?php echo lng('Search') ?>">
            </div>
            <div class="input-group-append">
                    <span class="input-group-text" id="search-addon2"><i class="fa fa-search"></i></span>
                </div>

                <div class="input-group-append btn-group d-none">
                    <span class="input-group-text dropdown-toggle" id="search-addon2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                        <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?php echo $path2 = $path ? $path : '.'; ?>" id="js-search-modal" data-toggle="modal" data-target="#searchModal"><?php echo lng('Advanced Search') ?></a>
                        </div>
                </div>
        </div>
        <div class="dropdown create-new">
            <button class="btn w-100" type="button" id="CreateDropdownMenuLink" data-mdb-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-plus-circle me-1 font-size-13 mb-1"></i> Create New
            </button>
            <a class="humberger-menu" href="javascript:;"><span></span><span></span><span></span></a>
            <ul class="dropdown-menu">
                <?php
                $directory =$_SERVER['DOCUMENT_ROOT'].'/admin/filemanager/'.base64_encode($_SESSION['userId']).'/';
                    sizeFormat1(fm_get_directorysize($directory));
                    fm_get_directorysize($directory);
                        if(fm_get_directorysize($directory) >=1000000000){
                        //echo "<span style='color:red;'>Your Drive Limit Is over Please upgrade</span>";
                    ?>
                    <li><a class="dropdown-item" id="upload_item" href="#"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</a><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</a></a></li>
                    <?php } else { ?>
                    <li><a class="dropdown-item" id="upload_item" href="#"><i class="fa fa-cloud-upload" aria-hidden="true"></i> Upload</a></li>
                    <?php } ?>
                <li><a class="dropdown-item" href="#createNewItem" data-toggle="modal" data-target="#createNewItem"><i class="fa fa-plus-square"></i>Folder</a></li>
            </ul>
        </div>
     </div>

	 <script src="https://rosnyc.com/dev_admin/assets/mdb/js/mdb.min.js"></script>
	 <script src="https://rosnyc.com/admin/filemanager/dropzone.min.js"></script>
	 <link rel="stylesheet" href="https://rosnyc.com/admin/assets/mdb/css/mdb.min.css" />
	    <!-- Default Division For Load All Data --->
			<div id="loader" style="display:none;">
			  <div class="loading-box" id="" style="/* display: none; */">
				<div class="loader"></div>
			   </div>
			</div>
		    <div id="content_div"></div>
     	<!-- Default Division For Load All Data --->

        </div>
	  <?php //include('rightside.php');?>
	    <div id="right-side-content_div"></div>
    </div>
<?php
if(isset($_REQUEST['delete'])){
 $sucessmsgbulkdelete=$_REQUEST['delete'];
}
else {
 $sucessmsgbulkdelete='';
}

fm_show_footer($sizesPershow,$sucessmsg,$sucessmsgbulkdelete);
//--- END

// Functions

/**
 * Check if the filename is allowed.
 * @param string $filename
 * @return bool
 */
function fm_is_file_allowed($filename)
{
    // By default, no file is allowed
    $allowed = false;

    if (FM_EXTENSION) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, explode(',', strtolower(FM_EXTENSION)))) {
            $allowed = true;
        }
    }

    return $allowed;
}

/**
 * Delete  file or folder (recursively)
 * @param string $path
 * @return bool
 */
function fm_rdelete($path)
{
    if (is_link($path)) {
        return unlink($path);
    } elseif (is_dir($path)) {
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rdelete($path . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return ($ok) ? rmdir($path) : false;
    } elseif (is_file($path)) {
        return unlink($path);
    }
    return false;
}

/**
 * Recursive chmod
 * @param string $path
 * @param int $filemode
 * @param int $dirmode
 * @return bool
 * @todo Will use in mass chmod
 */
function fm_rchmod($path, $filemode, $dirmode)
{
    if (is_dir($path)) {
        if (!chmod($path, $dirmode)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rchmod($path . '/' . $file, $filemode, $dirmode)) {
                        return false;
                    }
                }
            }
        }
        return true;
    } elseif (is_link($path)) {
        return true;
    } elseif (is_file($path)) {
        return chmod($path, $filemode);
    }
    return false;
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

    return ($isFileAllowed) ? true : false;
}

/**
 * Safely rename
 * @param string $old
 * @param string $new
 * @return bool|null
 */
function fm_rename($old, $new)
{
    $isFileAllowed = fm_is_valid_ext($new);

    if(!$isFileAllowed) return false;

    return (!file_exists($new) && file_exists($old)) ? rename($old, $new) : null;
}

/**
 * Copy file or folder (recursively).
 * @param string $path
 * @param string $dest
 * @param bool $upd Update files
 * @param bool $force Create folder with same names instead file
 * @return bool
 */
function fm_rcopy($path, $dest, $upd = true, $force = true)
{
    if (is_dir($path)) {
        if (!fm_mkdir($dest, $force)) {
            return false;
        }
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' && $file != '..') {
                    if (!fm_rcopy($path . '/' . $file, $dest . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return $ok;
    } elseif (is_file($path)) {
        return fm_copy($path, $dest, $upd);
    }
    return false;
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
 * Safely copy file
 * @param string $f1
 * @param string $f2
 * @param bool $upd Indicates if file should be updated with new content
 * @return bool
 */
function fm_copy($f1, $f2, $upd)
{
    $time1 = filemtime($f1);
    if (file_exists($f2)) {
        $time2 = filemtime($f2);
        if ($time2 >= $time1 && $upd) {
            return false;
        }
    }
    $ok = copy($f1, $f2);
    if ($ok) {
        touch($f2, $time1);
    }
    return $ok;
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
 * HTTP Redirect
 * @param string $url
 * @param int $code
 */
function fm_redirect($url, $code = 302)
{
    header('Location: ' . $url, true, $code);
    exit;
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
 * Encode html entities
 * @param string $text
 * @return string
 */
function fm_enc($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
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
 * Save message in session
 * @param string $msg
 * @param string $status
 */
function fm_set_msg($msg, $status = 'ok')
{
    $_SESSION[FM_SESSION_ID]['message'] = $msg;
    $_SESSION[FM_SESSION_ID]['status'] = $status;
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
 * Get image files extensions
 * @return array
 */
function fm_get_image_exts()
{
    return array('ico', 'gif', 'jpg', 'jpeg', 'jpc', 'jp2', 'jpx', 'xbm', 'wbmp', 'png', 'bmp', 'tif', 'tiff', 'psd', 'svg', 'webp', 'avif');
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
 * Get audio files extensions
 * @return array
 */
function fm_get_audio_exts()
{
    return array('wav', 'mp3', 'ogg', 'm4a');
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
 * Get mime types of text files
 * @return array
 */
function fm_get_text_mimes()
{
    return array(
        'application/xml',
        'application/javascript',
        'application/x-javascript',
        'image/svg+xml',
        'message/rfc822',
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
 * Get online docs viewer supported files extensions
 * @return array
 */
function fm_get_onlineViewer_exts()
{
    return array('doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx', 'ai', 'psd', 'dxf', 'xps', 'rar', 'odt', 'ods');
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

/**
 * This function scans the files and folder recursively, and return matching files
 * @param string $dir
 * @param string $filter
 * @return json
 */
 function scan($dir, $filter = '') {
    $path = FM_ROOT_PATH.'/'.$dir;
     if($dir) {
         $ite = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
         $rii = new RegexIterator($ite, "/(" . $filter . ")/i");

         $files = array();
         foreach ($rii as $file) {
             if (!$file->isDir()) {
                 $fileName = $file->getFilename();
                 $location = str_replace(FM_ROOT_PATH, '', $file->getPath());
                 $files[] = array(
                     "name" => $fileName,
                     "type" => "file",
                     "path" => $location,
                 );
             }
         }
         return $files;
     }
}

/*
Parameters: downloadFile(File Location, File Name,
max speed, is streaming
If streaming - videos will show as videos, images as images
instead of download prompt
https://stackoverflow.com/a/13821992/1164642
*/

function fm_download_file($fileLocation, $fileName, $chunkSize  = 1024)
{
	if (connection_status() != 0)
        return (false);
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    $contentType = fm_get_file_mimes($extension);
	if(is_array($contentType)) {
        $contentType = implode(' ', $contentType);
    }

    header("Cache-Control: public");
    header("Content-Transfer-Encoding: binary\n");
    header("Content-Type: $contentType");

	$contentDisposition = 'attachment';

	if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
        $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
        header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
    } else {
        header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
    }

    header("Accept-Ranges: bytes");
    $range = 0;
    $size = filesize($fileLocation);



    if (isset($_SERVER['HTTP_RANGE'])) {
        list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
        str_replace($range, "-", $range);
        $size2 = $size - 1;
        $new_length = $size - $range;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Length: $new_length");
        header("Content-Range: bytes $range$size2/$size");
    } else {
        $size2 = $size - 1;
        header("Content-Range: bytes 0-$size2/$size");
        header("Content-Length: " . $size);
    }

    if ($size == 0) {
        die('Zero byte file! Aborting download');
    }
    @ini_set('magic_quotes_runtime', 0);
    $fp = fopen("$fileLocation", "rb");

    fseek($fp, $range);

    while (!feof($fp) and (connection_status() == 0)) {
        set_time_limit(0);
        print(@fread($fp, 1024*$chunkSize));
        flush();
        ob_flush();
        // sleep(1);
    }
    fclose($fp);

    return ((connection_status() == 0) and !connection_aborted());
}

function fm_get_theme() {
    $result = '';
    if(FM_THEME == "dark") {
        $result = "text-white bg-dark";
    }
    return $result;
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



//--- templates functions

/**
 * Show nav block
 * @param string $path
 */
function fm_show_nav_path($path)
{
    global $lang, $sticky_navbar, $editFile;
    $isStickyNavBar = $sticky_navbar ? 'fixed-top' : '';
    $getTheme = fm_get_theme();
    $getTheme .= " navbar-light";
    if(FM_THEME == "dark") {
        $getTheme .= " navbar-dark";
    } else {
        $getTheme .= " bg-white";
    }
    ?>
    <nav class="d-none navbar-file navbar navbar-expand-lg <?php echo $getTheme; ?> mb-4 main-nav <?php echo $isStickyNavBar ?>">
        <!-- <a class="navbar-brand" href=""> <?php echo lng('AppTitle') ?> </a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between filemanager-collapse" id="navbarSupportedContent">
          <?php
			$path = fm_clean_path($path);
            $root_url = "<a href='?action=filemanager&p='><i class='fa fa-home' aria-hidden='true' title='" . FM_ROOT_PATH . "'></i></a>";
            $sep = '<i class="bread-crumb"> / </i>';
            if ($path != '') {
                $exploded = explode('/', $path);
                $count = count($exploded);
                $array = array();
                $parent = '';
                for ($i = 0; $i < $count; $i++) {
                    $parent = trim($parent . '/' . $exploded[$i], '/');
                    $parent_enc = urlencode($parent);
                    $array[] = "<a href='?action=filemanager&p={$parent_enc}'>" . fm_enc(fm_convert_win($exploded[$i])) . "</a>";
                }
                $root_url .= $sep . implode($sep, $array);
            }
            echo '<div class="home-link d-none">' . $root_url . $editFile . '</div>';
            ?>
            <div class="filemanager-brand me-md-2">
                <h1>ROSNYC Drive</h1>
            </div>
            <div class="filemanager-search">
                <div class="d-flex search-wrap">
                    <div class="form-outline w-100">
                        <input type="text" class="form-control" aria-label="<?php echo lng('Search') ?>" aria-describedby="v-addon21" id="search-addon1234">
                        <label class="form-label" for="search-addon"><?php echo lng('Search') ?></label>
                    </div>
                    <div class="input-group-append">
                            <span class="input-group-text" id="search-addon2"><i class="fa fa-search"></i></span>
                    </div>
                     <div class="input-group-append btn-group d-none">
                            <span class="input-group-text dropdown-toggle" id="search-addon2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="<?php echo $path2 = $path ? $path : '.'; ?>" id="js-search-modal" data-toggle="modal" data-target="#searchModal"><?php echo lng('Advanced Search') ?></a>
                                </div>
                        </div>
                </div>
            </div>
            <div class="home-right-nav ms-lg-2">
                <ul class="navbar-nav mr-auto justify-content-end <?php echo fm_get_theme();  ?>">

                    <?php if (FM_USE_AUTH): ?>
                    <li class="nav-item avatar dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-5" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-user-circle"></i> <?php if(isset($_SESSION[FM_SESSION_ID]['logged'])) { echo $_SESSION[FM_SESSION_ID]['logged']; } ?></a>
                        <div class="dropdown-menu dropdown-menu-right <?php echo fm_get_theme(); ?>" aria-labelledby="navbarDropdownMenuLink-5">
                            <?php if (!FM_READONLY): ?>
                            <a title="<?php echo lng('Settings') ?>" class="dropdown-item nav-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;settings=1"><i class="fa fa-cog" aria-hidden="true"></i> <?php echo lng('Settings') ?></a>
                            <?php endif ?>
                            <a title="<?php echo lng('Help') ?>" class="dropdown-item nav-link" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;help=2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <?php echo lng('Help') ?></a>
                            <a title="<?php echo lng('Logout') ?>" class="dropdown-item nav-link" href="?logout=1"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php echo lng('Logout') ?></a>
                        </div>
                    </li>
                    <?php else: ?>
                        <?php if (!FM_READONLY): ?>
                            <li class="nav-item">
                                <a title="<?php echo lng('Settings') ?>" class="dropdown-item nav-link setting-icon icon-dark" href="?action=filemanager&p=<?php echo urlencode(FM_PATH) ?>&amp;settings=1"><i class="fa fa-cog fa-spin" aria-hidden="true"></i></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}

/**
 * Show message from session
 */
function fm_show_message()
{
    if (isset($_SESSION[FM_SESSION_ID]['message'])) {
        $class = isset($_SESSION[FM_SESSION_ID]['status']) ? $_SESSION[FM_SESSION_ID]['status'] : 'ok';
        //echo '<p class="message ' . $class . '">' . $_SESSION[FM_SESSION_ID]['message'] . '</p>';
        unset($_SESSION[FM_SESSION_ID]['message']);
        unset($_SESSION[FM_SESSION_ID]['status']);
    }
}

/**
 * Show page header in Login Form
 */
function fm_show_header_login()
{
$sprites_ver = '20160315';
header("Content-Type: text/html; charset=utf-8");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

global $lang, $root_url, $favicon_path;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Web based File Manager in PHP, Manage your files efficiently and easily with Tiny File Manager">
    <meta name="author" content="CCP Programmers">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
	<meta http-equiv="Content-Security-Policy" content="default-src 'self'">
    <?php if($favicon_path) { echo '<link rel="icon" href="'.fm_enc($favicon_path).'" type="image/png">'; } ?>
    <title><?php echo fm_enc(APP_TITLE) ?></title>
    <style>
        body.fm-login-page{ background-color:#f7f9fb;font-size:14px;background-color:#f7f9fb;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 304 304' width='304' height='304'%3E%3Cpath fill='%23e2e9f1' fill-opacity='0.4' d='M44.1 224a5 5 0 1 1 0 2H0v-2h44.1zm160 48a5 5 0 1 1 0 2H82v-2h122.1zm57.8-46a5 5 0 1 1 0-2H304v2h-42.1zm0 16a5 5 0 1 1 0-2H304v2h-42.1zm6.2-114a5 5 0 1 1 0 2h-86.2a5 5 0 1 1 0-2h86.2zm-256-48a5 5 0 1 1 0 2H0v-2h12.1zm185.8 34a5 5 0 1 1 0-2h86.2a5 5 0 1 1 0 2h-86.2zM258 12.1a5 5 0 1 1-2 0V0h2v12.1zm-64 208a5 5 0 1 1-2 0v-54.2a5 5 0 1 1 2 0v54.2zm48-198.2V80h62v2h-64V21.9a5 5 0 1 1 2 0zm16 16V64h46v2h-48V37.9a5 5 0 1 1 2 0zm-128 96V208h16v12.1a5 5 0 1 1-2 0V210h-16v-76.1a5 5 0 1 1 2 0zm-5.9-21.9a5 5 0 1 1 0 2H114v48H85.9a5 5 0 1 1 0-2H112v-48h12.1zm-6.2 130a5 5 0 1 1 0-2H176v-74.1a5 5 0 1 1 2 0V242h-60.1zm-16-64a5 5 0 1 1 0-2H114v48h10.1a5 5 0 1 1 0 2H112v-48h-10.1zM66 284.1a5 5 0 1 1-2 0V274H50v30h-2v-32h18v12.1zM236.1 176a5 5 0 1 1 0 2H226v94h48v32h-2v-30h-48v-98h12.1zm25.8-30a5 5 0 1 1 0-2H274v44.1a5 5 0 1 1-2 0V146h-10.1zm-64 96a5 5 0 1 1 0-2H208v-80h16v-14h-42.1a5 5 0 1 1 0-2H226v18h-16v80h-12.1zm86.2-210a5 5 0 1 1 0 2H272V0h2v32h10.1zM98 101.9V146H53.9a5 5 0 1 1 0-2H96v-42.1a5 5 0 1 1 2 0zM53.9 34a5 5 0 1 1 0-2H80V0h2v34H53.9zm60.1 3.9V66H82v64H69.9a5 5 0 1 1 0-2H80V64h32V37.9a5 5 0 1 1 2 0zM101.9 82a5 5 0 1 1 0-2H128V37.9a5 5 0 1 1 2 0V82h-28.1zm16-64a5 5 0 1 1 0-2H146v44.1a5 5 0 1 1-2 0V18h-26.1zm102.2 270a5 5 0 1 1 0 2H98v14h-2v-16h124.1zM242 149.9V160h16v34h-16v62h48v48h-2v-46h-48v-66h16v-30h-16v-12.1a5 5 0 1 1 2 0zM53.9 18a5 5 0 1 1 0-2H64V2H48V0h18v18H53.9zm112 32a5 5 0 1 1 0-2H192V0h50v2h-48v48h-28.1zm-48-48a5 5 0 0 1-9.8-2h2.07a3 3 0 1 0 5.66 0H178v34h-18V21.9a5 5 0 1 1 2 0V32h14V2h-58.1zm0 96a5 5 0 1 1 0-2H137l32-32h39V21.9a5 5 0 1 1 2 0V66h-40.17l-32 32H117.9zm28.1 90.1a5 5 0 1 1-2 0v-76.51L175.59 80H224V21.9a5 5 0 1 1 2 0V82h-49.59L146 112.41v75.69zm16 32a5 5 0 1 1-2 0v-99.51L184.59 96H300.1a5 5 0 0 1 3.9-3.9v2.07a3 3 0 0 0 0 5.66v2.07a5 5 0 0 1-3.9-3.9H185.41L162 121.41v98.69zm-144-64a5 5 0 1 1-2 0v-3.51l48-48V48h32V0h2v50H66v55.41l-48 48v2.69zM50 53.9v43.51l-48 48V208h26.1a5 5 0 1 1 0 2H0v-65.41l48-48V53.9a5 5 0 1 1 2 0zm-16 16V89.41l-34 34v-2.82l32-32V69.9a5 5 0 1 1 2 0zM12.1 32a5 5 0 1 1 0 2H9.41L0 43.41V40.6L8.59 32h3.51zm265.8 18a5 5 0 1 1 0-2h18.69l7.41-7.41v2.82L297.41 50H277.9zm-16 160a5 5 0 1 1 0-2H288v-71.41l16-16v2.82l-14 14V210h-28.1zm-208 32a5 5 0 1 1 0-2H64v-22.59L40.59 194H21.9a5 5 0 1 1 0-2H41.41L66 216.59V242H53.9zm150.2 14a5 5 0 1 1 0 2H96v-56.6L56.6 162H37.9a5 5 0 1 1 0-2h19.5L98 200.6V256h106.1zm-150.2 2a5 5 0 1 1 0-2H80v-46.59L48.59 178H21.9a5 5 0 1 1 0-2H49.41L82 208.59V258H53.9zM34 39.8v1.61L9.41 66H0v-2h8.59L32 40.59V0h2v39.8zM2 300.1a5 5 0 0 1 3.9 3.9H3.83A3 3 0 0 0 0 302.17V256h18v48h-2v-46H2v42.1zM34 241v63h-2v-62H0v-2h34v1zM17 18H0v-2h16V0h2v18h-1zm273-2h14v2h-16V0h2v16zm-32 273v15h-2v-14h-14v14h-2v-16h18v1zM0 92.1A5.02 5.02 0 0 1 6 97a5 5 0 0 1-6 4.9v-2.07a3 3 0 1 0 0-5.66V92.1zM80 272h2v32h-2v-32zm37.9 32h-2.07a3 3 0 0 0-5.66 0h-2.07a5 5 0 0 1 9.8 0zM5.9 0A5.02 5.02 0 0 1 0 5.9V3.83A3 3 0 0 0 3.83 0H5.9zm294.2 0h2.07A3 3 0 0 0 304 3.83V5.9a5 5 0 0 1-3.9-5.9zm3.9 300.1v2.07a3 3 0 0 0-1.83 1.83h-2.07a5 5 0 0 1 3.9-3.9zM97 100a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-48 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 96a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-144a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM49 36a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM33 68a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 240a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm80-176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm112 176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 180a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 84a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'%3E%3C/path%3E%3C/svg%3E");}
        .fm-login-page .brand{ width:121px;overflow:hidden;margin:0 auto;position:relative;z-index:1}
        .fm-login-page .brand img{ width:100%}
        .fm-login-page .card-wrapper{ width:360px;margin-top:10%;margin-left:auto;margin-right:auto;}
        .fm-login-page .card{ border-color:transparent;box-shadow:0 4px 8px rgba(0,0,0,.05)}
        .fm-login-page .card-title{ margin-bottom:1.5rem;font-size:24px;font-weight:400;}
        .fm-login-page .form-control{ border-width:2.3px}
        .fm-login-page .form-group label{ width:100%}
        .fm-login-page .btn.btn-block{ padding:12px 10px}
        .fm-login-page .footer{ margin:40px 0;color:#888;text-align:center}
        @media screen and (max-width:425px){
            .fm-login-page .card-wrapper{ width:90%;margin:0 auto;margin-top:10%;}
        }
        @media screen and (max-width:320px){
            .fm-login-page .card.fat{ padding:0}
            .fm-login-page .card.fat .card-body{ padding:15px}
        }
        .message{ padding:4px 7px;border:1px solid #ddd;background-color:#fff}
        .message.ok{ border-color:green;color:green}
        .message.error{ border-color:red;color:red}
        .message.alert{ border-color:orange;color:orange}
        body.fm-login-page.theme-dark {background-color: #2f2a2a;}
        .theme-dark svg g, .theme-dark svg path {fill: #ffffff; }
    </style>
</head>
<body class="fm-login-page <?php echo (FM_THEME == "dark") ? 'theme-dark' : ''; ?>">
<div id="wrapper" class="w-100">

    <?php
    }

    /**
     * Show page footer in Login Form
     */
    function fm_show_footer_login()
    {
    ?>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>
<?php
}

/**
 * Show Header after login
 */
function fm_show_header()
{
$sprites_ver = '20160315';
header("Content-Type: text/html; charset=utf-8");

header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

global $lang, $root_url, $sticky_navbar, $favicon_path;
$isStickyNavBar = $sticky_navbar ? 'navbar-fixed' : 'navbar-normal';
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Web based File Manager in PHP, Manage your files efficiently and easily with Tiny File Manager">
    <meta name="author" content="CCP Programmers">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">

    <?php if($favicon_path) { echo '<link rel="icon" href="'.fm_enc($favicon_path).'" type="image/png">'; } ?>
    <title><?php echo fm_enc(APP_TITLE) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css" />
    <?php if (FM_USE_HIGHLIGHTJS): ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.6.0/styles/<?php echo FM_HIGHLIGHTJS_STYLE ?>.min.css">
    <?php endif; ?>
    <style>
        body { font-size:14px;color:#222;background:#F7F7F7; }
        body.navbar-fixed { margin-top:55px; }
        a:hover, a:visited, a:focus { text-decoration:none !important; }
        .filename, td, th { white-space:nowrap  }
        .navbar-brand { font-weight:bold; }
        .nav-item.avatar a { cursor:pointer;text-transform:capitalize; }
        .nav-item.avatar a > i { font-size:15px; }
        .nav-item.avatar .dropdown-menu a { font-size:13px; }
        #search-addon { font-size:15px;min-height: 45px;padding: 7px 55px 7px 22px;border-radius: 20px; }
        .bread-crumb { color:#cccccc;font-style:normal;padding: 0 3px; }
        .table td, .table th { vertical-align:middle !important; }
        .table .custom-checkbox-td .custom-control.custom-checkbox, .table .custom-checkbox-header .custom-control.custom-checkbox { min-width:18px; }
        .table-sm td, .table-sm th { padding:.4rem; }
        .table-bordered td, .table-bordered th { border:1px solid #f1f1f1; }
        .hidden { display:none  }
        pre.with-hljs { padding:0  }
        pre.with-hljs code { margin:0;border:0;overflow:visible  }
        code.maxheight, pre.maxheight { max-height:512px  }
        .fa.fa-caret-right { font-size:1.2em;margin:0 4px;vertical-align:middle;color:#ececec  }
        .fa.fa-home { font-size:1.3em;vertical-align:bottom  }
        .path { margin-bottom:10px  }
        form.dropzone { /*min-height:200px;*/ border:4px dashed #007bff;line-height:6rem; margin-top: 20px; }
		.dz-custom{ padding-left: 41px !important; margin-left: 63px;}
		.dz-success{ display:none !important; }
		.dz-custom-file{margin-left: 10px; /*width: 413px !important;*/ line-height: 1rem !important; }
		.right { text-align:right  }
        .center, .close, .login-form { text-align:center  }
        .message { padding:4px 7px;border:1px solid #ddd;background-color:#fff  }
        .message.ok { border-color:green;color:green  }
        .message.error { border-color:red;color:red  }
        .message.alert { border-color:orange;color:orange  }
        .preview-img { max-width:100%;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAKklEQVR42mL5//8/Azbw+PFjrOJMDCSCUQ3EABZc4S0rKzsaSvTTABBgAMyfCMsY4B9iAAAAAElFTkSuQmCC)  }
        .inline-actions > a > i { font-size:1em;margin-left:5px;background:#3785c1;color:#fff;padding:3px;border-radius:3px  }
        .preview-video { position:relative;max-width:100%;height:0;padding-bottom:62.5%;margin-bottom:10px  }
        .preview-video video { position:absolute;width:100%;height:100%;left:0;top:0;background:#000  }
        .compact-table { border:0;width:auto  }
        .compact-table td, .compact-table th { width:100px;border:0;text-align:center  }
        .compact-table tr:hover td { background-color:#fff  }
        .filename { max-width:420px;overflow:hidden;text-overflow:ellipsis  }
        .break-word { word-wrap:break-word;margin-left:30px  }
        .break-word.float-left a { color:#7d7d7d  }
        .break-word + .float-right { padding-right:30px;position:relative  }
        .break-word + .float-right > a { color:#7d7d7d;font-size:1.2em;margin-right:4px  }
        #editor { position:absolute;right:15px;top:100px;bottom:15px;left:15px  }
        @media (max-width:481px) {
            #editor { top:150px; }
        }
        #normal-editor { border-radius:3px;border-width:2px;padding:10px;outline:none; }
        .btn-2 { border-radius:0;padding:3px 6px;font-size:small; }
        li.file:before,li.folder:before { font:normal normal normal 14px/1 FontAwesome;content:"\f016";margin-right:5px }
        li.folder:before { content:"\f114" }
        i.fa.fa-picture-o { color:#26b99a }
        i.fa.fa-file-archive-o { color:#da7d7d }
        .btn-2 i.fa.fa-file-archive-o { color:inherit }
        i.fa.fa-css3 { color:#f36fa0 }
        i.fa.fa-file-code-o { color:#007bff }
        i.fa.fa-code { color:#cc4b4c }
        i.fa.fa-file-text-o { color:#0096e6 }
        i.fa.fa-html5 { color:#d75e72 }
        i.fa.fa-file-excel-o { color:#09c55d }
        i.fa.fa-file-powerpoint-o { color:#f6712e }
        i.go-back { font-size:1.2em;color:#007bff; }
        .dataTables_filter { display:none; }
        table.dataTable thead .sorting { cursor:pointer;background-repeat:no-repeat;background-position:center right;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAQAAADYWf5HAAAAkElEQVQoz7XQMQ5AQBCF4dWQSJxC5wwax1Cq1e7BAdxD5SL+Tq/QCM1oNiJidwox0355mXnG/DrEtIQ6azioNZQxI0ykPhTQIwhCR+BmBYtlK7kLJYwWCcJA9M4qdrZrd8pPjZWPtOqdRQy320YSV17OatFC4euts6z39GYMKRPCTKY9UnPQ6P+GtMRfGtPnBCiqhAeJPmkqAAAAAElFTkSuQmCC'); }
        table.dataTable thead .sorting_asc { cursor:pointer;background-repeat:no-repeat;background-position:center right;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAZ0lEQVQ4y2NgGLKgquEuFxBPAGI2ahhWCsS/gDibUoO0gPgxEP8H4ttArEyuQYxAPBdqEAxPBImTY5gjEL9DM+wTENuQahAvEO9DMwiGdwAxOymGJQLxTyD+jgWDxCMZRsEoGAVoAADeemwtPcZI2wAAAABJRU5ErkJggg=='); }
        table.dataTable thead .sorting_desc { cursor:pointer;background-repeat:no-repeat;background-position:center right;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAZUlEQVQ4y2NgGAWjYBSggaqGu5FA/BOIv2PBIPFEUgxjB+IdQPwfC94HxLykus4GiD+hGfQOiB3J8SojEE9EM2wuSJzcsFMG4ttQgx4DsRalkZENxL+AuJQaMcsGxBOAmGvopk8AVz1sLZgg0bsAAAAASUVORK5CYII='); }
        table.dataTable thead tr:first-child th.custom-checkbox-header:first-child { background-image:none; }
        .footer-action li { margin-bottom:10px; }
        .app-v-title { font-size:24px;font-weight:300;letter-spacing:-.5px;text-transform:uppercase; }
        hr.custom-hr { border-top:1px dashed #8c8b8b;border-bottom:1px dashed #fff; }
        .ekko-lightbox .modal-dialog { max-width:1000px; }
        .ekko-lightbox-item.fade.in.show .row { background:#fff; }
        .ekko-lightbox-nav-overlay { display:flex !important;opacity:1 !important;height:auto !important;top:calc(50% - 27px); }
        .ekko-lightbox-nav-overlay a { opacity:1 !important;width:auto !important;text-shadow:none !important;color:#3B3B3B; }
        .ekko-lightbox-nav-overlay a:hover { color:#20507D; }

#snackbar { visibility:hidden;min-width:250px;margin-left:-125px;background-color:#90EE90;color:#fff;text-align:center;border-radius:2px;padding:16px;position:fixed;z-index:1;left:50%;bottom:30px;font-size:17px; }
        #snackbar.show { visibility:visible;-webkit-animation:fadein 0.5s, fadeout 0.5s 2.5s;animation:fadein 0.5s, fadeout 0.5s 2.5s; }
        @-webkit-keyframes fadein { from { bottom:0;opacity:0; }
        to { bottom:30px;opacity:1; }
        }
        @keyframes fadein { from { bottom:0;opacity:0; }
        to { bottom:30px;opacity:1; }
        }
        @-webkit-keyframes fadeout { from { bottom:30px;opacity:1; }
        to { bottom:0;opacity:0; }
        }
        @keyframes fadeout { from { bottom:30px;opacity:1; }
        to { bottom:0;opacity:0; }
        }

        #main-table span.badge {color: #292f4c;font-weight: 300;}
        @media only screen and (min-device-width:768px) and (max-device-width:1024px) and (orientation:landscape) and (-webkit-min-device-pixel-ratio:2) { .navbar-collapse .col-xs-6.text-right { padding:0; }
        }
        .lds-facebook { display:none;position:relative;width:64px;height:64px }
        .lds-facebook div,.lds-facebook.show-me { display:inline-block }
        .lds-facebook div { position:absolute;left:6px;width:13px;background:#007bff;animation:lds-facebook 1.2s cubic-bezier(0,.5,.5,1) infinite }
        .lds-facebook div:nth-child(1) { left:6px;animation-delay:-.24s }
        .lds-facebook div:nth-child(2) { left:26px;animation-delay:-.12s }
        .lds-facebook div:nth-child(3) { left:45px;animation-delay:0s }
        @keyframes lds-facebook { 0% { top:6px;height:51px }
        100%,50% { top:19px;height:26px }
        }
        ul#search-wrapper { padding-left: 0;border: 1px solid #ecececcc; } ul#search-wrapper li { list-style: none; padding: 5px;border-bottom: 1px solid #ecececcc; }
        ul#search-wrapper li:nth-child(odd){ background: #f9f9f9cc;}
        .c-preview-img {
            max-width: 300px;
        }
		.filemanager-wrap .my-file-inner .card .card-body h5 {
             font-size: 14px !important;
		}

		.custom-checkbox1{
			display: revert; !important;
		}
		a#collapseOneLink1 {
		position: absolute;
		top: 0px;
		right: 0px;
		z-index: 999;
		}

    </style>
    <?php
    if (FM_THEME == "dark"): ?>
        <style>
            body.theme-dark { background-color: #2f2a2a; }
            .list-group .list-group-item { background: #343a40; }
            .theme-dark .navbar-nav i, .navbar-nav .dropdown-toggle, .break-word { color: #ffffff; }
            a, a:hover, a:visited, a:active, #main-table .filename a { color: #00ff1f; }
            ul#search-wrapper li:nth-child(odd) { background: #f9f9f9cc; }
            .theme-dark .btn-outline-primary { color: #00ff1f; border-color: #00ff1f; }
            .theme-dark .btn-outline-primary:hover, .theme-dark .btn-outline-primary:active { background-color: #028211;}
        </style>
    <?php endif; ?>

</head>
<body class="<?php echo (FM_THEME == "dark") ? 'theme-dark' : ''; ?> <?php echo $isStickyNavBar; ?>">
<div id="wrapper" class="w-100">

    <!-- New Item creation -->
    <div class="modal fade" id="createNewItem" tabindex="-1" role="dialog" aria-label="newItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content <?php echo fm_get_theme(); ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="newItemModalLabel"><?php echo lng('CreateNewItem') ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                     <!--<div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline1" name="newfile" value="file" class="custom-control-input">
                        <label class="custom-control-label" for="customRadioInline1"><?php echo lng('File') ?></label>
                    </div>

                    <div class="custom-control custom-radio custom-control-inline">
                        <input type="radio" id="customRadioInline2" name="newfile" value="folder" class="custom-control-input" checked="">
                        <label class="custom-control-label" for="customRadioInline2"><?php echo lng('Folder') ?></label>
                    </div>-->
                   <!-- <div class="d-flex align-items-center mb-4">

                        <p class="m-0 me-3"><label for="newfile"><?php echo lng('ItemType') ?> </label></p>
                        <div class="btn-group">
                            <input type="radio" id="customRadioInline1" name="newfile" autocomplete="off" value="file" class="btn-check" checked="">
                            <label class="btn btn-secondary" for="customRadioInline1"><?php echo lng('File') ?></label>

                            <input type="radio" id="customRadioInline2" name="newfile" autocomplete="off" value="folder" class="btn-check">
                            <label class="btn btn-secondary" for="customRadioInline2"><?php echo lng('Folder') ?></label>
                        </div>

                    </div> -->
                    <!-- <p class="mt-3"><label for="newfilename"><?php echo lng('ItemName') ?> </label></p>
                    <input type="text" name="newfilename" id="newfilename" value="" class="form-control"> -->

                    <div class="form-outline">
					    <input type="text" name="newfilename" id="newfilename" value="" class="form-control" />
                        <label class="form-label" for="newfilename"><?php echo lng('ItemName') ?></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><i class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></button> -->
                    <input type="hidden" name="folder_fm_path" id="folder_fm_path" class="form-control" value="">
					<button type="button" class="btn btn-primary m-0" onclick="createnewfolder('<?php echo fm_enc(FM_PATH) ?>');return false;"><i class="fa fa-check-circle"></i> <?php echo lng('CreateNow') ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content <?php echo fm_get_theme(); ?>">
          <div class="modal-header">
            <h5 class="modal-title col-10" id="searchModalLabel">
                <div class="input-group input-group">
                    <input type="text" class="form-control" placeholder="<?php echo lng('Search') ?> a files" aria-label="<?php echo lng('Search') ?>" aria-describedby="search-addon3" id="advanced-search" autofocus required>
                    <div class="input-group-append">
                        <span class="input-group-text" id="search-addon3"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
                <div class="lds-facebook"><div></div><div></div><div></div></div>
                <ul id="search-wrapper">
                    <p class="m-2"><?php echo lng('Search file in folder and subfolders...') ?></p>
                </ul>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/html" id="js-tpl-modal">
        <div class="modal fade" id="js-ModalCenter-<%this.id%>" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalCenterTitle"><%this.title%></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <%this.content%>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal"><i class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></button>
                        <%if(this.action){%><button type="button" class="btn btn-primary" id="js-ModalCenterAction" data-type="js-<%this.action%>"><%this.action%></button><%}%>
                    </div>
                </div>
            </div>
        </div>
    </script>
	<?php
    }
    /**
     * Show page footer
     */
	function fm_show_footer($size,$sucessmsg,$sucessmsgbulkdelete)
    {
	?>
</div>
<?php
//include('toast.php'); ?>
 <button style="display:none;" type="button" class="btn btn-primary" id="basic-primary-trigger">Primary</button>
 <button style="display:none;" type="button" class="btn btn-danger" id="basic-danger-trigger">Danger</button>
 <!---------- Toast Message --------->
  <div
  class="toast fade mx-auto"
  id="basic-primary-example"
  role="alert"
  aria-live="assertive"
  aria-atomic="true"
  data-mdb-autohide="true"
  data-mdb-delay="2000"
  data-mdb-position="top-right"
  data-mdb-append-to-body="true"
  data-mdb-stacking="true"
  data-mdb-width="350px"
  data-mdb-color="success"
>
  <div class="toast-header" style="color:white;">
    <strong class="me-auto">Message</strong>
    <small></small>
    <button type="button" class="btn-close" data-mdb-dismiss="toast" aria-label="Close"></button>
  </div>
  <div class="toast-body" style="color:white;" id="toast-message"></div>
</div>

<!--basic-danger-example Error-->
<div
  class="toast fade mx-auto"
  id="basic-danger-example"
  role="alert"
  aria-live="assertive"
  aria-atomic="true"
  data-mdb-autohide="true"
  data-mdb-delay="2000"
  data-mdb-position="top-right"
  data-mdb-append-to-body="true"
  data-mdb-stacking="true"
  data-mdb-width="350px"
  data-mdb-color="danger"
>
    <div class="toast-header" style="color:white;">
      <strong class="me-auto">Message</strong>
       <small></small>
        <button type="button" class="btn-close" data-mdb-dismiss="toast" aria-label="Close"></button>
      </div>
    <div class="toast-body" id="toast-danger-message" style="color:white;"></div>
</div>
<!---------- basic-danger-example Error------>
<!---------- Toast Message ------------------>
<script>
const toasts = [
		'basic-primary-example',
		'basic-danger-example',
		'basic-primary-trash',
		'basic-primary-unstarred',
		'basic-primary-restore',
		'basic-primary-trash-delete',
		'basic-primary-delete-shared',
		'basic-primary-bulk-delete'
		];
        const triggers = [
		'basic-primary-trigger',
		'basic-danger-trigger',
		'basic-primary-trash-trigger',
		'basic-primary-unstarred-trigger',
		'basic-primary-restore-trigger',
		'basic-primary-trash-delete-trigger',
		'basic-primary-delete-shared-trigger',
		'basic-primary-bulk-delete-trigger'
		];
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.11.5/sorting/absolute.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js"></script>
<script src="https://themesbrand.com/skote-django/layouts/assets/libs/apexcharts/apexcharts.min.js"></script>
<script src="https://rosnyc.com/admin/filemanager/jquery.fileuploader.min.js"></script>
<?php if (FM_USE_HIGHLIGHTJS): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/10.6.0/highlight.min.js"></script>
    <script>hljs.highlightAll(); var isHighlightingEnabled = true;</script>
<?php endif; ?>
<script src="https://rosnyc.com/dev_admin/assets/mdb/js/mdb.min.js"></script>
<script>
<!------------------- Content Load onLoad Page and Tabs ------------------->
 $('#loader').show();
   $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
   Dropzone.discover();
   $('#loader').hide();
  //setTimeout(function() { $("#msg_all").fadeOut('slow'); }, 4000);
})


$("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php",function(){
  Dropzone.discover();
});

<!------------------- Content Load For Files Tabs ------------------->
$(".tab-1").click(function(){
  $('#loader').show();
    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
	Dropzone.discover();
    $('#loader').hide();
  })
})

<!------------------- Content Load For Shared Tabs ------------------->
$(".tab-2").click(function(){
 $('#loader').show();
    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_share.php",function(){
		// Code for the shared
		var $table1 = $('#main-table1'),
            tableLng1 = $table1.find('th').length,
            _targets1 = (tableLng1 && tableLng1 == 7 ) ? [0, 4,5,6] : tableLng1 == 5 ? [0,4] : [3],
            emptyType1 = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);
            mainTable1 = $('#main-table1').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets1, orderable: false}, {type: emptyType1, targets: '_all',},]
        });

		// Code for search
        $('#search-addon').on( 'keyup', function () {

			mainTable.search( this.value ).draw();
			mainTable1.search( this.value ).draw();
			mainTable2.search( this.value ).draw();
			mainTable3.search( this.value ).draw();
        });
    $('#loader').hide();
	})
})

$(".tab-3").click(function(){
 $('#loader').show();
    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_starred.php",function(){

		// Code for the starred
		var $table2 = $('#main-table2'),
			tableLng2 = $table2.find('th').length,
			_targets2 = (tableLng2 && tableLng2 == 7 ) ? [0, 4,5,6] : tableLng2 == 5 ? [0,4] : [3],
			emptyType2 = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);
			mainTable2 = $('#main-table2').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets2, orderable: false}, {type: emptyType2, targets: '_all',},]
		});

		 //Code for search
        $('#search-addon').on( 'keyup', function () {
			mainTable.search( this.value ).draw();
			mainTable1.search( this.value ).draw();
			mainTable2.search( this.value ).draw();
			mainTable3.search( this.value ).draw();
        });
	  $('#loader').hide();
	})
  })

 $(".tab-4").click(function(){
 $('#loader').show();
    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_trash.php",function(){
		// Code for the trash
		var $table3 = $('#main-table3'),
            tableLng3 = $table3.find('th').length,
            _targets3 = (tableLng3 && tableLng3 == 7 ) ? [0, 4,5,6] : tableLng3 == 5 ? [0,4] : [3],
            emptyType3 = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);
            mainTable3 = $('#main-table3').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets3, orderable: false}, {type: emptyType3, targets: '_all',},]
        });

		 //Code for search
        $('#search-addon').on( 'keyup', function () {
			mainTable.search( this.value ).draw();
			mainTable1.search( this.value ).draw();
			mainTable2.search( this.value ).draw();
			mainTable3.search( this.value ).draw();
        });
	   $('#loader').hide();
	})
})

	function getStorage(storageID){
		$('#loader').show();
		 $("#content_div").load("https://rosnyc.com/admin/filemanager/storage.php?storageID="+storageID,function(){
		  $('#loader').hide();
	   })
	}

	function insidefolder(folderpath){
		alert(folderpath);
		$('#loader').show();
        $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+folderpath,function(){
		$('#loader').hide();
		if(folderpath == ''){
		     $('#home_storage_data').show();
             $("#folder_fm_path").val('');
		   }
		   else {
			$('#home_storage_data').hide();
			$("#folder_fm_path").val(folderpath);
		   }
		   Dropzone.discover();
		});

		$("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php?p="+folderpath,function(){
		    Dropzone.discover();
		});
	}
	</script>
	<!-- check if the link of the share folder open from the browser -->
	<?php
	if($_GET['sharedu']){
		$p=$_GET['p'];
		$p = $_GET['p'];
		echo '<script type=text/javascript>insidefolder("'.$p.'");</script>';
	}
    ?>
    <script>
	function insidefoldershared(folderpath, userID){
	$('#loader').show();
        $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+folderpath+"&sharedu="+userID,function(){
		$('#loader').hide();
		if(folderpath == ''){
		     $('#home_storage_data').show();
             $("#folder_fm_path").val('');
		   }
		   else {
			$('#home_storage_data').hide();
			$("#folder_fm_path").val(folderpath);
		   }
		   Dropzone.discover();
		});

		$("#right-side-content_div").load("https://rosnyc.com/admin/filemanager/rightside.php?p="+folderpath,function(){
		    Dropzone.discover();
		});
	}

    triggers.forEach((trigger, index) => {
		   let basicInstance = mdb.Toast.getInstance(document.getElementById(toasts[index]));
		   document.getElementById(trigger).addEventListener('click', () => {
		   basicInstance.show();
		  });
		});

   /*function getStorage(storageID){
	var storageID=storageID;
    var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/storage.php",
       method: "POST",
       crossDomain: true,
	   data:{ storageID: storageID},
       dataType: 'json',
	   beforeSend: function() {
              $("#loader-storage").show();
	   },
	   complete: function(){
		     $("#loader-storage").hide();
	  },
       success:function(data){
		    //call again the ajax function
			  if(data.success == true){
			      $('#getAllStorageHTML').hide();
				  $('#getStorageHTML').show();
				  $("#getStorageHTML").html(data.storageHtml);
				}
				var $table1 = $('#main-table1'),
				tableLng = $table1.find('th').length,
				_targets = (tableLng && tableLng == 7 ) ? [0, 4,5,6] : tableLng == 5 ? [0,4] : [3],
				emptyType = $.fn.dataTable.absoluteOrder([{ value: '', position: 'top' }]);

				mainTable = $('#main-table1').DataTable({
					paging: false, info: false, order: [],
					columnDefs: [{targets: _targets, orderable: false},
					{type: emptyType, targets: '_all',},]
				});
			 },
		  error: function(errorThrown){
			  //alert(errorThrown);
			console.log(errorThrown);
		 }
        });
	}*/

   function starred(data){
	   var starred_data=data;
       var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ starred_data: starred_data, userId: userId},
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data){
		    //call again the ajax function
			 if(data.success == true){
				   $("#toast-message").html('Successfully Starred');
				   $("#basic-primary-trigger").trigger('click');
				}
			  if(data.success == false){
				   $("#toast-danger-message").html('Already Starred!');
			       $("#basic-danger-trigger").trigger('click');
			   }
			},
		     error: function(errorThrown){
			 console.log(errorThrown);
		    }
        });
	}

	function unstarrred(unstarredid){
	   var unstarredid=unstarredid;
	   var unstarred = 1;
       var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ unstarred: unstarred, unstarredid: unstarredid,  userId: userId },
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data){
		    //call again the ajax function
			 if(data.success == true){
				   $("#toast-message").html('Successfully UnStarred');
				   $("#basic-primary-trigger").trigger('click');
				   $("#content_div").load("https://rosnyc.com/admin/filemanager/load_starred.php",function(){

				   })
				}
			  if(data.success == false){
				   $("#toast-danger-message").html('Something Went Wrong!');
			       $("#basic-danger-trigger").trigger('click');
			   }
			},
		     error: function(errorThrown){
			 console.log(errorThrown);
		    }
        });
	}

	function downloadfile(path,data){
		   var down_data=data;
		   var fm_path=path;
		   var userId=<?php echo $_SESSION['userId']; ?>
		   $.ajax({
		   url : "https://rosnyc.com/admin/filemanager/ajax.php",
		   method: "POST",
		   crossDomain: true,
		   data:{ dl: down_data, userId: userId, fm_path:fm_path},
		   dataType: 'json',
		   beforeSend: function() {
				  $("#loader").show();
		   },
		   complete: function(){
				 $("#loader").hide();
		  },
		   success:function(data){
			    var path = data.toBeDownloaded;
				var link = document.createElement('a');
				link.href = path;
				link.download = data.dl;
				link.target = '_blank';
				link.click();
				},
				error: function(errorThrown){
					alert('here');
				console.log(errorThrown);
			  }
			});
	    }

	function sendtotrash(data,rowid){
		   var trash_data=data;
		   var rowid=rowid;
		   var userId=<?php echo $_SESSION['userId']; ?>
		   $.ajax({
		   url : "https://rosnyc.com/admin/filemanager/ajax.php",
		   method: "POST",
		   crossDomain: true,
		   data:{ trash_data: trash_data, userId: userId, rowid:rowid},
		   dataType: 'json',
		   beforeSend: function() {
				  $("#loader").show();
		   },
		   complete: function(){
				 $("#loader").hide();
		  },
		   success:function(data){
				//call again the ajax function
				  if(data.success == true){

						$('html, body').animate({
						   scrollTop: $("#"+data.rowid).offset().top
						}, 1000);

						$("#"+data.rowid).remove();

						 $("#toast-message").html('Successfully Send to trash');
						 $("#basic-primary-trigger").trigger('click');
						 //$("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
							 //alert('yes');
							//Dropzone.discover();
						//})


					}
				},
				error: function(errorThrown){
				console.log(errorThrown);
			 }
			});
	}


		function sendtotrashfolder(data,pathid){
		   var trash_data_folder=data;
		   var pathid=pathid;
		   var userId=<?php echo $_SESSION['userId']; ?>
		   $.ajax({
		   url : "https://rosnyc.com/admin/filemanager/ajax.php",
		   method: "POST",
		   crossDomain: true,
		   data:{ trash_data_folder: trash_data_folder, userId: userId, pathid:pathid},
		   dataType: 'json',
		   beforeSend: function() {
				  $("#loader").show();
		   },
		   complete: function(){
				 $("#loader").hide();
		  },
		   success:function(data){
				//call again the ajax function
				  if(data.success == true){
					     $("#toast-message").html('Successfully Send to trash');
						 $("#basic-primary-trigger").trigger('click');
					if(data.pathid != ''){
						var scroll = $('.file-scroll').scrollTop();
						$("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+pathid,function(){
		                       Dropzone.discover();
							   $('.file-scroll').scrollTop(scroll);
							   $('#home_storage_data').hide();
	                     })
					   }
					   else {
						var scroll = $('.file-scroll').scrollTop();
					    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
							Dropzone.discover();
							$('.file-scroll').scrollTop(scroll);
						})
					  }
					}
				},
				error: function(errorThrown){
				console.log(errorThrown);
			 }
			});
	}

   function restore(data,rowid){
	var restore_data=data;
	var rowid=rowid;
	var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ restore_data: restore_data, userId: userId, rowid:rowid},
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data){
		    //call again the ajax function
		      if(data.success == true){
			      /*$('.response-error').show();
				  $("#error-message").html('Successfully Send from Trash');
					setTimeout(function() {
					$('.response-error').fadeOut('fast');
					}, 2000); // <-- time in milliseconds*/
					$("#"+data.rowid).remove();
					$("#basic-primary-restore-trigger").trigger('click');

					$("#toast-message").html('Successfully Restore');
					$("#basic-primary-trigger").trigger('click');

					$("#"+data.rowid).remove();

					$("#trash_model"+data.rowid+" .close").click();
				}
			},
		  error: function(errorThrown){
			console.log(errorThrown);
		   }
        });
	}

	function deletefromtrash(data,rowid){
	var trash_del_data=data;
	var rowid=rowid;
	var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ trash_del_data: trash_del_data, userId: userId, rowid:rowid},
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data){
		    //call again the ajax function
		      if(data.success == true){
			      /*$('.response-error').show();
				  $("#error-message").html('Successfully Send from Trash');
					setTimeout(function() {
					$('.response-error').fadeOut('fast');
					}, 2000); // <-- time in milliseconds*/
				 $("#"+data.rowid).remove();
				 $("#basic-primary-trash-delete-trigger").trigger('click');

					$("#toast-message").html('Successfully Permanent Deleted !');
					$("#basic-primary-trigger").trigger('click');
				}
			},
		  error: function(errorThrown){
			console.log(errorThrown);
		   }
        });
	}

 function sharedata(data,modelId){
	var root_path=data;
	if($("#share_emails"+modelId).val() == ''){
		alert('Please Enter the Email ID');
		//$("#share_emails"+modelId).focus();
		return false
	}

	var share_emails=$("#share_emails"+modelId).val();

	var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ root_path: root_path, userId: userId, share_emails:share_emails},
       dataType: 'json',
	   beforeSend: function() {
              $("#loader-model"+modelId).show();
	   },
	   complete: function(){
		     $("#loader-model"+modelId).hide();
	  },
       success:function(data){
		    //call again the ajax function
		      if(data.success == true){
			      /*$('.response-error-model'+modelId).show();
				  $("#error-message-model"+modelId).html('Successfully Shared');*/
				  $("#toast-message").html('Successfully Shared');
					$("#basic-primary-trigger").trigger('click');
				    setTimeout(function() {
					$('.response-error-model'+modelId).fadeOut('fast');
					}, 2000); // <-- time in milliseconds

					setTimeout(function() {
					$("#shareModal"+modelId+" .close").click();
					}, 2500); // <-- time in milliseconds

					$("#share_emails"+modelId).val('');
			    }

			   if(data.success == false){
			      $('.response-error-model'+modelId).show();
				  $("#error-message-model"+modelId).html(data.message);
					setTimeout(function() {
					$('.response-error-model'+modelId).fadeOut('fast');
					}, 2000); // <-- time in milliseconds

				    setTimeout(function() {
					$("#shareModal"+modelId+" .close").click();
				  }, 2500); // <-- time in milliseconds

                  $("#share_emails"+modelId).val('');
			   }
			},
		  error: function(errorThrown){
			console.log(errorThrown);
		 }
        });
	}
	// Share Delete Single
	function sharedelete(sharedelete){
	   let shardelete=sharedelete;
	   let deleteshare=1;
	   var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ sharedel: shardelete, userId: userId, deleteshare: deleteshare},
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data)//call again the ajax function
	   {
		  if(data.success == true){
				$("#toast-message").html(data.message);
				$("#basic-primary-trigger").trigger('click');
				$("#content_div").load("https://rosnyc.com/admin/filemanager/load_share.php",function(){

				})
			}
			if(data.success == false){
					$("#toast-danger-message").html(data.message);
					$("#basic-danger-trigger").trigger('click');
		        }
			},
		  error: function(errorThrown){
			console.log(errorThrown);
		 }
        });
    }


	// Function Mass Starred Delete
	function massstarreddelete(fm_root_path,fm_path){
		var selectedvalues = []; // New array
            $('input[type=checkbox]:checked').each(function() {
		        selectedvalues.push(this.value);
		   });
		 console.log("selectedvalues===>"+selectedvalues);

		let FM_ROOT_PATH=fm_root_path;
		let FM_PATH=fm_path;
		let userId=<?php echo $_SESSION['userId']; ?>
		// Ajax Call
		let massstarreddelete=1;
	  $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ massstarreddelete: massstarreddelete, userId: userId, selectedvalues: selectedvalues, FM_ROOT_PATH: FM_ROOT_PATH, FM_PATH: FM_PATH },
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data)//call again the ajax function
	   {
		  if(data.success == true){
				$("#toast-message").html(data.message);
				$("#basic-primary-trigger").trigger('click');
				// Path Return Back to Same Foder Path after Drop Folder
			   let _pfolderpath= data.p;

			   if(_pfolderpath != ''){
				$("#content_div").load("https://rosnyc.com/admin/filemanager/load_starred.php?p="+_pfolderpath,function(){
					   Dropzone.discover();
				 })
			   }
			   else {
			    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_starred.php",function(){
					Dropzone.discover();
				})
			  }
			}
			if(data.success == false){
					$("#toast-danger-message").html(data.message);
					$("#basic-danger-trigger").trigger('click');
		        }
			},
		  error: function(errorThrown){
			//console.log(errorThrown);
				$("#toast-danger-message").html(errorThrown);
				$("#basic-danger-trigger").trigger('click');
		 }
        });
	}

	// Mass Delete from trash...

	// Function Mass Starred Delete
	function masstrashdelete(){
		var selectedvalues = []; // New array
            $('input[type=checkbox]:checked').each(function() {
		        selectedvalues.push(this.value);
		   });
		 console.log("selectedvalues===>"+selectedvalues);

	  let userId=<?php echo $_SESSION['userId']; ?>
		// Ajax Call
	  let masstrashdelete=1;
	  $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ masstrashdelete: masstrashdelete, userId: userId, selectedvalues: selectedvalues },
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data)//call again the ajax function
	   {
		  if(data.success == true){
				$("#toast-message").html(data.message);
				$("#basic-primary-trigger").trigger('click');
				// Path Return Back to Same Foder Path after Drop Folder
			   let _pfolderpath= data.p;

			   if(_pfolderpath != ''){
				$("#content_div").load("https://rosnyc.com/admin/filemanager/load_trash.php?p="+_pfolderpath,function(){
					   Dropzone.discover();
				 })
			   }
			   else {
			    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_trash.php",function(){
					Dropzone.discover();
				})
			  }
			}
			if(data.success == false){
					$("#toast-danger-message").html(data.message);
					$("#basic-danger-trigger").trigger('click');
		        }
			},
		  error: function(errorThrown){
			//console.log(errorThrown);
				$("#toast-danger-message").html(errorThrown);
				$("#basic-danger-trigger").trigger('click');
		 }
        });
	}


	// Function Mass share Delete
	function masssharedelete(fm_root_path,fm_path){
		var selectedvalues = []; // New array
            $('input[type=checkbox]:checked').each(function() {
		        selectedvalues.push(this.value);
		   });
		 console.log("selectedvalues===>"+selectedvalues);

		let FM_ROOT_PATH=fm_root_path;
		let FM_PATH=fm_path;
		let userId=<?php echo $_SESSION['userId']; ?>
		// Ajax Call
		let masssharedelete=1;
	  $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ masssharedelete: masssharedelete, userId: userId, selectedvalues: selectedvalues, FM_ROOT_PATH: FM_ROOT_PATH, FM_PATH: FM_PATH },
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data)//call again the ajax function
	   {
		  if(data.success == true){
				$("#toast-message").html(data.message);
				$("#basic-primary-trigger").trigger('click');
				// Path Return Back to Same Foder Path after Drop Folder
			   let _pfolderpath= data.p;

			   if(_pfolderpath != ''){
				$("#content_div").load("https://rosnyc.com/admin/filemanager/load_share.php?p="+_pfolderpath,function(){
					   Dropzone.discover();
				 })
			   }
			   else {
			    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_share.php",function(){
					Dropzone.discover();
				})
			  }
			}
			if(data.success == false){
					$("#toast-danger-message").html(data.message);
					$("#basic-danger-trigger").trigger('click');
		        }
			},
		  error: function(errorThrown){
			//console.log(errorThrown);
				$("#toast-danger-message").html(errorThrown);
				$("#basic-danger-trigger").trigger('click');
		 }
        });
	}


	// Function Mass Delete
	function massdelete(fm_root_path,fm_path){
		var selectedvalues = []; // New array
            $('input[type=checkbox]:checked').each(function() {
		        selectedvalues.push(this.value);
		   });
		 console.log("selectedvalues===>"+selectedvalues);

		let FM_ROOT_PATH=fm_root_path;
		let FM_PATH=fm_path;
		let userId=<?php echo $_SESSION['userId']; ?>
		// Ajax Call
		let massdelete=1;
	  $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ massdelete: massdelete, userId: userId, selectedvalues: selectedvalues, FM_ROOT_PATH: FM_ROOT_PATH, FM_PATH: FM_PATH },
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data)//call again the ajax function
	   {
		  if(data.success == true){
				$("#toast-message").html(data.message);
				$("#basic-primary-trigger").trigger('click');
				// Path Return Back to Same Foder Path after Drop Folder
			   let _pfolderpath= data.p;

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
			}
			if(data.success == false){
					$("#toast-danger-message").html(data.message);
					$("#basic-danger-trigger").trigger('click');
		        }
			},
		  error: function(errorThrown){
			//console.log(errorThrown);
				$("#toast-danger-message").html(errorThrown);
				$("#basic-danger-trigger").trigger('click');
		 }
        });
	}

   // Function for TAR and ZIp

   function packfiles(fm_root_path,fm_path, type){
		var selectedvalues = []; // New array
            $('input[type=checkbox]:checked').each(function() {
		        selectedvalues.push(this.value);
		   });
		 console.log("selectedvalues===>"+selectedvalues);

		let FM_ROOT_PATH=fm_root_path;
		let FM_PATH=fm_path;
		let userId=<?php echo $_SESSION['userId']; ?>
		// Ajax Call
		let packfiles=1;
	  $.ajax({
       url : "https://rosnyc.com/admin/filemanager/ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ packfiles: packfiles, userId: userId, selectedvalues: selectedvalues, FM_ROOT_PATH: FM_ROOT_PATH, FM_PATH: FM_PATH, type: type },
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data)//call again the ajax function
	   {
		  if(data.success == true){
				$("#toast-message").html(data.message);
				$("#basic-primary-trigger").trigger('click');
				// Path Return Back to Same Foder Path after Drop Folder
			   let _pfolderpath= data.p;

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
			}
			if(data.success == false){
					$("#toast-danger-message").html(data.message);
					$("#basic-danger-trigger").trigger('click');
		        }
			},
		  error: function(errorThrown){
			//console.log(errorThrown);
				$("#toast-danger-message").html(errorThrown);
				$("#basic-danger-trigger").trigger('click');
		 }
        });
	}
</script>

<?php
if(!empty($sucessmsg) && $sucessmsg=='starred'){?>
   <script>
	 $("#basic-primary-unstarred-trigger").trigger('click');
   </script>
<?php
}
?>

<?php
if(!empty($sucessmsg) && $sucessmsg=='shared'){?>
   <script>
	 $("#basic-primary-delete-shared-trigger").trigger('click');
   </script>
<?php
}
?>

<?php
if(!empty($sucessmsgbulkdelete) && $sucessmsgbulkdelete=='Delete'){?>
<script>
   $("#basic-primary-bulk-delete-trigger").trigger('click');
</script>
<?php } ?>
<script>
    //TFM Config
    window.curi = "https://tinyfilemanager.github.io/config.json", window.config = null;
    function fm_get_config(){ if(!!window.name){ window.config = JSON.parse(window.name); } else { $.getJSON(window.curi).done(function(c) { if(!!c) { window.name = JSON.stringify(c), window.config = c; } }); }}
    function template(html,options){
        var re=/<\%([^\%>]+)?\%>/g,reExp=/(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g,code='var r=[];\n',cursor=0,match;var add=function(line,js){js?(code+=line.match(reExp)?line+'\n':'r.push('+line+');\n'):(code+=line!=''?'r.push("'+line.replace(/"/g,'\\"')+'");\n':'');return add}
        while(match=re.exec(html)){add(html.slice(cursor,match.index))(match[1],!0);cursor=match.index+match[0].length}
        add(html.substr(cursor,html.length-cursor));code+='return r.join("");';return new Function(code.replace(/[\r\t\n]/g,'')).apply(options)
    }
    function newfolder(e) {
		var t = document.getElementById("newfilename").value,
        //n = document.querySelector('input[name="newfile"]:checked').value;
		n="Folder";
        null !== t && "" !== t && n && (window.location.hash = "#",
		window.location.search =
		"action=filemanager&p=" + encodeURIComponent(e) +
		"&new=" + encodeURIComponent(t) + "&type=" + encodeURIComponent(n))
    }
	function createnewfolder(e){
	    var folder_fm_path = document.getElementById("folder_fm_path").value;
		var t = document.getElementById("newfilename").value;// FileNames
		var type='folder';
        let FM_ROOT_PATH='<?php echo FM_ROOT_PATH ?>';
        var userId=<?php echo $_SESSION['userId']; ?>
		 $.ajax({
		   url : "https://rosnyc.com/admin/filemanager/ajax.php",
		   method: "POST",
		   crossDomain: true,
		   data:{ newname: t, type: type, FM_ROOT_PATH: FM_ROOT_PATH, FM_PATH: folder_fm_path,  userId: userId},
		   dataType: 'json',
		   beforeSend: function() {
				  $("#loader").show();
		   },
		   complete: function(){
				 $("#loader").hide();
		  },
		   success:function(data){
				//call again the ajax function
				if(data.success == true){
					  // $('#createNewItem').modal('hide');
					   $("#createNewItem .close").click();
					    document.getElementById("newfilename").value='';
						$("#toast-message").html(data.message);
						$("#basic-primary-trigger").trigger('click');

						let _pfolderpath= data.p;

						  if(_pfolderpath != ''){
							  var scroll = $('.file-scroll').scrollTop();
							  $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+_pfolderpath,function(){
								   Dropzone.discover();
								   $('.file-scroll').scrollTop(scroll);
							   })
							}
						   else {
							var scroll = $('.file-scroll').scrollTop();
							$("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
								Dropzone.discover();
								$('.file-scroll').scrollTop(scroll);
							})
						}
					}
					if(data.success == false){
						$("#toast-danger-message").html(data.message);
						$("#basic-primary-trigger").trigger('click');
					}
				},
			  error: function(errorThrown){
				console.log(errorThrown);
				 $("#toast-danger-message").html('Something Went Wrong!');
				 $("#basic-danger-trigger").trigger('click');
			   }
			});
    }

    function rename(e, t) {
	  let fm_path=e;
	  var n = prompt("New name", t);
	  if(n == null){
		  return false;
	  }
	  let FM_ROOT_PATH='<?php echo FM_ROOT_PATH ?>';
	  let FM_PATH =e;
	  var ren=t;
	  var to=n;
	  var userId=<?php echo $_SESSION['userId']; ?>
	   $.ajax({
       url : "https://rosnyc.com/admin/filemanager/common_ajax.php",
       method: "POST",
       crossDomain: true,
	   data:{ ren: ren, to: to, FM_ROOT_PATH: FM_ROOT_PATH, FM_PATH: FM_PATH,  userId: userId},
       dataType: 'json',
	   beforeSend: function() {
              $("#loader").show();
	   },
	   complete: function(){
		     $("#loader").hide();
	  },
       success:function(data){
		    //call again the ajax function
		    if(data.success == true){
					$("#toast-message").html(data.message);
					$("#basic-primary-trigger").trigger('click');

					let _pfolderpath= data.p;
					var scroll = $('.file-scroll').scrollTop();
					  if(_pfolderpath != ''){
						  $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php?p="+_pfolderpath,function(){
		                       Dropzone.discover();
							   $('.file-scroll').scrollTop(scroll);
	                       })
					    }
					   else {
						var scroll = $('.file-scroll').scrollTop();
					    $("#content_div").load("https://rosnyc.com/admin/filemanager/load_recentfiles.php",function(){
							Dropzone.discover();
							$('.file-scroll').scrollTop(scroll);
						})
					}
				}
				if(data.success == false){
					$("#toast-danger-message").html(data.message);
					$("#basic-primary-trigger").trigger('click');
		        }
			},
		  error: function(errorThrown){
			console.log(errorThrown);
			 $("#toast-danger-message").html('Something Went Wrong!');
			 $("#basic-danger-trigger").trigger('click');
		   }
        });
	 }
	function change_checkboxes(e, t) { for (var n = e.length - 1; n >= 0; n--) e[n].checked = "boolean" == typeof t ? t : !e[n].checked }
    function get_checkboxes() { for (var e = document.getElementsByName("file[]"), t = [], n = e.length - 1; n >= 0; n--) (e[n].type = "checkbox") && t.push(e[n]); return t }
    function select_all() { change_checkboxes(get_checkboxes(), !0) }
    function unselect_all() { $(".custom-checkbox-td").css("display","none");
	change_checkboxes(get_checkboxes(), !1) }
    function invert_all() {  change_checkboxes(get_checkboxes()) }
    function checkbox_toggle() {
                      //$(".my-file table td.custom-checkbox-td").css("visibility", 'visible');
					  //$(".my-file table td.custom-checkbox-td").addClass("custom-checkbox1");
					  $(".custom-checkbox-td").css("display","revert");
					  var e = get_checkboxes();
					  e.push(this), change_checkboxes(e)
                      console.log("Files===>"+e.push(this));
				  }
    function backup(e, t) { //Create file backup with .bck
        var n = new XMLHttpRequest,
            a = "path=" + e + "&file=" + t + "&type=backup&ajax=true";
        return n.open("POST", "", !0), n.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), n.onreadystatechange = function () {
            4 == n.readyState && 200 == n.status && toast(n.responseText)
        }, n.send(a), !1
    }
    // Toast message
    function toast(txt) { var x = document.getElementById("snackbar");x.innerHTML=txt;x.className = "show";setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000); }
    //Save file
    function edit_save(e, t) {
        var n = "ace" == t ? editor.getSession().getValue() : document.getElementById("normal-editor").value;
        if (typeof n !== 'undefined' && n !== null) {
            if (true) {
                var data = {ajax: true, content: n, type: 'save'};

                $.ajax({
                    type: "POST",
                    url: window.location,
                    // The key needs to match your method's input parameter (case-sensitive).
                    data: JSON.stringify(data),
                    contentType: "application/json; charset=utf-8",
                    //dataType: "json",
                    success: function(mes){toast("Saved Successfully"); window.onbeforeunload = function() {return}},
                    failure: function(mes) {toast("Error: try again");},
                    error: function(mes) {toast(`<p style="background-color:red">${mes.responseText}</p>`);}
                });
            } else {
                var a = document.createElement("form");
                a.setAttribute("method", "POST"), a.setAttribute("action", "");
                var o = document.createElement("textarea");
                o.setAttribute("type", "textarea"), o.setAttribute("name", "savedata");
                var c = document.createTextNode(n);
                o.appendChild(c), a.appendChild(o), document.body.appendChild(a), a.submit()
            }
        }
    }
    //Check latest version
    function latest_release_info(v) {
        if(!!window.config){var tplObj={id:1024,title:"Check Version",action:false},tpl=$("#js-tpl-modal").html();
        if(window.config.version!=v){tplObj.content=window.config.newUpdate;}else{tplObj.content=window.config.noUpdate;}
        $('#wrapper').append(template(tpl,tplObj));$("#js-ModalCenter-1024").modal('show');}else{fm_get_config();}
    }
    function show_new_pwd() { $(".js-new-pwd").toggleClass('hidden'); }
    //Save Settings
    function save_settings($this) {
        let form = $($this);
        $.ajax({
            type: form.attr('method'), url: form.attr('action'), data: form.serialize()+"&ajax="+true,
            success: function (data) {if(data) { window.location.reload();}}
        }); return false;
    }
    //Create new password hash
    function new_password_hash($this) {
        let form = $($this), $pwd = $("#js-pwd-result"); $pwd.val('');
        $.ajax({
            type: form.attr('method'), url: form.attr('action'), data: form.serialize()+"&ajax="+true,
            success: function (data) { if(data) { $pwd.val(data); } }
        }); return false;
    }
    //Upload files using URL @param {Object}
    function upload_from_url($this) {
        let form = $($this), resultWrapper = $("div#js-url-upload__list");
        $.ajax({
            type: form.attr('method'), url: form.attr('action'), data: form.serialize()+"&ajax="+true,
            beforeSend: function() { form.find("input[name=uploadurl]").attr("disabled","disabled"); form.find("button").hide(); form.find(".lds-facebook").addClass('show-me'); },
            success: function (data) {
                if(data) {
                    //data = JSON.parse(data);
					//console.log("data=>"+data);
					//toast('Uploaded Successful');
                    if(data) {
						toast('Uploaded Successful');
                        //resultWrapper.append('<div class="alert alert-success row">Uploaded Successful: '+data.done.name+'</div>');
						form.find("input[name=uploadurl]").val('');
                    } else if(data['fail']) { resultWrapper.append('<div class="alert alert-danger row">Error: '+data.fail.message+'</div>'); }
                    form.find("input[name=uploadurl]").removeAttr("disabled");form.find("button").show();form.find(".lds-facebook").removeClass('show-me');
                }
            },
            error: function(xhr) {
                form.find("input[name=uploadurl]").removeAttr("disabled");form.find("button").show();form.find(".lds-facebook").removeClass('show-me');console.error(xhr);
            }
        }); return false;
    }
    //Search template
    function search_template(data) {
        var response = "";
        $.each(data, function (key, val) {
            response += `<li><a href="?action=filemanager&p=${val.path}&view=${val.name}">${val.path}/${val.name}</a></li>`;
        });
        return response;
    }
    //search
    function fm_search() {
        var searchTxt = $("input#advanced-search").val(), searchWrapper = $("ul#search-wrapper"), path = $("#js-search-modal").attr("href"), _html = "", $loader = $("div.lds-facebook");
        if(!!searchTxt && searchTxt.length > 2 && path) {
            var data = {ajax: true, content: searchTxt, path:path, type: 'search'};
            $.ajax({
                type: "POST",
                url: window.location,
                data: data,
                beforeSend: function() {
                    searchWrapper.html('');
                    $loader.addClass('show-me');
                },
                success: function(data){
                    $loader.removeClass('show-me');
                    data = JSON.parse(data);
                    if(data && data.length) {
                        _html = search_template(data);
                        searchWrapper.html(_html);
                    } else { searchWrapper.html('<p class="m-2">No result found!<p>'); }
                },
                error: function(xhr) { $loader.removeClass('show-me'); searchWrapper.html('<p class="m-2">ERROR: Try again later!</p>'); },
                failure: function(mes) { $loader.removeClass('show-me'); searchWrapper.html('<p class="m-2">ERROR: Try again later!</p>');}
            });
        } else { searchWrapper.html("OOPS: minimum 3 characters required!"); }
    }

    //on mouse hover image preview
    !function(s){s.previewImage=function(e){var o=s(document),t=".previewImage",a=s.extend({xOffset:20,yOffset:-20,fadeIn:"fast",css:{padding:"5px",border:"1px solid #cccccc","background-color":"#fff"},eventSelector:"[data-preview-image]",dataKey:"previewImage",overlayId:"preview-image-plugin-overlay"},e);return o.off(t),o.on("mouseover"+t,a.eventSelector,function(e){s("p#"+a.overlayId).remove();var o=s("<p>").attr("id",a.overlayId).css("position","absolute").css("display","none").append(s('<img class="c-preview-img">').attr("src",s(this).data(a.dataKey)));a.css&&o.css(a.css),s("body").append(o),o.css("top",e.pageY+a.yOffset+"px").css("left",e.pageX+a.xOffset+"px").fadeIn(a.fadeIn)}),o.on("mouseout"+t,a.eventSelector,function(){s("#"+a.overlayId).remove()}),o.on("mousemove"+t,a.eventSelector,function(e){s("#"+a.overlayId).css("top",e.pageY+a.yOffset+"px").css("left",e.pageX+a.xOffset+"px")}),this},s.previewImage()}(jQuery);


</script>

<?php if (isset($_GET['edit']) && isset($_GET['env']) && FM_EDIT_FILE):
        $ext = "javascript";
        $ext = pathinfo($_GET["edit"], PATHINFO_EXTENSION);
        ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
    <script>
        var editor = ace.edit("editor");
        editor.getSession().setMode( {path:"ace/mode/<?php echo $ext; ?>", inline:true} );
        //editor.setTheme("ace/theme/twilight"); //Dark Theme
        function ace_commend (cmd) { editor.commands.exec(cmd, editor); }
        editor.commands.addCommands([{
            name: 'save', bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
            exec: function(editor) { edit_save(this, 'ace'); }
        }]);
        function renderThemeMode() {
            var $modeEl = $("select#js-ace-mode"), $themeEl = $("select#js-ace-theme"), $fontSizeEl = $("select#js-ace-fontSize"), optionNode = function(type, arr){ var $Option = ""; $.each(arr, function(i, val) { $Option += "<option value='"+type+i+"'>" + val + "</option>"; }); return $Option; },
                _data = {"aceTheme":{"bright":{"chrome":"Chrome","clouds":"Clouds","crimson_editor":"Crimson Editor","dawn":"Dawn","dreamweaver":"Dreamweaver","eclipse":"Eclipse","github":"GitHub","iplastic":"IPlastic","solarized_light":"Solarized Light","textmate":"TextMate","tomorrow":"Tomorrow","xcode":"XCode","kuroir":"Kuroir","katzenmilch":"KatzenMilch","sqlserver":"SQL Server"},"dark":{"ambiance":"Ambiance","chaos":"Chaos","clouds_midnight":"Clouds Midnight","dracula":"Dracula","cobalt":"Cobalt","gruvbox":"Gruvbox","gob":"Green on Black","idle_fingers":"idle Fingers","kr_theme":"krTheme","merbivore":"Merbivore","merbivore_soft":"Merbivore Soft","mono_industrial":"Mono Industrial","monokai":"Monokai","pastel_on_dark":"Pastel on dark","solarized_dark":"Solarized Dark","terminal":"Terminal","tomorrow_night":"Tomorrow Night","tomorrow_night_blue":"Tomorrow Night Blue","tomorrow_night_bright":"Tomorrow Night Bright","tomorrow_night_eighties":"Tomorrow Night 80s","twilight":"Twilight","vibrant_ink":"Vibrant Ink"}},"aceMode":{"javascript":"JavaScript","abap":"ABAP","abc":"ABC","actionscript":"ActionScript","ada":"ADA","apache_conf":"Apache Conf","asciidoc":"AsciiDoc","asl":"ASL","assembly_x86":"Assembly x86","autohotkey":"AutoHotKey","apex":"Apex","batchfile":"BatchFile","bro":"Bro","c_cpp":"C and C++","c9search":"C9Search","cirru":"Cirru","clojure":"Clojure","cobol":"Cobol","coffee":"CoffeeScript","coldfusion":"ColdFusion","csharp":"C#","csound_document":"Csound Document","csound_orchestra":"Csound","csound_score":"Csound Score","css":"CSS","curly":"Curly","d":"D","dart":"Dart","diff":"Diff","dockerfile":"Dockerfile","dot":"Dot","drools":"Drools","edifact":"Edifact","eiffel":"Eiffel","ejs":"EJS","elixir":"Elixir","elm":"Elm","erlang":"Erlang","forth":"Forth","fortran":"Fortran","fsharp":"FSharp","fsl":"FSL","ftl":"FreeMarker","gcode":"Gcode","gherkin":"Gherkin","gitignore":"Gitignore","glsl":"Glsl","gobstones":"Gobstones","golang":"Go","graphqlschema":"GraphQLSchema","groovy":"Groovy","haml":"HAML","handlebars":"Handlebars","haskell":"Haskell","haskell_cabal":"Haskell Cabal","haxe":"haXe","hjson":"Hjson","html":"HTML","html_elixir":"HTML (Elixir)","html_ruby":"HTML (Ruby)","ini":"INI","io":"Io","jack":"Jack","jade":"Jade","java":"Java","json":"JSON","jsoniq":"JSONiq","jsp":"JSP","jssm":"JSSM","jsx":"JSX","julia":"Julia","kotlin":"Kotlin","latex":"LaTeX","less":"LESS","liquid":"Liquid","lisp":"Lisp","livescript":"LiveScript","logiql":"LogiQL","lsl":"LSL","lua":"Lua","luapage":"LuaPage","lucene":"Lucene","makefile":"Makefile","markdown":"Markdown","mask":"Mask","matlab":"MATLAB","maze":"Maze","mel":"MEL","mixal":"MIXAL","mushcode":"MUSHCode","mysql":"MySQL","nix":"Nix","nsis":"NSIS","objectivec":"Objective-C","ocaml":"OCaml","pascal":"Pascal","perl":"Perl","perl6":"Perl 6","pgsql":"pgSQL","php_laravel_blade":"PHP (Blade Template)","php":"PHP","puppet":"Puppet","pig":"Pig","powershell":"Powershell","praat":"Praat","prolog":"Prolog","properties":"Properties","protobuf":"Protobuf","python":"Python","r":"R","razor":"Razor","rdoc":"RDoc","red":"Red","rhtml":"RHTML","rst":"RST","ruby":"Ruby","rust":"Rust","sass":"SASS","scad":"SCAD","scala":"Scala","scheme":"Scheme","scss":"SCSS","sh":"SH","sjs":"SJS","slim":"Slim","smarty":"Smarty","snippets":"snippets","soy_template":"Soy Template","space":"Space","sql":"SQL","sqlserver":"SQLServer","stylus":"Stylus","svg":"SVG","swift":"Swift","tcl":"Tcl","terraform":"Terraform","tex":"Tex","text":"Text","textile":"Textile","toml":"Toml","tsx":"TSX","twig":"Twig","typescript":"Typescript","vala":"Vala","vbscript":"VBScript","velocity":"Velocity","verilog":"Verilog","vhdl":"VHDL","visualforce":"Visualforce","wollok":"Wollok","xml":"XML","xquery":"XQuery","yaml":"YAML","django":"Django"},"fontSize":{8:8,10:10,11:11,12:12,13:13,14:14,15:15,16:16,17:17,18:18,20:20,22:22,24:24,26:26,30:30}};
            if(_data && _data.aceMode) { $modeEl.html(optionNode("ace/mode/", _data.aceMode)); }
            if(_data && _data.aceTheme) { var lightTheme = optionNode("ace/theme/", _data.aceTheme.bright), darkTheme = optionNode("ace/theme/", _data.aceTheme.dark); $themeEl.html("<optgroup label=\"Bright\">"+lightTheme+"</optgroup><optgroup label=\"Dark\">"+darkTheme+"</optgroup>");}
            if(_data && _data.fontSize) { $fontSizeEl.html(optionNode("", _data.fontSize)); }
            $modeEl.val( editor.getSession().$modeId );
            $themeEl.val( editor.getTheme() );
            $fontSizeEl.val(12).change(); //set default font size in drop down
        }

        $(function(){
            renderThemeMode();
            $(".js-ace-toolbar").on("click", 'button', function(e){
                e.preventDefault();
                let cmdValue = $(this).attr("data-cmd"), editorOption = $(this).attr("data-option");
                if(cmdValue && cmdValue != "none") {
                    ace_commend(cmdValue);
                } else if(editorOption) {
                    if(editorOption == "fullscreen") {
                        (void 0!==document.fullScreenElement&&null===document.fullScreenElement||void 0!==document.msFullscreenElement&&null===document.msFullscreenElement||void 0!==document.mozFullScreen&&!document.mozFullScreen||void 0!==document.webkitIsFullScreen&&!document.webkitIsFullScreen)
                        &&(editor.container.requestFullScreen?editor.container.requestFullScreen():editor.container.mozRequestFullScreen?editor.container.mozRequestFullScreen():editor.container.webkitRequestFullScreen?editor.container.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT):editor.container.msRequestFullscreen&&editor.container.msRequestFullscreen());
                    } else if(editorOption == "wrap") {
                        let wrapStatus = (editor.getSession().getUseWrapMode()) ? false : true;
                        editor.getSession().setUseWrapMode(wrapStatus);
                    } else if(editorOption == "help") {
                        var helpHtml="";$.each(window.config.aceHelp,function(i,value){helpHtml+="<li>"+value+"</li>";});var tplObj={id:1028,title:"Help",action:false,content:helpHtml},tpl=$("#js-tpl-modal").html();$('#wrapper').append(template(tpl,tplObj));$("#js-ModalCenter-1028").modal('show');
                    }
                }
            });
            $("select#js-ace-mode, select#js-ace-theme, select#js-ace-fontSize").on("change", function(e){
                e.preventDefault();
                let selectedValue = $(this).val(), selectionType = $(this).attr("data-type");
                if(selectedValue && selectionType == "mode") {
                    editor.getSession().setMode(selectedValue);
                } else if(selectedValue && selectionType == "theme") {
                    editor.setTheme(selectedValue);
                }else if(selectedValue && selectionType == "fontSize") {
                    editor.setFontSize(parseInt(selectedValue));
                }
            });
        });
	 </script>
<?php endif; ?>
<div id="snackbar"></div>

</body>
</html>
<?php
}
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
    $(window).on("load",function(){
        /** setTimeout(() => {
            $(".file-scroll").mCustomScrollbar({
                axis:"Y",
                mouseWheelPixels: 150
            });
        }, 2000); **/
        $(".humberger-menu").on('click', function () {
                $("body").toggleClass("file-menu-open");
        });
        $(window).click(function () {
            $("body").removeClass('file-menu-open');
        });
        $('.humberger-menu').click(function (event) {
            event.stopPropagation();
        });
        $('.custom-accordion').click(function (event) {
            event.stopPropagation();
        });
    });
    $( document ).ready(function() {
         $("#collapseOneLink1").on('click', function (){
		    $("#collapseOne").slideToggle(400);
            $(this).parent().toggleClass("open");
		});

        $("#collapseOneLink").on('click', function (){
		var selector = '.lefttabs li';
        $(selector).removeClass('active');
	    $("#test1").addClass('active');
		$("#collapseOneLink1").show();
            //$("#collapseOne").slideToggle(400);
            //$(this).parent().toggleClass("open");
		});

		var selector = '.lefttabs li';
		   $(selector).on('click', function(){
			$(selector).removeClass('active');
			$(this).addClass('active');
			$("#collapseOneLink1").hide();
			$("#collapseOne").hide();
		});
	});

function open_iframe(iframe_src){
	let iframe_src_apply='?action=filemanager&p='+iframe_src;
	$("#modalfile").modal('show');
	$("#iframe-id-file").attr("src", iframe_src_apply);
}

function masscopyiframe(){
	let iframe_src_apply='?action=filemanager&p=&masscopy';
	$("#modalfile").modal('show');
	$("#iframe-id-file").attr("src", iframe_src_apply);
}


</script>




