<?php
	
	
	class KeywordsFetcher {
		
		var $separators = ['，', '；', '、', ' ', ';', '/', '|', '_', '#'];
		
		protected $meta = null;
		protected $keywords = null;
		
		function __construct($html, $url) {
			$html = new HTML($html);
			
			$meta = $html->fetchElementWithAttribute('meta', 'name', 'keywords');
			if ($meta) {
				$this->meta = $meta;
				$this->keywords = $meta->attribute('content');
			}
		}
		
		protected function split($content) {
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
		
		/**
		 *
		 *  get keywords: <meta name="keywords" content="XXX,YYY" />
		 *
		 */
		function keywords() {
			if ($this->keywords == null) {
				return null;
			}
			$array = $this->split($this->keywords);
			Log::info('got ' . count($array) . ' keyword(s) in meta(' . $this->meta . ')');
			return $array;
		}
		
	}
	
	
	
