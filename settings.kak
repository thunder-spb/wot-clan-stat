<?php
$host = "localhost";		// your mySQL server host
$account = "username";		// your mySQL username
$password = "passwd";		// your mySQL password
$dbname = "database_name";	// database name where all tables stored or will be stored
$wot_host = "worldoftanks.ru"; // leave it unchanged if you plan use it on Russian cluster
// Next lines where you can set any number of your clans
// clan_id -- your clanid, main property which would be used for gathering information about your clan
// clan_tag -- your clan tag, will be shown as tab on the top of the page
// clan_name -- your clan name
$clan_array = array (
	array("clan_id" => "102", "clan_tag" => "[SMPLC]",  "clan_name" => "Sample clan"),
);
$hosttime = " +0 hour";		// timezone shift
?>
