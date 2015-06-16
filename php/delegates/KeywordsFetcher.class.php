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
		
		function keywords() {
			if ($this->keywords == null) {
				return null;
			}
			
			$content = str_replace($this->separators, ',', $this->keywords);
			$array = explode(',', $content);
			
			Log::info('got ' . count($array) . ' keyword(s) in meta(' . $this->meta . ')');
			
			return $array;
		}
		
	}
	
	
	
