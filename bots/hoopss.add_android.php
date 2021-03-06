#!/usr/bin/php
<?php

error_reporting(E_ALL);
@ini_set("display_errors","1");
@set_time_limit(0);

require_once("/var/www/vhosts/hoopss.com/httpdocs/kutuphane/curl_class_cli.php");


require_once("/var/www/vhosts/hoopss.com/httpdocs/kutuphane/get_links_class_cli.php");
$get_links = new GetLinks;

$curl = new CURL;
$db = new database;

if(isset($argv[2])) $limit = $argv[2];
else $limit = 10;

if(!isset($argv[1])) echo "Usage: php hoopss.add_android.php <url> [<limit>]";
else $url = $argv[1];


if(isset($_REQUEST['keyword'])) $keyword = $_REQUEST['keyword'];
else $keyword = "";

$file_types = "apk|APK";

$file_types_array = explode("|",$file_types);

$get_links->start_record = 0;
$get_links->record_increment = 100;

$keyword = "";
$lang = "";


$curl_query = $curl->query($url);
if($curl_query)
{
	$directories = $get_links->getDirectories($url);
      	$file_links = $get_links->getFilesAndroid($url,$file_types_array,$keyword);
}


foreach($directories as $key_dir => $value_dir)
        $file_links = $get_links->getFilesAndroid($directories[$key_dir],$file_types_array,$keyword);

?>

