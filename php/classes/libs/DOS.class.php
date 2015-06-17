<?php
	
	require_once('Log.class.php');
	require_once('Object.class.php');
	
	
	class DOS extends Object {
		
		// create dirs circularly
		function mkdir($dir, $mode = 0777) {
			if (!is_dir($dir)) {
				// make sure the parent dir exists
				if (!self::mkdir(dirname($dir), $mode)) {
					return false;
				}
				// create it
				if (!mkdir($dir, $mode)) {
					return false;
				}
			}
			return true;
		}
		
		function read($file) {
			$content = file_get_contents($file);
			//Log::info('readed ' . strlen($content) . ' byte(s) from file: '. $file);
			return $content;
		}
		
		function write($file, $content) {
			//Log::info('writting ' . strlen($content) . ' byte(s) into file: '. $file);
			return file_put_contents($file, $content);
		}
		
	}
	
	
	
