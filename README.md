# World of Tanks Clan Statistics #

This is initial version of WoTClanStat. Russian version only.

First version was released by [z13ii](http://forum.worldoftanks.ru/index.php?/topic/84257-)  
Updated by [merokoo](http://forum.worldoftanks.ru/index.php?/user/261770-merokoo/) and then [ciklop1979](http://forum.worldoftanks.ru/index.php?/user/1612931-ciklop1979/)

Working demo is here: [http://thunder.wot-blackdeath.ru/wot-stat](http://thunder.wot-blackdeath.ru/wot-stat/)  

## Installation #

1. Rename _settings.kak.sample_ into _settings.kak_. Then change _settings.kak_
	You need to set there:

```php

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
$timetolife = 30;               // time to life stat for each tank
```

2. After that you need to create mySQL tables. Tables structures located under *mysql* folder.
	
3. Setting up cron. It's easy. We need to run 3 script to update our database.

```shell
20 * * * *  wget -O - -q http://YOUR.SITE.NAME/get_clanlist.php >/dev/null 2>&1
* * * * *   wget -O - -q http://YOUR.SITE.NAME/get_global_mm.php >/dev/null 2>&1
* * * * *  wget -O - -q http://YOUR.SITE.NAME/get_wm_province.php >/dev/null 2>&1
```
  Add these lines to your cron tab
	
## Files description #
1. Files used in frontend by javascript
	* ui_main_json.php - Clan Overview
	* ui_boicy_json.php - Clan members
	* ui_boicy_dmb_json.php - Ex-Clan members
	* get_pls01_json.php - Stats: Overall / 30 days / 7 days
	* get_pls02_json.php - Achievements and medals
	* get_pls03_json.php - Overall stats on all your tanks
	* get_pls07_json.php - Overall stats in 30 days
	* get_pls06_json.php - Detailed stats in 30 days
	* get_pls05_json.php - Hangar detailed stats in 7 days
	* get_pls041_json.php - Stats by tanks types
	* get_pls042_json.php - Stats by tanks country 
	* get_plnews1.php &	get_plnews2.php - events for player
	* get_news1(2,3).php - events for clan
