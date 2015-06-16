<?php
	
	function __autoload($class) {
		require_once('classes/libs/' . $class . '.class.php');
	}
	
	require_once('delegates/KeywordsFetcher.class.php');
	
	// usage
	if ($argc <= 1 || $argv[1] == '-h' || $argv[1] == '--help') {
		
		echo "\n";
		echo "	Usage:\n";
		echo "		" . $argv[0] . " DIR\n";
		echo "\n";
		
		exit;
	}
	
	// dir
	$dir = $argv[1];
	$len = strlen($dir);
	if ($len > 0 && $dir[$len - 1] == '/') {
		$dir = substr($dir, 0, $len - 1);
	}
	
	// keywords pool
	$keywords_pool = [];
	
	$max_keywords_len = 6;
	
	$fetcher = new KeywordsFetcher(null, null);
	
	// check each item of the array,
	// if contains separators, explode it
	function dig_keywords($array) {
		global $fetcher;
		$out = [];
		foreach ($array as $string) {
			$arr = $fetcher->split($string);
			if (count($arr) > 1) {
				Log::info("explode more than 1 keywords: $string");
				foreach ($arr as $kw) {
					$kw = trim($kw);
					if (strlen($kw) > 0) {
						array_push($out, $kw);
					}
				}
			} else {
				$kw = trim($string);
				if (strlen($kw) > 0) {
					array_push($out, $kw);
				}
			}
		}
		return $out;
	}
	
	// load keywords from file
	function load_keywords($file) {
		$content = DOS::read($file);
		if (!$content) {
			return null;
		}
		$array = explode("\n", $content);
		$count = intval($array[0]);
		if ($count > 0) {
			// remove first line (count)
			array_shift($array);
		}
		
		return dig_keywords($array);
	}
	
	// save keywords into file
	function save_keywords($file, $keywords) {
		$content = count($keywords) . "\r\n" . implode("\r\n", $keywords) . "\r\n";
		DOS::write($file, $content);
	}
	
	// add keywords to keywords pool
	function add_keywords($array) {
		global $keywords_pool;
		global $max_keywords_len;
		
		$count = 0;
		
		foreach ($array as $keyword) {
			$len = strlen($keyword);
			$utf8len = @iconv_strlen($keyword, 'UTF-8');
			if ($len > ($max_keywords_len * 3) && $utf8len > $max_keywords_len) {
				Log::warning("drop keyword(len=$len:$utf8len>$max_keywords_len): $keyword");
				continue;
			}
			
			if (!in_array($keyword, $keywords_pool)) {
				array_push($keywords_pool, $keyword);
				$count++;
			}
		}
		
		return $count;
	}
	
	//
	// main
	//
	foreach (scandir($dir) as $file) {
		if ($file == '.' || $file == '..' || (new Path($file))->extension != 'txt') {
			// only read .txt file
			continue;
		}
		
		$array = load_keywords($dir . '/' . $file);
		if ($array) {
			$count = add_keywords($array);
			echo "got $count/" . count($array) . " keyword(s) from $file .\n";
		}
	}
	
	$file = $dir . '.txt';
	save_keywords($file, $keywords_pool);
	echo "saved " . count($keywords_pool) . " keyword(s) into $file .\n";
	
	
	
