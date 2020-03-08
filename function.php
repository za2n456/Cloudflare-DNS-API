<?php
	function spin($content) {
        $zz = substr_count($content, "}");
        $m  = 0;
        while ($m < $zz) {
            $nn     = strpos($content, "}");
            $y      = substr($content, 0, $nn);
            $p      = strrpos($y, "{");
            $bdata  = substr($content, $p, $nn - $p + 1);
            $bdata2 = preg_replace("/{|}/", "", $bdata);
            $cspin  = explode("|", $bdata2);
            shuffle($cspin);
            $newspin = $cspin[0];
            $content = str_replace($bdata, $newspin, $content);
            ++$m;
        }
        return $content;
	}
	function mywpclean_character($content){
		$a = array('”', '“', 'â€œ', 'â€', '‘', 'â€˜', 'â€', ' ™', '™', '¦', 'â€', 'Â½', 'Ã©', 'Ã', '¢', '•', 'ã', '—', '[', ']', 'â€™', '’', '–', '&#8211;', '&#8230;', '&#8220;', '&#8221;', '&#8217;', '&#038;', '&#8212;', '&#8216;', '&#8242;', '&#8243;', '&#8482;', '&#174;');
		$b = array('', '', '', '', '', '', ' ', "'", "'", '', '', ' 1/2', 'e', 'a', '-', '*', 'a', '-', '', '', "'", "'", '-', '-', '...', '"', '"', "'", '', '-', "'", "'", '"', '', '');
		$content = str_replace($a, $b, $content);
		$content = preg_replace('/&#(.*?);/', ' ', $content);
		$content = htmlspecialchars_decode($content, ENT_QUOTES | ENT_HTML5);
		return $content;
	}

	function GenerateUrl($string, $delimiter = '-') {
		$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		$pattern = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
		$string = preg_replace($pattern, '$1', $string);
		$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
		$pattern = '~[^0-9a-z]+~i';
		$string = preg_replace($pattern, $delimiter, $string);
		return strtolower(trim($string, $delimiter));
	}

	function rrmdir($dir) {
		if (is_dir($dir)) {
		 $objects = scandir($dir);
		 foreach ($objects as $object) {
		   if ($object != "." && $object != "..") {
			 if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
		   }
		 }
		 reset($objects);
		 rmdir($dir);
		}
	}

	function listfile($folder, $pilih=false){
		$i=0;
		if ($handle = opendir($folder)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
				    $namafile[] = $entry;
					$i++;
				}
			}
			closedir($handle);
		}
		if ($pilih) {
			$file = array_rand($namafile, 1);
			return $namafile[$file];
		} else {
			return $namafile;
		}
	}

	function find_string($filename, $delimiter='', $string) {
		$f = fopen($filename, "r");
		$result = false;
		while ($row = fgetcsv($f, '', $delimiter)) {
			if ($row[0] == $string) {
				$result = $row[3];
				break;
			}
		}
		fclose($f);
		return $result;
	}

	function random_lines($filename, $max='', $unique=true) {
		if (!file_exists($filename) || !is_readable($filename))
			return null;
		$filesize = filesize($filename);
		$lines = array();
		$n = 0;

		$handle = @fopen($filename, 'r');
		if($max) {
			$numlines=$max;
		} else {
			$linecount = 0;
			while(!feof($handle)){
			  $line = fgets($handle, 4096);
  			  $linecount = $linecount + substr_count($line, PHP_EOL);
			}
			$numlines = $linecount;
		}

		if ($handle) {
			while ($n < $numlines) {
				fseek($handle, rand(0, $filesize));

				$started = false;
				$gotline = false;
				$line = "";

				while (!$gotline) {
					if (false === ($char = fgetc($handle))) {
						$gotline = true;
					} elseif ($char == "\n" || $char == "\r") {
						if ($started)
							$gotline = true;
						else
							$started = true;
					} elseif ($started) {
						$line .= $char;
					}
				}

				if ($unique && array_search($line, $lines))
					continue;

				$n++;
				array_push($lines, $line);
			}

			fclose($handle);
		}

		return $lines;
	}

	function site_url($subdomain=''){
		if (isset($_SERVER['HTTPS']) &&
			($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
			isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
			$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
		  $protokol = 'https://';
		} else {
		  $protokol = 'http://';
		}
		if ($subdomain){
			return $protokol.$subdomain.'.'.$_SERVER['HTTP_HOST'];
		} else {
			return $protokol.$_SERVER['HTTP_HOST'];
		}
	}

	function site_name(){
		return ucwords($_SERVER['HTTP_HOST']);
	}

	function get_title($site_name='', $delimiter='') {
		if(isset($_GET['q'])) {
			return ucwords(GenerateUrl($_GET['q'], ' ')).$delimiter.$site_name;
		} elseif(isset($_GET['p'])) {
			return 'Page '.ucwords($_GET['p']).$delimiter.$site_name;
		} else {
			return $site_name;
		}
	}

	function remove_http($url) {
	   $disallowed = array('http://', 'https://');
	   foreach($disallowed as $d) {
	      if(strpos($url, $d) === 0) {
	         return str_replace($d, '', $url);
	      }
	   }
	   return $url;
	}

	function excerpt($s, $limit=5, $replacement='') {
		return preg_replace('/((\w+\W*){'.($limit-1).'}(\w+))(.*)/', '${1}', $s).$replacement;
	}

	function isBot() {
		return (
		    isset($_SERVER['HTTP_USER_AGENT']) // check if the user agent header key exists
		    && preg_match('/bot|crawl|spider|mediapartners|slurp|patrol/i', $_SERVER['HTTP_USER_AGENT'])
		);
	}

	function get_curl($url, $proxy='') {
		$agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_URL, $url);         // URL for CURL call
		if ($proxy){
		curl_setopt($ch, CURLOPT_PROXY, $proxy);     // PROXY details with port
		}
		//curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxyauth);   // Use if proxy have username and password
		//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5); // If expected to call with specific PROXY type
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  // If url has redirects then go to the final redirected URL.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // Do not outputting it out directly on screen.
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$curl_scraped_page = curl_exec($ch);
		curl_close($ch);

		return $curl_scraped_page;
	}

	function get_curl_multi($data, $options = array()) {
	  $agent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)';
	  // array of curl handles
	  $curly = array();
	  // data to be returned
	  $result = array();

	  // multi handle
	  $mh = curl_multi_init();

	  // loop through $data and create curl handles
	  // then add them to the multi-handle
	  foreach ($data as $id => $d) {

		$curly[$id] = curl_init();

		$url = (is_array($d) && !empty($d['url'])) ? $d['url'] : $d;
		curl_setopt($curly[$id], CURLOPT_USERAGENT, $agent);
		curl_setopt($curly[$id], CURLOPT_URL,            $url);
		curl_setopt($curly[$id], CURLOPT_HEADER,         0);
		curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);

		// post?
		if (is_array($d)) {
		  if (!empty($d['post'])) {
		    curl_setopt($curly[$id], CURLOPT_POST,       1);
		    curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
		  }
		}

		// extra options?
		if (!empty($options)) {
		  curl_setopt_array($curly[$id], $options);
		}

		curl_multi_add_handle($mh, $curly[$id]);
	  }

	  // execute the handles
	  $running = null;
	  do {
		curl_multi_exec($mh, $running);
	  } while($running > 0);


	  // get content and remove handles
	  foreach($curly as $id => $c) {
		$result[$id] = curl_multi_getcontent($c);
		curl_multi_remove_handle($mh, $c);
	  }

	  // all done
	  curl_multi_close($mh);

	  return $result;
	}

	function _cache($data, $name, $ext='') {
		require_once '_class/cache.class.php';
		// setup 'default' cache
    $c = new Cache(array(
		  'name'      => $name,
		  'path'      => 'cache/',
		  'extension' => '.'.$ext
		));

		if($c->isCached($name) == false){
			if($data) {
			// store an array
			$c->store($name, $data, 31556952);
			}
		}

		$cached = $c->retrieve($name);
		return $cached;
	}

	function get_content($q='', $str='') {
		require_once '_class/cache.class.php';
		// setup 'default' cache
    $c = new Cache(array(
		  'name'      => $str,
		  'path'      => 'cache/',
		  'extension' => '.google'
		));

    	include 'simple_html_dom.php';
		if (!$q) {
			$file = listfile('keyword', true);
			$q = random_lines('keyword/'.$file, 1)[0];
		}
		$q = urlencode($q);
		$bing1 = get_curl('https://www.bing.com/images/search?q='.$q.'&qft=+filterui%3aimagesize-custom_500_500&Market=id-ID&first='.rand(1, 10));
		$bing2 = get_curl('https://www.bing.com/search?q='.$q.'&Market=id-ID&first='.rand(1, 10));
		//print_r($bing2);

  	$image = new simple_html_dom();
  	$image->load($bing1);
  	$web = new simple_html_dom();
  	$web->load($bing2);

		$content = array();
		$images = array();

		foreach($image->find('.item a.thumb') as $img){
       		$images[] = $img->href;
       	}

       	foreach($web->find('.b_caption p') as $text){
       		$content[] = $text;
       	}

		if($c->isCached($str) == false){
			if($content || $images) {
			// store an array
			$c->store($str, array(
			  'content' => $content,
			  'images' => $images,
			  'keyword' => urldecode($q)
			), 31556952);
			}
		}

		$cached = $c->retrieve($str);
		return $cached;
	}

	function bing_content($q) {
    	include 'simple_html_dom.php';
		$q = urlencode($q);

		$content = array();
		$images = array();

		$qq = file_get_contents(site_url().'/image.php?q='.$q);
		$qq = json_decode($qq, true);
		//print_r ($qq);
		foreach($qq as $img){
			$images[] = 'https://i0.wp.com/'.remove_http($img['images']);
		}

	  	//BING SCRAPER
		$proxy = random_lines('proxy.txt', 1)[0];
		$bing = get_curl('https://www.bing.com/search?q='.$q.'&Market=id-ID&first='.rand(1, 10), $proxy);
		$bingweb = new simple_html_dom();
		$bingweb->load($bing);

		foreach($bingweb->find('.b_caption p') as $text){
			$content[] = $text;
		}

		$cached =  array(
			  'content' => $content,
			  'images' => $images,
			  'thumb' => $images[0].'?resize=200,180');

		return $cached;
	}

	function bing_web($q) {
		$kw = urlencode($q);

		$content = array();

  	//BING SCRAPER
		$proxy = random_lines('proxy.txt', 1)[0];
		$bing = get_curl('https://www.bing.com/search?format=rss&count=20&q='.$kw, $proxy);
		$bingweb = simplexml_load_string($bing);
		//var_dump( $bingweb);
		foreach($bingweb->channel->item as $item){
			$content[] = $item->description;
			//var_dump ($content);
		}

		$data =  array(
			  'content' => $content,
			  'keyword' => $q);

		return $data;
	}
?>
