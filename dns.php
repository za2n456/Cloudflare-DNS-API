<?php
require_once('vendor/autoload.php');

$key = new \Cloudflare\API\Auth\APIKey('bpohon665@gmail.com', '637ecb82025b092135933142eeef8550f8a66');
$adapter = new Cloudflare\API\Adapter\Guzzle($key);
$zones = new \Cloudflare\API\Endpoints\Zones($adapter);
$dns = new \Cloudflare\API\Endpoints\DNS($adapter);
$domains = file('data/domain.txt');
$subdomains = file('data/subdomain.txt');

foreach ($domains as $domain) {
    $domain = trim($domain);
    echo $domain. PHP_EOL;
    $zoneID = $zones->getZoneID($domain);
    
    //if ($dns->deleteRecord($zoneID, $dns->getRecordID($zoneID, 'A', $domain)) === true) {
    	//echo "DNS record deleted.". PHP_EOL;
    //}
    
    //if ($dns->deleteRecord($zoneID, $dns->getRecordID($zoneID, 'CNAME')) === true) {
    	//echo "DNS record deleted.". PHP_EOL;
    //}
    
    if ($dns->addRecord($zoneID, 'TXT', $domain, 'webagc.azurewebsites.net', 0, false) === true) {
		echo "DNS record created.". PHP_EOL;
	}
	
    if ($dns->updateRecordDetails($zoneID, $dns->getRecordID($zoneID, 'A', $domain), array("type"=>"A","name"=>$domain,"content"=>"40.112.243.7","ttl"=>1,"proxied"=>true)) === true) {
		echo "DNS record updated.". PHP_EOL;
	}
	
	//foreach ($subdomains as $subdomain) {
    	//$subdomain = trim($subdomain);
		//echo $subdomain.'.'.$domain. PHP_EOL;
		//if ($dns->deleteRecord($zoneID, $dns->getRecordID($zoneID, 'A', $subdomain.'.'.$domain)) === true) {
			//echo "DNS record deleted.". PHP_EOL;
		//}
		
		//if ($dns->addRecord($zoneID, 'CNAME', $subdomain, $domain, 0, true) === true) {
			//echo "DNS record created.". PHP_EOL;
		//}
	//}
}
