<?php
	
	require_once('Log.class.php');
	require_once('String.class.php');
	require_once('HTML.class.php');
	
	class HTTP {
		
		function request($url) {
			$content = file_get_contents($url);
			return $content;
		}
		
		function requestHTML($url, $outputCharset = 'UTF-8') {
			$content = self::request($url);
			if (!$content) {
				Log::error("failed to get HTML from url: $url");
				return null;
			}
			
			$html = new HTML($content);
			$charset = $html->charset();
			if ($charset) {
				$charset = $html->standard_charset($charset);
			}
			if (!$charset) {
				Log::error("**** charset error in url: $url");
				return null;
			}
			
			if ($charset !== $outputCharset) {
				Log::info("convert charset from '$charset' to '$outputCharset', url: $url");
				$content = String::convert($content, $charset, $outputCharset);
			}
			return $content;
		}
		
	}
	
