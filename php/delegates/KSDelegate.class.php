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
		
		protected function add($keywords, $host) {
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
		
		protected function save($host) {
			$path = $this->dir . $host . '.txt';
			$keywords = $this->data->$host;
			$content = count($keywords) . "\r\n" . implode("\r\n", $keywords) . "\r\n";
			DOS::write($path, $content);
		}
		
		//
		//  spider interface
		//
		
		public function process($html, $url) {
			// 1. fetch keywords
			$fetcher = new KeywordsFetcher($html, $url);
			$keywords = $fetcher->keywords();
			if ($keywords && count($keywords) > 0) {
				// add keywords
				$host = (new URL($url))->host;
				$this->add($keywords, $host);
				// save keywords
				$this->save($host);
			}
			
			// 2. collect links
			$collector = new LinkCollector($html, $url);
			return $collector->links();
		}
	}
	
