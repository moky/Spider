<?php
	
	require_once(dirname(__FILE__).'/../classes/libs/HTML.class.php');
	
	
	/**
	 *
	 *  fetcher keywords: <meta name="keywords" content="keyword1, keyword2" />
	 *
	 */
	class KeywordsFetcher {
		
		private $separators = ['，', '；', '、', ' ', ';', '/', '|', '_', '#'];
		
		private $meta = null;
		private $keywords = null;
		
		public function __construct($html, $url) {
			$html = new HTML($html);
			// fetch element: <meta name="keywords" content="..." />
			$meta = $html->fetchElementWithAttribute('meta', 'name', 'keywords');
			if ($meta) {
				$this->meta = $meta;
				$this->keywords = $meta->attribute('content');
			}
		}
		
		//
		//  split content string to an array
		//
		public function split($content) {
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
		
		//
		//  get keywords array
		//
		public function keywords() {
			if ($this->keywords == null) {
				return null;
			}
			$array = $this->split($this->keywords);
			Log::info('got ' . count($array) . ' keyword(s) in meta(' . $this->meta . ')');
			return $array;
		}
		
	}
	
