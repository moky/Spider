<?php
	
	require_once('KeywordsFetcher.class.php');
	require_once('LinkCollector.class.php');
	
	/**
	 *
	 *  Keywords Spider Delegate
	 *
	 */
	
	class KSDelegate implements ISpiderDelegate {
		
		protected $data = null;
		protected $dir = null;
		
		function __construct($dir) {
			// keywords pool
			$this->data = new Dictionary();
			// output dir
			$this->dir = $dir;
			DOS::mkdir($this->dir);
		}
		
		function add_keywords($keywords, $host) {
			$array = $this->data->$host;
			if (!$array) {
				$array = [];
			}
			foreach ($keywords as $kw) {
				if (strlen($kw) < 1 || in_array($kw, $array)) {
					continue;
				}
				array_push($array, $kw);
			}
			$this->data->$host = $array;
		}
		
		function save_keywords($dir, $host) {
			$path = $dir . $host . '.txt';
			$keywords = $this->data->$host;
			$keywords = count($keywords) . "\r\n" . implode("\r\n", $keywords) . "\r\n";
			DOS::write($path, $keywords);
		}
		
		//
		//  spider interface
		//
		
		public function process($spider, $html, $url) {
			// 1. fetch keywords
			$fetcher = new KeywordsFetcher($html, $url);
			$keywords = $fetcher->keywords();
			if ($keywords && count($keywords) > 0) {
				// add keywords
				$host = (new URL($url))->host;
				self::add_keywords($keywords, $host);
				// save keywords
				self::save_keywords($this->dir, $host);
			}
			
			// 2. collect links
			$collector = new LinkCollector($html, $url);
			return $collector->links();
		}
	}
	
