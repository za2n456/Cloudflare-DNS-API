<?php
require_once('vendor/autoload.php');

$key = new \Cloudflare\API\Auth\APIKey('xxx', 'xxx');
$adapter = new Cloudflare\API\Adapter\Guzzle($key);
$zones = new \Cloudflare\API\Endpoints\Zones($adapter);
$dns = new \Cloudflare\API\Endpoints\DNS($adapter);

use HerokuClient\Client as HerokuClient;
$heroku = new HerokuClient([
    'apiKey' => 'xxx', // Or set the HEROKU_API_KEY environmental variable
]);

$total_domain = 587;
$per_page = 50;
$start_page = 1;
$total_page = ceil ($total_domain / $per_page);

for ($i=$start_page; $i<=$total_page; $i++) { 
foreach ($zones->listZones('','',$i,$per_page,'','','')->result as $zone) {
    echo $zone->name.' ('.$zone->plan->name.')'.PHP_EOL;
    file_put_contents('data/domain.txt', $zone->name."\n", FILE_APPEND | LOCK_EX);
    
    $zoneID = $zones->getZoneID($zone->name);
    
    //if ($dns->deleteRecord($zoneID, $dns->getRecordID($zoneID, 'CNAME', $zone->name)) === true) {
    	//echo "DNS record deleted.". PHP_EOL;
    //}
    //if ($dns->deleteRecord($zoneID, $dns->getRecordID($zoneID, 'CNAME')) === true) {
    	//echo "DNS record deleted.". PHP_EOL;
    //}
    //if ($dns->addRecord($zoneID, 'A', $zone->name, '40.65.98.0', 0, true) === true) {
		//echo "DNS record created.". PHP_EOL;
	//}
    
    // Add domain to heroku
    //if ($heroku->post('apps/kora-apps/domains',['hostname' => $zone->name]) === true) {
    	//echo "Heroku domain created.". PHP_EOL;
    //}
}
}
