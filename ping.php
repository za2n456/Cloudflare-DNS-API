<?php
require_once('vendor/autoload.php');
$domains = file('data/domain.txt');
$subdomains = file('data/subdomain.txt');

foreach ($domains as $domain) {
    $domain = trim($domain);
    echo $domain. PHP_EOL;
	$google = file_get_contents('https://www.google.com/webmasters/sitemaps/ping?sitemap=https://'.$domain.'/sitemap-index.xml');
	$bing = file_get_contents('https://www.bing.com/webmaster/ping.aspx?siteMap=https://'.$domain.'/sitemap-index.xml');
	
	$status1 = ( strpos($google,"Sitemap Notification Received") !== false ) ? "OK" : "ERROR";
	echo "Submitting Google Sitemap: {$status1}\n";
	$status2 = ( strpos($bing,"Thanks for submitting your Sitemap.") !== false ) ? "OK" : "ERROR";
	echo "Submitting Bing Sitemap: {$status2}\n";
	
	foreach ($subdomains as $subdomain) {
    	$subdomain = trim($subdomain);
		echo $subdomain.'.'.$domain. PHP_EOL;		
		$google = file_get_contents('https://www.google.com/webmasters/sitemaps/ping?sitemap=https://'.$subdomain.'.'.$domain.'/sitemap-index.xml');
		$bing = file_get_contents('https://www.bing.com/webmaster/ping.aspx?siteMap=https://'.$subdomain.'.'.$domain.'/sitemap-index.xml');
		
		$status1 = ( strpos($google,"Sitemap Notification Received") !== false ) ? "OK" : "ERROR";
		echo "Submitting Google Sitemap: {$status1}\n";
		$status2 = ( strpos($bing,"Thanks for submitting your Sitemap.") !== false ) ? "OK" : "ERROR";
		echo "Submitting Bing Sitemap: {$status2}\n";
	}
}
