#!/usr/bin/php
<?php

error_reporting(E_ALL);
@ini_set("display_errors","0");
@set_time_limit(0);

require_once("/var/www/vhosts/hoopss.com/httpdocs/kutuphane/curl_class_cli.php");


require_once("/var/www/vhosts/hoopss.com/httpdocs/kutuphane/get_links_class_cli.php");
$get_links = new GetLinks;

$curl = new CURL;
$db = new database;

if(isset($_REQUEST['keyword'])) $keyword = $_REQUEST['keyword'];
else $keyword = "";

$file_types = "zip|ZIP|rar|RAR|tar|TAR|gz|GZ|bz2|BZ2|z|Z|7z|7Z|iso|ISO|nrg|NRG";

$file_types_array = explode("|",$file_types);

$get_links->start_record = 0;
$get_links->record_increment = 100;

$keyword = "";
$lang = "";

if(isset($argv[1])) $limit = $argv[1];
else $limit = 100;

$last_checked_site_id_que = $db->query("SELECT * FROM last_checked_site_id;");
$last_checked_site_id = $db->result($last_checked_site_id_que);

$last_id = $last_checked_site_id['archive_last_id'];

echo "SELECT id,link FROM archive_google_site_links WHERE enabled='1' LIMIT $last_id, $limit;";
echo $site_que = $db->query("SELECT id,link FROM archive_google_site_links WHERE enabled='1' LIMIT $last_id, $limit;");
while($site = $db->result($site_que))
{
	echo "\n\n \033[35m$site[link]\033[37m \n\n";
	if($curl->query($site['link']))
	{
       		$directories = $get_links->getDirectories($site['link']);
       		$file_links = $get_links->getFilesArchieve($site['link'],$file_types_array,$keyword);
	}
	$db->query("UPDATE last_checked_site_id SET archive_last_id='$last_id';");
	$last_id++;
}



foreach($directories as $key_dir => $value_dir)
        $file_links = $get_links->getFilesArchieve($directories[$key_dir],$file_types_array,$keyword);


?>

