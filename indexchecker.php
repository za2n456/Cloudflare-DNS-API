<?php
error_reporting(1);
include 'function.php';

$domains = file('data/domain.txt');
foreach ($domains as $domain) {
	$indexed = get_curl(trim($domain));
	preg_match('/About (.*) results<nobr>/', $indexed, $matches);
	
	$data = trim($domain).','.$matches[1]."\n";
	file_put_contents('data/domain.csv', $data, FILE_APPEND | LOCK_EX);
	echo $data;
}

