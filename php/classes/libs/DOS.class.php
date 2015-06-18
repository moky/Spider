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
					Log::error('failed to create folder: ' . $dir);
					return false;
				}
			}
			return true;
		}
		
		function read($file) {
			$content = file_get_contents($file);
			if ($content) {
				//Log::info('readed ' . strlen($content) . ' byte(s) from file: '. $file);
			} else {
				Log::error('failed to read file: ' . $file);
			}
			return $content;
		}
		
		function write($file, $content) {
			$len = file_put_contents($file, $content);
			if ($len > 0) {
				//Log::info('wrote ' . $len . '/' . strlen($content) . ' byte(s) into file: '. $file);
			} else {
				Log::error('failed to write file: ' . $file);
			}
			return $len;
		}
		
	}
	
	
	
