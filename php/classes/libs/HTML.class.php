<?php
	
	require_once('Log.class.php');
	require_once('XML.class.php');
	
	class HTML extends XML {
		
		var $supported_charsets = ['UTF-8', 'UTF-16', 'GB2312', 'GBK'];
		
		//
		// charset
		//
		function standard_charset($charset) {
			$charset = trim($charset);
			if (strlen($charset) > 0) {
				$charset = strtoupper($charset);
				if (in_array($charset, $this->supported_charsets)) {
					return $charset;
				}
			}
			Log::warning("**** not supported charset: $charset");
			return null;
		}
		
		function charset() {
			if ($this->data == null) {
				return null;
			}
			
			$len = strlen($this->data);
			for ($seek = 0; $seek < $len;) {
				$meta = self::fetch_element('meta', $seek);
				if (!$meta) {
					// finished
					break;
				}
				
				// 1. '<meta charset="UTF-8" />'
				$charset = self::fetch_attribute($meta, 'charset');
				if ($charset) {
					return $charset;
				}
				
				// 2. '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				$http_equiv = self::fetch_attribute($meta, 'http-equiv');
				if ($http_equiv && strtolower($http_equiv) == 'content-type') {
					$content = self::fetch_attribute($meta, 'content');
					if (!$content) {
						Log::error("**** content error, meta: $meta");
						continue;
					}
					$p1 = stripos($content, 'charset=');
					if ($p1 === false) {
						Log::error("**** charset error, meta: $meta");
						continue;
					}
					$p1 += 8;
					$p2 = strpos($content, ';', $p1);
					return $p2 === false ? substr($content, $p1) : substr($content, $p1, $p2 - $p1);
				}
			}
			
			Log::warning("**** charset not found");
			return null;
		}
		
		
	}
	
	
