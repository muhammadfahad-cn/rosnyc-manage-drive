<?php
$root = __DIR__;
$root = str_replace("filemanager","",$root);
require_once($root."/constants.php");
$dbhost = DB_HOST;
$dbuser = DB_USER;
$dbpass = DB_PASS;
$dbname = DB_NAME;
$con = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

  if(mysqli_connect_errno())
	   {
		  //print_f("Connection Failed: %s\n",mysqli_connect_error());
	   }
	   else {
		   //print('Connection is successfull');
	    }
?>