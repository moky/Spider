<?php
	
	class KeywordsFetcher {
		
		var $separators = ['，', '；', '、', ' ', ';', '/', '|', '_', '#'];
		
		protected $meta = null;
		protected $keywords = null;
		
		function __construct($html, $url) {
			$html = new HTML($html);
			
			$meta = $html->fetch_element_with_attribute('meta', 'name', 'keywords');
			if ($meta) {
				$this->meta = $meta;
				$this->keywords = $html->fetch_attribute($meta, 'content');
			}
		}
		
		function split($content) {
			$content = str_replace($this->separators, ',', $content);
			$array = explode(',', $content);
			$out = [];
			foreach ($array as $string) {
				if (strlen($string) > 0) {
					array_push($out, $string);
				}
			}
			return $out;
		}
		
		function keywords() {
			if ($this->keywords == null) {
				return null;
			}
			$array = $this->split($this->keywords);
			Log::info('got ' . count($array) . ' keyword(s) in meta(' . $this->meta . ')');
			return $array;
		}
		
	}
	
	
	
