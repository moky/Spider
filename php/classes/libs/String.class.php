<?php
	
	require_once('Object.class.php');
	
	
	class String extends Object {
		
		
		function convert($string, $fromCharset, $toCharset) {
			if (!$string) {
				return null;
			}
			if ($fromCharset == $toCharset) {
				$transit = $fromCharset == 'UTF-8' ? 'GB2312' : 'UTF-8';
				$string = mb_convert_encoding($string, $transit, $fromCharset);
				$string = mb_convert_encoding($string, $toCharset, $transit);
			} else {
				$string = mb_convert_encoding($string, $toCharset, $fromCharset);
			}
			return $string;
		}
		
		
	}
	
	
	
